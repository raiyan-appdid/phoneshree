@extends('layouts/contentLayoutMaster')

@section('title', 'Product')
@section('page-style')
@endsection

@section('content')

    <section>
        <div class="row match-height">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <x-card>
                    {!! $dataTable->table() !!}
                </x-card>
            </div>
        </div>
    </section>


    <x-side-modal title="Add product" id="add-product-modal">
        <x-form id="add-product" method="POST" class="" :route="route('admin.products.store')">
            <div class="col-md-12 col-12 ">
                <x-input name="customer_name" />
                <x-input name="customer_number" />
                <x-input name="customer_pic" />
                <x-input name="documents" />
                <x-input name="imei_number" />
                <x-input name="customer_buy_price" />
                <x-input name="product_image" />
                <x-input name="product_title" />
                <x-input name="product_description" />
                <x-input name="product_selling_price" />
                <x-input name="sold_to_customer_name" />
                <x-input name="sold_to_customer_number" />
                <x-input name="product_sold_price" />
                <x-select name="brand_id" label="Brand" :options="$brands" />
            </div>
        </x-form>
    </x-side-modal>
    <x-side-modal title="Update product" id="edit-product-modal">
        <x-form id="edit-product" method="POST" class="" :route="route('admin.products.update')">
            <div class="col-md-12 col-12 ">
                <x-input name="customer_name" />
                <x-input name="customer_number" />
                <x-input name="customer_pic" />
                <x-input name="documents" />
                <x-input name="imei_number" />
                <x-input name="customer_buy_price" />
                <x-input name="product_image" />
                <x-input name="product_title" />
                <x-input name="product_description" />
                <x-input name="product_selling_price" />
                <x-input name="sold_to_customer_name" />
                <x-input name="sold_to_customer_number" />
                <x-input name="product_sold_price" />
                <x-select name="brand_id" label="Brand" :options="$brands" />
                <x-input name="id" type="hidden" />
            </div>
        </x-form>
    </x-side-modal>
@endsection
@section('page-script')
    <script>
        $(document).ready(function() {
            $('#product-table_wrapper .dt-buttons').append(
                `<button type="button" data-show="add-product-modal" class="btn btn-flat-success waves-effect float-md-right">Add</button>`
            );
            $(document).on('click', '[data-show]', function() {
                const modal = $(this).data('show');
                $(`#${modal}`).modal('show');
            });
        });

        function setValue(data, modal) {
            $(`${modal} #id`).val(data.id);
            $(`${modal} #customer_name`).val(data.customer_name);
            $(`${modal} #customer_number`).val(data.customer_number);
            $(`${modal} #imei_number`).val(data.imei_number);
            $(`${modal} #customer_buy_price`).val(data.customer_buy_price);
            $(`${modal} #product_title`).val(data.product_title);
            $(`${modal} #product_description`).val(data.product_description);
            $(`${modal} #product_selling_price`).val(data.product_selling_price);
            $(`${modal} #sold_to_customer_name`).val(data.sold_to_customer_name);
            $(`${modal} #sold_to_customer_number`).val(data.sold_to_customer_number);
            $(`${modal} #product_sold_price`).val(data.product_sold_price);
            $(modal).modal('show');
        }
    </script>
@endsection
