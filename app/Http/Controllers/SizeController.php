<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    protected $model = Size::class;

    public function store(Request $request)
    {
        $this->validateSize($request);
        $size = new Size([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ? $request->status : 'active',
            'category_id' => $request->category_id
        ]);
        $size->save();
        return response()->json($size, 201);
    }

    public function update(Request $request, $id)
    {
        $size = Size::find($id);
        if(!$size){
            throw new HttpResponseException(response()->json([
                'message' => 'Size not found.',
            ], 404));
        }

        $this->validateSize($request, $size);
        $size->name = $request->name ? $request->name : $size->name;
        $size->description = $request->description ? $request->description : $size->description;
        $size->status = $request->status ? $request->status : $size->status;
        $size->category_id = $request->category_id ? $request->category_id : $size->category_id;
        $size->save();
        return response()->json($size);
    }

    public function validateSize(Request $request, $size = null)
    {
        $rules = [
            'name' => 'max:80',
            'status' => 'in:active,inactive',
            'category_id' => 'exists:categories,id'
        ];

        if(!$size){
            $rules['name'] .= '|required|unique:sizes,name';
            $rules['category_id'] .= '|required';
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
