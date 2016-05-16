@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Usuarios para inscribir
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
                                <option value="di"  >Dcocumento de identidad</option>
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
                            <th>Documento de identidad</th>
                            {{--<th>Email</th>--}}
                            <th>Actividad</th>
                            {{--<th>Tipo</th>--}}
                            <th>Modalidad de pago</th>
                            <th>Número de pago</th>
                            <th>Monto del pago</th>
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
                                        <td>{{ $user->di  }}</td>
{{--                                        <td>{{ $user->email }}</td>--}}
                                        <td>{{ $user->curso_nombre }}</td>
                                        {{--<td>--}}
                                            {{--{{$user->tipo}}--}}
                                        {{--</td>--}}
                                        @if($user->tipo == 'Diplomado' || $user->tipo == 'Cápsula')
                                            <td>
                                                {{ $user->modalidad }}
                                            </td>
                                            <td>
                                                {{ $user->numero_pago }}
                                            </td>
                                            <td>
                                                {{ $user->monto }}
                                            </td>
                                            {{--<td>--}}
                                                {{--@if(Entrust::can('activar_inscripcion'))--}}
                                                    {{--{!! Form::open(array('method' => 'GET','route' => array('inscripcion.documentos', $user->id))) !!}--}}
                                                        {{--<button type="submit" class='btn btn-info' data-toggle='tooltip' data-placement="bottom" title="Ver documentos">--}}
                                                            {{--<span class="glyphicon glyphicon-file" aria-hidden="true"></span>--}}
                                                        {{--</button>--}}
                                                        {{--{!! Form::button('<span class="glyphicon glyphicon-file" data-toggle="tooltip" data-placement="bottom" title="Ver documentos" aria-hidden="true"></span>', array('type' => 'submit', 'class' => 'btn btn-info'))!!}--}}
                                                    {{--{!! Form::close() !!}--}}
                                                {{--@endif--}}
                                            {{--</td>--}}
                                        @else
                                            <td>NA</td>
                                            <td>NA</td>
                                            <td>NA</td>
                                        @endif
                                        <td>
                                            @if(Entrust::can('activar_inscripcion'))
                                                {!! Form::open(array('method' => 'POST','route' => array('inscripcion.store'), 'id' => 'form_inscripcion'.$user->id)) !!}
                                                    <input name="val" type="hidden" value="{{$user->id}}">
                                                    <button type="button" onclick="activarInscripcion('{{$user->id}}')" class='btn btn-success' data-toggle='tooltip' data-placement="bottom" title="Aprobar">
                                                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                                    </button>
                                                    {{--{!! Form::button('<span class="glyphicon glyphicon-ok" data-toggle="tooltip" data-placement="bottom" title="Activar" aria-hidden="true"></span>', array('type' => 'submit', 'class' => 'btn btn-success'))!!}--}}
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                        <td>
                                            @if(Entrust::can('desactivar_inscripcion'))
                                                {!! Form::open(array('method' => 'DELETE','route' => array('inscripcion.destroy', $user->id), 'id' => 'form_inscripcion2'.$user->id)) !!}
                                                    <button type="button" onclick="rechazarModal('{{$user->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Rechazar" aria-hidden="true">
                                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                    </button>
                                                    <div id="motivo"></div>
                                                {!! Form::close() !!}
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
                <div class="row">
                    <div class="col-sm-8">
                        <?php echo $usuarios->render(); ?>
                    </div>
                </div>
                <div class="">
                    <a href="{{URL::to("/")}}/" class="btn btn-default text-right"><span class="glyphicon glyphicon-chevron-left"></span> Regresar</a>
                </div>
            </div>
        @endif
    </div>
@stop