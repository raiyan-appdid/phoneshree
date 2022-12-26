@extends('layouts/contentLayoutMaster')

@section('title', 'Membership')
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


    <x-side-modal title="Add membership" id="add-membership-modal">
        <x-form id="add-membership" method="POST" class="" :route="route('admin.memberships.store')">
            <div class="col-md-12 col-12 ">
                <x-input name="name" />
                <x-input name="validity" />
                <x-input name="amount" />
            </div>
        </x-form>
    </x-side-modal>
    <x-side-modal title="Update membership" id="edit-membership-modal">
        <x-form id="edit-membership" method="POST" class="" :route="route('admin.memberships.update')">

            <div class="col-md-12 col-12 ">
                <x-input name="name" />
                <x-input name="validity" />
                <x-input name="amount" />
                <x-input name="id" type="hidden" />
            </div>

        </x-form>
    </x-side-modal>
@endsection
@section('page-script')
    <script>
        $(document).ready(function() {
            $('#membership-table_wrapper .dt-buttons').append(
                `<button type="button" data-show="add-membership-modal" class="btn btn-flat-success waves-effect float-md-right">Add</button>`
            );
            $(document).on('click', '[data-show]', function() {
                const modal = $(this).data('show');
                $(`#${modal}`).modal('show');
            });
        });

        function setValue(data, modal) {
            $(`${modal} #id`).val(data.id);
            $(`${modal} #name`).val(data.name);
            $(`${modal} #validity`).val(data.validity);
            $(`${modal} #amount`).val(data.amount);
            $(modal).modal('show');
        }
    </script>
@endsection
