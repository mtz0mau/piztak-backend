<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $model = null;

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

    public function show($id)
    {
        $register = $this->model::find($id);
        if ($register) {
            return response()->json($register);
        } else {
            return response()->json(['message' => 'Register not found'], 404);
        }
    }

    public function destroy($id)
    {
        $register = $this->model::find($id);
        if(!$register){
            throw new HttpResponseException(response()->json([
                'message' => 'Register not found.'
            ], 404));
        }

        $register->update(['status' => 'inactive']);

        return response()->json([
            'message' => 'Customer deleted successfully',
            'data' => $register
        ], 200);
    }
}