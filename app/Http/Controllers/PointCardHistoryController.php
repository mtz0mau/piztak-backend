<?php

namespace App\Http\Controllers;

use App\Models\PointCardHistory;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PointCardHistoryController extends Controller
{
    protected $model = PointCardHistory::class;

    public function store(Request $request)
    {
        $this->validatePointCardHistory($request);
        $pointCardHistory = new PointCardHistory([
            'amount' => $request->amount,
            'action' => $request->action,
            'description' => $request->description,
            'point_card_id' => $request->point_card_id,
            'status' => $request->status ? $request->status : 'active'
        ]);
        $pointCardHistory->previous_amount = $pointCardHistory->pointCard->points;
        $pointCardHistory->save();
        return response()->json($pointCardHistory, 201);
    }

    public function update(Request $request, $id)
    {
        $pointCardHistory = PointCardHistory::find($id);
        if(!$pointCardHistory){
            throw new HttpResponseException(response()->json([
                'message' => 'Point card history not found.'
            ], 404));
        }

        $this->validatePointCardHistory($request, $pointCardHistory);
        $pointCardHistory->amount = $request->amount ? $request->amount : $pointCardHistory->amount;
        $pointCardHistory->action = $request->action ? $request->action : $pointCardHistory->action;
        $pointCardHistory->point_card_id = $request->point_card_id ? $request->point_card_id : $pointCardHistory->point_card_id;
        $pointCardHistory->description = $request->description ? $request->description : $pointCardHistory->description;
        $pointCardHistory->status = $request->status ? $request->status : $pointCardHistory->status;
        $pointCardHistory->save();
        return response()->json($pointCardHistory, 200);
    }

    public function validatePointCardHistory(Request $request, $pointCardHistory = null)
    {
        $rules = [
            'amount' => 'numeric',
            'action' => 'in:add,subtract',
            'status' => 'in:active,inactive',
            'point_card_id' => 'exists:point_cards,id'
        ];

        if(!$pointCardHistory){
            $rules['amount'] .= '|required';
            $rules['action'] .= '|required';
            $rules['point_card_id'] .= '|required';
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
