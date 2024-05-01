<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\CashRegisterHistory;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CashRegisterController extends Controller
{
    protected $model = CashRegister::class;

    public function index(Request $request)
    {
        $where = [];
        $model = new $this->model();
        foreach($model->getFillable() as $column){
            if($param = $request->input($column)) $where[$column] = $param;            
        }

        $data = $this->model::where($where)->with(['cashRegisterHistories' => function($query){
            $start_date = $_GET['start_date'] ?? date('Y-m-d');
            $end_date = $_GET['end_date'] ?? date('Y-m-').date('d')+1;
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }])->get();
    
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
        $this->validateCashRegister($request);
        $cashRegister = new CashRegister([
            'description' => $request->description,
            'balance' => $request->balance ? $request->balance : 0,
            'status' => $request->status ? $request->status : 'active'
        ]);
        $cashRegister->save();
        return response()->json($cashRegister, 201);
    }

    public function update(Request $request, $id)
    {
        $cashRegister = CashRegister::find($id);
        if(!$cashRegister){
            throw new HttpResponseException(response()->json([
                'message' => 'Cash Register not found.'
            ], 404));
        }
        $this->validateCashRegister($request);

        $cashRegister->description = $request->description ? $request->description : $cashRegister->description;
        $cashRegister->balance = $request->balance ? $request->balance : $cashRegister->balance;
        $cashRegister->status = $request->status ? $request->status : $cashRegister->status;
        $cashRegister->save();
        return response()->json($cashRegister, 200);
    }

    public function histories(Request $request, $id)
    {
        $cash_register = CashRegister::find($id);
        if(!$cash_register){
            throw new HttpResponseException(response()->json([
                'message' => 'Cash Register not found.'
            ], 404));
        }

        $amount = floatval($request->get('amount'));
        $action = $request->get('action');
        $description = $request->get('description');

        if($action === 'subtract'){
            if($cash_register->balance < $amount){
                throw new HttpResponseException(response()->json([
                    'message' => 'Validation Failed',
                    'errors' => [
                        'amount' => ['No hay suficiente efectivo en caja.']
                    ]
                ], 404)); 
            }
        }

        CashRegisterHistory::create([
            'amount' => $amount,
            'action' => $action,
            'description' => $description,
            'previous_amount' => $cash_register->balance,
            'cash_register_id' => $cash_register->id
        ]);
        $cash_register->balance = $action === 'add' ? $cash_register->balance + $amount : $cash_register->balance - $amount;
        $cash_register->save();

        $cash_register = $this->model::where(['id' => 1])->with(['cashRegisterHistories' => function($query){
            $start_date = $_GET['start_date'] ?? date('Y-m-d');
            $end_date = $_GET['end_date'] ?? date('Y-m-').date('d')+1;
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }])->first();
        return response()->json([
            "data" => $cash_register
        ]);
    }

    public function validateCashRegister(Request $request)
    {
        $rules = [
            'balance' => 'numeric',
            'status' => 'in:active,inactive'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new HttpResponseException(response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422));
        }
    }
}