{% extends "_template.twig.php" %}
{% block content %}
<p>Página inicial</p>
{% endblock %}

{% block customJS %}
<script src="{{ BASE_URL }}/assets/js/fabric.js"></script>
<script src="{{ BASE_URL }}/assets/js/fontfaceobserver.js"></script>
{% endblock %}