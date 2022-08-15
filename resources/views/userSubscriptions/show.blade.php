<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $subscriptions['name'] }}'s Details
        </h2>
    </x-slot>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <div class="container pt-3">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-right pb-2">
                    <a class="btn btn-primary" href="{{ route('userSubscription.index') }}">Back</a>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>ID : </strong> {{ $subscriptions['id'] }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name : </strong> {{ $subscriptions['name'] }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Email : </strong> {{ $subscriptions['email'] }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Status : </strong> {{ $subscriptions['status'] }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Product Name : </strong> {{ $subscriptions['productName'] }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Created At : </strong> {{ $subscriptions['createdAt'] }}
                </div>
            </div>

        </div>
</x-app-layout>
