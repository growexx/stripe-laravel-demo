<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List of Payments') }}
        </h2>
    </x-slot>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <div class="container pt-4 text-center">
        <table class="table table-hover mt-3">
            <thead class="thead-light">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Invoice number</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Customer Email</th>
                    <th scope="col">Plan</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Created</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $invoice['invno'] }}</td>
                        <td>{{ $invoice['cust_name'] }}</td>
                        <td>{{ $invoice['cust_email'] }}</td>
                        <td>{{ $invoice['desc'] }}</td>
                        <td>{{ $invoice['amount'] }}</td>
                        <td>{{ $invoice['createdAt'] }}</td>
                        <td>
                            <a href="{{ route('payment.show', $invoice['id']) }}" title="View">
                                <button type="button" class="btn btn-primary">Show</button>
                            </a>
                            <a href="{{ $invoice['invoice_pdf'] }}" target="_blank">
                                <button type="button" class="btn btn-success">Download</button>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
