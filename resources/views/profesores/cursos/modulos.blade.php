@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Módulos del curso {{$curso->nombre}}
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
                                        @if(Entrust::can('ver_notas_profe'))
                                            {!!Form::open(["url"=>"profesor/cursos/".$curso->id."/modulos/".$modulo->id."/secciones",  "method" => "GET" ])!!}
                                            <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Secciones">
                                                <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                                            </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        @endif
                    </table>
                </div>
                @if(Entrust::can('ver_cursos_profe'))
                    <a href="{{URL::to("/")}}/profesor/cursos" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Volver</a>
                @endif
            </div>
        @endif
    </div>



@stop