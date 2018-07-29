@extends('layout', ['title' => 'Customers'])

@section('content')

    <h1>Customers <small class="text-muted font-weight-light">({{ number_format($customers->total()) }} found)</small></h1>

    <table class="table my-4">
        <tr>
            <th><a class="{{ request('order', 'name') === 'name' ? 'text-dark' : '' }}" href="{{ route('customers', ['order' => 'name'] + request()->except('page')) }}">Name</a></th>
            <th><a class="{{ request('order') === 'company' ? 'text-dark' : '' }}" href="{{ route('customers', ['order' => 'company'] + request()->except('page')) }}">Company</a></th>
            <th><a class="{{ request('order') === 'birthday' ? 'text-dark' : '' }}" href="{{ route('customers', ['order' => 'birthday'] + request()->except('page')) }}">Birthday</a></th>
            <th><a class="{{ request('order') === 'last_action' ? 'text-dark' : '' }}" href="{{ route('customers', ['order' => 'last_action'] + request()->except('page')) }}">Last Action</a></th>
        </tr>

        @foreach ($customers as $customer)
            <tr>
                <td>{{ $customer->last_name }}, {{ $customer->first_name }}</td>
                <td>{{ $customer->company->name }}</td>
                <td>{{ $customer->birth_date->format('d M Y') }}</td>
                <td>
                    {{ $customer->lastAction->created_at->diffForHumans() }}
                    <span class="text-secondary">({{ $customer->lastAction->type }})</span>
                </td>
            </tr>
        @endforeach
    </table>

    {{ $customers->appends(request()->all())->links() }}

@endsection