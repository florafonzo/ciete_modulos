@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Pagos realizados
            </h3>
        </div>
        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes')
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!! Form::open(array('method' => 'get', 'route' => array('pago.buscar'), 'id' => 'form_busq')) !!}
                        <div class="buscador">
                            <select class="form-control " id="param1" name="parametro">
                                <option value="0"  selected="selected">Buscar por</option>
                                <option value="nombre"  >Nombre</option>
                                <option value="apellido"  >Apellido</option>
                                {{--<option value="di"  >Dcocumento de identidad</option>--}}
                                <option value="curso"  >Nombre actividad</option>
                            </select>
                            {!!Form::text('busqueda', null,array('placeholder' => 'Escriba su busqueda...','class' => 'form-control bus', 'id' => 'busq'))!!}
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
                            <th>Actividad</th>
                            <th>Fecha de pago</th>
                            <th>Modalidad</th>
                            <th>Número de pago</th>
                            <th>Monto</th>
                            <th>Acciones</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($busq)
                            @if($pagos != '')
                                @foreach($pagos as $index => $pago)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $pago->participante->nombre }}</td>
                                        <td>{{ $pago->participante->apellido  }}</td>
                                        <td>{{ $pago->curso->nombre }}</td>
                                        <td>{{ $pago->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $pago->modalidad->nombre }}</td>
                                        <td>{{ $pago->numero_pago }}</td>
                                        <td>{{ $pago->monto}}</td>
                                        <td>
                                            @if(Entrust::can('aprobar_pago'))
                                                {!! Form::open(array('method' => 'post','route' => array('pago.aprobar'), 'id' => 'form_pago'.$pago->id)) !!}
                                                <input name="val" type="hidden" value="{{$pago->id}}">
                                                <button type="button" onclick="aprobarPago('{{$pago->id}}')" class='btn btn-success' data-toggle='tooltip' data-placement="bottom" title="Aprobar">
                                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                                </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                        <td>
                                            @if(Entrust::can('desaprobar_pago'))
                                                {!! Form::open(array('method' => 'DELETE','route' => array('pago.destroy', $pago->id), 'id' => 'form_pago2'.$pago->id)) !!}
                                                <button type="button" onclick="rechazarPago('{{$pago->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Rechazar" aria-hidden="true">
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
                                    <td>No existen pagos por aprobar</td>
                                @endif
                            @endif
                        @else
                            @if($pagos->count())
                                @foreach($pagos as $index => $pago)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $pago->participante->nombre }}</td>
                                        <td>{{ $pago->participante->apellido  }}</td>
                                        <td>{{ $pago->curso->nombre }}</td>
                                        <td>{{ $pago->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $pago->modalidad->nombre }}</td>
                                        <td>{{ $pago->numero_pago }}</td>
                                        <td>{{ $pago->monto}}</td>
                                        <td>
                                            @if(Entrust::can('aprobar_pago'))
                                                {!! Form::open(array('method' => 'post','route' => array('pago.aprobar'), 'id' => 'form_pago'.$pago->id)) !!}
                                                    <input name="val" type="hidden" value="{{$pago->id}}">
                                                    <button type="button" onclick="aprobarPago('{{$pago->id}}')" class='btn btn-success' data-toggle='tooltip' data-placement="bottom" title="Aprobar">
                                                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                                    </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                        <td>
                                            @if(Entrust::can('desaprobar_pago'))
                                                {!! Form::open(array('method' => 'DELETE','route' => array('pago.destroy', $pago->id), 'id' => 'form_pago2'.$pago->id)) !!}
                                                    <button type="button" onclick="rechazarPago('{{$pago->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Rechazar" aria-hidden="true">
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
                                    <td>No existen pagos por aprobar</td>
                                @endif
                            @endif
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-8">
                        <?php echo $pagos->render(); ?>
                    </div>
                </div>
                <div class="">
                    <a href="{{URL::to("/")}}/" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                </div>
            </div>
        @endif
    </div>
@stop