<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>welcome mail</title>
    <style>
        .head{
            width: 100%;
            height: 20%;
        }
        .body{
            width: 100%;
            height: auto;
            text-align: center;
        }
        .logo{
            text-align: center;
        }
        .footers{
            text-align: center;
            margin-top: 10%
        }
        .title{
            font-size:20px;
            font-weight: normal;
        }
        .footer{
            color: gray;
            font-size:15px;
        }
    </style>
</head>
<body >
    <div>
        <div class="head">
            <div class="logo">
                  <img  src="{{ $message->embed(public_path() . '/icons/logo_mfl.png') }}" />
            </div>
        </div>
        <div class="body">
            <h1 class="title">bienvenue sur MFLTravel, {{$user->name}}</h1>
            <h1>profitez amplement des fontionalites pour developper votre commerce</h1>
        </div>
        <div class="footers">
            <p class="footer">veuillez ne pas répondre à ce courrier électronique</p>
            <p class="footer">Projet REV</p>
        </div>
    </div>
</body>
</html>