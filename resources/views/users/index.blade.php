@extends('layouts.app')


@section('content')

    <div class="row col-md-6 col-md-offset-3">


        <div class="row">
            <div class="pull-left">

                <h2>Resource List</h2>

            </div>

            <div class="pull-right">

                <a class="btn btn-success" href="{{ route('users.create') }}"> Create New Resource</a>

            </div>
        </div>


        @if ($message = Session::get('success'))

            <div class="alert alert-success">

                <p>{{ $message }}</p>

            </div>

        @endif

        <div class="row">

            <table class="table table-stripped">

                <tr>

                    <th>#</th>

                    <th>Name</th>

                    <th>E-mail</th>

                    <th>Grade</th>

                    <th width="280px">Action</th>

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