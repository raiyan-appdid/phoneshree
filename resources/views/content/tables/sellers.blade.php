@extends('layouts/contentLayoutMaster')

@section('title', 'Seller')
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

    <x-side-modal title="Add seller" id="add-seller-modal">
        <x-form id="add-seller" method="POST" class="" :route="route('admin.sellers.store')">
            <div class="col-md-12 col-12 ">
                <x-input name="name" />
                <x-input name="number" />
                <x-input name="email" />
                <x-input name="shop_name" />
                <x-input name="short_description" />
                <x-input-file name="shop_image" />
                <x-input name="address" />
            </div>
        </x-form>
    </x-side-modal>
    <x-side-modal title="Update seller" id="edit-seller-modal">
        <x-form id="edit-seller" method="POST" class="" :route="route('admin.sellers.update')">
            <div class="col-md-12 col-12 ">
                <x-input name="name" />
                <x-input name="number" />
                <x-input name="email" />
                <x-input name="shop_name" />
                <x-input name="short_description" />
                <x-input-file name="shop_image" />
                <x-input name="address" />
                <x-input name="id" type="hidden" />
            </div>
        </x-form>
    </x-side-modal>
@endsection
@section('page-script')
    <script>
        $(document).ready(function() {
            $('#seller-table_wrapper .dt-buttons').append(
                `<button type="button" data-show="add-seller-modal" class="btn btn-flat-success waves-effect float-md-right">Add</button>`
            );
            $(document).on('click', '[data-show]', function() {
                const modal = $(this).data('show');
                $(`#${modal}`).modal('show');
            });
        });

        function setValue(data, modal) {

            $(`${modal} #id`).val(data.id);
            $(`${modal} #name`).val(data.name);
            $(`${modal} #number`).val(data.number);
            $(`${modal} #email`).val(data.email);
            $(`${modal} #shop_name`).val(data.shop_name);
            $(`${modal} #short_description`).val(data.short_description);
            $(`${modal} #address`).val(data.address);
            $(modal).modal('show');
        }
    </script>
@endsection
