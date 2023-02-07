<?php
namespace App\Http\Controllers\Admin;

use App\DataTables\ProductDataTable;
use App\Helpers\FileUploader;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(ProductDataTable $table)
    {
        $pageConfigs = ['has_table' => true];
        $brands = Brand::all();
        // $table->with('id', 1);
        return $table->render('content.tables.products', compact('pageConfigs', 'brands'));
    }
    public function store(Request $request)
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
        $data->brand_id = $request->brand_id;
        $data->save();
        return response([
            'header' => 'Added!',
            'message' => $request->name . 'Added successfully!',
            'table' => 'product-table',
        ]);

    }
    public function edit($id)
    {
        $name = Product::findOrFail($id);
        return response($name);
    }

    public function update(Request $request)
    {
        $data = Product::findOrFail($request->id);
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
        $data->brand_id = $request->brand_id;
        $data->save();
        return response([
            'header' => 'Updated!',
            'message' => $request->name . 'Updated successfully!',
            'table' => 'product-table',
        ]);

    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:products,id',
            'status' => 'required|in:active,blocked',
        ]);

        Product::findOrFail($request->id)->update(['status' => $request->status]);

        return response([
            'message' => 'product status updated successfully',
            'table' => 'product-table',
        ]);
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return response([
            'header' => 'Deleted!',
            'message' => 'product deleted successfully',
            'table' => 'product-table',
        ]);
    }
}
