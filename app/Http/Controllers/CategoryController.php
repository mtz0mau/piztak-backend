<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    protected $model = Category::class;

    public function store(Request $request)
    {
        $this->validateCategory($request);
        $nameImage = "";

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('images');
            $nameImage = $path;
        }
        $category = new Category([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ? $request->status : 'active',
            'image' => $nameImage
        ]);
        $category->save();
        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if(!$category){
            throw new HttpResponseException(response()->json([
                'message' => 'Category not found.'
            ], 404));
        }

        $this->validateCategory($request, $category);

        $nameImage = "";
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('images');
            $nameImage = $path;
        }
        if(!empty($nameImage) && $category->image) Storage::delete($category->image);

        $category->name = $request->name ? $request->name : $category->name;
        $category->description = $request->description;
        $category->status = $request->status ? $request->status : $category->status;
        $category->image = $nameImage ?? $category->image;
        $category->save();
        return response()->json($category);
    }

    public function getImage($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image) {
            $path = storage_path('app/' . $category->image);
            $contents = file_get_contents($path);
            $response = response($contents, 200)
                ->header('Content-Type', 'image/webp');
            return $response;
        } else {
            abort(404);
        }
    }

    public function validateCategory(Request $request, $category = null)
    {
        $rules = [
            'name' => 'max:80',
            'status' => 'in:active,inactive'
        ];

        if(!$category){
            $rules['name'] .= '|required|unique:categories,name';
        } else{
            $rules['name'] .= $request->name === $category->name ? '' : '|unique:categories,name';
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
