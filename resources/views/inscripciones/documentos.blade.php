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
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Documento</th>
                            <th>Acciones</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {{--@if($busq)--}}
                        @if($docs != null)
                            @foreach($docs as $index => $doc)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{$nombres[$index]}}</td>
                                    <td>
                                        @if(Entrust::can('activar_inscripcion'))
                                            {!! Form::open(array('method' => 'GET','route' => array('inscripcion.verPdf', $doc), "target" => "_blank")) !!}
                                                <button type="submit" class='btn btn-info' data-toggle='tooltip' data-placement="bottom" title="Ver">
                                                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                                </button>
{{--                                                {!! Form::button('<span class="glyphicon glyphicon-eye-open" data-toggle="tooltip" data-placement="bottom" title="Ver" aria-hidden="true"></span>', array('type' => 'submit', 'class' => 'btn btn-info'))!!}--}}
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                            @endforeach
                        @else
                            <td></td>
                            <td>No existen documentoss</td>
                        @endif

                        </tbody>
                    </table>
                </div>
                <a href="{{URL::to("/")}}/inscripcion/procesar" class="btn btn-default text-right"><span class="glyphicon glyphicon-chevron-left"></span> Volver</a>
            </div>
        @endif
    </div>



@stop