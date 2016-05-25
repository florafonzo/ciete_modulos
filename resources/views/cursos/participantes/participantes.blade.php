@extends('layouts.layout')

@section('content')
    <div class="row" xmlns="http://www.w3.org/1999/html">

        <div class="col-md-12 col-sm-12 bienvenida">
            <h3>
                Participantes de la actividad {{$curso->nombre}}
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes'){{--Errores--}}
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!!Form::open(["url"=>"actividades/".$curso->id."/grupos/".$seccion."/participantes/buscar",  "method" => "GET" ])!!}
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
                            {{--<th>Fecha Inicio</th>--}}
                            {{--<th>Fecha Fin</th>--}}
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if($participantes != null)
                                @foreach($participantes as $index => $participante)
                                    @if($busq)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $participante->nombre }}</td>
                                            <td>{{ $participante->apellido  }}</td>
                                            <td>{{ $participante->documento_identidad  }}</td>
                                            {{--<td>{{ $participantes->fecha_inicio  }}</td>--}}
                                            {{--<td>{{ $participantes->fecha_fin  }}</td>--}}

                                            <td>
                                                @if(Entrust::can('eliminar_part_curso'))
                                                    {!!Form::open(["url"=>"actividades/".$curso->id."/grupos/".$seccion."/participantes/".$participante->id."/eliminar",  "method" => "delete", "id" => "form_eliminar_part".$participante->id ])!!}
                                                    <button type="button" onclick="eliminarPart('{{$participante->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Eliminar">
                                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                                    </button>
                                                    {!! Form::close() !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $participante[0]->nombre }}</td>
                                            <td>{{ $participante[0]->apellido  }}</td>
                                            <td>{{ $participante[0]->documento_identidad  }}</td>
                                            {{--<td>{{ $participantes->fecha_inicio  }}</td>--}}
                                            {{--<td>{{ $participantes->fecha_fin  }}</td>--}}

                                            <td>
                                                @if(Entrust::can('eliminar_part_curso'))
                                                    {!!Form::open(["url"=>"actividades/".$curso->id."/grupos/".$seccion."/participantes/".$participante[0]->id."/eliminar",  "method" => "delete", "id" => "form_eliminar_part".$participante[0]->id ])!!}
                                                    <button type="button" onclick="eliminarPart('{{$participante[0]->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Eliminar">
                                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                                    </button>
                                                    {!! Form::close() !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                @if($busq_)
                                    <td></td>
                                    <td><strong> 0 resultados de la busqueda </strong></td>
                                @else
                                    <td></td>
                                    <td> <strong> No existen participantes inscritos </strong></td>
                                @endif
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2 " style="">
                    @if(Entrust::can('ver_lista_cursos'))
                        <a href="{{URL::to('/')}}/actividades/{{$curso->id}}/grupos/participantes" type="button" class="btn btn-default" style="text-decoration: none"><span class="glyphicon glyphicon-chevron-left"></span> Regresar </a>
                    @endif
                </div>
                <div class="col-md-2 " style="">
                    @if(Entrust::can('agregar_part_curso'))
                        {!!Form::open(["url"=>"actividades/".$curso->id."/grupos/".$seccion."/participantes/agregar",  "method" => "GET" ])!!}
                        <button type="submit" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Agregar participante a la actividad" >
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Agregar
                        </button>
                        {!! Form::close() !!}
                    @endif
                </div>
            </div>
        @endif
    </div>

@stop