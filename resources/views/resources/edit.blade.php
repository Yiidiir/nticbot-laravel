@extends('layouts.app')


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


        {!! Form::model($resource, ['method' => 'PATCH','route' => ['resources.update', $resource->id]]) !!}

        @include('resources.form')

        {!! Form::close() !!}

    </div>
@endsection