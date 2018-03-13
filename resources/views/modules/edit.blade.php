@extends('layouts.app')
@section('title', 'Edit Module')


@section('content')

    <div class="container col-md-6 col-md-offset-3">
        <div class="row">


            <div class="pull-left">

                <h2>Edit Module</h2>

            </div>

            <div class="pull-right">

                <a class="btn btn-primary" href="{{ route('modules.index') }}"> Back</a>

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

        {!! Form::model($module, ['method' => 'PATCH','route' => ['modules.update', $module->id]]) !!}

        @include('modules.form')

        {!! Form::close() !!}

    </div>
@endsection