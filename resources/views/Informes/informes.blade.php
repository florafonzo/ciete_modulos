@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Informes Académicos
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes')
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!! Form::open(array('method' => 'get', 'route' => array('informes.buscar'), 'id' => 'form_busq')) !!}
                        <div class="buscador">
                            <select class="form-control " id="param" name="parametro">
                                <option value="0"  selected="selected">Buscar por</option>
                                <option value="curso"  >Actividad</option>
                                <option value="modulo"  >Módulo</option>
                                <option value="seccion"  >Sección</option>
                            </select>
                            {!!Form::text('busqueda', null,array('placeholder' => 'Escriba su busqueda...','class' => 'form-control bus', 'id' => 'busqueda'))!!}
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
                            <th>Actividad</th>
                            <th>Profesor</th>
                            <th>Módulo</th>
                            <th>Sección</th>
                            <th>Acciones</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($busq)
                            @if($informes != [])
                                @foreach($informes as $index => $informe)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $informe->curso->nombre }}</td>
                                        <td>{{ $informe->profesor->nombre }} {{ $informe->profesor->apellido }} </td>
                                        <td>{{ $informe->modulo->nombre }}</td>
                                        <td>{{ $informe->seccion }}</td>
                                        <td>
                                            @if(Entrust::can('ver_informes_academicos'))
                                                <a type="button" href="{{URL::to('/')}}/informes/{{$informe->id}}/profesor/{{$informe->profesor->id}}/actividad/{{$informe->curso->id}}/modulo/{{$informe->modulo->id}}/seccion/{{$informe->seccion}}" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Ver" aria-hidden="true" target="_blank"><span class="glyphicon glyphicon-eye-open" ></span></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @if($busq_)
                                    <td></td>
                                    <td> 0 resultados de la busqueda</td>
                                @else
                                    <td></td>
                                    <td>No se han generado informes</td>
                                @endif
                            @endif
                        @else
                            @if($informes->count())
                                @foreach($informes as $index => $informe)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $informe->curso->nombre }}</td>
                                        <td>{{ $informe->profesor->nombre }} {{ $informe->profesor->apellido }} </td>
                                        <td>{{ $informe->modulo->nombre }}</td>
                                        <td>{{ $informe->seccion }}</td>
                                        <td>
                                            @if(Entrust::can('ver_informes_academicos'))
                                                <a type="button" href="{{URL::to('/')}}/informes/{{$informe->id}}/profesor/{{$informe->profesor->id}}/actividad/{{$informe->curso->id}}/modulo/{{$informe->modulo->id}}/seccion/{{$informe->seccion}}" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Ver" aria-hidden="true" target="_blank"><span class="glyphicon glyphicon-eye-open" ></span></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @if($busq_)
                                    <td></td>
                                    <td> 0 resultados de la busqueda</td>
                                @else
                                    <td></td>
                                    <td>No se han generado informes</td>
                                @endif
                            @endif
                        @endif
                        </tbody>
                    </table>
                </div>
                <a href="{{URL::to("/")}}/" class="btn btn-default text-right"><span class="glyphicon glyphicon-chevron-left"></span> Regresar</a>
            </div>
        @endif
    </div>



@stop