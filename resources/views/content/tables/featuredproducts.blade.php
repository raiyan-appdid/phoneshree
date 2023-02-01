@extends('layouts/contentLayoutMaster')

@section('title', 'FeaturedProduct')
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


    {{-- <x-side-modal title="Add featuredproduct" id="add-featuredproduct-modal">
        <x-form id="add-featuredproduct" method="POST" class="" :route="route('admin.featuredproducts.store')">
            <div class="col-md-12 col-12 ">
                <x-input name="name" />
            </div>
        </x-form>
    </x-side-modal> --}}
    {{-- <x-side-modal title="Update featuredproduct" id="edit-featuredproduct-modal">
        <x-form id="edit-featuredproduct" method="POST" class="" :route="route('admin.featuredproducts.update')">

            <div class="col-md-12 col-12 ">
                <x-input name="name" />
                <x-input name="id" type="hidden" />
            </div>

        </x-form>
    </x-side-modal> --}}
@endsection
@section('page-script')
    <script>
        {{--  $(document).ready(function() {
            $('#featuredproduct-table_wrapper .dt-buttons').append(
                `<button type="button" data-show="add-featuredproduct-modal" class="btn btn-flat-success waves-effect float-md-right">Add</button>`
            );
            $(document).on('click', '[data-show]', function() {
                const modal = $(this).data('show');
                $(`#${modal}`).modal('show');
            });
        });

        function setValue(data, modal) {

            $(`${modal} #id`).val(data.id);
            $(`${modal} #name`).val(data.name);
            $(modal).modal('show');
        }
        --}}
    </script>
@endsection
