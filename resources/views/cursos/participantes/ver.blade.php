@extends('layouts.layout')

@section('content')
    <div class="row" xmlns="http://www.w3.org/1999/html">
        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Datos del participante
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes'){{--Errores--}}
                @if($participante->count())
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tr>
                                <th>
                                    Nombre:
                                </th>
                                <td>
                                    {{$participante->nombre}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Apellido:
                                </th>
                                <td>
                                    {{$participante->apellido}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Documento de identidad:
                                </th>
                                <td>
                                    {{$participante->documento_identidad}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Archivo Documento de identidad:
                                </th>
                                <td>
                                    @if ($participante->di_file == '')
                                        <strong>No ha guardado nigún archivo</strong>
                                    @else
                                        <a class="btn btn-info btn-sm" href="{{URL::to('/')}}/actividades/{{$curso->id}}/grupos/{{$seccion}}/participantes/{{$participante->id}}/archivo/{{$participante->di_file}}/ver" data-toggle="tooltip" data-placement="bottom" title="Ver archivo" target="_blank">
                                            <span class="glyphicon glyphicon-eye-open"></span>
                                        </a>
                                        {!!Form::hidden('di_f', null)!!}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Teléfono:
                                </th>
                                <td>
                                    {{$participante->telefono}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Celular:
                                </th>
                                <td>
                                    {{$participante->celular}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Dirección:
                                </th>
                                @if($estado != '')
                                    <td>
                                        <strong>País: </strong>{{$pais->nombre_mostrar}}<br>
                                        <strong>Estado: </strong>{{$estado->estado}}<br>
                                        <strong>Ciudad: </strong>{{$ciudad->ciudad}}<br>
                                        <strong>Municipio: </strong>{{$municipio->municipio}}<br>
                                        <strong>Parroquia: </strong>{{$parroquia->parroquia}}
                                    </td>
                                @else
                                    <td>
                                        @if($pais != '')
                                            <strong>País: </strong>{{$pais->nombre_mostrar}}<br>
                                        @else
                                            <strong>País: </strong>Incompleto<br>
                                        @endif
                                        <strong>Estado: </strong>Incompleto<br>
                                        <strong>Ciudad: </strong>Incompleto<br>
                                        <strong>Municipio: </strong>Incompleto<br>
                                        <strong>Parroquia: </strong>Incompleto
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <th>
                                    Correo:
                                </th>
                                <td>
                                    {{$usuario->email}}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Correo alternativo:
                                </th>
                                <td>
                                    {{$participante->correo_alternativo}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Twitter:
                                </th>
                                <td>
                                    {{$participante->twitter}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Ocupación:
                                </th>
                                <td>
                                    {{$participante->ocupacion}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Archivo Título:
                                </th>
                                <td>
                                    @if ($participante->titulo_pregrado == '')
                                        <strong>No ha guardado nigún archivo</strong>
                                    @else
                                        <a class="btn btn-info btn-sm" href="{{URL::to('/')}}/actividades/{{$curso->id}}/grupos/{{$seccion}}/participantes/{{$participante->id}}/archivo/{{$participante->titulo_pregrado}}/ver" data-toggle="tooltip" data-placement="bottom" title="Ver archivo" target="_blank">
                                            <span class="glyphicon glyphicon-eye-open"></span>
                                        </a>
                                        {!!Form::hidden('di_f', null)!!}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                            <tr>
                                <th>
                                    Universidad:
                                </th>
                                <td>
                                    {{$participante->universidad}}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <h4><strong>Pagos realizados</strong></h4>
                    @if($pagos->count())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                @foreach($pagos as $index => $pago)
                                    <tr style="background-color: rgba(51,122,183,0.5);">
                                        <th><strong>Pago #{{$index + 1}}</strong></th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Modalidad:
                                        </th>
                                        <td>
                                            {{$pago->modalidad->nombre}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Fecha pago:
                                        </th>
                                        <td>
                                            {{$pago->fechas->format('d-m-Y')}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Banco:
                                        </th>
                                        <td>
                                            {{$pago->banco->nombre}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Número de pago:
                                        </th>
                                        <td>
                                            {{$pago->numero_pago}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Monto:
                                        </th>
                                        <td>
                                            {{$pago->monto}}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @else
                        No ha realizado ningún pago
                    @endif
                    @if(Entrust::can('participantes_curso'))
                        <div class="" style="text-align: center;">
                            <a href="{{URL::to('/')}}/actividades/{{$curso->id}}/grupos/{{$seccion}}/participantes" type="button" class="btn btn-default" >
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>Regresar
                            </a>
                        </div>
                    @endif
                @endif

            </div>
        @endif
    </div>
@stop