<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List of Plans') }}
        </h2>
    </x-slot>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <div class="container pt-4">
        @if (Auth::user()->role == 'admin')
            <a href="{{ route('plans.create') }}" class="btn btn-outline-dark">Create New Plan</a>
        @endif

        <div class="row justify-content-center pt-3">
            <div class="col-md-12">
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
                <div class="card">
                    <div class="card-header">Plans</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <ul class="list-group">
                            @foreach ($plans as $plan)
                                <li class="list-group-item clearfix">
                                    <div class="pull-left">
                                        <h5>{{ $plan->name }}</h5>
                                        <h5>Rs.:{{ number_format($plan->cost, 2) }} monthly</h5>
                                        <h5>{{ $plan->description }}</h5>
                                        @if (Auth::user()->role == 'user')
                                            <a href="{{ route('plans.show', $plan->slug) }}"
                                                class="btn btn-outline-dark pull-right">Choose</a>
                                        @endif
                                        @if (Auth::user()->role == 'admin')
                                            <a href="{{ route('plans.edit', $plan->id) }}"
                                                class="btn btn-outline-dark pull-right">Edit</a>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
