@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Pagos de la actividad {{$curso->nombre}}
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes'){{--Errores--}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha de pago</th>
                            <th>Monto</th>
                            <th>Modalidad de pago</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        @if($pagos->count())
                            <tbody>
                            @foreach($pagos as $index => $pago)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pago->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $pago->monto }}</td>
                                    <td>{{ $pago->modalidad->nombre }}</td>
                                    <td>{{ $pago->estatus }}</td>
                                    <td class="boton_">
                                        @if(Entrust::can('generar_recibo'))
                                            {!! Form::open(array('method' => 'GET','route' => array('participante.recibo', $curso->id, $pago->id), "target" => "_blank")) !!}
                                                <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Recibo de pago">
                                                    <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>
                                                </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td>Le queda <strong>{{$pagos_restantes}}</strong> cuota(s) por pagar</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        @else
                             <td>No existe ningún pago</td>
                        @endif
                    </table>
                </div>
                @if(Entrust::can('generar_pago'))
                    @if($completo == false)
                        <div class="" style="text-align: center;">
                            {!! Form::open(array('method' => 'GET','route' => array('participante.nuevo', $curso->id))) !!}
                                <button type="submit" class="btn btn-success">Agregar pago</button>
                            {!! Form::close() !!}
                        </div>
                    @endif
                @endif
                @if(Entrust::can('ver_cursos_part'))
                <div class="">
                    <a href="{{URL::to('/')}}/participante/actividades" type="button" class="btn btn-default" ><span class="glyphicon glyphicon-chevron-left"></span>Regresar</a>
                </div>
                @endif
            </div>
        @endif
    </div>

@stop