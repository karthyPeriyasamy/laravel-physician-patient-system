@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-10">
                            {{ __('User') }}
                        </div>
                        <div class="col-md-2 float-right">
                            <button class="btn btn-primary">New Appointment</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-bordered mb-5" data-toggle="table">
                        <thead>
                            <tr class="table-info">
                                <th data-field="id">User Id</th>
                                <th data-field="name">Description</th>
                                <th data-field="price">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $data)
                            <tr>
                                <td>{{ $data->user_id }}</td>
                                <td>{{ $data->description }}</td>
                                <td>{{ $data->status === 1? "Open" : "Progress"  }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
