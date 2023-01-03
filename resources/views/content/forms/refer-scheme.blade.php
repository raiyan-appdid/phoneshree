@extends('layouts/contentLayoutMaster')

@section('title', 'Membership')
@section('page-style')
@endsection

@section('content')

    <x-card>

        <form action="{{ route('admin.referscheme.store') }}" method="POST">
            @csrf
            <x-input name="referred_person_reward_amount" value="{{ $data->referred_person_reward_amount ?? '' }}" />
            <x-input name="referred_by_reward_amount" value="{{ $data->referred_by_reward_amount ?? '' }}" />
            <button class="btn btn-primary" type="submit" name="" id="">Submit</button>
        </form>

    </x-card>
@endsection
@section('page-script')
    <script></script>
@endsection
