@extends('layouts.app')
@section('title', 'Edit Resource')


@section('content')

    <div class="container col-md-6 col-md-offset-3">
        <div class="row">


            <div class="pull-left">

                <h2>Edit Resource</h2>

            </div>

            <div class="pull-right">

                <a class="btn btn-primary" href="{{ route('resources.index') }}"> Back</a>

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

        {!! Form::model($resource, ['method' => 'PATCH','route' => ['resources.update', $resource->id]]) !!}

        @include('resources.form')

        {!! Form::close() !!}

    </div>
@endsection