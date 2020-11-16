@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Users List</div>
                <div class="card-body">
                    <table class="table table-bordered mb-5" data-toggle="table">
                        <thead>
                            <tr class="table-info">
                                <th>User name</th>
                                <th>User Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $data)
                            <tr>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->email }}</td>
                                <td>{{ $data->is_physician ? " Physician" : " Patient" }}</td>
                                <td><input type="checkbox" disabled {{$data->approved === 1 ? 'checked' : '' }}></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">

    </div>
</div>
@endsection
