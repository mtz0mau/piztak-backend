<?php

namespace App\Http\Controllers;

use App\Models\DeliveryOption;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryOptionController extends Controller
{
    protected $model = DeliveryOption::class;

    public function index(Request $request)
    {
        $where = [];
        $model = new $this->model();
        foreach($model->getFillable() as $column){
            if($param = $request->input($column)) $where[$column] = $param;            
        }

        $data = $this->model::where($where)->get();
    
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
        $this->validateDeliveryOption($request);

        $deliveryOption = new DeliveryOption([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ? $request->status : 'active'
        ]);

        $deliveryOption->save();
        return response()->json($deliveryOption, 201);
    }

    public function update(Request $request, $id)
    {
        $deliveryOption = DeliveryOption::find($id);
        if(!$deliveryOption){
            return response()->json(['message' => 'Delivery option not found'], 404);
        }

        $this->validateDeliveryOption($request, $deliveryOption);

        $deliveryOption->name = $request->name;
        $deliveryOption->description = $request->description;
        $deliveryOption->status = $request->status ? $request->status : $deliveryOption->status;
        $deliveryOption->save();

        return response()->json($deliveryOption);
    }

    public function validateDeliveryOption(Request $request, $deliveryOption = null)
    {
        $rules = [
            'name' => 'required|min:3|max:80',
            'status' => 'in:active,inactive'
        ];

        if(!$deliveryOption){
            $rules['name'] .= '|unique:delivery_options,name';
        } else{
            $rules['name'] .= $deliveryOption->name === $request->name ? '' : '|unique:delivery_options,name';
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
