<nav class="navbar">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header nav-barra">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active fondo">
                    <a class="lista-menu" href="{{URL::to('/')}}">Inicio</a>
                </li>
                <li class="dropdown fondo">
                    <a class="lista-menu" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Nosotros <span class="caret"></span></a>
                    <ul class="dropdown-menu menu_bajo">
                        <li><a href="{{URL::to('/')}}/mision-y-vision">¿Quiénes somos?</a></li>
                        <li><a href="{{URL::to('/')}}/estructura">Estructura</a></li>
                        <li><a href="{{URL::to('/')}}/servicios">Servicios</a></li>
                        <li><a href="{{URL::to('/')}}/equipo">Equipo</a></li>
                        <li><a href="{{URL::to('/')}}/contacto">Contacto</a></li>
                    </ul>
                </li>
                <li class="dropdown fondo">
                    <a class="lista-menu dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Actividades <span class="caret"></span></a>
                    <ul class="dropdown-menu menu_bajo">
                        @foreach($menu['cursos'] as $curso)
                            <li>
                                <a href="{{URL::to('/')}}/descripcion/actividad/{{$curso->id}}"> {{$curso->nombre}}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="dropdown fondo">
                    <a class="lista-menu dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Webinars <span class="caret"></span></a>
                    <ul class="dropdown-menu menu_bajo">
                        @foreach($menu['webis'] as $web)
                            <li>
                                <a href="{{URL::to('/')}}/descripcion/webinar/{{$web->id}}"> {{$web->nombre}}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="fondo">
                    <a class="lista-menu dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Inscripciones <span class="caret"></span></a>
                    <ul class="dropdown-menu menu_bajo">
                        <li >
                            <a href="{{URL::to('/')}}/inscripcion/procedimiento">Procedimiento</a>
                        </li>
                        <li >
                            <a href="{{URL::to('/')}}/inscripcion/actividades">Cápsulas y Diplomados</a>
                        </li>
                        <li>
                            <a href="{{URL::to('/')}}/inscripcion/webinars"> Webinars </a>
                        </li>
                    </ul>
                </li>
                <li class="fondo">
                    <a class="lista-menu dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Aulas Virtuales <span class="caret"></span></a>
                    <ul class="dropdown-menu menu_bajo">
                        <li >
                            <a href="http://moodle.ula.ve/"> Moodle ULA</a>
                        </li>
                        <li>
                            <a href="http://aulaciete.org.ve/"> Aula CIETE</a>
                        </li>
                    </ul>
                </li>
                <li class="fondo">
                    <a class="lista-menu" href="{{URL::to('/')}}/creditos">Créditos</a>
                </li>
                <li class="fondo borde_no">
                    {{--<a class="lista-menu" href="{{URL::to('/')}}/ayuda">Ayuda  <span class="glyphicon glyphicon-question-sign"></span></a>--}}
                    <a class="lista-menu dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Ayuda <span class="caret"></span></a>
                    <ul class="dropdown-menu menu_bajo">
                        <li >
                            <a href="{{URL::to('/')}}/ayuda/preguntas"> Preguntas frecuentes</a>
                        </li>
                        <li>
                            <a href="{{URL::to('/')}}/ayuda/tutorial"> Video tutorial</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right" style="margin-right: -1%">
                <li class="redes">
                    <ul class="list-inline">
                        <li><a href="https://www.facebook.com/ciete.ula"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-facebook.png" alt=""/></a></li>
                        <li><a href="https://twitter.com/cieteula"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-twitter.png" alt=""/></a></li>
                        <li><a href="https://www.youtube.com/channel/UCY9f2COL913LKZoxeFcoQPA"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-youtube.png" alt=""/></a></li>
                        <li><a href="http://instagram.com/ciete.ula"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-instagram.png" alt=""/></a></li>
                        <li><a href="https://plus.google.com/u/0/+CIETEULA/"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-googleplus.png" alt=""/></a></li>
                        {{--<li><a href="http://cieteula.tumblr.com/"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-tumblr.png" alt=""/></a></li>--}}
                        <li><a href="https://www.pinterest.com/raymarq/"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-pinterest.png" alt=""/></a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!--<div class="col-md-4 col-xs-12 text-center redes">
            <ul class="list-inline">
                <li><a href="https://www.facebook.com/ciete.ula"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-facebook.png" alt=""/></a></li>
                <li><a href="https://twitter.com/cieteula"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-twitter.png" alt=""/></a></li>
                <li><a href="https://www.youtube.com/channel/UCY9f2COL913LKZoxeFcoQPA"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-youtube.png" alt=""/></a></li>
                <li><a href="http://instagram.com/ciete.ula"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-instagram.png" alt=""/></a></li>
                <li><a href="https://plus.google.com/u/0/+CIETEULA/"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-googleplus.png" alt=""/></a></li>
                <li><a href="http://cieteula.tumblr.com/"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-tumblr.png" alt=""/></a></li>
                <li><a href="https://www.pinterest.com/raymarq/"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-pinterest.png" alt=""/></a></li>
            </ul>
        </div>-->
    </div>
    <div class="row">
        <div class="col-md-2 col-md-offset-10">
            <div style="font-weight: bold">
                Zoom:
                <button type="button" id="zoom-in" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Agrandar" >
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
                <button type="button" id="zoom-out" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Reducir" >
                    <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>

</nav>

{{--<nav class="clearfix row" >--}}
    {{--<a href="" id="menu-button"><i class="fa fa-bars"></i></a>--}}
    {{--<ul class="menu dropdown col-md-8 list-inline text-center">--}}
        {{--<li>--}}
            {{--<a class="lista-menu" href="/">Inicio</a>--}}
        {{--</li>--}}
        {{--<li class="dropdown-submenu lista-menu">--}}
            {{--Cursos Disponibles--}}
            {{--<ul class="dropdown-menu" role="menu">--}}
                {{--<li><a tabindex="-1" href="#">Presenciales</a></li>--}}
                {{--<li><a tabindex="-1" href="#">A distancia</a></li>--}}
            {{--</ul>--}}
        {{--</li>--}}
        {{--<li>--}}
            {{--<a class="lista-menu" id="" >--}}
                {{--¿Cómo inscribirse?--}}
            {{--</a>--}}
        {{--</li>--}}
        {{--<li class="dropdown-submenu lista-menu">--}}
            {{--Información del Centro--}}
            {{--<ul class="dropdown-menu" role="menu">--}}
                {{--<li><a tabindex="-1" href="/Misión-y-Visión">¿Quiénes somos?</a></li>--}}
                {{--<li><a tabindex="-1" href="/Estructura">Estructura</a></li>--}}
                {{--<li><a tabindex="-1" href="/Servicios">Servicios</a></li>--}}
                {{--<li><a tabindex="-1" href="/Equipo">Equipo</a></li>--}}
                {{--<li><a tabindex="-1" href="Contacto">Contacto</a></li>--}}
            {{--</ul>--}}
        {{--</li>--}}
    {{--</ul>--}}
    {{--<div class="col-md-4 col-xs-12 text-center redes">--}}
        {{--<ul class="list-inline">--}}
            {{--<li><a href="https://www.facebook.com/ciete.ula"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-facebook.png" alt=""/></a></li>--}}
            {{--<li><a href="https://twitter.com/cieteula"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-twitter.png" alt=""/></a></li>--}}
            {{--<li><a href="https://www.youtube.com/channel/UCY9f2COL913LKZoxeFcoQPA"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-youtube.png" alt=""/></a></li>--}}
            {{--<li><a href="http://instagram.com/ciete.ula"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-instagram.png" alt=""/></a></li>--}}
            {{--<li><a href="https://plus.google.com/u/0/+CIETEULA/"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-googleplus.png" alt=""/></a></li>--}}
            {{--<li><a href="http://cieteula.tumblr.com/"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-tumblr.png" alt=""/></a></li>--}}
            {{--<li><a href="https://www.pinterest.com/raymarq/"><img class="img-responsive" src="{{URL::to('/')}}/images/logo-pinterest.png" alt=""/></a></li>--}}
        {{--</ul>--}}
    {{--</div>--}}
{{--</nav>--}}