<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\PointCard;
use DateTime;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    protected $model = Customer::class;

    public function index(Request $request)
    {
        $where = [];
        $model = new $this->model();
        foreach($model->getFillable() as $column){
            if($param = $request->input($column)) $where[$column] = $param;            
        }

        $data = $this->model::where($where)->with(['addresses' => function ($query) {
            $query->where('status', 'active');
        }, 'pointCard' => function ($query) {
            $query->where('status', 'active');
        }]) ->get();

        foreach($data as $customer){
            foreach($customer->addresses as $address) $address->district;
        }
    
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

    public function addPointCard(Request $request, $id)
    {
        $serial = $request->get('serial');
        if(!$serial) return response()->json(['message' => 'Serial invalid.'], 422);

        // validar si el cliente ya tiene
        $customer = Customer::find($id);
        if(count(PointCard::where('serial', $serial)->get())){
            return response()->json(['error' => 'La tarjeta ya pertenece a otro cliente.' ], 422);
        }

        if($customer->point_card_id){
            $pointCard = PointCard::find($customer->point_card_id);
            $pointCard->serial = $serial;
            $pointCard->save();
        } else{
            // Fecha que quieres verificar
            $fecha = date('Y-m-d');

            // Rango de fechas
            $fechaInicio = '2023-08-10';
            $fechaFin = '2023-08-13';

            // Convertir las fechas a objetos DateTime
            $fechaObjeto = new DateTime($fecha);
            $fechaInicioObjeto = new DateTime($fechaInicio);
            $fechaFinObjeto = new DateTime($fechaFin);

            // Verificar si la fecha está dentro del rango
            $points = $fechaObjeto >= $fechaInicioObjeto && $fechaObjeto <= $fechaFinObjeto ? 50 : 0;

            $pointCard = PointCard::create([
                'serial' => $serial,
                'point_card_type_id' => 1,
                'points' => $points
            ]);

            $customer->point_card_id = $pointCard->id;
            $customer->save();
        }

        $customer->pointCard;
        foreach($customer->addresses as $address) $address->district;
        
        return response()->json([
            "data" => $customer,
            "message" => "ok"
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validateCustomer($request);
        $customer = new Customer;
        $customer->firstname = $request->firstname;
        $customer->lastname = $request->lastname;
        $customer->phone_number = $request->phone_number;
        $customer->email = $request->email;
        $customer->birthdate = $request->birthdate;
        $customer->point_card_id = $request->point_card_id;
        $customer->status = $request->status ? $request->status : 'active';
        $customer->save();
        $customer->addresses;
        $customer->pointCard;
        return response()->json([
            "data" => $customer,
            "message" => "ok"
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);

        $this->validateCustomer($request, $customer);

        if ($customer) {
            $customer->firstname = $request->firstname;
            $customer->lastname = $request->lastname;
            $customer->phone_number = $request->phone_number;
            // $customer->email = $request->email;
            // $customer->birthdate = $request->birthdate;
            $customer->status = $request->status ?? $customer->status;
            // $customer->point_card_id = $request->point_card_id;
            $customer->save();

            $customer = Customer::where(["id" => $id])->with(['addresses' => function ($query) {
                $query->where('status', 'active');
            }]) ->first();
            foreach($customer->addresses as $address) $address->district;
            $customer->pointCard;
            return response()->json([
                "data" => $customer
            ]);
        } else {
            return response()->json(['message' => 'Customer not found'], 404);
        }
    }

    protected function validateCustomer(Request $request, $customer = null)
    {
        $rules = [
            'firstname' => 'min:3|required',
            'lastname' => 'max:160',
            'phone_number' => 'required|min:10|numeric',
            // 'email' => 'email|unique:customers,email',
            // 'birthdate' => 'date',
            'status' => 'in:active,inactive',
            // 'point_card_id' => 'exists:point_cards,id|unique:customers,point_card_id',
        ];

        if (!$customer) {
            // Create
            $rules['phone_number'] .= "|unique:customers,phone_number";
        } else{
            // Update
            // $rules['email'] .= ",$customer->id";
            $rules['phone_number'] .= $customer->phone_number === $request->phone_number ? "" : "|unique:customers,phone_number"; 
        }

        $customMessages = [
            'firstname' => [
                'min' => 'El nombre debe tener al menos 3 caracteres.',
                'required' => 'El nombre es obligatorio.'
            ]
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422));
        }
    }

    protected function validateAddress(Request $request)
    {
        $rules = [
            'street' => 'max:80|required',
            'street_number' => 'max:20|required',
            'interior_number' => 'max:20',
            'postal_code' => 'max:10',
            'district_id' => 'exists:districts,id',
            'status' => 'in:active,inactive',
        ];

        $customMessages = [
            'street' => [
                'required' => 'El campo calle es obligatorio.'
            ]
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422));
        }
    }

    public function storeAddresses(Request $request, $id){
        $this->validateAddress($request);

        $address = new Address([
            'street' => $request->street,
            'street_number' => $request->street_number,
            'interior_number' => $request->interior_number,
            'postal_code' => $request->postal_code,
            'references' => $request->references,
            'district_id' => $request->district_id,
            'customer_id' => $id,
            'status' => $request->status ? $request->status : 'active',
        ]);
        
        $address->save();
        $address->district;

        // Devolver una respuesta adecuada
        return response()->json([
            "data" => $address
        ], 201);
    }

    public function updateAddress(Request $request, $id, $address_id)
    {
        $address = Address::where(['id' => $address_id, 'customer_id' => $id])->first();
        $this->validateAddress($request);

        if(!$address){
            return response()->json(['message' => 'Dirección no encontrada'], 404);
            exit;
        }

        $model = new Address();
        foreach($model->getFillable() as $column){
            if($request->$column){
                // Validar status
                if($column === 'status'){
                    $address->$column = $request->$column ? $request->$column : $address->$column;
                } else{
                    $address->$column = $request->$column;
                }
            }
            
        }
        $address->save();
        $address->district;

        return response()->json([
            "data" => $address
        ]);
    }

    public function destroyAddress($id, $address_id)
    {
        $register = Address::where([
            'id' => $address_id,
            'customer_id' => $id
        ])->first();

        if(!$register){
            throw new HttpResponseException(response()->json([
                'message' => 'Register not found.'
            ], 404));
        }

        $register->update(['status' => 'inactive', 'is_primary' =>  false]);

        return response()->json([
            'message' => 'Address deleted successfully',
            'data' => $register
        ], 200);
    }

    public function getForWhatsapp(Request $request)
    {
        $reset = $request->get('reset', false);
        if($reset){
            foreach(Customer::all() as $customer){
                $customer->whatsapp_msg = 0;
                $customer->save();
            }
            return response()->json([
                'message' => 'Customers reset whatsapp_msg'
            ]);
        }

        $max_registers = intval($request->get('max_registers', 100));
        $customers = Customer::where(['status' => true, 'whatsapp_msg' => 0])->inRandomOrder()->take($max_registers)->get();

        foreach($customers as $customer){
            $customer->whatsapp_msg = 1;
            $customer->save();
        }

        return response()->json([
            'data' => $customers,
            'meta' => [
                'max_registers' => $max_registers,
                'total_registers' => count($customers),
                'total_registers_in_db' => Customer::count()
            ]
        ]);
    }
}