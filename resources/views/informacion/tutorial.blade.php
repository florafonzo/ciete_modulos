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
                @include('partials.mensajes')
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 col-sm-12 col-xs-12" style="text-align: justify">
                        <div class="row">
                            <h3 style="font-weight: bold">Video tutorial</h3>
                        </div>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/NPsI_HM-dx8"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop