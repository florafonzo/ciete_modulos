@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 bienvenida">
            <h3>
                Pago de la actividad {{$curso->nombre}}
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
            @include('partials.mensajes'){{--Errores--}}
                {!! Form::open(array('method' => 'POST', 'route' => array('participante.pago', $curso->id), 'class' => 'form-horizontal col-md-10', 'enctype' => "multipart/form-data")) !!}
                <div class="form-group">
                    {!!Form::label('banco', 'Banco desde el cual realizó el pago:',  array( 'class' => 'col-md-4 '))!!}
                    <div class="col-sm-8">
                        <select class="form-control" name="banco" id="banco" required>
                            <option value="0"  selected="selected">Seleccione el banco</option>
                            @foreach($bancos as $index => $banco)
                                <option value="{{$index}}">{{$banco}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('modalidad', 'Modalidad de pago:',  array( 'class' => 'col-md-4 '))!!}
                    <div class="col-sm-8">
                        <select class="form-control" name="tipo_pago" id="tipo_pago" required>
                            <option value="0"  selected="selected">Seleccione la modalidad de pago</option>
                            @foreach($tipo_pago as $index=>$pago)
                                <option value="{{$index}}">{{$pago}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('numero', 'Número de depósito o transferencia:',  array( 'class' => 'col-md-4 '))!!}
                    <div class="col-sm-8">
                        {!!Form::text('numero_pago', Session::get('numero_pago') ,array('required', 'class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!!Form::label('monto', 'Monto del pago:', array( 'class' => 'col-md-4 ', 'placeholder' => 'Monto cancelado')) !!}
                    <div class="col-sm-8">
                        {!!Form::text('monto', Session::get('monto') ,array('required', 'class' => 'form-control')) !!}
                    </div>
                </div>
                <a href="{{URL::to("/")}}/participante/actividades/{{$curso->id}}/pagos" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                @if(Entrust::can('generar_pago'))
                    {!! Form::submit('Guardar', array('class' => 'btn btn-success')) !!}
                @endif
                {!! Form::close() !!}
            @endif
        </div>
    </div>

@stop