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

        table, tr{
            border: 1px solid black;
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
        <h3>
            Reporte de pago - {{$fecha_hoy}}
        </h3>
    </div>
    <div style="width: 80%; padding-left: 10%;">
        @foreach($participantes as $index2 => $part)
            <h3 style="color: #337ab7">#{{$index2 + 1}}</h3>
            <table class="tabla1" style="width:100%">
                <tr>
                    <td class="fila" style="width: 30%"><strong>Participante:</strong> </td>
                    <td class="fila">{{ $part->nombre }} {{ $part->apellido }}</td>
                </tr>
                <tr>
                    <td class="fila"><strong>DI:</strong></td>
                    <td>{{ $part->documento_identidad }}</td>
                </tr>
                <tr>
                    <td class="fila"><strong>Pagos realizados: </strong></td>
                    <td>{{$part->num_pagos}} </td>
                </tr>
                @foreach($part->pagos_part as $index => $pago)
                    <tr>
                        <td class="fila"><strong>Pago {{$index + 1}}:</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Monto:</strong> {{$pago->monto}}Bs.</td>
                        <td><strong>Banco</strong>: {{$pago->banco->nombre}}</td>
                        <td><strong>Ref:</strong> {{$pago->numero_pago}}</td>
                        <td><strong>Fecha:</strong> {{$pago->fechas->format('d-m-Y')}}</td>
                    </tr>
                @endforeach
                {{--<tr>--}}
                    {{--<td class="fila"><strong>Modalidad de pago:</strong></td>--}}
                    {{--<td>{{$modalidad->nombre}}</td>--}}
                {{--</tr>--}}
            </table>
        @endforeach

    </div>
</div>
</body>
</html>
