<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List of Plans') }}
        </h2>
    </x-slot>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <div class="container pt-4">
        @if (Auth::user()->role == 'admin')
            <a href="{{ route('plans.create') }}" class="btn btn-outline-secondary">Create New Plan</a>
        @endif
        @if (session()->get('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        @if (session()->get('info'))
            <div class="alert alert-info">
                {{ session()->get('info') }}
            </div>
        @endif
        <table class="table table-hover mt-3 text-center">
            <thead class="thead-light">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Plan Name</th>
                    <th scope="col">Cost</th>
                    <th scope="col">Description</th>
                    <th scope="col" colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plans as $plan)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $plan->name }}</td>
                        <td>{{ number_format($plan->cost, 2) }} monthly</td>
                        <td>{{ $plan->description }}</td>
                        <td>
                            @if (Auth::user()->role == 'admin')
                                <a href="{{ route('plans.edit', $plan->id) }}">
                                    <button type="button" class="btn btn-outline-primary">Edit</button>
                                </a>
                            @endif
                        </td>
                        <td>
                            @if (Auth::user()->role == 'user')
                                <a href="{{ route('plans.show', $plan->id) }}">
                                    <button type="button" class="btn btn-outline-success">Choose</button>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
