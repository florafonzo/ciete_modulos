@extends('layouts.layout')

@section('content')
    <div class="row">

        <div class="col-md-12 col-sm-12 col-md-offset-2 bienvenida">
            <h3>
                Editar datos
            </h3>
        </div>

        @if (!(Auth::guest()))
            @include('partials.menu_usuarios')
            <div class="col-md-8 col-sm-8 opciones_part2">
                @include('partials.mensajes'){{--Errores--}}
                @if($datos->count())
                    {!! Form::open(array('method' => 'PUT', 'route' => array('participante.update', Auth::user()->id), 'class' => 'form-horizontal col-md-10', 'enctype' => "multipart/form-data")) !!}
                    <div class="form-group" id="perfil">
                        {!!Form::label('imagen_perfil', 'Imagen de Perfil: ',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8" id="borde">
                            @if (Session::has('imagen'))
                                {!!Form::file('file_perfil',['required'=>'True', 'id' => 'file_perfil', 'accept' => 'image/jpeg'])!!}
                                {!!Form::hidden('img_carg','yes',['id' => 'oculto'])!!}
                                {!!Form::hidden('file_viejo',$foto)!!}
                            @else
                                @if (Session::has('cortar'))
                                    <br>
                                    {!!Form::hidden('img_carg',null)!!}
                                    {!!Form::hidden('cortar','yes')!!}
                                    {!!Form::hidden('dir',$ruta)!!}
                                    {!!Form::hidden('file_viejo',$foto)!!}
                                    <img src="{{$ruta}}" id="imagen_cortada" width="150" height="150"><br><br>
                                    <a class="btn btn-success btn-xs" href="{{URL::to('/')}}/participante/perfil/imagen">Cambiar imágen</a>
                                @else
                                    <br>
                                    {!!Form::hidden('img_carg',null)!!}
                                    {!!Form::hidden('cortar',null)!!}
                                    <img src="{{URL::to('/')}}/images/images_perfil/{{$foto}}" id="imagen_cortada" width="150" height="150"><br><br>
                                    {{--{!!Html::image('/img/images_perfil/'.$perfil->file_perfil,null, ['height'=>'279', 'width'=>'270 ']) !!} <br><br>--}}
                                    <a class="btn btn-warning btn-sm" href="{{URL::to('/')}}/participante/perfil/imagen" title="Cambiar foto" data-toggle="tooltip" data-placement="bottom" aria-hidden="true" style="text-decoration: none">Cambiar imágen</a>
                                @endif
                            @endif
                        </div>
                    </div>
                    <img class="" id="imagen2" src="" alt="">
                    {!!Form::hidden('file_viejo',$foto)!!}
                    <div class="form-group">
                        {!!Form::label('nombre', 'Nombre:', array( 'class' => 'col-md-4 ')) !!}
                        <div class="col-sm-8">
                            {!!Form::text('nombre', $datos[0]->nombre ,array('required', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('apellido', 'Apellido:',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!!Form::text('apellido', $datos[0]->apellido ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('documento_identidad', 'Documento de Identidad:',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!!Form::text('documento_identidad', $datos[0]->documento_identidad ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!!Form::label('di_file', 'Archivo documento de identidad:',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            @if (Session::has('di_f'))
                                {!!Form::hidden('di_f','yes')!!}
                                {!!Form::file('archivo_documento_identidad',['id' => 'di_file', 'accept' => 'application/pdf'])!!}
                            @else
                                <a class="btn btn-info btn-sm" href="{{URL::to('/')}}/participante/perfil/archivo/{{$datos[0]->di_file}}/ver" data-toggle="tooltip" data-placement="bottom" title="Ver archivo" target="_blank">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </a>
                                {!!Form::hidden('di_f', null)!!}
                                <a class="btn btn-warning btn-sm" href="{{URL::to('/')}}/participante/perfil/documento_identidad">Cambiar archivo</a>

                                {{--<button type="button" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Ver archivo"><span class="glyphicon glyphicon-eye-open"></span></button>--}}
                                {{--<a class="btn btn-success btn-xs" href="{{URL::to('/')}}/participante/perfil/documento_identidad">Cambiar archivo</a>--}}
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('telefono', 'Teléfono de Fijo:',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!!Form::text('telefono', $datos[0]->telefono ,array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('celular', 'Teléfono Móvil: ',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!!Form::text('celular', $datos[0]->celular, array( 'class' => 'form-control'))!!}
                        </div>
                    </div>
                    @if($datos[0]->nuevo)
                        <div class="form-group">
                            {!!Form::label('pais', 'Pais: ', array('class' => 'col-md-4 '))!!}
                            <div class="col-sm-8 pais">
                                <select class="form-control " id="id_pais" name="id_pais">
                                    <option value="0"  selected="selected"> Seleccione un pais</option>
                                    @foreach ($paises as $index=>$pais)
                                        <option value="{{$index}}">{{$pais}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group localidad">
                            <div class="form-group">
                                {!!Form::label('estado', 'Estado:', array('class' => 'col-md-4 '))!!}
                                <div class="col-sm-8">
                                    <!--{!! Form::select('id_estado', $estados, null, array( 'class' => 'form-control', 'id'=>'id_est'))!!}-->
                                    <select class="form-control col-sm-8" id="id_est" name="id_est">
                                        <option value="0"  selected="selected"> Seleccione un estado</option>
                                        @foreach ($estados as $index=>$estado)
                                            <option value="{{$index}}">{{$estado}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group localidad1">
                            <div class="form-group">
                                {!!Form::label('ciudad', 'Ciudad:', array('class' => 'col-md-4 '))!!}
                                <div class="col-sm-8">
                                    <select class="form-control" id="ciudad" name="ciudad">

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group localidad2">
                            <div class="form-group">
                                {!!Form::label('municipio', 'Municipio:', array('class' => 'col-md-4 '))!!}
                                <div class="col-sm-8">
                                    <select class="form-control" id="municipio" name="municipio">

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group localidad3">
                            <div class="form-group">
                                {!!Form::label('parroquia', 'Parroquia:', array('class' => 'col-md-4 '))!!}
                                <div class="col-sm-8">
                                    <select class="form-control" id="parroquia" name="parroquia">

                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        {!!Form::label('email', 'Correo electrónico:',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!! Form::email('email', $email[0]->email, array('required','class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('password', 'Contraseña:',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!! Form::password('password', array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('password1', 'Confirme su contraseña:',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('correo_alternativo', 'Correo electrónico alternativo: ',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!! Form::email('correo_alternativo', $datos[0]->correo_alternativo, array(  'class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group" >
                        {!!Form::label('twitter', 'Usuario twitter: ',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!! Form::text('twitter', $datos[0]->twitter, array(  'class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group" >
                        {!!Form::label('ocupacion', 'Ocupacion: ',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!! Form::text('ocupacion', $datos[0]->ocupacion, array(  'class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="form-group" >
                        {!!Form::label('titulo', 'Titulo de pregrado: ',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            @if (Session::has('titulo_'))
                                {!!Form::hidden('titulo_','yes')!!}
                                {!!Form::file('titulo',['id' => 'di_file', 'accept' => 'application/pdf'])!!}
                            @else
                                <a class="btn btn-info btn-sm" href="{{URL::to('/')}}/participante/perfil/archivo/{{$datos[0]->titulo_pregrado}}/ver" data-toggle="tooltip" data-placement="bottom" title="Ver archivo" target="_blank">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </a>
                                {!!Form::hidden('titulo_', null)!!}
                                <a class="btn btn-warning btn-sm" href="{{URL::to('/')}}/participante/perfil/titulo">Cambiar archivo</a>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('univ', 'Universidad dónde obtuvo el título: ',  array( 'class' => 'col-md-4 '))!!}
                        <div class="col-sm-8">
                            {!! Form::text('univ', $datos[0]->universidad, array(  'class' => 'form-control'))!!}
                        </div>
                    </div>
                    @if(Entrust::can('editar_perfil_part'))
                        <a href="{{URL::to("/")}}/participante/perfil" class="btn btn-default text-right"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                        {!! Form::submit('Guardar', array('class' => 'btn btn-success')) !!}
                    @endif
                    {!! Form::close() !!}
                @endif

            </div>
        @endif
    </div>

    {{--Modal edición imagen de perfil--}}
    <div class="modal fade" id="imagenModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <a href="{{URL::to('/')}}/participante/perfil/{{Auth::user()->id}}/editar" class="pull-right"> <span class="glyphicon glyphicon-remove" style="color: #333;"></span> </a>
                    <h4> Edición de imagen</h4>
                </div>
                <div class="modal-body">

                    <div class="content2 esconder" id="" style="display: none; ">
                        <div class="component ">
                            <div class="overlay ">
                                <div class="overlay-inner ">
                                </div>
                            </div>
                            <img class="resize-image" id="imagen" src="" alt="image for resizing">
                        </div>

                    </div>
                    <div id="pepe">
                    </div>
                    <br>
                    <div style="text-align: center">
                        <button class="btn js-crop" type="button"><i class="fa fa-crop"></i> Cortar </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-default pull-left" href="{{URL::to('/')}}/participante/perfil/{{Auth::user()->id}}/editar"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
                    {!!Form::open(['url' => 'participante/perfil/procesar/',  "method" => "post", "id" => "form_imagen"])!!}
                    <input type="hidden" class="" id="rutas" name="rutas">
                    <button type="submit" class="btn btn-success btn-success pull-right" id="aceptar" ><span class="glyphicon glyphicon-ok"></span> Aceptar</button>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
    {{--Fin Modal--}}




@stop