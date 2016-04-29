@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Cursos inscritos
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes')
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Seccion</th>
                            <th>Fecha inicio</th>
                            <th>Fecha fin</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        @if($cursos != null)
                            <tbody>
                            @foreach($cursos as $index => $curso)
                                <tr>
                                    <td>{{ $curso[0]->nombre }}</td>
                                    <td>{{ $tipo_curso[$index] }}</td>
                                    <td>{{ $seccion[$index]  }}</td>
                                    <td>{{ $inicio[$index]->format('d-m-Y')  }}</td>
                                    <td>{{ $fin[$index]->format('d-m-Y')  }}</td>
                                    <td class="boton_">
                                        @if(Entrust::can('ver_notas_part'))
                                            {!!Form::open(["url"=>"participante/cursos/".$curso[0]->id."/modulos",  "method" => "GET" ])!!}
                                                <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Módulos">
                                                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                                </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                    <td class="boton_">
                                        @if(Entrust::can('ver_pagos'))
                                            <a type="button" class="btn btn-primary" href="{{URL::to('/')}}/participante/cursos/{{$curso[0]->id}}/pagos" data-toggle="tooltip" data-placement="bottom" title="Pagos">
                                                <span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        @endif
                    </table>
                </div>
                @if(Entrust::can('ver_perfil_part'))
                    <div>
                        <a href="{{URL::to("/")}}/" class="btn btn-default text-right"><span class="glyphicon glyphicon-chevron-left"></span> Regresar</a>
                    </div>
                @endif
            </div>
        @endif
    </div>



@stop