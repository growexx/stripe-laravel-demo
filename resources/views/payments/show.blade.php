<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $invoice['cust_name'] }}'s Details
        </h2>
    </x-slot>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <div class="container pt-3">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-right pb-2">
                    <a class="btn btn-primary" href="{{ route('payment.index') }}">Back</a>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Invoice Number : </strong> {{ $invoice['invno'] }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Customer Name : </strong> {{ $invoice['cust_name'] }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Customer Email : </strong> {{ $invoice['cust_email'] }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Plan : </strong> {{ $invoice['desc'] }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Amount : </strong> {{ $invoice['amount'] }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Created : </strong> {{ $invoice['createdAt'] }}
                </div>
            </div>

        </div>
</x-app-layout>
