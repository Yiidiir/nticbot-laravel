<div class="row">


    <div class="form-group @if ($errors->has('title')) has-error @endif">

        {{ Form::label('name', 'Name ') }}

        {!! Form::text('name', null, array('placeholder' => 'name','class' => 'form-control')) !!}

        @if ($errors->has('name'))
            <div class="help-block">{{ $errors->first('name') }}</div>
        @endif

    </div>

    <div class="form-group @if ($errors->has('title')) has-error @endif">

        {{ Form::label('code', 'Code') }}

        {!! Form::text('code', null, array('placeholder' => 'code','class' => 'form-control')) !!}

        @if ($errors->has('code'))
            <div class="help-block">{{ $errors->first('code') }}</div>
        @endif

    </div>

</div>

<div class="row">

    <div class="form-group">

        {{ Form::label('description', 'Description:') }}

        {!! Form::textarea('description', null, array('placeholder' => 'Body','class' => 'form-control','style'=>'height:150px')) !!}

    </div>
    <div class="form-group">

        {!! Form::label('degree', 'Choose a degree') !!}
        {!! Form::select('degree',  ['L'=>'Licence','M'=>'Master','D'=>'Doctorat'], null, ['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {{ Form::label('semester', 'Semester') }}
        {{ Form::selectRange('semester', 1, 6,isset($module->semester)?$module->semester:1,array('class'=>'form-control')) }}
    </div>


    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>

    </div>

</div>




