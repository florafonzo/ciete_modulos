@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 bienvenida">
            <h3>
                Participantes de la actividad {{$curso->nombre}}<br/> Módulo: {{$modulo->nombre}} - Grupo: {{$seccion}}
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes')
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!!Form::open(["url"=>"profesor/actividades/".$curso->id."/modulos/".$modulo->id."/grupos/".$seccion."/participantes/buscar",  "method" => "GET" ])!!}
                        {{--{!! Form::open(array('method' => 'get', 'route' => array('usuarios.buscar'), 'id' => 'form_busq')) !!}--}}
                        <div class="buscador">
                            <select class="form-control " name="parametro">
                                <option value="0"  selected="selected">Buscar por</option>
                                <option value="nombre">Nombre</option>
                                <option value="apellido">Apellido</option>
                                <option value="documento_identidad">Dcocumento de identidad</option>
                            </select>
                            {!!Form::text('busqueda', null,array('placeholder' => 'Escriba su busqueda...','class' => 'form-control bus'))!!}
                            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" ></span> </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Documento de identidad</th>
                            <th>Acciones</th>
                            <th></th>
                        </tr>
                        </thead>
                        @if($participantes != null)
                            <tbody>
                            @foreach($participantes as $index => $participante)
                                @if($busq)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $participante->nombre }}</td>
                                        <td>{{ $participante->apellido }}</td>
                                        <td>{{ $participante->documento_identidad }}</td>
                                        <td>
                                            @if(Entrust::can('ver_notas_profe'))
                                                {!!Form::open(["url"=>"profesor/actividades/".$curso->id."/modulos/".$modulo->id."/grupos/".$seccion."/participantes/".$participante->id."/notas",  "method" => "GET" ])!!}
                                                <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Notas">
                                                    <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                                                </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $participante[0]->nombre }}</td>
                                        <td>{{ $participante[0]->apellido }}</td>
                                        <td>{{ $participante[0]->documento_identidad }}</td>
                                        <td>
                                            @if(Entrust::can('ver_notas_profe'))
                                                {!!Form::open(["url"=>"profesor/actividades/".$curso->id."/modulos/".$modulo->id."/grupos/".$seccion."/participantes/".$participante[0]->id."/notas",  "method" => "GET" ])!!}
                                                <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Notas">
                                                    <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                                                </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        @else
                            @if($busq_)
                                <td></td>
                                <td> 0 resultados de la busqueda</td>
                            @else
                                <td></td>
                                <td>No existen participantes inscritos</td>
                            @endif
                        @endif
                    </table>
                </div>
                @if(Entrust::can('ver_perfil_prof'))
                    <a href="{{URL::to("/")}}/profesor/actividades/{{$curso->id}}/modulos/{{$modulo->id}}/grupos" class="btn btn-default text-right"><span class="glyphicon glyphicon-chevron-left  "></span> Regresar</a>
                @endif
            </div>
        @endif
    </div>



@stop