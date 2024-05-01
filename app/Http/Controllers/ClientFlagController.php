<?php

namespace App\Http\Controllers;

use App\Models\ClientFlag;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientFlagController extends Controller
{
    protected $model = ClientFlag::class;

    public function store(Request $request)
    {
        $this->validateClientFlag($request);

        $clientFlag = new ClientFlag([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ? $request->status : 'active'
        ]);
        $clientFlag->save();
        return response()->json($clientFlag, 201);
    }

    public function update(Request $request, $id)
    {
        $clientFlag = ClientFlag::find($id);
        if(!$clientFlag){
            return response()->json([
                'message' => 'Bandera no encontrada.'
            ], 404);
            exit;
        }

        $this->validateClientFlag($request, $clientFlag);

        $clientFlag->name = $request->name;
        $clientFlag->description = $request->description;
        $clientFlag->status = $request->status ? $request->status : $clientFlag->status;
        $clientFlag->save();
        return response()->json($clientFlag);
    }

    public function validateClientFlag(Request $request, $clientFlag = null)
    {
        $rules = [
            'name' => 'required',
            'status' => 'in:active,inactive'
        ];

        if(!$clientFlag){
            $rules['name'] .= "|unique:client_flags,name";
        } else{
            $rules['name'] .= $clientFlag->name === $request->name ? '' : '|unique:client_flags,name';
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
