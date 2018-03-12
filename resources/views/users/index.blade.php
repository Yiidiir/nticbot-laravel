@extends('layouts.app')
@section('title','Manage Users')

@section('content')

    <div class="row col-md-6 col-md-offset-3">


        <div class="row">
            <div class="pull-left">

                <h2>Users List</h2>

            </div>

            <div class="pull-right">

                <a class="btn btn-success" href="{{ route('users.create') }}"> Add New User</a>

            </div>
        </div>


        @if ($message = Session::get('success'))

            <div class="alert alert-success">

                <p>{{ $message }}</p>

            </div>

        @endif

        <div class="row">
            @section('setValues')
                {{ $grade = 0 ? '':'' }}
                @if (Request::path() == 'users')
                    {{ $grade = 0 ? 'active':'' }}
                @elseif(Request::path() == 'users/teachers')
                    {{ $grade = 1 }}
                @elseif(Request::path() == 'users/students')
                    {{ $grade = 2 }}
                @elseif(Request::path() == 'users/admins')
                    {{ $grade = 3 }}
                @endif
            @endsection
            <div class="form-group">
                <a class="btn btn btn-default {{$grade == 0 ? 'active':''}}" href="{{ route('users.index') }}/"> All</a>
                <a class="btn btn btn-default {{$grade == 1 ? 'active':''}}" href="{{ route('users.index') }}/teachers">
                    Teachers</a>
                <a class="btn btn btn-default {{$grade == 2 ? 'active':''}}" href="{{ route('users.index') }}/students">
                    Students</a>
                <a class="btn btn btn-default {{$grade == 3 ? 'active':''}}" href="{{ route('users.index') }}/admins">
                    Admins</a>
            </div>
            <table class="table table-stripped">

                <tr>

                    <th>#</th>

                    <th>Name</th>

                    <th>E-mail</th>

                    <th>Grade</th>

                    <th width="150px">Action</th>

                </tr>

                @foreach ($users as $user)

                    <tr>

                        <td>{{ $user->id}}</td>

                        <td>{{ $user->name}}</td>

                        <td>{{ $user->email}}</td>

                        <td>{{ $user->role() }}</td>

                        <td>


                            <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>

                            {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}

                            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}

                            {!! Form::close() !!}

                        </td>

                    </tr>

                @endforeach

            </table>
        </div>

        {!! $users->links() !!}
    </div>
@endsection