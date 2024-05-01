<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    protected $model = SubCategory::class;

    public function store(Request $request)
    {
        $this->validateSubCategory($request);
        $subCategory = new SubCategory([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ? $request->status : 'active',
            'category_id' => $request->category_id
        ]);
        $subCategory->save();
        return response()->json($subCategory, 201);
    }

    public function update(Request $request, $id)
    {
        $subCategory = SubCategory::find($id);
        if(!$subCategory){
            throw new HttpResponseException(response()->json([
                'message' => 'Sub Category not found.'
            ], 404));
        }

        $this->validateSubCategory($request, $subCategory);
        $subCategory->name = $request->name ? $request->name : $subCategory->name;
        $subCategory->category_id = $request->category_id ? $request->category_id : $subCategory->category_id;
        $subCategory->description = $request->description ? $request->description : $subCategory->description;
        $subCategory->status = $request->status ? $request->status : $subCategory->status;
        $subCategory->save();
        return response()->json($subCategory);
    }

    public function validateSubCategory(Request $request, $subCategory = null)
    {
        $rules = [
            'name' => 'max:80',
            'status' => 'in:active,inactive',
            'category_id' => 'exists:categories,id'
        ];

        if(!$subCategory){
            $rules['name'] .= '|required|unique:sub_categories,name';
            $rules['category_id'] .= '|required';
        } else{
            $rules['name'] .= $request->name === $subCategory->name ? '' : '|unique:sub_categories,name';
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