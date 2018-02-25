<div class="row">


    <div class="form-group @if ($errors->has('title')) has-error @endif">

        {{ Form::label('name', 'Name:') }}

        {!! Form::text('name', null, array('placeholder' => 'Yidir BOUHADJER','class' => 'form-control')) !!}

        @if ($errors->has('name'))
            <div class="help-block">{{ $errors->first('name') }}</div>
        @endif

    </div>

    <div class="form-group @if ($errors->has('temail')) has-error @endif">

        {{ Form::label('email', 'E-mail:') }}

        {!! Form::text('email', null, array('placeholder' => 'yidir.bouhadjer@univ-constantine2.dz','class' => 'form-control')) !!}

        @if ($errors->has('email'))
            <div class="help-block">{{ $errors->first('email') }}</div>
        @endif

    </div>

    <div class="form-group">
        {{ Form::label('password', 'Password:') }}
        {{ Form::text('password', null, array('value' => 'secret','class' => 'form-control')) }}
    </div>


    <div class="form-group">
        {{ Form::label('role', 'User Role') }}
        {{ Form::select('size', ['S' => 'Student', 'T' => 'Teacher', 'A'=> 'Admin'], 'T',['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>

    </div>

</div>




