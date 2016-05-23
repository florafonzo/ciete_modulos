@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Informe Académico de la actividad {{$curso->nombre}}<br>Módulo - {{$modulo->nombre}}
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes'){{--Errores--}}
                {!! Form::open(array('method' => 'POST', 'route' => array('profesor.informe', $curso->id, $modulo->id, $seccion), 'class' => 'form-horizontal col-md-10', 'enctype' => "multipart/form-data", "target" => "_blank")) !!}
                    <div class="form-group">
                        {!!Form::label('conclu', 'Conclusión de la actividad:', array( 'class' => 'col-md-4 ')) !!}
                        <div class="col-sm-8">
                            {!!Form::textarea('conclusion', old('conclusion'),array( 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('positivo', 'Aspectos positivos:',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!!Form::textarea('positivo', old('positivo') ,array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('negativo', 'Aspectos negativos:',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!!Form::textarea('negativo',old('negativo') ,array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('sugerencias', 'Sugerencias de mejora:',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!!Form::textarea('sugerencias', old('sugerencias') ,array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    @if(Entrust::can('informe_academico'))
                        <a href="{{URL::to("/")}}/profesor/actividades/{{$curso->id}}/modulos/{{$modulo->id}}/grupos" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-download-alt"></span> Generar
                        </button>
                    @endif
                {!! Form::close() !!}
            </div>
        @endif
    </div>
@stop