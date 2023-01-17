@extends('layouts/contentLayoutMaster')

@section('title', 'Membership')
@section('page-style')
@endsection

@section('content')

    <x-card>
        <form action="{{ route('admin.extras.storeOrUpdate') }}" id="extras-store" method="post">
            <div class="row">
                <div class="col-md-6">
                    <label for="">Contact 1</label>
                    <input type="number" name="phone1" value="{{ $extrasData->phone1 ?? '' }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="">Contact 2</label>
                    <input type="number" name="phone2" value="{{ $extrasData->phone2 ?? '' }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="">Email</label>
                    <input type="email" value="{{ $extrasData->email ?? '' }}" name="email" class="form-control">
                </div>
                <div class="col-md-6 col-6">
                    <label for="">Privacy Policy</label>
                    <input type="url" name="privacy_policy" value="{{ $extrasData->privacy_policy ?? '' }}"
                        id="" class="form-control">
                </div>
                <div class="col-md-6 col-6">
                    <label for="">Terms and Conditions</label>
                    <input type="url" name="terms_and_conditions" value="{{ $extrasData->terms_and_conditions ?? '' }}"
                        id="" class="form-control">
                </div>
                <div class="col-md-6">
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
