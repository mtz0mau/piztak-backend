<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    protected $model = Address::class;

    public function store(Request $request)
    {
        $this->validateAddress($request);

        $address = new Address([
            'street' => $request->street,
            'street_number' => $request->street_number,
            'interior_number' => $request->interior_number,
            'postal_code' => $request->postal_code,
            'references' => $request->references,
            'customer_id' => $request->customer_id,
            'status' => $request->status ? $request->status : 'active',
        ]);
        
        $address->save();

        // Devolver una respuesta adecuada
        return response()->json($address, 201);
    }

    public function update(Request $request, $id)
    {
        $address = Address::find($id);
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

        return response()->json($address);
    }

    protected function validateAddress(Request $request)
    {
        $rules = [
            'street' => 'max:80|required',
            'street_number' => 'max:20|required',
            'interior_number' => 'max:20',
            'postal_code' => 'max:10',
            'references' => 'min:3',
            'customer_id' => 'required|exists:customers,id',
            'district_id' => 'exists:districts,id',
            'status' => 'in:active,inactive',
        ];

        $customMessages = [
            'street' => [
                'required' => 'El campo calle es obligatorio.'
            ],
            'customer_id' => [
                'exists' => 'El cliente seleccionado no existe.'
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
}
