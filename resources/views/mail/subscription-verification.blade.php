<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <p>Haz clic en el enlace para verificar tu correo electrónico.</p>
    <a href="{{route('newsletter-verify', $subscriber->verified_token)}}">Haz clic aquí</a>
</body>
</html>
