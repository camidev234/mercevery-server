<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function createProduct(ProductRequest $request)
    {
        try {
            $newProduct = new Product();
            $newProduct->product_name = $request->product_name;
            $newProduct->normal_price = $request->normal_price;
            $newProduct->actual_price = $request->actual_price;
            $newProduct->company_id = $request->company_id;
            $newProduct->category_id = $request->category_id;
            $newProduct->save();

            return response()->json([
                'message' => 'product created successfully',
                'product' => new ProductResource($newProduct->load('category'))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'server error',
                'status_code' => 500
            ], 500);
        }
    }
}
