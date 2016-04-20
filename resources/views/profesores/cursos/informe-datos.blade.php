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
{{--                {!!Form::open(["url"=>"profesor/cursos/".$curso->id."/modulos/".$modulo->id."/informe/datos/generar",  "method" => "POST" ])!!}--}}
                {!! Form::open(array('method' => 'POST', 'route' => array('profesor.informe', $curso->id, $modulo->id), 'class' => 'form-horizontal col-md-10', 'enctype' => "multipart/form-data")) !!}
                    <div class="form-group">
                        {!!Form::label('cohorte', 'Cohorte:', array( 'class' => 'col-md-4 ')) !!}
                        <div class="col-sm-8">
                            {!!Form::text('cohorte', old('cohorte'),array('required', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('grupo', 'Grupo:', array( 'class' => 'col-md-4 ')) !!}
                        <div class="col-sm-8">
                            {!!Form::text('grupo', old('grupo'),array('required', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('conclu', 'Conclusión del curso:', array( 'class' => 'col-md-4 ')) !!}
                        <div class="col-sm-8">
                            {!!Form::textarea('conclusion', old('conclusion'),array('required', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('positivo', 'Aspectos positivos:',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!!Form::textarea('positivo', old('positivo') ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('negativo', 'Aspectos negativos:',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!!Form::textarea('negativo',old('negativo') ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('sugerencias', 'Sugerencias de mejora:',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!!Form::text('sugerencias', old('sugerencias') ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    @if(Entrust::can('informe_academico'))
                        <a href="{{URL::to("/")}}/profesor/cursos/{{$curso->id}}/modulos/" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-download-alt"></span> Generar
                        </button>
                    @endif
                {!! Form::close() !!}
            </div>
        @endif
    </div>
@stop