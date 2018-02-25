@extends('layouts.app')


@section('content')

    <div class="container col-md-6 col-md-offset-3">
        <div class="row">


            <div class="pull-left">

                <h2>Edit User</h2>

            </div>

            <div class="pull-right">

                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>

            </div>


        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}

        @include('users.form')

        {!! Form::close() !!}

    </div>
@endsection