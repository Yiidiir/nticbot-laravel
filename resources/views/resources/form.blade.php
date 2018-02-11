<div class="row">


    <div class="form-group @if ($errors->has('title')) has-error @endif">

        {{ Form::label('title', 'Title:') }}

        {!! Form::text('title', null, array('placeholder' => 'Title','class' => 'form-control')) !!}

        @if ($errors->has('title'))
            <div class="help-block">{{ $errors->first('title') }}</div>
        @endif

    </div>

</div>

<div class="row">

    <div class="form-group">

        {{ Form::label('description', 'Description:') }}

        {!! Form::textarea('description', null, array('placeholder' => 'Body','class' => 'form-control','style'=>'height:150px')) !!}

    </div>

    <div class="form-group">
        {{ Form::label('title', 'Google Drive link:') }}
        {{ Form::text('google_drive', null, array('placeholder' => 'https://drive.google.com/file/d/0B8JXivo4iMxJVE5pd0s5aE04TjhTT2dWNEVvckhMdGRyT0Iw/view','class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('publish_year', 'Academic Year (Start):') }}
        {{ Form::selectRange('publish_year', 2008, date('Y'),date('Y'),array('class'=>'form-control')) }}
    </div>


</div>

<div class="row">

    <button type="submit" class="btn btn-primary">Submit</button>

</div>

