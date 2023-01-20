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
                    <x-input name="terms_and_conditions" type="url" value="{{ $extrasData->terms_and_conditions ?? '' }}" />
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
