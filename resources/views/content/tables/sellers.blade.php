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

    {{-- @dd($sellerData) --}}

    <x-side-modal title="Add seller" id="add-seller-modal">
        <x-form id="add-seller" method="POST" class="" :route="route('admin.sellers.store')">
            <div class="col-md-12 col-12 ">
                <x-input :required="false" name="name" />
                <x-input :required="false" name="number" />
                <x-input :required="false" name="email" />
                <x-input :required="false" name="shop_name" />
                <x-input :required="false" name="short_description" />
                <x-input :required="false" name="gst_no" />
                <x-input-file name="shop_image" />
                <x-input :required="false" name="address" />
                <x-select :required="false" name="state_id" label="State" :options="$state" />
                <label for="">City</label>
                <select class="select2" name="city_id" id="city_id">
                    <option value="">Select City</option>
                </select>
                <x-select :required="false" name="referred_by" :options="$sellerData" />
            </div>
        </x-form>
    </x-side-modal>
    <x-side-modal title="Update seller" id="edit-seller-modal">
        <x-form id="edit-seller" method="POST" class="" :route="route('admin.sellers.update')">
            <div class="col-md-12 col-12 ">
                <x-input :required="false" name="name" />
                <x-input :required="false" name="number" />
                <x-input :required="false" name="email" />
                <x-input :required="false" name="shop_name" />
                <x-input :required="false" name="short_description" />
                <x-input :required="false" name="gst_no" />
                <x-input-file :required="false" name="shop_image" />
                <x-input :required="false" name="address" />
                <x-select name="state_id" id="state_id-edit" label="State" :options="$state" />
                <label for="">City</label>
                <select class="select2" name="city_id" id="city_id-edit">
                    <option value="">Select City</option>
                </select>
                <x-input name="id" type="hidden" />
                <x-select :required="false" name="referred_by" id="referred_by-edit" :options="$sellerData" />
            </div>
        </x-form>
    </x-side-modal>
@endsection
@section('page-script')
    <script>
        $(document).ready(function() {

            $("#city_id").select2("destroy").select2({
                tags: true
            });

            $('#seller-table_wrapper .dt-buttons').append(
                `<button type="button" data-show="add-seller-modal" class="btn btn-flat-success waves-effect float-md-right">Add</button>`
            );
            $(document).on('click', '[data-show]', function() {
                const modal = $(this).data('show');
                $(`#${modal}`).modal('show');
            });
        });
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });

        $('#state_id').on('change', function() {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.others.get-city') }}",
                data: {
                    'state_id': $(this).val()
                },
                success: function(response) {
                    console.log(response);
                    response.forEach(element => {
                        $('#city_id').append(
                            `<option value="${element.id}">${element.name}</option>`)
                    });
                }
            });
        })

        $('#state_id-edit').on('change', function() {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.others.get-city') }}",
                data: {
                    'state_id': $(this).val()
                },
                success: function(response) {
                    console.log(response);
                    response.forEach(element => {
                        $('#city_id-edit').append(
                            `<option value="${element.id}">${element.name}</option>`)
                    });
                }
            });
        })

        function setValue(data, modal) {
            $(`${modal} #id`).val(data.id);
            $(`${modal} #name`).val(data.name);
            $(`${modal} #number`).val(data.number);
            $(`${modal} #email`).val(data.email);
            $(`${modal} #shop_name`).val(data.shop_name);
            $(`${modal} #short_description`).val(data.short_description);
            $(`${modal} #address`).val(data.address);
            $(`${modal} #gst_no`).val(data.gst_no);
            $('#referred_by-edit').val(data.referred_by).trigger('change');
            $(modal).modal('show');
        }
    </script>
@endsection
