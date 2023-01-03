<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\FileUploader;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function addProducts(Request $request)
    {
        $request->validate([
            'seller_id' => 'required',
            'customer_name' => 'required',
            'customer_number' => 'required',
            'customer_pic' => 'required',
            'documents' => 'required',
            'product_image' => 'required',
            'imei_number' => 'required',
            'customer_buy_price' => 'required',
            'product_title' => 'required',
            'product_description' => 'required',
            'product_selling_price' => 'required',
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
        if (isset($request->documents)) {
            foreach ($request->documents as $documents) {
                $image[] = FileUploader::uploadFile($documents, 'images/documents');
            }
            $storeImage = implode(",", $image);
            $data->documents = $storeImage;
        } else {
            $data->documents = "N/A";
        }
        if (isset($request->product_image)) {
            foreach ($request->product_image as $product_image) {
                $image[] = FileUploader::uploadFile($product_image, 'images/product-image');
            }
            $storeImage = implode(",", $image);
            $data->product_image = $storeImage;
        } else {
            $data->product_image = "N/A";
        }
        $data->imei_number = $request->imei_number;
        $data->customer_buy_price = $request->customer_buy_price;
        $data->product_image = $request->product_image;
        $data->product_title = $request->product_title;
        $data->product_description = $request->product_description;
        $data->product_selling_price = $request->product_selling_price;
        $data->sold_to_customer_name = $request->sold_to_customer_name;
        $data->sold_to_customer_number = $request->sold_to_customer_number;
        $data->product_sold_price = $request->product_sold_price;
        $data->save();
        return response('Products added Successfully', 200);
    }

    public function getProduct()
    {
        $data = Product::all();
        return response($data, 200);
    }

    public function soldProduct(Request $request)
    {
        $request->validate([
            // 'product_id' => 'required',
            // 'sold_to_customer_name' => 'required',
            // 'sold_to_customer_number' => 'required',
            // 'product_sold_price' => 'required',
        ]);
        $data = Product::findOrFail($request->product_id);
        $data->sold_to_customer_name = $request->sold_to_customer_name;
        $data->sold_to_customer_number = $request->sold_to_customer_number;
        $data->product_sold_price = $request->product_sold_price;
        $data->status = "sold";
        $data->save();
        return response('Product sold to ' . $request->sold_to_customer_name, 200);
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
        $request->validate([
            'product_id' => 'required',
        ]);
        $data = Product::findOrFail($request->product_id);
        $data->status = "inventory";
        $data->save();
        return response('Product ' . $data->product_title . 'is shifted to inventory');
    }
    public function getLiveProducts()
    {
        $data = Product::where('status', 'livesell')->get();
        return response($data, 200);
    }

    public function getInventoryProducts()
    {
        $data = Product::where('status', 'inventory')->get();
        return response($data, 200);
    }

    public function getsoldproducts()
    {
        $data = Product::where('status', 'sold')->get();
        return response($data, 200);
    }

}
