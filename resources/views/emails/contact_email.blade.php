<!DOCTYPE html>
<html>
<head>
    <title>Сообщение с сайта № {{$id}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .button {
            display: inline-block;
            color: #000000;
            background-color: rgba(0, 159, 46, 0.6);
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 3px;
        }
    </style>
</head>
<body>
<div class="container">

    <h1>Сообщение с сайта № {{$id}}</h1>
    <p>
        Email отправителя: <b>{{$email}}</b>
    </p>
    <p>
        Сообщение: <i>{{$content}}</i>
    </p>
</div>
</body>
</html>
