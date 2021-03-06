@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Grupos del webinar {{$webinar->nombre}}
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
                            <th>Grupo</th>
                            <th>Acciones</th>
                            <th></th>
                        </tr>
                        </thead>
                        @if($secciones != null)
                            <tbody>
                            @foreach($secciones as $seccion)
                                <tr>
                                    <td>{{ $seccion }}</td>
                                    <td  class="boton_">
                                        @if(Entrust::can('listar_alumnos'))
                                            {!!Form::open(["url"=>"profesor/webinars/".$webinar->id."/grupos/".$seccion."/participantes",  "method" => "GET" ])!!}
                                            <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Participantes">
                                                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                            </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                    <td class="boton_">
                                        @if(Entrust::can('listar_alumnos'))
                                            {!!Form::open(["url"=>"profesor/webinars/".$webinar->id."/grupos/".$seccion."/lista",  "method" => "GET", "target" => "_blank"])!!}
                                            <button type="submit" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Lista de alumnos">
                                                <span class="glyphicon glyphicon-save" aria-hidden="true"></span>
                                            </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        @else
                            <td>No existen participantes en el webinar</td>
                        @endif
                    </table>
                </div>
                @if(Entrust::can('ver_cursos_profe'))
                    <a href="{{URL::to("/")}}/profesor/webinars" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Volver</a>
                @endif
            </div>
        @endif
    </div>



@stop