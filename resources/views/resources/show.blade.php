@extends('layouts.app')


@section('content')

    <div class="container col-md-6 col-md-offset-3">

        <div class="row">

            <div class="pull-left">

                <h2> Show Resource</h2>

            </div>

            <div class="pull-right">

                <a class="btn btn-primary" href="{{ route('resources.index') }}"> Back</a>

            </div>

        </div>


        <div class="row">


            <div class="form-group">

                <strong>Title:</strong>

                {{ $resource->title}}

            </div>


            <div class="form-group">

                <strong>Body:</strong>

                {{ $resource->description}}

            </div>

            <div class="form-group">

                <strong>Year:</strong>

                {{ $resource->publish_year}}

            </div>


        </div>

    </div>

@endsection