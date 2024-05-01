<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    protected $model = Coupon::class;

    public function store(Request $request)
    {
        $this->validateCoupon($request);
        $coupon = new Coupon([
            'discount' => $request->discount,
            'type_discount' => $request->type_discount,
            'limit' => $request->limit ?? 1,
            'description' => $request->description,
            'status' => $request->status ?? 'active',
            'user_id' => $request->user_id
        ]);
        $coupon->generateCouponCode();
        $coupon->save();
        return response()->json($coupon, 201);
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::find($id);
        if(!$coupon){
            throw new HttpResponseException(response()->json([
                'message' => 'Coupon not found.',
            ], 404));
        }
        $this->validateCoupon($request, $coupon);
        $coupon->discount = $request->discount ?? $coupon->discount;
        $coupon->type_discount = $request->type_discount ?? $coupon->type_discount;
        $coupon->limit = $request->limit ?? $coupon->limit;
        $coupon->description = $request->description ?? $coupon->description;
        $coupon->status = $request->status ?? $coupon->status;
        $coupon->user_id = $request->user_id ?? $coupon->user_id;
        $coupon->save();
        return response()->json($coupon);
    }

    public function validateCoupon(Request $request, $coupon = null)
    {
        $rules = [
            'discount' => 'numeric',
            'type_discount' => 'in:percentage,cash',
            'limit' => 'numeric',
            'status' => 'in:active,inactive',
            'user_id' => 'exists:users,id'
        ];

        if(!$coupon) {
            $rules['discount'] .= '|required';
            $rules['type_discount'] .= '|required';
            $rules['user_id'] .= '|required';
        }

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new HttpResponseException(response()->json([
                'Message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422));
        }
    }
}
