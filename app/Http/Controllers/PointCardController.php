<?php

namespace App\Http\Controllers;

use App\Models\PointCard;
use App\Models\PointCardHistory;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PointCardController extends Controller
{
    protected $model = PointCard::class;

    public function store(Request $request)
    {
        $this->validatePointCard($request);

        $pointCard = new PointCard([
            'status' => $request->status ? $request->status : 'active',
            'point_card_type_id' => $request->point_card_type_id
        ]);
        $pointCard->save();
        return response()->json($pointCard, 201);
    }

    public function update(Request $request, $id)
    {
        $pointCard = PointCard::find($id);
        if(!$pointCard){
            throw new HttpResponseException(response()->json([
                'message' => 'Point Card not found.'
            ], 404));
        }

        $this->validatePointCard($request);

        $pointCard->points = $request->points ? $request->points : $pointCard->points;
        $pointCard->status = $request->status ? $request->status : $pointCard->status;
        $pointCard->save();
        return response()->json($pointCard, 200);
    }

    public function validatePointCard(Request $request)
    {
        $rules = [
            'status' => 'in:active,inactive',
            'point_card_type_id' => 'required|exists:point_card_types,id',
            'points' => 'numeric'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            throw new HttpResponseException(response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422));
        }
    }

    // Historires
    public function indexHistories(Request $request, $id)
    {
        $where = ['point_card_id' => $id];
        $model = new PointCardHistory();
        foreach($model->getFillable() as $column){
            if($param = $request->input($column)) $where[$column] = $param;            
        }

        $data = PointCardHistory::where($where)->get();
    
        $metadata = [
            'count' => count($data),
            'version' => '1.0',
            'author' => 'Mauricio Martinez Martinez',
        ];
    
        return response()->json($data, 200, [ 'X-Metadata' => json_encode($metadata) ]);
    }
}
