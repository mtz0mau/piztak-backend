<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $model = Product::class;

    public function index(Request $request)
    {
        $where = [];
        $model = new $this->model();

        foreach($model->getFillable() as $column){
            if($param = $request->input($column)) $where[$column] = $param;            
        }

        $products = $this->model::where($where)->get();

        if($request->has('sizes')) foreach($products as $product) $product->sizes;
        if($request->has('extraIngredients')) foreach($products as $product) foreach($product->sizes as $size) $size->extraIngredients;
    
        $metadata = [
            'pagination' => [
                'count' => count($products),
                'page' => 1,
                'pageSize' => 1
            ],
            'version' => '1.0',
            'author' => 'Mauricio Martinez Martinez',
        ];

        $response = [
            "data" => $products,
            "meta" => $metadata  
        ];
    
        return response()->json($response, 200);
    }

    public function show($id)
    {
        $register = $this->model::find($id);
        $register->sizes;
        if ($register) {
            return response()->json($register);
        } else {
            return response()->json(['message' => 'Register not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $this->validateProduct($request);

        $nameImage = "";

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('images');
            $nameImage = $path;
        }

        $product = new Product([
            'name' => $request->name,
            'is_special' => $request->is_special ? $request->is_special : false,
            'sub_category_id' => $request->sub_category_id,
            'category_id' => $request->category_id,
            'image' => $nameImage
        ]);
        $product->save();
        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        

        if(!$product){
            throw new HttpResponseException(response()->json([
                'message' => 'Product not found.'
            ], 422));
        }

        $this->validateProduct($request, $product);

        $nameImage = "";
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('images');
            $nameImage = $path;
        }

        if(!empty($nameImage) && $product->image) Storage::delete($product->image);
        
        $product->name = $request->name ? $request->name : $product->name;
        $product->is_special = $request->is_special ? $request->is_special : $product->is_special;
        $product->sub_category_id = $request->sub_category_id ? $request->sub_category_id : $product->sub_category_id;
        $product->category_id = $request->category_id ? $request->category_id : $product->category_id;
        $product->image = $nameImage ? $nameImage : $product->image;
        $product->save();
        return response()->json($product);
    }

    public function getImage($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image) {
            $path = storage_path('app/' . $product->image);
            $contents = file_get_contents($path);
            $response = response($contents, 200)
                ->header('Content-Type', 'image/webp');
            return $response;
        } else {
            abort(404);
        }
    }

    public function validateProduct(Request $request, $product = null)
    {
        $rules = [
            'name' => 'max:80',
            'is_special' => 'boolean',
            'sub_category_id' => 'integer|exists:sub_categories,id',
            'category_id' => 'integer|exists:categories,id',
            'image' => 'image'
        ];

        if(!$product){
            $rules['name'] .= '|required|unique:products,name';
            $rules['image'] .= '|required';
            $rules['category_id'] .= '|required';
            $rules['sub_category_id'] .= '|required';
        } else{
            $rules['name'] .= $request->name === $product->name ? '' : '|unique:products,name';
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
