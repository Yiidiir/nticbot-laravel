@extends('layouts.app')


@section('content')

    <div class="container col-md-6 col-md-offset-3">
        <div class="row">


            <div class="pull-left">

                <h2>Edit Module</h2>

            </div>

            <div class="pull-right">

                <a class="btn btn-primary" href="{{ route('announcements.index') }}"> Back</a>

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

        {!! Form::model($announcement, ['method' => 'PATCH','route' => ['announcements.update', $announcement->id]]) !!}

        @include('announcements.form')

        {!! Form::close() !!}

    </div>
@endsection