@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Actividades para inscripción
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
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        @if(count($cursos) != 0)
                            {{$cursos}}
                            <tbody>
                            @foreach($cursos as $index => $curso)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $curso->nombre }}</td>
                                    <td>{{ $curso->tipo_curso  }}</td>
                                    <td>{{ $curso->inicio->format('d-m-Y') }}</td>
                                    <td>{{ $curso->fin->format('d-m-Y')  }}</td>
                                    <td>{{ $curso->estado }}</td>
                                    @if($curso->activo_preinscripcion)
                                        <td>
                                            @if(Entrust::can('desactivar_preinscripcion'))
                                                {!! Form::open(array('method' => 'GET', 'route' => array('preinscripcion.desactivar', $curso->id), 'id' => 'form_desactivar'.$curso->id)) !!}
                                                {{--{!! Form::button('<span class="glyphicon glyphicon-trash" id="{{$curso->id}}" data-toggle="tooltip" data-placement="bottom" title="Desactivar" aria-hidden="true"></span>',
                                                array('type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#modal_eliminar_cursos','class' => 'btn btn-danger'))!!}--}}
                                                <button type="button" onclick="desactivarPrecurso('{{$curso->id}}')" class='btn btn-danger' data-toggle='tooltip' data-placement="bottom" title="Desactivar">
                                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                    @else
                                        <td>
                                            @if(Entrust::can('activar_preinscripcion'))
                                                {!! Form::open(array('method' => 'GET','route' => array('preinscripcion.activar', $curso->id))) !!}
                                                <button type="submit" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Activar" >
                                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                                </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        @else
                            <td></td>
                            <td>No existen actividades para la inscripción</td>
                        @endif
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-8">
                        <?php echo $cursos->render(); ?>
                    </div>
                </div>
                <div class="">
                    <a href="{{URL::to("/")}}/" class="btn btn-default text-right"><span class="glyphicon glyphicon-chevron-left"></span> Regresar</a>
                </div>
            </div>
        @endif
    </div>
@stop