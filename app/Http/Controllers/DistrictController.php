<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DistrictController extends Controller
{
    protected $model = District::class;

    public function index(Request $request)
    {
        $where = [];
        $model = new $this->model();
        foreach($model->getFillable() as $column){
            if($param = $request->input($column)) $where[$column] = $param;            
        }

        $data = $this->model::where($where)->with('deliveryOptions')->get();
    
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
        // Validar
        $this->validateDistrict($request);

        // Crear el objeto
        $district = new District([
            'name' => $request->name,
            'status' => $request->status ? $request->status : 'active',
        ]);

        $district->save();

        // Devolver una respuesta adecuada
        return response()->json($district, 201);
    }

    public function update(Request $request, $id)
    {
        $district = District::find($id);
        $this->validateDistrict($request);
        
        if(!$district){
            return response()->json(['message' => 'Colonia no encontrada'], 404);
            exit;
        }

        $district->name = $request->name;
        $district->status = $request->status ? $request->status : $district->status;
        $district->save();

        return response()->json($district);
    }

    public function validateDistrict(Request $request)
    {
        $rules = [
            'name' => 'min:3|max:80|required',
            'status' => 'in:active,inactive'
        ];

        $customMessages = [
            'min' => 'El campo debe ser al menos de :min caracteres.',
            'max' => 'El campo no debe ser de más de :max caracteres.',
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
