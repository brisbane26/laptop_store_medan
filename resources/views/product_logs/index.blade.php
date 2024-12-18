@extends('layouts.main')

@section('content')
<div class="container">
    <h2>{{ $title }}</h2> <!-- Menampilkan title -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Action</th>
                <th>Old Value</th>
                <th>New Value</th>
                <th>Admin Name</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
            <tr>        
                <td>{{ $log->id }}</td>
                <td>{{ $log->action }}</td>
                <td>{{ $log->old_value }}</td>
                <td>{{ $log->new_value }}</td>
                <td>{{ $log->admin_name }}</td>
                <td>{{ $log->date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-center mt-4">
    {{ $logs->links() }}
</div>
@endsection
