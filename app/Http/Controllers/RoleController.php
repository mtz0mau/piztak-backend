<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    protected $model = Role::class;

    public function store(Request $request)
    {
        $this->validateRole($request);
        $role = new Role([
            'name' => $request->name,
            'status' => $request->status ?? 'active'
        ]);
        $role->save();
        return response()->json($role, 201);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if(!$role){
            throw new HttpResponseException(response()->json([
                'message' => 'Role not found.'
            ], 404));
        }

        $this->validateRole($request, $role);
        $role->name = $request->name ?? $role->name;
        $role->status = $request->status ?? $role->status;
        $role->save();
        return response()->json($role);
    }

    public function validateRole(Request $request, $role = null)
    {
        $rules = [
            'name' => 'max:80',
            'status' => 'in:active,inactive'
        ];

        if(!$role){
            $rules['name'] .= '|required|unique:roles,name';
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