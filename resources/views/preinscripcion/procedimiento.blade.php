@extends('layouts.layout')

@section('content')
<div class="container-fluid">
        <div class="row paneles">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel_login">
                    <div class="panel-heading">Procedimiento para inscribirse</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert errores">
                                <strong>Whoops!</strong> Hubo ciertos errores con los datos ingresados:<br><br>
                                <ul class="lista_errores">
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