@extends('layouts.layout')

@section('content')
    <div class="row" xmlns="http://www.w3.org/1999/html"> <!--Sección del cuerpo-->
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="col-md-12 col-xs-12 descripcion_princ">
                    <h3>
                        Ayuda
                    </h3>
                </div>
            </div>
            @include('partials.mensajes')
            <div class="row">
                <div class="">
                    <img src="{{URL::to('/')}}/images/ayuda1.png" class="img-responsive ayuda">
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 col-md-offset-2 parr_contacto">
                    <h3 style="font-weight: bold">Preguntas frecuentes</h3>
                    <h4 style="font-weight: bold; color: inherit;">Inscripción</h4>
                    <div class="preguntas">
                        <a class="" style="background-color: white; color: #418C47; font-weight: bold;" href="#p1" data-toggle="collapse"><span class="glyphicon glyphicon-plus"></span>  ¿Debo crear una cuenta para inscribirme en alguna actividad?</a>
                        <div id="p1" class="collapse">
                            <p>
                                No, sólo debe completar el formulario con sus datos al cual puede acceder mediante la opción "Inscripciones"
                                del menú principal ubicado en la parte superior. Una vez inscrito se le envíaran los datos para que pueda acceder al portal.
                            </p>
                        </div>
                    </div>
                    <div class="preguntas">
                        <a class="" style="background-color: white; color: #418C47; font-weight: bold;" href="#p2" data-toggle="collapse"><span class="glyphicon glyphicon-plus"></span>  ¿Puedo pagar las actividades en varias partes?</a>
                        <div id="p2" class="collapse">
                            <p>
                                Si, puede cancelar los Diplomados hasta en tres (3) partes. Para el primer pago debe cancelar la
                                mitad del monto total y luego cancela la otra mitad en 2 partes.
                            </p>
                        </div>
                    </div>
                    <div class="preguntas">
                        <a class="" style="background-color: white; color: #418C47; font-weight: bold;" href="#p3" data-toggle="collapse"><span class="glyphicon glyphicon-plus"></span>  ¿Debo cancelar el monto de la actividad antes de ingresar mis datos?</a>
                        <div id="p3" class="collapse">
                            <p>
                                Si, debe cancelar el monto total si es una Cápsula y si es un Diplomado puede cancelar la mitad o el monto total.
                                Para luego ingresar los datos del pago en el formulario de inscripción.
                            </p>
                        </div>
                    </div>
                    <div class="preguntas">
                        <a class="" style="background-color: white; color: #418C47; font-weight: bold;" href="#p4" data-toggle="collapse"><span class="glyphicon glyphicon-plus"></span>  ¿Cómo puedo saber si mi inscripción fue confirmada?</a>
                        <div id="p4" class="collapse">
                            <p>
                                Debe esperar un lapso de 48 horas dentro del cual recibirá un coreo confirmando su inscripción y sus datos para ingresar al portal.
                            </p>
                        </div>
                    </div>
                    <div class="preguntas">
                        <a class="" style="background-color: white; color: #418C47; font-weight: bold;" href="#p5" data-toggle="collapse"><span class="glyphicon glyphicon-plus"></span>  ¿Mi inscripción puede ser rechazada?</a>
                        <div id="p5" class="collapse">
                            <p>
                                Si, existe la posibilidad que su inscripción sea rechazada pero no se preocupe se le indicará el motivo por correo y podrá comunicarse
                                con nosotros vía el formulario de contacto al cual puede acceder por el menú principal por la opción "Nosotros".
                            </p>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-md-offset-2 parr_contacto">
                    <h4 style="font-weight: bold; color: inherit;">Prosecución de las actividades</h4>
                    <div class="preguntas">
                        <a class="" style="background-color: white; color: #1968b3; font-weight: bold;" href="#p6" data-toggle="collapse"><span class="glyphicon glyphicon-plus"></span>  ¿Cómo realizo los pagos durante una actividad?</a>
                        <div id="p6" class="collapse">
                            <p>
                                Cuando ingresa a su cuenta debe pulsar la opción "Actividades inscritas" del menú lateral y luego en la lista de actividades debe
                                pulsar el botón con la forma de una tarjeta de crédito con la descrpción "Pagos". En esa sección podrá ver sus pagos realizados a sí como
                                generar nuevos pagos si no ha cancelado la totalidad.
                            </p>
                        </div>
                    </div>
                    <div class="preguntas">
                        <a class="" style="background-color: white; color: #1968b3; font-weight: bold;" href="#p7" data-toggle="collapse"><span class="glyphicon glyphicon-plus"></span>  ¿Puedo ver mis calificaciones?</a>
                        <div id="p7" class="collapse">
                            <p>
                                Claro, en la opción "Actividades inscritas" del menú lateral accede y debe pulsar el botón
                                con la forma de un ojo para ver los módulos y luego pulsa el botón con la forma de un libro para acceder a sus calificaciones.
                            </p>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@stop