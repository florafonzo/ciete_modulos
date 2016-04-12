@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Usuarios preinscritos
            </h3>
        </div>
        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes')
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!! Form::open(array('method' => 'get', 'route' => array('inscripcion.buscar'), 'id' => 'form_busq')) !!}
                        <div class="buscador">
                            <select class="form-control " id="param1" name="parametro">
                                <option value="0"  selected="selected">Buscar por</option>
                                <option value="nombre"  >Nombre</option>
                                <option value="apellido"  >Apellido</option>
                                {{--<option value="documento_identidad"  >Dcocumento de identidad</option>--}}
                                <option value="email"  >Correo</option>
                                <option value="tipo">Tipo curso</option>
                            </select>
                            {!!Form::text('busqueda', null,array('placeholder' => 'Escriba su busqueda...','class' => 'form-control bus', 'id' => 'busq'))!!}
                            {!! Form::select('busqu', $tipos, null, array('class' => 'form-control busq','hidden' => 'true', 'id' => 'busq2')) !!}
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
                            {{--<th>Documento de identidad</th>--}}
                            <th>Email</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {{--@if($busq)--}}
                            @if($usuarios->count())
                                @foreach($usuarios as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->nombre }}</td>
                                        <td>{{ $user->apellido  }}</td>
{{--                                        <td>{{ $user[0]->doc_id  }}</td>--}}
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            {{$user->tipo}}
                                        </td>
                                        @if($user->tipo == 'curso')
                                            <td>
                                                @if(Entrust::can('activar_inscripcion'))
                                                    {!! Form::open(array('method' => 'GET','route' => array('inscripcion.verPdf', $user->id))) !!}
                                                    {!! Form::button('<span class="glyphicon glyphicon-eye-open" data-toggle="tooltip" data-placement="bottom" title="Ver documentos" aria-hidden="true"></span>', array('type' => 'submit', 'class' => 'btn btn-info'))!!}
                                                    {!! Form::close() !!}
                                                @endif
                                            </td>
                                        @endif
                                        <td>
                                            @if(Entrust::can('activar_inscripcion'))
                                                {{-- {!! Form::open(array('method' => 'GET','route' => array('usuarios.edit', $user[0]->id))) !!}--}}
                                                {!! Form::button('<span class="glyphicon glyphicon-ok" data-toggle="tooltip" data-placement="bottom" title="Activar" aria-hidden="true"></span>', array('type' => 'submit', 'class' => 'btn btn-success'))!!}
                                                {{--  {!! Form::close() !!}--}}
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
                                    <td>No existen usuarios preinscritos</td>
                                @endif
                            @endif

                        </tbody>
                    </table>
                </div>

            </div>
        @endif
    </div>



@stop