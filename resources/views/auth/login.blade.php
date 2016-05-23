@extends('layouts.layout')

@section('content')
    <div class="container-fluid">
        <div class="row paneles">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel_login">
                    <div class="panel-heading">Entrar</div>
                    <div class="panel-body">
                        @include('partials.mensajes')

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-4 control-label">Correo electrónico</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Contraseña</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> Recordar mis datos
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">Entrar</button>

                                    <a class="btn btn-link" href="{{ url('/password/email') }}">¿Olvidó su contraseña?</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
