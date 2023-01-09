@extends('layouts/contentLayoutMaster')

@section('title', 'FeaturedProductPricing')
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


    <x-side-modal title="Add featuredproductpricing" id="add-featuredproductpricing-modal">
        <x-form id="add-featuredproductpricing" method="POST" class="" :route="route('admin.featuredproduct-pricing.store')">
            <div class="col-md-12 col-12 ">
                <x-input name="price" type="number" />
                <x-input name="number_of_days" />
            </div>
        </x-form>
    </x-side-modal>
    <x-side-modal title="Update featuredproductpricing" id="edit-featuredproductpricing-modal">
        <x-form id="edit-featuredproductpricing" method="POST" class="" :route="route('admin.featuredproduct-pricing.update')">

            <div class="col-md-12 col-12 ">
                <x-input name="price" type="number" />
                <x-input name="number_of_days" />
                <x-input name="id" type="hidden" />
            </div>

        </x-form>
    </x-side-modal>
@endsection
@section('page-script')
    <script>
        $(document).ready(function() {
            $('#featuredproductpricing-table_wrapper .dt-buttons').append(
                `<button type="button" data-show="add-featuredproductpricing-modal" class="btn btn-flat-success waves-effect float-md-right">Add</button>`
            );
            $(document).on('click', '[data-show]', function() {
                const modal = $(this).data('show');
                $(`#${modal}`).modal('show');
            });
        });

        function setValue(data, modal) {

            $(`${modal} #id`).val(data.id);
            $(`${modal} #price`).val(data.price);
            $(`${modal} #number_of_days`).val(data.number_of_days);
            $(modal).modal('show');
        }
    </script>
@endsection
