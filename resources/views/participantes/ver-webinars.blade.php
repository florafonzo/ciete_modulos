@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Webinars inscritos
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
                            <th>Nombre</th>
                            <th>Grupo</th>
                            <th>Fecha inicio</th>
                            <th>Fecha fin</th>
                            {{--<th>Acciones</th>--}}
                            <th></th>
                        </tr>
                        </thead>
                        @if($webinars != null)
                            <tbody>
                            @foreach($webinars as $index => $web)
                                <tr>
                                    <td>{{ $web[0]->nombre }}</td>
                                    <td>{{ $seccion[0]  }}</td>
                                    <td>{{ $inicio[$index]->format('d-m-Y')  }}</td>
                                    <td>{{ $fin[$index]->format('d-m-Y') }}</td>
                                    {{--<td>--}}
                                        {{--@if(Entrust::can('ver_notas_part'))--}}
                                            {{--{!!Form::open(["url"=>"participante/webinars/".$web[0]->id."/notas",  "method" => "GET" ])!!}--}}
                                                {{--<button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Notas">--}}
                                                    {{--<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>--}}
                                                {{--</button>--}}
                                            {{--{!! Form::close() !!}--}}
                                        {{--@endif--}}
                                    {{--</td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                        @endif
                    </table>
                </div>
            </div>
        @endif
    </div>

@stop