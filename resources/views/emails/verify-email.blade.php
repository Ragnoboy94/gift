<!DOCTYPE html>
<html>
<head>
    <title>Подтверждение электронной почты</title>
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
    <h1>Подтверждение электронной почты</h1>
    <p>
        Здравствуйте! Пожалуйста, нажмите на кнопку ниже, чтобы подтвердить свой адрес электронной почты.
    </p>
    <p>
        Если вы не запрашивали подтверждения своего аккаунта, проигнорируйте это письмо, и ваш аккаунт останется без изменений.
    </p>
    <p>
        <a href="{{ $verificationUrl }}" class="button">Подтвердить адрес электронной почты</a>
    </p>
</div>
</body>
</html>
