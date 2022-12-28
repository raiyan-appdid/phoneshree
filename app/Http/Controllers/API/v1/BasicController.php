<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\FileUploader;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Seller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BasicController extends Controller
{
    public function sellerRegister(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'number' => 'required',
            'email' => 'required',
            'shop_name' => 'required',
            'short_description' => 'required',
            // 'shop_image' => 'required',
        ]);
        $data = new Seller;
        $data->name = $request->name;
        $data->number = $request->number;
        $data->email = $request->email;
        $data->shop_name = $request->shop_name;
        $data->short_description = $request->short_description;
        $data->membership_expiry_date = Carbon::now()->addDays(7);
        // if (isset($request->shop_image)) {
        //     $data->shop_image = FileUploader::uploadFile($request->shop_image, 'images/seller');
        // } else {
        //     $data->shop_image = "N/A";
        // }
        $data->address = $request->address;
        $data->save();
        return response('Seller Registered Successfully', 200);
    }

    public function addProducts(Request $request)
    {
        $request->validate([
            'seller_id' => 'required',
            'customer_name' => 'required',
            'customer_number' => 'required',
            'customer_pic' => 'required',
            'documents' => 'required',
            'documents' => 'required',
            'product_image' => 'required',
            'imei_number' => 'required',
            'customer_buy_price' => 'required',
            'product_title' => 'required',
            'product_description' => 'required',
            'product_selling_price' => 'required',
            'sold_to_customer_name' => 'required',
            'sold_to_customer_number' => 'required',
            'product_sold_price' => 'required',
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
        $data->emei_number = $request->imei_number;
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

    public function soldProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'sold_to_customer_name' => 'required',
            'sold_to_customer_number' => 'required',
            'product_sold_price' => 'required',
        ]);
        $data = Product::findOrFail($request->product_id);
        $data->sold_to_customer_name = $request->sold_to_customer_name;
        $data->sold_to_customer_number = $request->sold_to_customer_number;
        $data->product_sold_price = $request->product_sold_price;
        $data->status = "sold";
        $data->save();
        return response('Product sold to ' . $request->sold_to_customer_name, 200);
    }

    public function sellerDetails(Request $request)
    {
        $request->validate([
            'seller_id' => 'required',
        ]);
        $data = Seller::findOrFail($request->seller_id);
        return response($data, 200);
    }

    public function sellerEdit(Request $request)
    {
        $request->validate([
            'seller_id' => 'required',
            'number' => 'required',
            'email' => 'required',
            'shop_name' => 'required',
            'short_description' => 'required',
            'shop_image' => 'required',
            'address' => 'required',
        ]);
        $data = Seller::findOrFail($request->seller_id);
        $data->name = $request->name;
        $data->number = $request->number;
        $data->email = $request->email;
        $data->shop_name = $request->shop_name;
        $data->short_description = $request->short_description;
        if (isset($request->shop_image)) {
            $data->shop_image = FileUploader::uploadFile($request->shop_image, 'images/seller');
        } else {
            $data->shop_image = "N/A";
        }
        $data->address = $request->address;
        $data->save();
        return response("Profile Updated Successfully", 200);
    }
}
