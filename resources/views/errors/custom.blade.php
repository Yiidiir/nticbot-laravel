
@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1>
                        Oops!</h1>
                    <h2>
                        {{ $exception->getStatusCode() }}
                    </h2>
                    <div class="error-details">
                        {{ $exception->getMessage() }}
                    </div>
                    <div class="error-actions">
                        <a href="{{ url()->previous() }}" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-chevron-left"></span>
                            Go Back </a><a href="#" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-envelope"></span> Seek for Help! </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


        <p class="bg-danger"></p>



@endsection