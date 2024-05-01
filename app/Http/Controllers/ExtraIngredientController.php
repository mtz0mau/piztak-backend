<?php

namespace App\Http\Controllers;

use App\Models\ExtraIngredient;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExtraIngredientController extends Controller
{
    protected $model = ExtraIngredient::class;

    public function store(Request $request)
    {
        $this->validateExtraIngredient($request);
        $extraIngredient = new ExtraIngredient([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'status' => $request->status ?? 'active'
        ]);
        $extraIngredient->save();
        return response()->json($extraIngredient, 201);
    }

    public function update(Request $request, $id)
    {
        $extraIngredient = ExtraIngredient::find($id);
        if(!$extraIngredient){
            throw new HttpResponseException(response()->json([
                'message' => 'Validation failed.'
            ], 404));
        }
        $this->validateExtraIngredient($request, $id);
        $extraIngredient->name = $request->name ?? $extraIngredient->name;
        $extraIngredient->category_id = $request->category_id ?? $extraIngredient->category_id;
        $extraIngredient->status = $request->status ?? $extraIngredient->status;
        $extraIngredient->save();
        return response()->json($extraIngredient);
    }

    public function validateExtraIngredient(Request $request, $extraIngredient = null)
    {
        $rules = [
            'name' => 'max:80',
            'status' => 'in:active,inactive',
            'category_id' => 'exists:categories,id'
        ];

        if(!$extraIngredient){
            $rules['name'] .= '|required';
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
