@extends('layouts.app')
@section('title', 'Add New User')


@section('content')




    <div class="container-fluid col-md-6 col-md-offset-3">

        <div class="row">
            <div class="pull-left">

                <h2>Add New User</h2>

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


        <div class="row">
            {!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}

            @include('users.form')

            {!! Form::close() !!}
        </div>

    </div>


@endsection