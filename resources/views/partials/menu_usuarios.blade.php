

<div class="col-md-4 col-xs-12 opciones_part">
    <div class="row">
        <div class="col-md-6 col-xs-12-6 col-md-offset-3">
            <img class="img-responsive" src="{{URL::to('/')}}/images/images_perfil/{{$foto}}">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12 menu_part">
            <div class="navbar-header" style="color: white;">
               {{--<strong> Opciones </strong>--}}
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-bar-2">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="nav-bar-2">
                <div class="">
                    <form action="#" method="get" class="sidebar-form">
                        <div class="input-group">
                            <input type="text" id="buscador" class="form-control" placeholder="Buscar menú..."/>
                            <span class="input-group-btn">
                                <button type='button' id='search-btn' class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
                            </span>
                        </div>
                    </form>
                </div>
                <ul class="nav nav-pills nav-stacked">
                    @if(Entrust::can('ver_perfil_part'))
                        <li id="perfil" class="menu_usuarios menuItem @if(Request::is('participante*') and (!(Request::is('participante/actividades*')) and !(Request::is('participante/webinars*')))) active @endif">
                            <a class="menu_usu" href="{{URL::to('/participante/perfil')}}"> Perfil </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_perfil_prof'))
                        <li id="perfil" class="menu_usuarios menuItem  @if(Request::is('profesor*') and (!(Request::is('profesor/actividades*')) and !(Request::is('profesor/webinars*')))) active @endif">
                            <a class="menu_usu"  href="{{URL::to('/profesor/perfil')}}"> Perfil </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_usuarios'))
                        <li id="usuarios" class=" menu_usuarios menuItem  @if(Request::is('usuarios*')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/usuarios')}}"> Usuarios </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_lista_cursos'))
                        <li id="actividades" class="menu_usuarios menuItem  @if(Request::is('actividades') or (!(Request::is('actividades/desactivados*')) and Request::is('actividades/*'))) active @endif">
                            <a class="menu_usu"  href="{{URL::to('/actividades')}}"> Actividades </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_lista_cursos'))
                        <li id="actividades" class="menu_usuarios menuItem  @if(Request::is('actividades/desactivados*')) active @endif">
                            <a class="menu_usu"  href="{{URL::to('/actividades/desactivados')}}"> Actividades desactivadas </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_roles'))
                        <li id="roles" class="menu_usuarios menuItem  @if(Request::is('roles*')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/roles')}}"> Roles </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_webinars'))
                        <li id="webinars" class="menu_usuarios menuItem  @if(Request::is('webinars') or (!(Request::is('webinars/desactivados*')) and Request::is('webinars/*'))) active @endif">
                            <a class="menu_usu" href="{{URL::to('/webinars')}}"> Webinars </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_webinars'))
                        <li id="webinars" class="menu_usuarios menuItem  @if(Request::is('webinars/desactivados*')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/webinars/desactivados')}}"> Webinars desactivados </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_cursos_part'))
                        <li id="actividades" class="menu_usuarios menuItem  @if(Request::is('participante/actividades*')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/participante/actividades')}}"> Actividades inscritas </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_cursos_part'))
                        <li id="webinars" class="menu_usuarios menuItem  @if(Request::is('participante/webinars*')) active @endif">
                            <a class="menu_usu"  href="{{URL::to('/participante/webinars')}}"> Webinars inscritos </a>
                        </li>
                    @endif
                    @if(Entrust::can('activar_preinscripcion'))
                        <li id="inscripciones" class="menu_usuarios menuItem  @if(Request::is('inscripcion/actividades/procesar')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/inscripcion/actividades/procesar')}}"> Inscripciones de Actividades </a>
                        </li>
                    @endif
                    @if(Entrust::can('activar_preinscripcion'))
                        <li id="inscripciones" class="menu_usuarios menuItem  @if(Request::is('inscripcion/webinars/procesar')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/inscripcion/webinars/procesar')}}"> Inscripciones de Webinars </a>
                        </li>
                    @endif
                    @if(Entrust::can('activar_inscripcion'))
                        <li id="inscripciones" class="menu_usuarios menuItem  @if(Request::is('inscripcion*') and (!(Request::is('inscripcion/webinars/procesar'))) and (!(Request::is('inscripcion/actividades/procesar')))) active @endif">
                            <a class="menu_usu" href="{{URL::to('/')}}/inscripcion/procesar"> Gestión Inscripciones </a>
                        </li>
                    @endif
                    @if(Entrust::can('gestionar_pagos'))
                        <li id="pagos" class="menu_usuarios menuItem  @if(Request::is('pagos*')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/')}}/pagos"> Gestión Pagos </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_informes_academicos'))
                        <li id="informes" class="menu_usuarios menuItem  @if(Request::is('informes*')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/')}}/informes"> Informes académicos </a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_cursos_profe'))
                        <li id="actividades" class="menu_usuarios menuItem  @if(Request::is('profesor/actividades*')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/profesor/actividades')}}"> Actividades dictadas</a>
                        </li>
                    @endif
                    @if(Entrust::can('ver_cursos_profe'))
                        <li id="webinars" class="menu_usuarios menuItem  @if(Request::is('profesor/webinars*')) active @endif">
                            <a class="menu_usu" href="{{URL::to('/profesor/webinars')}}"> Webinars dictados</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
