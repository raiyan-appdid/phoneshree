@extends('layouts/contentLayoutMaster')

@section('title', 'Membership')
@section('page-style')
@endsection

@section('content')

    <x-card>
        <form action="{{ route('admin.extras.storeOrUpdate') }}" id="extras-store" method="post">
            <div class="row">
                <div class="col-md-6">
                    <x-input name="phone1" label="Contact 1" value="{{ $extrasData->phone1 ?? '' }}" />
                </div>
                <div class="col-md-6">
                    <x-input name="phone2" label="Contact 2" value="{{ $extrasData->phone2 ?? '' }}" />

                </div>
                <div class="col-md-6">
                    <x-input name="email" label="Email" value="{{ $extrasData->email ?? '' }}" />
                </div>
                <div class="col-md-6 col-6">
                    <x-input name="privacy_policy" type="url" value="{{ $extrasData->privacy_policy ?? '' }}" />
                </div>
                <div class="col-md-6 col-6">
                    <x-input name="terms_and_conditions" type="url"
                        value="{{ $extrasData->terms_and_conditions ?? '' }}" />
                </div>
                <div class="col-md-6 col-6">
                    <x-input name="android_version" type="text" value="{{ $extrasData->android_version ?? '' }}" />
                </div>
                <div class="col-md-6 col-6">
                    <x-input name="buyer_phone_expiry" label="Buyer Phone Expiry (In days)" type="number"
                        value="{{ $extrasData->buyer_phone_expiry ?? '' }}" />
                </div>
                <div class="col-2 mb-2 text-center">
                    <x-custom-switch label="Force Update" id="andriod_force" :checked="$extrasData->android_force_update == 'active' ? true : false" type="danger" value="0"
                        name="android_force_update" />
                </div>
                {{-- <div class="col-2 mb-2 text-center">
                    <x-custom-switch label="Force Update" id="ios_force" :checked="$extrasData->i_force_update == 'active' ? true : false" type="danger" value="0"
                        name="i_force_update" />
                </div> --}}
                <div class="col-2 mb-2 text-center">
                    <x-custom-switch label="Maintenance mode" id="maintenance" :checked="$extrasData->maintenance == 'active' ? true : false" type="danger"
                        value="1" name="maintenance" />
                </div>
                <div class="col-md-12">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>

        </form>
    </x-card>
@endsection
@section('page-script')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });


        })
        $('#extras-store').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('admin.extras.storeOrUpdate') }}",
                data: $(this).serialize(),
                success: function(response) {
                    snb((response.type) ? response.type : 'success', response.header, response
                        .message);
                }
            });
        })
        // $('#welcome-bonus').on('submit', function(e) {
        //     e.preventDefault();
        //     $.ajax({
        //         type: "POST",
        //         url: "{{ route('admin.others.welcome-bonus') }}",
        //         data: $(this).serialize(),
        //         success: function(response) {
        //             snb((response.type) ? response.type : 'success', response.header, response
        //                 .message);
        //         }
        //     });
        // })
    </script>
@endsection
