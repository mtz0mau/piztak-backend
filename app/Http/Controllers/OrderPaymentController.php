<?php

namespace App\Http\Controllers;

use App\Models\OrderPayment;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderPaymentController extends Controller
{
    protected $model = OrderPayment::class;

    public function store(Request $request)
    {
        $this->validateOrderPayment($request);
        $orderPayment = new OrderPayment([
            'amount' => $request->amount ?? 0,
            'payment_status' => $request->payment_status ?? false,
            'status' => $request->status ?? 'active',
            'order_id' => $request->order_id,
            'payment_type_id' => $request->payment_type_id,
        ]);
        $orderPayment->save();
        return response()->json($orderPayment, 201);
    }

    public function update(Request $request, $id)
    {
        $orderPayment = OrderPayment::find($id);
        if(!$orderPayment){
            throw new HttpResponseException(response()->json([
                'message' => 'Order payment not found.',
            ], 404));
        }

        $this->validateOrderPayment($request, $orderPayment);
        $orderPayment->amount = $request->amount ?? $orderPayment->amount;
        $orderPayment->payment_status = $request->payment_status ?? $orderPayment->payment_status;
        $orderPayment->status = $request->status ?? $orderPayment->status;
        $orderPayment->order_id = $request->order_id ?? $orderPayment->order_id;
        $orderPayment->payment_type_id = $request->payment_type_id ?? $orderPayment->payment_type_id;
        $orderPayment->save();
        return response()->json($orderPayment);
    }

    public function validateOrderPayment(Request $request, $orderPayment = null)
    {
        $rules = [
            'amount' => 'numeric',
            'payment_status' => 'boolean',
            'status' => 'in:active,inactive',
            'order_id' => 'exists:orders,id',
            'payment_type_id' => 'exists:payment_types,id'
        ];

        if(!$orderPayment){
            $rules['amount'] .= '|required';
            $rules['payment_status'] .= '|required';
            $rules['order_id'] .= '|required';
            $rules['payment_type_id'] .= '|required';
        }

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new HttpResponseException(response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422));
        }
    }
}