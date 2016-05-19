@extends('layouts.layout')

@section('content')
    <div class="row" xmlns="http://www.w3.org/1999/html"> <!--Sección del cuerpo-->
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="col-md-12 col-sm-12 descripcion_princ">
                    <h3>
                        Ayuda
                    </h3>
                </div>
            </div>
            <div class="col-md-12 col-sm-12">
                <div class="row">
                    <img src="{{URL::to('/')}}/images/ayuda1.png" class="img-responsive ayuda">
                </div>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 col-sm-12 col-xs-12" style="text-align: justify">
                        <h4 style="font-weight: bold">Preguntas frecuentes</h4>
                        <p style="font-weight: bold; color: lightslategrey;">Inscripción</p>
                        <p style="font-weight: bold;">¿Debo crear una cuenta para inscribirme en alguna actividad?</p>
                        <p>
                            No, sólo debe completar el formulario con sus datos al cual puede acceder mediante la opción "Inscripciones"
                            del menú principal ubicado en la parte superior. Una vez inscrito se le envíaran los datos para que pueda acceder al portal.
                        </p>

                        <p style="font-weight: bold;">¿Puedo pagar las actividades en varias partes?</p>
                        <p>
                            Si, puede cancelar los Diplomados hasta en tres (3) partes. Para el primer pago debe cancelar la
                            mitad del monto total y luego cancela la otra mitad en 2 partes.
                        </p>

                        <p style="font-weight: bold;">¿Debo cancelar el monto de la actividad antes de ingresar mis datos?</p>
                        <p>
                            Si, debe cancelar el monto total si es una Cápsula y si es un Diplomado puede cancelar la mitad o el monto total.
                            Para luego ingresar los datos del pago en el formulario de inscripción.
                        </p>

                        <p style="font-weight: bold;">¿Cómo puedo saber si mi inscripción fue confirmada?</p>
                        <p>
                            Debe esperar un lapso de 48 horas dentro del cual recibirá un coreo confirmando su inscripción y sus datos para ingresar al portal.
                        </p>

                        <p style="font-weight: bold;">¿Mi inscripción puede ser rechazada?</p>
                        <p>
                            Si, existe la posibilidad que su inscripción sea rechazada pero no se preocupe se le indicará el motivo por correo y podrá comunicarse
                            con nosotros vía el formulario de contacto al cual puede acceder por el menú principal por la opción "Nosotros".
                        </p>
                        </br>
                        <p style="font-weight: bold; color: lightslategrey;">Prosecución de las actividades</p>
                        <p style="font-weight: bold;">¿Cómo realizo los pagos durante una actividad?</p>
                        <p>
                            Cuando ingresa a su cuenta debe pulsar la opción "Actividades inscritas" del menú lateral y luego en la lista de actividades debe
                            pulsar el botón en forma de tarjeta de crédito con la descrpción "Pagos". En esa sección podrá ver susu pagos realizados a sí como
                            generar nuevos pagos si no ha cancelado la totalidad.
                        </p>
                        <p style="font-weight: bold;">¿Puedo ver mis calificaciones?</p>
                        <p>
                            Claro, en la opción "Actividades inscritas" del menú lateral accede a la lista de actividades inscritas y debe pulsar el botón
                            en forma de ojos para ver los módulos y luego pulsa el botón en forma de libro para acceder a sus calificaciones.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop