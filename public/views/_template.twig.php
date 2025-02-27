<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Sticker Creator{% if title %} - {{title}} {% endif %}</title>
    <link rel="stylesheet" href="{{ BASE_URL }}/assets/css/global.css">
    {% block customCSS %} {% endblock %}
</head>
<body>
<div class="content">
    {% block content %}
    {% endblock %}
    {% block customJS %} {% endblock %}
</div>
</body>
</html>