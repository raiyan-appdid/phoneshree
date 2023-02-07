@extends('layouts/contentLayoutMaster')

@section('title', 'Dashboard Analytics')
@section('page-style')
    <style>
        .avatar svg {
            height: 20px;
            width: 20px;
            font-size: 1.45rem;
            flex-shrink: 0;
        }

        .dark-layout .avatar svg {
            color: #fff;
        }

        .cursor {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')

    <section id="dashboard-card">
        <div class="row match-height">
            <div style="cursor: pointer;" onclick="location.href='{{ route('admin.sellers.index') }}';"
                class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ $sellerCount }}</h2>
                    <p>
                    <h4 class="text-center"><span class="badge badge-light-info">Total Merchants</span></h4>
                    </p>

                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='{{ route('admin.products.index') }}';"
                class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ $productCount }}</h2>
                    <p>
                    <h4 class="text-center"><span class="badge badge-light-secondary">Total Products</span></h4>
                    </p>
                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='{{ route('admin.products.index') }}';"
                class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ $soldProductCount }}</h2>
                    <p>
                    <h4 class="text-center"><span class="badge badge-light-danger">Total Sold Products</span></h4>
                    </p>
                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='{{ route('admin.products.index') }}';"
                class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ $liveProductCount }}</h2>
                    <p>
                    <h4 class="text-center"><span class="badge badge-light-warning">Total Live Products</span></h4>
                    </p>
                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='{{ route('admin.products.index') }}';"
                class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ $inventoryProductCount }}</h2>
                    <p>
                    <h4 class="text-center"><span class="badge badge-light-success">Total Inventory Products</span></h4>
                    </p>
                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='{{ route('admin.featured-product.index') }}';"
                class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ $featuredProductCount }}</h2>
                    <p>
                    <h4 class="text-center"><span class="badge badge-light-info">Featured Product</span></h4>
                    </p>
                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='{{ route('admin.banner-ads.index') }}';"
                class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ $activeBannersCount }}</h2>
                    <p>
                    <h4 class="text-center"><span class="badge badge-light-info">Banner Ads</span></h4>
                    </p>
                </x-card>
            </div>
            {{-- <div style="cursor: pointer;" onclick="location.href='admin';" class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ 0 }}</h2>
                    <p>
                    <h4 class="text-center"><span class="badge badge-light-secondary">Blocked</span></h4>
                    </p>
                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='admin';" class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ 0 }}</h2>
                    <h4 class="text-center"><span class="badge badge-light-info">Total</span></h4>
                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='admin';" class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ 0 }}</h2>
                    <h4 class="text-center"><span class="badge badge-light-secondary">Blocked</span></h4>
                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='admin';" class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ 0 }}</h2>
                    <h4 class="text-center"><span class="badge badge-light-info">Total</span></h4>
                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='admin';" class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ 0 }}</h2>
                    <h4 class="text-center"><span class="badge badge-light-secondary">Blocked</span></h4>
                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='admin';" class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ 0 }}</h2>
                    <h4 class="text-center"><span class="badge badge-light-info">Total</span></h4>
                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='admin';" class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ 0 }}</h2>
                    <h4 class="text-center"><span class="badge badge-light-secondary">Blocked</span></h4>
                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='admin';" class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ 0 }}</h2>
                    <h4 class="text-center"><span class="badge badge-light-info">Total</span></h4>
                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='admin';" class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ 0 }}</h2>
                    <h4 class="text-center"><span class="badge badge-light-secondary">Blocked</span></h4>
                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='admin';" class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ 0 }}</h2>
                    <h4 class="text-center"><span class="badge badge-light-info">Total</span></h4>
                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='admin';" class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ 0 }}</h2>
                    <h4 class="text-center"><span class="badge badge-light-secondary">Blocked</span></h4>
                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='admin';" class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ 0 }}</h2>
                    <h4 class="text-center"><span class="badge badge-light-info">Total</span></h4>
                </x-card>
            </div>
            <div style="cursor: pointer;" onclick="location.href='admin';" class="col-lg-3 col-md-3 col-sm-3 ">
                <x-card>
                    <h2 class="text-center">{{ 0 }}</h2>
                    <h4 class="text-center"><span class="badge badge-light-secondary">Blocked</span></h4>
                </x-card>
            </div> --}}
        </div>
    </section>
@endsection

@section('page-script')
@endsection
