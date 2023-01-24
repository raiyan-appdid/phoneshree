@extends('layouts/contentLayoutMaster')

@section('title', 'PopUp')
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


    <x-side-modal title="Add popup" id="add-popup-modal">
        <x-form id="add-popup" method="POST" class="" :route="route('admin.popup.store')">
            <div class="col-md-12 col-12 ">
                <x-input name="description" />
                <x-input-file name="image" />
                <x-select name="type" :options="['user', 'merchant']" />
            </div>
        </x-form>
    </x-side-modal>
    <x-side-modal title="Update popup" id="edit-popup-modal">
        <x-form id="edit-popup" method="POST" class="" :route="route('admin.popup.update')">

            <div class="col-md-12 col-12 ">
                <x-input name="description" />
                Previous Image
                <div class="avatar avatar-lg">
                    <img class="view-on-click" src="" id="previous-image" alt="avatar">
                </div>

                <x-input-file name="image" />
                <x-select name="type" id="edit-type" :options="['user', 'merchant']" />
                <x-input name="id" type="hidden" />
            </div>

        </x-form>
    </x-side-modal>
@endsection
@section('page-script')
    <script>
        $(document).ready(function() {
            $('#popup-table_wrapper .dt-buttons').append(
                `<button type="button" data-show="add-popup-modal" class="btn btn-flat-success waves-effect float-md-right">Add</button>`
            );
            $(document).on('click', '[data-show]', function() {
                const modal = $(this).data('show');
                $(`#${modal}`).modal('show');
            });
        });

        function setValue(data, modal) {

            $(`${modal} #id`).val(data.id);
            $(`${modal} #description`).val(data.description);
            $('#edit-type').val(data.type).trigger('change');
            $('#previous-image').attr('src', "{{ asset('/') }}" + data.image);
            $(modal).modal('show');
        }
    </script>
@endsection
