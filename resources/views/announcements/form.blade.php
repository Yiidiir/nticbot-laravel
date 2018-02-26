<div class="row">
    <br>

    <div class="form-group">

        {{ Form::label('body', 'Message') }}

        {!! Form::textarea('body', null, array('placeholder' => 'Les interrogation commenceront le Jeudi prochain 12/03/2105','class' => 'form-control','style'=>'height:150px')) !!}

    </div>

    <div class="form-group form-inline">

        {{ Form::checkbox('planned_time_on',true,isset($announcement->planned_time)?true:false) }}

        {{ Form::label('planned_time_on', 'Broadcast on exact Date ') }}
        {{ Form::date('planned_time', isset($announcement->planned_time)?date_format(date_create($announcement->planned_time),'Y-m-d'):\Carbon\Carbon::now(),['class' => 'form-control'])}}

        {{ Form::label('planned_time_time', 'Exact time ') }}
        <input type="time" name="planned_time_time"
               value="{{ isset($announcement->planned_time)?date_format(date_create($announcement->planned_time),'H:i'):'08:30' }}"
               class="form-control">
    </div>


    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>

    </div>

</div>




