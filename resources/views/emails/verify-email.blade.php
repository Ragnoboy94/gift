<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение электронной почты</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-5">
                <div class="card-header">
                    <h3>Подтверждение электронной почты</h3>
                </div>
                <div class="card-body">
                    <p>Здравствуйте! Пожалуйста, нажмите на кнопку ниже, чтобы подтвердить свой адрес электронной почты.</p>
                    <a href="{{ $verificationUrl }}" class="btn btn-primary">Подтвердить адрес электронной почты</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
