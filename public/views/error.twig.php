{% extends "_template.twig.php" %}
{% block content %}
<div class="error-box">
    <h1>{{ statusCode }}</h1>
    <p>Houve um erro ao acessar esta página. Clique <a href="{{ BASE_URL }}">aqui</a> para voltar à página inicial.</p>
</div>
{% endblock %}