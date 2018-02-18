@extends('layouts.app')


@section('content')




    <div class="container-fluid col-md-6 col-md-offset-3">

        <div class="row">
            <div class="pull-left">

                <h2>Add New Resource</h2>

            </div>

            <div class="pull-right">

                <a class="btn btn-primary" href="{{ route('resources.index') }}"> Back</a>

            </div>
        </div>


        {{--{{!! var_dump(array_column($modules['L'],'name')) !!}}--}}


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
            {!! Form::open(array('route' => 'resources.store','method'=>'POST')) !!}

            @include('resources.form')

            {!! Form::close() !!}
        </div>

    </div>


@endsection