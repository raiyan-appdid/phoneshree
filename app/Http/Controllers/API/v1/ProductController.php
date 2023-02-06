<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\FileUploader;
use App\Http\Controllers\Controller;
use App\Models\ActiveFeaturedProduct;
use App\Models\Document;
use App\Models\FeaturedProductTransaction;
use App\Models\Product;
use App\Models\ProductImage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function addProducts(Request $request)
    {
        $request->validate([
            'seller_id' => 'required',
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
        if (isset($request->product)) {
            foreach ($request->product as $item) {
                $productImage = new ProductImage;
                $productImage->product_id = $data->id;
                $productImage->image = FileUploader::uploadFile($item, 'images/product-image');
                $productImage->save();
            }
        }
        if (isset($request->document)) {
            foreach ($request->document as $item) {
                $documentImage = new Document;
                $documentImage->product_id = $data->id;
                $documentImage->image = FileUploader::uploadFile($item, 'images/document');
                $documentImage->save();
            }
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
        $productTransaction = FeaturedProductTransaction::where('product_id', $request->product_id)->first();
        if (isset($productTransaction)) {
            if (Carbon::parse($productTransaction->expiry_date) >= Carbon::today()) {
                $data = new ActiveFeaturedProduct;
                $data->city_id = $productTransaction->city_id;
                $data->product_id = $productTransaction->product_id;
                $data->featured_product_transaction_id = $productTransaction->id;
                $data->expiry_date = $productTransaction->expiry_date;
                $data->save();
            }
        }
        return response('Product ' . $data->product_title . 'is now live', 200);
    }
    public function productToInvetory(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
        ]);
        $data = Product::with(['productImage', 'document'])->find($request->product_id);
        $data->status = "inventory";
        $data->save();
        $activeProduct = ActiveFeaturedProduct::where('product_id', $request->product_id)->first();
        if (isset($activeProduct)) {
            $activeProduct->delete();
        }
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

    public function editProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        $data = Product::where('id', $request->product_id)->with(['productImage', 'document'])->first();
        $data->customer_name = $request->customer_name;
        $data->customer_number = $request->customer_number;
        if (isset($request->customer_pic)) {
            $data->customer_pic = FileUploader::uploadFile($request->customer_pic, 'images/customer');
        }
        $data->imei_number = $request->imei_number;
        $data->customer_buy_price = $request->customer_buy_price;
        $data->product_title = $request->product_title;
        $data->product_description = $request->product_description;
        $data->product_selling_price = $request->product_selling_price;
        $data->sold_to_customer_name = $request->sold_to_customer_name;
        $data->sold_to_customer_number = $request->sold_to_customer_number;
        $data->product_sold_price = $request->product_sold_price;
        $data->save();

        if (isset($request->product)) {
            foreach ($request->product as $item) {
                $productImage = new ProductImage;
                $productImage->product_id = $data->id;
                $productImage->image = FileUploader::uploadFile($item, 'images/product-image');
                $productImage->save();
            }
        }
        if (isset($request->document)) {
            foreach ($request->document as $item) {
                $documentImage = new Document;
                $documentImage->product_id = $data->id;
                $documentImage->image = FileUploader::uploadFile($item, 'images/document');
                $documentImage->save();
            }
        }
        return response([
            'message' => "Successfully Updated",
            'data' => $data,
        ], 200);
    }

    public function deleteImage(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'type' => 'required|in:product_image,document',
        ]);
        if ($request->type == "product_image") {
            $request->validate([
                'id' => 'required|exists:product_images,id',
            ]);
            $data = ProductImage::where('id', $request->id)->delete();
        } else if ($request->type == "document") {
            $request->validate([
                'id' => 'required|exists:documents,id',
            ]);
            $data = Document::where('id', $request->id)->delete();
        } else {
            return response([
                'message' => "Incorrect Type",
            ], 200);
        }
        return response([
            'message' => 'Deleted',
        ], 200);
    }
}
