<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List of Subscriptions') }}
        </h2>
    </x-slot>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <div class="container pt-4 text-center">
        <table class="table table-hover mt-3">
            <thead class="thead-light">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Customer Email</th>
                    <th scope="col">Status</th>
                    <th scope="col">Product</th>
                    <th scope="col">Created</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subscriptions as $subscription)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $subscription['name'] }}</td>
                        <td>{{ $subscription['email'] }}</td>
                        <td><span class="badge badge-success">{{ $subscription['status'] }}</span></td>
                        <td>{{ $subscription['productName'] }}</td>
                        <td>{{ $subscription['createdAt'] }}</td>
                        <td>
                            <a href="{{ route('userSubscription.show', $subscription['id']) }}" title="View">
                                <button type="button" class="btn btn-primary">Show</button>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
