@extends('layouts.layout')

@section('content')
<div class="container-fluid">
        <div class="row paneles">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel_login">
                    <div class="panel-heading">Procedimiento para preinscribirse</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert lista_errores">
                                <strong>Whoops!</strong> Hubo ciertos errores con los datos ingresados:<br><br>
                                <ul class="">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

		            
		            <div class="row">
		                <div class="">
		                    <img src="{{URL::to('/')}}/images/tutorial_preinscripcion.png" class="img-responsive center-block">
		                </div>
		            </div>
		            
        		</div>
            </div>
        </div>
    </div>
</div>
@stop