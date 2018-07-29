@extends('layout', ['title' => 'Customers'])

@section('content')

    <h1>Customers <small class="text-muted font-weight-light">({{ number_format($customers->total()) }} found)</small></h1>

    <table class="table my-4">
        <tr>
            <th>Name</th>
            <th>Company</th>
            <th>Birthday</th>
            <th>Last action</th>
        </tr>

        @foreach ($customers as $customer)
            <tr>
                <td>{{ $customer->last_name }}, {{ $customer->first_name }}</td>
                <td>{{ $customer->company->name }}</td>
                <td>{{ $customer->birth_date->format('F j') }}</td>
                <td>{{ $customer->last_action_date->diffForHumans() }}</td>
            </tr>
        @endforeach
    </table>

    {{ $customers->appends(request()->all())->links() }}

@endsection