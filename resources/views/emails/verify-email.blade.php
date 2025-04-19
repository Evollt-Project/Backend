<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Подтверждение Email</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .email-header {
            background-color: #10b981;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .email-body {
            padding: 30px;
            color: #333;
            line-height: 1.6;
        }

        .email-button {
            display: inline-block;
            padding: 12px 24px;
            margin: 20px 0;
            background-color: #10b981;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .email-footer {
            font-size: 14px;
            color: #888;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <h1>👋 Добро пожаловать, {{ $user->first_name }}!</h1>
    </div>
    <div class="email-body">
        <p>Спасибо за регистрацию в <strong>{{ config('app.name') }}</strong>!</p>
        <p>Чтобы завершить процесс регистрации, пожалуйста, подтвердите ваш email, нажав на кнопку ниже:</p>

        <p style="text-align: center;">
            <a href="{{ $url }}" class="email-button">Подтвердить Email</a>
        </p>

        <p>Если вы не создавали аккаунт — просто проигнорируйте это письмо.</p>
        <p>Ссылка для подтверждения действительна в течение <strong>60 минут</strong>.</p>
    </div>
    <div class="email-footer">
        С уважением, <br>
        Команда {{ config('app.name') }}
    </div>
</div>
</body>
</html>
