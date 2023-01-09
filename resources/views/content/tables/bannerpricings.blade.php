@extends('layouts/contentLayoutMaster')

@section('title', 'BannerPricing')
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


    <x-side-modal title="Add bannerpricing" id="add-bannerpricing-modal">
        <x-form id="add-bannerpricing" method="POST" class="" :route="route('admin.banner-pricing.store')">
            <div class="col-md-12 col-12 ">
                <x-input name="price" type="number" />
                <x-input name="number_of_days" />
            </div>
        </x-form>
    </x-side-modal>
    <x-side-modal title="Update bannerpricing" id="edit-bannerpricing-modal">
        <x-form id="edit-bannerpricing" method="POST" class="" :route="route('admin.banner-pricing.update')">

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
            $('#bannerpricing-table_wrapper .dt-buttons').append(
                `<button type="button" data-show="add-bannerpricing-modal" class="btn btn-flat-success waves-effect float-md-right">Add</button>`
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
