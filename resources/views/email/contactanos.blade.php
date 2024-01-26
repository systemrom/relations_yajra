<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Content Mail</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body{
            width: 100%;
            height: 100vh;
            display: grid;
            place-content: center;
            place-items: center;
        }
        .containner{
            width: 280px;
            height: 300px;
            border: 2px dotted black;
        }
        .containner .title, .body, .footer{
            border-bottom: 1px dashed black;
        }
        .containner .title .parraf{
            color: #060665;
            font-size: 15px;
        }
        .containner .body .parraf{
            color: #060665;
            font-size: 15px;
        }
        .containner .footer .parraf{
            color: #060665;
            font-size: 15px;
        }
    </style>
</head>
<body>
    <div class="containner">
        <div class="title">
            <p class="parraf">Correo electrónico</p>
        </div>
        <div class="body">
            <p class="parraf">First mail of Laravel</p>
            <label>sajfhsahfbn@gmail.com</label>
            <label>asfyashff@outlook.es</label>
        </div>
        <div class="footer">
            <p class="parraf">That's are list of mails for contact......</p>
        </div>
    </div>
</body>
</html>




