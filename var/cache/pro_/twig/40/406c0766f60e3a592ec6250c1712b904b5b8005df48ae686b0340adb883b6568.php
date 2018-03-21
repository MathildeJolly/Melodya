<?php

/* OCPlatformBundle:Advert:index.html.twig */
class __TwigTemplate_4324224fed57c784fd7eb2c988fc666e3e3b94ec83e874dce6b549260e5c6a3a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        <title>Bienvenue sur ma première page avec OpenClassrooms !</title>
    </head>
    <body>
        <h1> Hello ";
        // line 7
        echo twig_escape_filter($this->env, (isset($context["nom"]) ? $context["nom"] : null), "html", null, true);
        echo " !</h1>

        <p>
            Le Hello World est un grand classique en programmation.
            Il signifie énormément, car cela veut dire que vous avez
            réussi à exécuter le programme pour accomplir une tâche
            simple : afficher ce Hello World !
        </p>
    </body>
</html>";
    }

    public function getTemplateName()
    {
        return "OCPlatformBundle:Advert:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  27 => 7,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "OCPlatformBundle:Advert:index.html.twig", "C:\\wamp64\\www\\Symfony\\src\\OC\\PlatformBundle/Resources/views/Advert/index.html.twig");
    }
}
