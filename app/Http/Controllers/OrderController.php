<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\CashRegisterHistory;
use App\Models\Customer;
use App\Models\DeliveryOption;
use App\Models\ExtraIngredient;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\PointCard;
use App\Models\PointCardHistory;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\SizeExtraIngredient;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $model = Order::class;

    public function index(Request $request)
    {
        $query = Order::query();

        // Obtener los parámetros de fecha de inicio y fecha final de la URL, si se han proporcionado
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Aplicar filtros de fecha si se han proporcionado
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $model = new Order;
        $where = [];
        foreach($model->getFillable() as $column){
            if($param = $request->input($column)) $where[$column] = $param;
        }

        $data = $query->where($where)->with(['products' => function($query){
            $query->select('products.category_id');
        }])->get();

    
        $metadata = [
            'pagination' => [
                'count' => count($data),
                'page' => 1,
                'pageSize' => 1
            ],
            'version' => '1.0',
            'author' => 'Mauricio Martinez Martinez',
        ];

        $response = [
            "data" => $data,
            "meta" => $metadata  
        ];
    
        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        $this->validateOrder($request);
        // Paso 2: Iniciar una transacción de base de datos
        DB::beginTransaction();
        $customer_id = $request->customer_id;

        try {
            $order = Order::create([
                'delivery_time' => $request->delivery_time,
                'coments' => $request->coments,
                'status' => $request->status ?? 'active',
                'delivery_option_id' => $request->delivery_option_id,
                'customer_id' => $customer_id,
                'address_id' => $request->address_id,
                'coupon_id' => $request->coupon_id,
                'front_id' => $request->front_id,
                'delivery_price' => $request->delivery_price ?? 0,
                'order_status' => 'registered',
                'order_number' => $request->order_number,
            ]);
        
            // Agregar los productos al pedido
            $products = json_decode($request->products);
            $pizzas = json_decode($request->pizzas);

            foreach($pizzas as $pizza){
                $price = 0;
                $name = '';
                $prices = [];
                $productBase = null;
                $specials = [];
                $dictionaryName = [
                    1 => [
                        0 => ''
                    ],
                    2 => [
                        0 => 'Mitad',
                        1 => 'Mitad',
                    ],
                    3 => [
                        0 => 'Un cuarto',
                        1 => 'Un cuarto',
                        2 => 'Mitad',
                    ],
                    4 => [
                        0 => 'Un cuarto',
                        1 => 'Un cuarto',
                        2 => 'Un cuarto',
                        3 => 'Un cuarto',
                    ],
                ];

                foreach($pizza->ingredients as $i => $ingredient_id){
                    $price = ProductSize::where(['size_id' => $pizza->size_id, 'product_id' => $ingredient_id])->pluck('price')->first();
                    if(!$price) continue;
                    $prices[] = floatval($price ?? 0);
                    $ingredient = Product::find($ingredient_id);
                    $specials[] = $ingredient->is_special;
                    $name.= $dictionaryName[count($pizza->ingredients)][$i] . ' ' . $ingredient->name . ', ';
                    $productBase = $ingredient;
                }

                $price = max($prices);

                if(in_array('1', $specials)){
                    $s = 0;
                    foreach($specials as $special) $s+= intval($special);

                    if($s <= (count($pizza->ingredients) / 2)){
                        $dictionary = [
                            1 => 205,
                            2 => 150,
                            3 => 110,
                            4 => 70,
                        ];
                        $price = $dictionary[$pizza->size_id];
                    }
                }
                
                

                $extra_ingredients = [];
                $extra_price = 0;

                foreach($pizza->extra_ingredients as $extra_ingredient_id){
                    $extraIngredient = ExtraIngredient::find($extra_ingredient_id);
                    if(!$extraIngredient) continue;
                    $extra_ingredients[] = $extraIngredient->name;

                    $priceExtraIngredient = SizeExtraIngredient::where([
                        'size_id' => $pizza->size_id,
                        'extra_ingredient_id' => $extra_ingredient_id
                    ])->pluck('price')->first();

                    if(!$priceExtraIngredient) continue;
                    $extra_price+=$priceExtraIngredient;
                }

                if(!$productBase) continue;
                $productBase->orders()->attach($order->id, [
                    'order_product.unit_price' => $price,
                    'order_product.size_id' => $pizza->size_id,
                    'order_product.quantity' => $pizza->quantity,
                    'order_product.name' => $name,
                    'order_product.extra_price' => $extra_price,
                    'order_product.extra_ingredients' => implode(',', $extra_ingredients),
                ]);
            }
        
            foreach ($products as $product) {
                $productDB = Product::findOrFail($product->product_id);
                $name = $productDB->name;
                $price = ProductSize::where(['size_id' => $product->size_id, 'product_id' => $product->product_id])->pluck('price')->first();
        
                if (!$price) continue;
                $extra_ingredients = [];
                $extra_price = 0;

                foreach($product->extra_ingredients as $extra_ingredient_id){
                    $extraIngredient = ExtraIngredient::find($extra_ingredient_id);
                    if(!$extraIngredient) continue;
                    $extra_ingredients[] = $extraIngredient->name;

                    $priceExtraIngredient = SizeExtraIngredient::where([
                        'size_id' => $product->size_id,
                        'extra_ingredient_id' => $extra_ingredient_id
                    ])->pluck('price')->first();

                    if(!$priceExtraIngredient) continue;
                    $extra_price+=$priceExtraIngredient;
                }
        
                $productDB->orders()->attach($order->id, [
                    'order_product.unit_price' => $price,
                    'order_product.size_id' => $product->size_id,
                    'order_product.quantity' => $product->quantity,
                    'order_product.name' => $name,
                    'order_product.extra_price' => $extra_price,
                    'order_product.extra_ingredients' => implode(',', $extra_ingredients),
                ]);
            }
             // Confirmar la transacción
            DB::commit();

            // Paso 6: Obtener los detalles del pedido
            $order = Order::with(['products' => function($query){
                $query->select('products.category_id');
            }])->with('orderPayments')->where('id', $order->id)->first();

            $customer = Customer::find($customer_id);
            
            if($customer->point_card_id){
                // Bonificar Puntos
                $points = $order->subtotal*.10;
                $pointCard = PointCard::find($customer->point_card_id);

                PointCardHistory::create([
                    'amount' => $points,
                    'action' => 'add',
                    'previous_amount' => $pointCard->points,
                    'description' => 'Bonificación del pedido con folio: '.$order->id,
                    'point_card_id' => $pointCard->id
                ]);

                $pointCard->points = floor($pointCard->points + $points);
                $pointCard->save();
            }

            $customer->pointCard;
            foreach($customer->addresses as $address) $address->district;

            // Paso 7: Devolver la respuesta
            return response()->json(['data' => $order, 'customer' => $customer], 201);
        } catch (\Throwable $th) {
            // Si se produce un error, revertir la transacción
            DB::rollback();
            return response()->json([
                'message' => 'error',
                'errors' => [
                    'generic' => 'No se pudo procesar el pedido.'
                ]
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if(!$order){
            throw new HttpResponseException(response()->json([
                'message' => 'Order not found.',
            ], 404));
        }
        $this->validateOrder($request, $order);
        $order->delivery_time = $request->delivery_time ?? $order->delivery_time;
        $order->status = $request->status ?? $order->status;
        $order->delivery_option_id = $request->delivery_option_id ?? $order->delivery_option_id;
        $order->customer_id = $request->customer_id ?? $order->customer_id;
        $order->address_id = $request->address_id ?? $order->address_id;
        $order->coupon_id = $request->coupon_id ?? $order->coupon_id;
        $order->front_id = $request->front_id ?? $order->front_id;
        $order->order_status = $request->order_status ?? $order->order_status;
        $order->save();
        return response()->json($order);
    }

    public function payment(Request $request, $id){
        /* si el amount es menor o igual que la cantidad a pagar del pedido
                el payment_status lo definimos en true
                y en caja la accion sera de ingreso

            Si el amount es mayor a la cantidad del pedido y el order.order_payment.amount es 0,
                la resta entre el amount y el total a pagar sera la cantidad que se retirara de caja

            Si el order.order_payment.amount es mayor que 0,
                esa cantidad para a ser ingresada en la caja
        */
        $order = Order::with(['products' => function($query){
            $query->select('products.category_id');
        }])->where('id', $id)->first();

        $order_number = $order->order_number;

        // Validar que el importe no se haya hecho
        // $validatePaid = CashRegisterHistory::where([
        //     'action' => 'add',
        //     'description' => 'Cobro del pedido '.$order_number. ' con folio: '.$order->id,
        // ])->first();

        // if($validatePaid) return response()->json([
        //     'msg' => 'Pago ya realizado'
        // ], 422);

        $amount = floatval($request->get('amount'));
        $total = $order->total;
        $total_payments = $order->total_payments;

        $order_amount = $order->paid;

        $payment_type_id = $request->payment_type_id;

        $cash_register = null;
        $cash_register_history = null;
        $cash_register = CashRegister::find(1);

        $order_payment = OrderPayment::where(['order_id' => $id, 'payment_type_id' => 1, 'payment_status' => false])->first();
        $msg = '';
        
        // $request->payment_type_id
        if($amount <= ($total - $total_payments)){
            $msg = 'Cobro normal';
            $order_payment = OrderPayment::create([
                'amount' => $amount,
                'payment_status' => true,
                'order_id' => $id,
                'payment_type_id' => $payment_type_id,
            ]);

            if($payment_type_id == 1){
                // Accion en caja
                $cash_register_history = CashRegisterHistory::create([
                    'amount' => $amount,
                    'action' => 'add',
                    'description' => 'Cobro del pedido '.$order_number. ' con folio: '.$order->id . ' ' . $request->get('description'),
                    'previous_amount' => $cash_register->balance,
                    'cash_register_id' => $cash_register->id
                ]);

                $cash_register->balance+= $cash_register_history->amount;
                $cash_register->save();
            }
        } else if($amount > ($total - $total_payments) && $payment_type_id == 1 && !$order_payment){
            $msg = 'retiro de cambio';
            $order_payment = OrderPayment::create([
                'amount' => $amount,
                'payment_status' => false,
                'order_id' => $id,
                'payment_type_id' => $payment_type_id,
            ]);

            $cash_register_history = CashRegisterHistory::create([
                'amount' => $amount - ($total - $total_payments),
                'action' => 'subtract',
                'description' => 'Cambio del pedido '.$order_number. ' con folio: '.$order->id,
                'previous_amount' => $cash_register->balance,
                'cash_register_id' => $cash_register->id
            ]);

            $cash_register->balance-= $cash_register_history->amount;
            $cash_register->save();
        } else if($order_payment->amount > ($total - $total_payments)){
            $msg = 'cobro del pedido y su cambio';
                $order_payment->payment_status = true;
                $order_payment->save();

                // Accion en caja
                $cash_register_history = CashRegisterHistory::create([
                    'amount' => $order_payment->amount,
                    'action' => 'add',
                    'description' => 'Cobro del pedido '.$order_number. ' con folio: '.$order->id,
                    'previous_amount' => $cash_register->balance,
                    'cash_register_id' => $cash_register->id
                ]);
                $cash_register->balance+= $cash_register_history->amount;
                $cash_register->save();
        }

        // $order_amount_cash = 0;
        // foreach($order->orderPayments as $orderPayment){
        //     if($orderPayment->payment_type_id == 1 && $orderPayment->payment_status === true){
        //         $order_amount_cash+=$orderPayment->amount;
        //     }
        // }


        // $order_amount_ct = 0;
        // foreach($order->orderPayments as $orderPayment){
        //     if($orderPayment->payment_type_id != 1 && $orderPayment->payment_status === true){
        //         $order_amount_ct+=$orderPayment->amount;
        //     }
        // }

        // if($amount > ($total-$order_amount_ct) && $order_amount_cash < $total){
        //     if($cash_register->balance < $amount - $total){
        //         throw new HttpResponseException(response()->json([
        //             'message' => 'Validation Failed',
        //             'errors' => [
        //                 'amount' => ['No hay suficiente efectivo en caja.']
        //             ]
        //         ], 404)); 
        //     }


        //     $order_payment = OrderPayment::create([
        //         'amount' => $amount,
        //         'payment_status' => false,
        //         'order_id' => $id,
        //         'payment_type_id' => $payment_type_id,
        //     ]);

        //     // Accion en caja
        //     $cash_register_history = CashRegisterHistory::create([
        //         'amount' => $amount - $total,
        //         'action' => 'subtract',
        //         'description' => 'Cambio del pedido '.$order_number,
        //         'previous_amount' => $cash_register->balance,
        //         'cash_register_id' => $cash_register->id
        //     ]);

        //     $cash_register->balance-= $cash_register_history->amount;
        //     $cash_register->save();
        // }

        // $order_amount_cash = 0;
        // foreach($order->orderPayments as $orderPayment){
        //     if($orderPayment->payment_type_id == 1){
        //         $order_amount_cash+=$orderPayment->amount;
        //     }
        // }

        // if($order_amount_cash > $total-$order_amount_ct){
        //     $order_payment = OrderPayment::where(['order_id' => $id])->first();
        //     $order_payment->payment_status = true;
        //     $order_payment->save();

        //     // Accion en caja
        //     $cash_register_history = CashRegisterHistory::create([
        //         'amount' => $order_amount,
        //         'action' => 'add',
        //         'description' => 'Cobro del pedido '.$order_number,
        //         'previous_amount' => $cash_register->balance,
        //         'cash_register_id' => $cash_register->id
        //     ]);
        //     $cash_register->balance+= $cash_register_history->amount;
        //     $cash_register->save();
        // }

        $order = Order::with(['products' => function($query){
            $query->select('products.category_id');
        }])->where('id', $id)->first();
        $order->total_payments;
        $order->subtotal;
        $order->total;
        $order->paid;

        $cash_register = CashRegister::where(['id' => 1])->with(['cashRegisterHistories' => function($query){
            $start_date = $_GET['start_date'] ?? date('Y-m-d');
            $end_date = $_GET['end_date'] ?? date('Y-m-').date('d')+1;
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }])->first();

        return response()->json([
            'data' => [
                'order' => $order,
                'cash_register' => $cash_register,
                'amount' => $amount,
                'msg' => $msg
            ]
        ], 200);
    }

    public function validateOrder(Request $request, $order = null)
    {
        $rules = [
            'delivery_time' => 'date',
            'status' => 'in:active,inactive',
            'delivery_option_id' => 'exists:delivery_options,id',
            'customer_id' => 'exists:customers,id',
            'address_id' => 'exists:addresses,id',
            'coupon_id' => 'exists:coupons,id',
            'products' => 'json'
        ];

        // Validar el delivery_option
        $delivery_option = DeliveryOption::find($request->delivery_option_id);
        $rules['address_id'] .= $delivery_option && $delivery_option->allow_delivery ? '|required' : '|prohibited';

        if(!$order){
            $rules['delivery_option_id'] .= '|required';
            $rules['front_id'] = 'unique:orders,front_id';
        } else{
            $rules['order_status'] = 'in:registered,prepared,delivery,delivered,canceled,mishap';
        }

        $validator = Validator::make($request->all(), $rules, [
            'customer_id.required' => 'No se ha seleccionado ningún cliente.',
            'address_id.required' => 'No se ha seleccionado ninguna dirección.',
        ]);
        if($validator->fails()){
            throw new HttpResponseException(response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422));
        }
    }

    public function updateOrderStatus($id, $newStatus)
    {
        $order = Order::find($id);
        if ($order) {
            $order->order_status = $newStatus;
            $order->save();

            $order = Order::with(['products' => function($query){
                $query->select('products.category_id');
            }])->with('orderPayments')->where('id', $order->id)->first();
            
            return response()->json([
                'message' => 'Order status updated successfully.',
                'data' => $order
            ]);
        } else {
            return response()->json(['message' => 'Order not found.'], 404);
        }
    }

    public function analisis()
    {
        $client = new Client();
        // Realizar la solicitud a la API y obtener la respuesta JSON
        $response = $client->get('http://api.piztak.com/api/pedidos');
        $data = json_decode($response->getBody()->getContents(), true);

        // Filtrar los pedidos que se hayan creado entre las fechas dadas
        $fechaInicio = Carbon::parse('2022-09-22');
        $fechaFin = Carbon::parse('2022-09-27');

        $pedidosFiltrados = array_filter($data['data'], function ($pedido) use ($fechaInicio, $fechaFin) {
            $fechaCreacion = Carbon::parse($pedido['createAt']);
            return $fechaCreacion->between($fechaInicio, $fechaFin);
        });

        // Calcular la suma del costo de cada producto de cada pedido
        $costoTotal = 0;
        foreach ($pedidosFiltrados as $pedido) {
            foreach ($pedido['productos'] as $producto) {
                $costoTotal += $producto['precio'];
            }
        }


        $primerPedido = [
            'suma' => $costoTotal,
            'total' => count($pedidosFiltrados),
            'costo' => count($pedidosFiltrados) * 100,
            'diferencia' => $costoTotal - count($pedidosFiltrados) * 100,
            'pedidosFaltantes' => ceil((4800 - ($costoTotal - count($pedidosFiltrados) * 100)) / 100)
        ];


        $fechaActual = Carbon::now();
        $fechaTresMesesAtras = $fechaActual->subMonths(3);


        $pedidos = Order::whereBetween('created_at', ['2023-08-07', '2023-08-14'])->where('order_status', '!=', 'canceled')->get();
        $total = 0;
        foreach($pedidos as $pedido) $total+=$pedido->total;
        return "Cantidad de pedidos: ".count($pedidos).". Total de venta: $".$total.".00"; 
        $orders = $pedidos->groupBy(function ($pedido) {
            return Carbon::parse($pedido->created_at)->week;
        });

        return view('analisis', compact('orders', 'primerPedido'));
    }


    public function analisisDia()
    {
        $fechaInicio = Carbon::parse('2023-08-14 19:00:00');
        $fechaFin = Carbon::parse('2023-08-20 23:00:00');
        
        // $pedidos = Order::whereBetween('created_at', [$fechaInicio, $fechaFin])
        //     ->where(function ($query) {
        //         $query->whereTime('created_at', '>=', '19:00:00')
        //               ->whereTime('created_at', '<=', '22:00:00');
        //     })
        //     ->get();

        $pedidos = Order::whereBetween('created_at', [$fechaInicio, $fechaFin])->get();
        
        $pedidosPorDia = $pedidos->groupBy(function ($pedido) {
            return Carbon::parse($pedido->created_at)->format('Y-m-d');
        });
        
        return view('dia', ['pedidosPorDia' => $pedidosPorDia]);
    }
}
