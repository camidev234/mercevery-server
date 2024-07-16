<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    public function createProduct(ProductRequest $request)
    {
        try {
            // instance new product
            $newProduct = new Product();
            // asign the properties
            $newProduct->product_name = $request->product_name;
            $newProduct->normal_price = $request->normal_price;
            $newProduct->actual_price = $request->actual_price;
            $newProduct->company_id = $request->company_id;
            $newProduct->category_id = $request->category_id;
            $newProduct->save();

            // if the user was created, return the info about him
            return response()->json([
                'message' => 'product created successfully',
                'product' => new ProductResource($newProduct->load('category')),
                'status_code' => 201
            ], 201);
            // get generic exception
        } catch (\Exception $e) {
            // return the error message
            return response()->json([
                'error' => 'server error',
                'status_code' => 500
            ], 500);
        }
    }

    public function show($product)
    {
        try {
            // find the product passed on path parameters
            $productFind = Product::find($product);
            // if product does not exists throw exception to catch
            if (!$productFind) {
                throw new NotFoundHttpException();
            }

            //else, return info about the product and status code 200
            return response()->json(new ProductResource($productFind->load('category')), 200);
            //catch not found exception
        } catch (NotFoundHttpException $exception) {
            return response()->json([
                'error' => 'This product does not exists',
                'status_code' => 400
            ], 404);
            // catch generic exception
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'server error',
                'status_code' => 500,
                'messgae' => $e->getMessage()
            ], 500);
        }
    }
}
