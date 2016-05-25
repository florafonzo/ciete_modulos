@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 bienvenida">
            <h3>
                Módulos de la actividad {{$curso->nombre}}
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
                            <th>Módulo</th>
                            <th>Acciones</th>
                            <th></th>
                        </tr>
                        </thead>
                        @if($modulos != null)
                            <tbody>
                            @foreach($modulos as $modulo)
                                <tr>
                                    <td>{{ $modulo->nombre }}</td>
                                    <td class="boton_">
                                        @if(Entrust::can('ver_notas_part'))
                                            {!!Form::open(["url"=>"participante/actividades/".$curso->id."/modulos/".$modulo->id."/notas",  "method" => "GET" ])!!}
                                            <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Notas">
                                                <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                                            </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                            </tbody>
                        @endif
                    </table>
                </div>
                @if(Entrust::can('ver_cursos_part'))
                    <a href="{{URL::to("/")}}/participante/actividades" class="btn btn-default text-right"><span class="glyphicon glyphicon-chevron-left"></span> Regresar</a>
                @endif
            </div>
        @endif
    </div>



@stop