@extends('layouts/contentLayoutMaster')

@section('title', 'Wallet Management')
@section('page-style')
@endsection

@section('content')
    {{ 'Wallet Management' }}

    <style>
        .amount {
            padding: 2px;
            background-color: #3dc6d8d9;
            color: white;
            border-radius: 5px
        }
    </style>


    <x-card>

        <form action="{{ route('admin.others.storeWalletData') }}" method="POST" id="store-wallet">
            <div class="row">
                <div class="col-md-4">
                    <x-select name="seller" label="Merchants" :options="$sellerData" />
                </div>
                <div class="col-md-4 my-auto">
                    <div class="amount text-center">
                        Current Amount : <span class="current-amount">0</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <x-select name="type" :options="['credit', 'debit']" />
                </div>
                <div class="col-md-4">
                    <x-input attrs="disabled" name="amount" />
                </div>
                <div class="col-md-4 my-auto">
                    <div class="amount text-center">
                        Updated Amount : <span class="updated-amount">0</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary waves-effect">Submit</button>
                </div>
            </div>
        </form>
    </x-card>
@endsection
@section('page-script')
    <script>
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });

        $('#store-wallet').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('admin.others.storeWalletData') }}",
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response);
                    snb('success', response.header, response.message)
                    window.location.reload();
                }
            });

        })

        $('#seller').on('change', function() {
            blockUI();
            $.ajax({
                type: "POST",
                url: "{{ route('admin.others.getWalletData') }}",
                data: {
                    id: $(this).val()
                },
                success: function(response) {
                    console.log(response);
                    $('.current-amount').text(response.current_wallet_balance)
                    unblockUI();
                }
            });
        })

        $('#type').on('change', function() {
            $('#amount').attr('disabled', false);
        })

        $('#amount').on('keyup', function() {
            if ($('#type').val() == 'credit') {
                const add = parseInt($('.current-amount').text()) + parseInt($(this).val());
                $('.updated-amount').text('');
                $('.updated-amount').text(add);
                checkUpdatedAmount(add)
            }
            if ($('#type').val() == 'debit') {
                const minus = parseInt($('.current-amount').text()) - parseInt($(this).val());
                $('.updated-amount').text('');
                $('.updated-amount').text(minus);
                checkUpdatedAmount(minus)
            }
        })

        function checkUpdatedAmount(amount) {
            if (amount < 0) {
                $('.updated-amount').parent('.amount').removeClass('bg-success');
                $('.updated-amount').parent('.amount').addClass('bg-danger');

            }
            if (amount >= 0) {
                $('.updated-amount').parent('.amount').removeClass('bg-danger');
                $('.updated-amount').parent('.amount').addClass('bg-success');
            }
        }
    </script>
@endsection
