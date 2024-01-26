<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        <p>Detalle del Vehiculo</p>
        <ul>
            <li><strong>Placa:</strong> {{ $data['placa'] }}</li>
            <li><strong>Peso:</strong> {{ $data['peso'] }}</li>
            <li><strong>Paquete:</strong> {{ $data['paquete'] }}</li>
            <li><strong>Volumen:</strong> {{ $data['volumen'] }}</li>
            <li><strong>Compania:</strong> {{ $data['compania'] }}</li>

            <img src="{{ $message->embed(storage_path('app/imagen/' . $data['imagenVehiculo'])) }}" width="260px" height="180px" style="margin-top: 10px">

        </ul>
    </body>
</html>
