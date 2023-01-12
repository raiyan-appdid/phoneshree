@extends('layouts/contentLayoutMaster')

@section('title', 'Membership')
@section('page-style')
@endsection

@section('content')

    <x-card>
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center">Refer Scheme Set Up</h2>
            </div>
        </div>
        <form action="{{ route('admin.referscheme.store') }}" method="POST" id="refer-scheme">

            <x-input name="referred_person_reward_amount" value="{{ $data->referred_person_reward_amount ?? '' }}" />
            <x-input name="referred_by_reward_amount" value="{{ $data->referred_by_reward_amount ?? '' }}" />
            <button class="btn btn-primary" type="submit" name="" id="">Submit</button>
        </form>
    </x-card>
    <x-card>
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center">Welcome Bonus</h2>
            </div>
        </div>
        <form action="{{ route('admin.others.welcome-bonus') }}" method="POST" id="welcome-bonus">
            <x-input name="welcome_bonus" value="{{ $welcomeBonus->welcome_bonus ?? '' }}" />
            <button class="btn btn-primary" type="submit" name="" id="">Submit</button>
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
        $('#refer-scheme').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('admin.referscheme.store') }}",
                data: $(this).serialize(),
                success: function(response) {
                    snb((response.type) ? response.type : 'success', response.header, response
                        .message);
                }
            });
        })
        $('#welcome-bonus').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('admin.others.welcome-bonus') }}",
                data: $(this).serialize(),
                success: function(response) {
                    snb((response.type) ? response.type : 'success', response.header, response
                        .message);
                }
            });
        })
    </script>
@endsection
