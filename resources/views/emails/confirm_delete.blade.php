<!DOCTYPE html>
<html>
<head>
    <title>Подтверждение удаления учетной записи</title>
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
            color: #FFF;
            background-color: #FF0000;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 3px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Подтверждение удаления учетной записи</h1>
    <p>
        Здравствуйте! Мы получили запрос на удаление вашей учетной записи. Если вы подали этот запрос, пожалуйста, нажмите на кнопку ниже, чтобы подтвердить его.
    </p>
    <p>
        Если вы не запрашивали удаление своего аккаунта, проигнорируйте это письмо, и ваш аккаунт останется без изменений.
    </p>
    <p>
        <a href="{{ $confirmationLink }}" class="button">Подтвердить удаление учетной записи</a>
    </p>
</div>
</body>
</html>
