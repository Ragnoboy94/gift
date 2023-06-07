<!DOCTYPE html>
<html>
<head>
    <title>Ваш заказ собран и готов к доставке.</title>
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
            color: #ffffff;
            background-color: rgb(118, 192, 196);
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 3px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Заказ собран и готов к доставке</h1>
    <p>
        Здравствуйте! Ознакомиться с деталями заказа можно на сайте или перейдя по ссылке ниже.
    </p>

    <p>
        <a href="{{ $orderDataUrl }}" class="button">Посмотреть детали заказа</a>
    </p>
    <p>
        С уважением <a href="{{$homeUrl}}">Сервис подарков</a>.
    </p>
</div>
</body>
</html>
