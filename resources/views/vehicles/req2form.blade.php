@extends('layouts.app')

@section('content')
{!! Form::open(['route' => 'postCar']) !!}

     <div class="form-group col-sm-6">
	    {!! Form::label('modelYear', 'Model Year:') !!}
	    {!! Form::text('modelYear', null, ['class' => 'form-control']) !!}
	</div>
	 <div class="form-group col-sm-6">
	    {!! Form::label('manufacturer', 'Manufacturer:') !!}
	    {!! Form::text('manufacturer', null, ['class' => 'form-control']) !!}
	</div>
	 <div class="form-group col-sm-6">
	    {!! Form::label('model', 'Model:') !!}
	    {!! Form::text('model', null, ['class' => 'form-control']) !!}
	</div>


	<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}

@endsection