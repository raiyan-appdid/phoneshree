@extends('layouts/contentLayoutMaster')

@section('title', 'Notification')
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


    <x-side-modal title="Add notification" id="add-notification-modal">
        <x-form id="add-notification" method="POST" class="" :route="route('admin.notification.store')">
            <div class="col-md-12 col-12 ">
                <x-input name="title" />
                <x-input name="description" />
            </div>
        </x-form>
    </x-side-modal>

@endsection
@section('page-script')
    <script>
        $(document).ready(function() {
            $('#notification-table_wrapper .dt-buttons').append(
                `<button type="button" data-show="add-notification-modal" class="btn btn-flat-success waves-effect float-md-right">Add</button>`
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
    </script>
@endsection
