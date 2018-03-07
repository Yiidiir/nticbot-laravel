@extends('layouts.app')


@section('content')

    <div class="container col-md-6 col-md-offset-3">

        <div class="row">

            <div class="pull-left">

                <h2> Show Resource</h2>

            </div>

            <div class="pull-right">
                @if(Auth::user()!==null)
                    <a class="btn btn-primary" href="{{ route('resources.index') }}"> Back</a>
                @endif
            </div>

        </div>


        <div class="row">


            <div class="div-group">

                <strong>Title:</strong>

                {{ $resource->title}}

            </div>


            <div class="div-group">

                <strong>Body:</strong>

                {{ $resource->description}}

            </div>

            <div class="div-group">

                <strong>Year:</strong>

                {{ $resource->publish_year}}

            </div>


            <div class="div-group">

                <strong>Module:</strong>

                {{ $resource->module->name}}

            </div>


            <div class="div-group">

                <strong>Download :</strong>
                {{ link_to($resource->google_drive, $title = 'Download Resource', $attributes = array(), $secure = null)}}
            </div>


        </div>

    </div>

@endsection