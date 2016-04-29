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
            /*border: 1px solid black;*/
            /*border-collapse: collapse;*/
        }
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
        <h3>N° 00{{$pago->id}}</h3>
        <h3>
            Recibo de pago
        </h3>
    </div>
    <div style="width: 80%; padding-left: 10%;">
        <table class="tabla1" style="width:100%">
            <tr>
                <td class="fila" style="width: 30%"><strong>Fecha:</strong> </td>
                <td class="fila">{{ $pago->created_at->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td class="fila"><strong>Recibido de:</strong></td>
                <td>{{$participante[0]->nombre}} {{$participante[0]->apellido}}</td>
            </tr>
            <tr>
                <td class="fila"><strong>La cantidad de:</strong></td>
                <td>{{$pago->monto}} Bs</td>
            </tr>
            <tr>
                <td class="fila"><strong>Por concepto de:</strong></td>
                <td>Pago de la actividad {{$curso->nombre}}</td>
            </tr>
            <tr>
                <td class="fila"><strong>Modalidad de pago:</strong></td>
                <td>{{$modalidad->nombre}}</td>
            </tr>
        </table>

    </div>
</div>
</body>
</html>
