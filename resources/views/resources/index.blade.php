@extends('layouts.app')
@section('title', 'Manage Resources')


@section('content')

    <div class="row col-md-6 col-md-offset-3">


        <div class="row">
            <div class="pull-left">

                <h2>Resource List</h2>

            </div>

            <div class="pull-right">

                <a class="btn btn-success" href="{{ route('resources.create') }}"> Create New Resource</a>

            </div>
        </div>


        @if ($message = Session::get('success'))

            <div class="alert alert-success">

                <p>{{ $message }}</p>

            </div>

        @endif

        <div class="row">

            <table class="table table-stripped">

                <tr>

                    <th>#</th>

                    <th>Title</th>

                    <th>Body</th>

                    <th width="280px">Action</th>

                </tr>

                @foreach ($resources as $resource)

                    <tr>

                        <td>{{$resource->id}}</td>

                        <td>{{ $resource->title}}</td>

                        <td>{{ $resource->description}}</td>

                        <td>

                            <a class="btn btn-info" href="{{ route('resources.show',$resource->id) }}">Show</a>

                            <a class="btn btn-primary" href="{{ route('resources.edit',$resource->id) }}">Edit</a>

                            {!! Form::open(['method' => 'DELETE','route' => ['resources.destroy', $resource->id],'style'=>'display:inline']) !!}

                            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}

                            {!! Form::close() !!}

                        </td>

                    </tr>

                @endforeach


            </table>
            @if(count($resources)<1)
                <h4 class="text-center">You did not add any resources!</h4>
            @endif
        </div>

        {!! $resources->links() !!}
    </div>
@endsection