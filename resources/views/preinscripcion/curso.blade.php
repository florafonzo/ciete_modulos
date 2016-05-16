@extends('layouts.layout')

@section('content')
    <div class="container-fluid">
        <div class="row paneles">
            <div class="col-md-8 col-md-offset-2">
                @include('partials.mensajes')
                <div class="panel panel_login">
                    <div class="panel-heading">Formulario de inscripción de Diplomados y Cápsulas</div>
                    <div class="panel-body">
                        <form id="form" method="POST" action="{{ url('inscripcion/actividades') }}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" placeholder="Nombre" name="nombre" value="{{old('nombre')}}" required>
                            </div>
                            <div class="form-group">
                                <label for="apellido">Apellido:</label>
                                <input type="text" class="form-control" id="apellido" placeholder="Apellido" name="apellido" value="{{old('apellido')}}" required>
                            </div>
                            <div class="form-group">
                                <label for="apellido">Documento de identidad:</label>
                                <input type="text" class="form-control" id="" placeholder="Número de documento de identidad" name="di" value="{{old('di')}}" required>
                            </div>
                            <div class="form-group">
                                <label for="correo">Correo eléctronico:</label>
                                <input type="email" class="form-control" id="correo" placeholder="Email" name="email" value="{{old('email')}}" required>
                            </div>
                            <div class="form-group">
                                <label for="curso">Actividad:</label>
                                <select name="curso" id="curso" required>
                                    <option value="0"  selected="selected">Seleccione el Diplomado o Cápsula</option>
                                    @foreach($cursos as $index=>$curso)
                                        <option value="{{$index}}">{{$curso}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>A continuación complete los datos del pago:</label>
                            </div>
                            <div class="form-group">
                                <label for="tipo_pago">Modalidad de pago:</label>
                                <select name="tipo_pago" id="tipo_pago" required>
                                    <option value="0"  selected="selected">Seleccione la modalidad de pago</option>
                                    @foreach($tipo_pago as $index=>$pago)
                                        <option value="{{$index}}">{{$pago}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="numero_pago">Número de depósito o transferencia:</label>
                                <input type="text" class="form-control" id="numero_pago" placeholder="Número de depósito o transferencia" name="numero_pago" value="{{old('monto')}}" required>
                            </div>
                            <div class="form-group">
                                <label for="monto">Monto del pago:</label>
                                <input type="text" class="form-control" id="monto" placeholder="Monto del pago" name="monto" value="{{old('monto')}}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop