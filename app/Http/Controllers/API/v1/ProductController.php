<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\FileUploader;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function addProducts(Request $request)
    {
        \Log::info($request->all());
        $request->validate([
            'seller_id' => 'required',
            'customer_name' => 'required',
            'customer_number' => 'required',
            'customer_pic' => 'required',
            'imei_number' => 'required',
            'customer_buy_price' => 'required',
            'product_title' => 'required',
            'product_description' => 'required',
            'document' => 'required',
            'product' => 'required',
        ]);
        $data = new Product;
        $data->seller_id = $request->seller_id;
        $data->customer_name = $request->customer_name;
        $data->customer_number = $request->customer_number;
        if (isset($request->customer_pic)) {
            $data->customer_pic = FileUploader::uploadFile($request->customer_pic, 'images/customer');
        } else {
            $data->customer_pic = "N/A";
        }
        // if (isset($request->documents)) {
        //     foreach ($request->documents as $documents) {
        //         $image[] = FileUploader::uploadFile($documents, 'images/documents');
        //     }
        //     $storeImage = implode(",", $image);
        //     $data->documents = $storeImage;
        // } else {
        //     $data->documents = "N/A";
        // }
        // if (isset($request->product_image)) {
        //     foreach ($request->product_image as $product_image) {
        //         $image[] = FileUploader::uploadFile($product_image, 'images/product-image');
        //     }
        //     $storeImage = implode(",", $image);
        //     $data->product_image = $storeImage;
        // } else {
        //     $data->product_image = "N/A";
        // }
        $data->imei_number = $request->imei_number;
        $data->customer_buy_price = $request->customer_buy_price;
        // $data->product_image = $request->product_image;
        $data->product_title = $request->product_title;
        $data->product_description = $request->product_description;
        $data->product_selling_price = $request->product_selling_price;
        $data->sold_to_customer_name = $request->sold_to_customer_name;
        $data->sold_to_customer_number = $request->sold_to_customer_number;
        $data->product_sold_price = $request->product_sold_price;
        $data->save();

        //storing multiple images
        foreach ($request->product as $item) {
            $productImage = new ProductImage;
            $productImage->product_id = $data->id;
            $productImage->image = FileUploader::uploadFile($item, 'images/product-image');
            $productImage->save();
        }
        foreach ($request->document as $item) {
            $documentImage = new Document;
            $documentImage->product_id = $data->id;
            $documentImage->image = FileUploader::uploadFile($item, 'images/document');
            $documentImage->save();
        }
        return response('Products added Successfully', 200);
    }

    public function getProduct(Request $request)
    {
        $request->validate([
            'seller_id' => 'required',
        ]);
        $data = Product::where('seller_id', $request->seller_id)->with(['productImage', 'document'])->get();
        return response($data, 200);
    }

    public function soldProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'sold_to_customer_name' => 'required',
            'sold_to_customer_number' => 'required',
            'product_sold_price' => 'required',
        ]);
        $data = Product::with(['productImage', 'document'])->find($request->product_id);
        $data->sold_to_customer_name = $request->sold_to_customer_name;
        $data->sold_to_customer_number = $request->sold_to_customer_number;
        $data->product_sold_price = $request->product_sold_price;
        $data->status = "sold";
        $data->save();
        return response([
            'message' => 'Success',
            'data' => "sold to " . $request->sold_to_customer_name,
        ], 200);
    }

    public function productToLive(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'product_selling_price' => 'required',
        ]);
        $data = Product::findOrFail($request->product_id);
        $data->product_selling_price = $request->product_selling_price;
        $data->status = "livesell";
        $data->save();
        return response('Product ' . $data->product_title . 'is now live', 200);
    }
    public function productToInvetory(Request $request)
    {
        $data = Product::with(['productImage', 'document'])->find($request->product_id);
        $data->status = "inventory";
        $data->save();
        return response('Product ' . $data->product_title . 'is shifted to inventory');
    }
    public function getLiveProducts(Request $request)
    {
        $request->validate([
            'seller_id' => 'required',
        ]);
        $data = Product::where('status', 'livesell')->where('seller_id', $request->seller_id)->with(['productImage', 'document'])->get();
        return response($data, 200);
    }

    public function getInventoryProducts(Request $request)
    {
        $request->validate([
            'seller_id' => 'required',
        ]);
        $data = Product::where('status', 'inventory')->where('seller_id', $request->seller_id)->with(['productImage', 'document'])->get();
        return response($data, 200);
    }

    public function getsoldproducts(Request $request)
    {
        $request->validate([
            'seller_id' => 'required',
        ]);
        $data = Product::where('status', 'sold')->where('seller_id', $request->seller_id)->with(['productImage', 'document'])->orderBy('created_at', 'desc')->get();
        return response($data, 200);
    }

}
