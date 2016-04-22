<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
    <style>
        body{
            font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;
        }
        .titulo_{
            width: 100%;
            text-align: center;
        }
        .imagen_{
            width: 300px;
            height: auto;
        }

        .tabla1, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        /*.footer {*/
            /*position: absolute;*/
            /*bottom: 0;*/
            /*width: 100%;*/
            /*height: 60px;*/
            /*display: block;*/
            /*background-color: #f5f5f5;*/
        /*}*/
        /*.footer > .container {*/
            /*padding-right: 15px;*/
            /*padding-left: 15px;*/
            /*margin-right: auto;*/
            /*margin-left: auto;*/
        /*}*/
        .footer {
            position: fixed;
        }

        .header {
            top: 0;
        }

        .footer {
            bottom: 45px;
        }
    </style>
</head>
<body>

<div class="header">
    <img class="imagen_" src="{{URL::to('/')}}/images/ula_logo.png">
</div>
<div class="footer">
    <img src="{{URL::to('/')}}/images/pie_pagina.png" width="90%">
</div>
<div style="z-index: 2">
    <div class="titulo_">
        <h3>
            INFORME ACADÉMICO
        </h3>
    </div>
    <div style="width: 90%; padding-left: 5%;">
        <h4 style="background-color: #FFC000;">Información del tutor</h4>
        <table class="tabla1" style="width:100%">
            <tr>
                <td class="fila"><strong>Nombres y apellido:</strong> {{$nombre}} {{$apellido}}</td>
                <td class="fila"><strong>Cédula de identidad:</strong> {{$ci}}</td>
            </tr>
            <tr>
                <td class="fila"><strong>Correo electrónico:</strong> {{$correo}}</td>
                <td class="fila"><strong>Celular:</strong> {{$celular}}</td>
            </tr>
        </table>

    </div>
    <div style="width: 90%; padding-left: 5%;">
        <h4 style="background-color: #BDD6EE;">Información básica</h4>
        <table class="tabla1" style="width:100%">
            <tr>
                <td style="width: 30%;"><strong>Nombre del módulo:</strong></td>
                <td >{{$modulo->nombre}}</td>
                <td style="border-left: none !important;"></td>
            </tr>
            <tr>
                <td><strong>Diplomado:</strong> {{$curso->nombre}}</td>
                <td><strong>Cohorte:</strong> {{$cohorte}}</td>
                <td><strong>Gruppo:</strong> {{$grupo}}</td>
            </tr>
            <tr>
                <td><strong>Fecha de inicio:</strong> {{$inicio->format('d-m-Y')}}</td>
                <td><strong>Fecha cierre:</strong> {{$fin->format('d-m-Y')}}</td>
                <td></td>
            </tr>
        </table>

    </div>
    <div style="width: 90%; padding-left: 5%;">
        <h4 style="background-color: #A8D08D;">Registro académico</h4>
        <table class="tabla1" style="width:100%">
            <tr>
                <td><strong>Fecha de generación: </strong> {{$fecha_actual}}</td>
            </tr>
        </table>
    </div>
    <br>
    <div style="padding-left: 10%;">
        <table class="tabla1" style="width: 90%">
            <thead class="">
                <tr>
                    <th class="columna">Nombres</th>
                    <th class="columna">Apellidos</th>
                    <th class="columna">FINAL</th>
                    <th class="columna">Proyecto</th>
                </tr>
            </thead>
            @if($participantes != null)
                <tbody class=" ">
                @foreach($participantes as $index => $participante)
                    <tr>
                        <td>{{ $participante[0][0] }}</td>
                        <td>{{ $participante[1][0] }}</td>
                        <td>{{ $participante[2][0] }}</td>
                        @if($participante[3][0] == 'R')
                            <td style="background-color: #FFFF00">{{ $participante[3][0] }}</td>
                        @else
                            <td>{{ $participante[3][0] }}</td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            @else
                <td>No existen participantes</td>
            @endif
        </table>
    </div>
    <div style="width: 90%; padding-left: 5%;">
        <h4 style="background-color: #F4B083;">Resultados académicos</h4>
        <table class="tabla1" style="width:100%">
            <tr>
                <td style="width: 30%;"><strong>Total estudiantes: </strong>{{$total}}</td>
                <td ><strong>Aprobados: </strong>{{$aprobados}} <strong style="border-left:1px solid #000;height:500px">  Reprobados: </strong>{{$reprobados}} <strong style="border-left:1px solid #000;height:500px">  Desertores: </strong>{{$desertores}}</td>
            </tr>
            <tr>
                <td style="width: 30%;"><strong>Conclusión del curso</strong> <br>(en relación a las competencias previstas)</td>
                <td>{{$conclusion}}</td>
            </tr>
            <tr>
                <td style="width: 30%;"><strong>Aspectos positivos del curso</strong></td>
                <td>{{$positivo}}</td>
            </tr>
            <tr>
                <td style="width: 30%;"><strong>Aspectos negtivos del curso</strong></td>
                <td>{{$negativo}}</td>
            </tr>
            <tr>
                <td style="width: 30%;"><strong>Sugerencias de mejora</strong></td>
                <td>{{$sugerencias}}</td>
            </tr>
        </table>
    </div>
    <div style="width: 90%; padding-left: 5%;">
        <h4 style="background-color: #8EAADB;">Infromación del documento</h4>
        <table class="tabla1" style="width:100%">
            <tr>
                <td ><strong>Realizado por: </strong>{{$nombre}} {{$apellido}}</td>
                <td style="width: 30%;"><strong>Fecha: </strong> </td>
            </tr>
            <tr>
                <td ><strong>Revisado por: </strong>{{$nombre}} {{$apellido}}</td>
                <td style="width: 30%;"><strong>Fecha: </strong> </td>
            </tr>
            <tr>
                <td ><strong>Aprobado por: </strong>{{$nombre}} {{$apellido}}</td>
                <td style="width: 30%;"><strong>Fecha: </strong> </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
