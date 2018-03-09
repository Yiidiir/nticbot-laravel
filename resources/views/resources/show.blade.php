@extends('layouts.app')

@section('title', $resource->title.' ('.$resource->module->code.') ')
@section('content')

    <div class="container col-md-6 col-md-offset-3">

        <div class="row">

            <div class="pull-left">


            </div>

            <div class="pull-right">
                @if(Auth::user()!==null)
                    <div class="form-group">
                        <a class="btn btn-primary" href="{{ route('resources.index') }}"> Back</a>
                    </div>
                @endif
            </div>

        </div>


        <div class="panel panel-default">
            <div class="panel-heading">{{$resource->title.' ('.$resource->module->code.') '}}
            </div>
            <div class="panel-body">
                <div class="form-group">
                <span class="label label-primary">{{\App\Module::getDegree($resource->module->degree)}}</span>
                <span class="label label-success">{{'Semester '.$resource->module->semester}}</span>
                <span class="label label-warning">{{$resource->module->name}}</span>
                <span class="label label-default">{{$resource->publish_year.' - '.($resource->publish_year+1)}}</span>
                </div>

                    <p class="lead">
                        {{ $resource->description}}
                    </p>

                <div class="div-group">


                    <a type="button" href="{{$resource->google_drive}}" class="btn btn-primary btn-lg"
                       aria-label="Left Align">
                        <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download Resource
                    </a>


                </div>


            </div>

        </div>

    </div>

@endsection