<?php

namespace App\Http\Controllers;

use App\Models\PointCardType;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PointCardTypeController extends Controller
{
    protected $model = PointCardType::class;

    public function store(Request $request)
    {
        $this->validatePointCardType($request);

        $pointCardType = new PointCardType([
            'name' => $request->name,
            'discount' => $request->discount,
            'status' => $request->status ? $request->stauts : 'active'
        ]);
        $pointCardType->save();
        return response()->json($pointCardType, 201);
    }

    public function update(Request $request, $id)
    {
        $pointCardType = PointCardType::find($id);
        if(!$pointCardType){
            return response()->json(['message' => 'Point card type not found'], 404);
            exit;
        }

        $this->validatePointCardType($request, $pointCardType);

        $pointCardType->name = $request->name;
        $pointCardType->discount = $request->discount;
        $pointCardType->status = $request->status ? $request->status : $pointCardType->status;
        $pointCardType->save();
        return response()->json($pointCardType, 200);
    }

    public function validatePointCardType(Request $request, $pointCardType = null)
    {
        $rules = [
            'name' => 'required',
            'discount' => 'required|numeric',
            'status' => 'in:active,inactive'
        ];

        if(!$pointCardType){
            $rules['name'] .= '|unique:point_card_types,name';
        } else{
            $rules['name'] .= $request->name === $pointCardType->name ? '' : '|unique:point_card_types,name';
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
