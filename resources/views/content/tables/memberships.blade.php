@extends('layouts/contentLayoutMaster')

@section('title', 'Membership')
@section('page-style')
@endsection

@section('content')

    <section>
        <div class="row match-height">
            <div class="col-lg-12 col-md-12 col-sm-12">

                <x-card>
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="text-center">Trial Period</h2>
                        </div>
                    </div>
                    <form action="" method="POST" id="free-trial">
                        @csrf
                        <label for="free_trial_period" class="form-label">Free Trial Period (In days)</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="free_trial_period"
                                    value="{{ $freeTrial->free_trial_period ?? '' }}" class="form-control"
                                    id="free_trial_period">
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary waves-effect" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </x-card>

                <x-card>
                    <div class="row  mb-1">
                        <div class="col-md-12">
                            <h2 class="text-center">Package Management</h2>
                        </div>
                    </div>
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

        $('#free-trial').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "{{ route('admin.others.free-trial') }}",
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response);
                    snb((response.type) ? response.type : 'success', response.header, response
                        .message);
                }
            });
        })

        function setValue(data, modal) {
            $(`${modal} #id`).val(data.id);
            $(`${modal} #name`).val(data.name);
            $(`${modal} #validity`).val(data.validity);
            $(`${modal} #amount`).val(data.amount);
            $(modal).modal('show');
        }
    </script>
@endsection
