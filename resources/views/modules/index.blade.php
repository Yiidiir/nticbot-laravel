@extends('layouts.app')


@section('content')

    <div class="row col-md-6 col-md-offset-3">


        <div class="row">
            <div class="pull-left">

                <h2>Module List</h2>

            </div>

            <div class="pull-right">

                <a class="btn btn-success" href="{{ route('modules.create') }}"> Add new Module</a>

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

                @foreach ($modules as $module)

                    <tr>

                        <td>{{$module->id}}</td>

                        <td>{{ $module->name}}</td>

                        <td>{{ $module->description}}</td>

                        <td>


                            <a class="btn btn-primary" href="{{ route('modules.edit',$module->id) }}">Edit</a>

                            {!! Form::open(['method' => 'DELETE','route' => ['modules.destroy', $module->id],'style'=>'display:inline']) !!}

                            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}

                            {!! Form::close() !!}

                        </td>

                    </tr>

                @endforeach

            </table>
        </div>

        {!! $modules->links() !!}
    </div>
@endsection