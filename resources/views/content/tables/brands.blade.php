@extends('layouts/contentLayoutMaster')

@section('title', 'Brand')
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


    <x-side-modal title="Add brand" id="add-brand-modal">
        <x-form id="add-brand" method="POST" class="" :route="route('admin.brands.store')">
            <div class="col-md-12 col-12 ">
                <x-input name="title" />
                <x-input-file name="logo" />
            </div>
        </x-form>
    </x-side-modal>
    <x-side-modal title="Update brand" id="edit-brand-modal">
        <x-form id="edit-brand" method="POST" class="" :route="route('admin.brands.update')">
            <div class="col-md-12 col-12 ">
                <x-input name="title" />
                <x-input-file name="logo" />
                <x-input name="id" type="hidden" />
            </div>
        </x-form>
    </x-side-modal>
@endsection
@section('page-script')
    <script>
        $(document).ready(function() {
            $('#brand-table_wrapper .dt-buttons').append(
                `<button type="button" data-show="add-brand-modal" class="btn btn-flat-success waves-effect float-md-right">Add</button>`
            );
            $(document).on('click', '[data-show]', function() {
                const modal = $(this).data('show');
                $(`#${modal}`).modal('show');
            });
        });

        function setValue(data, modal) {
            $(`${modal} #id`).val(data.id);
            $(`${modal} #title`).val(data.title);
            $(modal).modal('show');
        }
    </script>
@endsection
