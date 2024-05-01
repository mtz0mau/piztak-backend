<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentTypeController extends Controller
{
    protected $model = PaymentType::class;

    public function store(Request $request)
    {
        $this->validatePaymentType($request);
        
        $paymentType = new PaymentType([
            'name' => $request->name,
            'description' => $request->description,
            'card_accepted' => $request->card_accepted,
            'cash_accepted' => $request->cash_accepted,
            'status' => $request->status ? $request->status : 'active'
        ]);
        $paymentType->save();
        return response()->json($paymentType, 201);
    }

    public function update(Request $request, $id)
    {
        $paymentType = PaymentType::find($id);
        if(!$paymentType){
            return response()->json(['message' => 'Payment type not found'], 404);
            exit;
        }

        // Validacines de campos
        $this->validatePaymentType($request, $paymentType);

        // Update
        $paymentType->name = $request->name;
        $paymentType->description = $request->description;
        if(isset($request->card_accepted)) $paymentType->card_accepted = boolval($request->card_accepted);
        if(isset($request->cash_accepted)) $paymentType->cash_accepted = boolval($request->cash_accepted);
        $paymentType->status = $request->status ? $request->status : $paymentType->status;
        $paymentType->save();
        return response()->json($paymentType);
    }

    public function validatePaymentType(Request $request, $paymentType = null)
    {
        $rules = [
            'name' => 'required|min:3|max:80',
            'card_accepted' => 'boolean',
            'cash_accepted' => 'boolean',
            'status' => 'in:active,inactive'
        ];

        if(!$paymentType){
            $rules['name'] .= '|unique:payment_types,name';
        } else{
            $rules['name'] .= $paymentType->name === $request->name ? '' : '|unique:payment_types,name';
        }

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new HttpResponseException(response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422));
        }
    }
}
