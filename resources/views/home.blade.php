@extends('layouts.app')
@section('title','Homepage')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-info">
                    <div class="panel-heading">Latest Announcement</div>

                    <div class="panel-body mark">
                        <b>{{ date_format(date_create($latest_announcement->planned_time),'m/d/Y \a\t H\hi') }}
                            : </b>{{$latest_announcement->body}}
                    </div>
                </div>

                <h2>Browse Resources</h2>

                @if(empty($params_data['degree']))
                    <div class="row">
                        <div class="col-lg-4">
                            <h4>Licence</h4>
                            <p>Browse Courses,TDs and more resources for Licence students. </p>
                            <p><a class="btn btn-primary" href="L" role="button">Browse Resources &raquo;</a></p>
                        </div>
                        <div class="col-lg-4">
                            <h4>Master</h4>
                            <p>Browse Courses,TDs and more resources for Master's degree seekers. </p>
                            <p><a class="btn btn-primary" href="M" role="button">Browse Resources &raquo;</a></p>
                        </div>
                        <div class="col-lg-4">
                            <h4>Doctorate</h4>
                            <p>Browse Courses,TDs and more resources for Doctorates. </p>
                            <p><a class="btn btn-primary" href="D" role="button">Browse Resources &raquo;</a></p>
                        </div>
                    </div>
                @elseif(!empty($params_data['degree']))
                    @if(empty($params_data['semester']))
                        <div class="row">
                            @foreach($params_data['semesters'] as $semester)
                                <div class="col-lg-4">
                                    <h4>Semester {{$semester}}</h4>
                                    <p><a class="btn btn-primary" href="{{$params_data['degree'].'/'.$semester}}"
                                          role="button">Browse Resources &raquo;</a></p>
                                </div>
                            @endforeach
                        </div>
                    @elseif(!empty($params_data['semester']) && empty($params_data['module']))
                        <div class="row">
                            @foreach($params_data['modules'] as $module)
                                <div class="col-lg-4">
                                    <h4>{{$module->name}}</h4>
                                    <p><a class="btn btn-primary" href="{{$module->semester}}/{{$module->code}}"
                                          role="button">Browse Resources &raquo;</a></p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        @foreach($params_data['resources'] as $resource)
                            <div class="col-lg-4">
                                <h4>{{$resource->title}}</h4>
                                <p>{{ $resource->description }}</p>
                                <p><a class="btn btn-primary" href="{{route('resources.show',$resource->id)}}"
                                      role="button">Browse Resources &raquo;</a></p>
                            </div>
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
