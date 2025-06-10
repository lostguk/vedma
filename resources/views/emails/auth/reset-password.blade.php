<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Восстановление пароля</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f7f7; margin: 0; padding: 20px;">
<div style="max-width: 540px; margin: 0 auto; background-color: #ffffff; padding: 24px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
    <h2 style="text-align: center; color: #181e39;">Восстановление пароля</h2>

    <p style="font-size: 16px; color: #333;">
        Чтобы сбросить пароль, нажмите на кнопку ниже:
    </p>

    <div style="text-align: center; margin: 24px 0;">
        <a href="{{ $url }}" style="background-color: #181e39; color: #ffffff; padding: 12px 24px; border-radius: 4px; text-decoration: none; display: inline-block;">
            Сбросить пароль
        </a>
    </div>

    <p style="font-size: 14px; color: #666;">
        Или скопируйте и вставьте эту ссылку в адресную строку браузера:
    </p>
    <p style="font-size: 14px; color: #1a1a1a; word-break: break-word;">
        <a href="{{ $url }}">{{ $url }}</a>
    </p>

    <p style="font-size: 13px; color: #888; text-align: center; margin-top: 32px;">
        Ссылка действительна в течение 60 минут.
    </p>
</div>
</body>
</html>
