{% extends "_template.twig.php" %}
{% block content %}
<h1>Houve um erro ao acessar esta página. Clique <a href="{{ BASE_URL }}">aqui</a> para voltar à página inicial.</h1>
<p>Código do erro: {{ statusCode }}</p>
{% endblock %}