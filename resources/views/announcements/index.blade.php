@extends('layouts.app')


@section('content')

    <div class="row col-lg-8 col-md-offset-2">


        <div class="row">
            <div class="pull-left">

                <h2>Announcements List</h2>

            </div>

            <div class="pull-right">

                <a class="btn btn-success" href="{{ route('announcements.create') }}"> Broadcast A New Announcement</a>

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

                    <th>Message</th>

                    <th width="200px">Broadcast time</th>

                    <th width="200px">Action</th>

                </tr>

                @foreach ($announcements as $announcement)

                    <tr>

                        <td>{{$announcement->id}}</td>

                        <td>{{ $announcement->body}}</td>

                        <th>{{ date_format(date_create($announcement->planned_time),'d/m/Y \a\t H\hi') }}</th>

                        <td>


                            @if(date($announcement->planned_time)>now())
                                <a class="btn btn-primary"
                                   href="{{ route('announcements.edit',$announcement->id) }}">Edit</a>

                                {!! Form::open(['method' => 'DELETE','route' => ['announcements.destroy', $announcement->id],'style'=>'display:inline']) !!}

                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}

                                {!! Form::close() !!}
                            @else
                                <h5>Already broadcasted!</h5>
                            @endif
                        </td>

                    </tr>

                @endforeach

            </table>
            @if(count($announcements)<1)
                <h4 class="text-center">No Announcements Found!</h4>
            @endif
        </div>

        {!! $announcements->links() !!}
    </div>
@endsection