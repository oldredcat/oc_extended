<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* app\admin\theme\default\error\not_found.twig */
class __TwigTemplate_8908344bbc69375995dba453e18b4a53 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield ($context["header"] ?? null);
        yield "
<body class=\"bg-light\">
<div class=\"container-fluid\">
    <div class=\"row justify-content-md-center mt-5\">
        <div class=\"col-md-6\">
            <div class=\"bg-white mt-5 p-5 m-auto text-center r-5\">
                <div class=\"text-black-50 pb-3\">
                    <span class=\"fa-5x\">404</span>
                </div>
                <h5>";
        // line 10
        yield ($context["not_found_caption"] ?? null);
        yield "</h5>
                <p class=\"text-black-50\">";
        // line 11
        yield ($context["not_found_text"] ?? null);
        yield "</p>
                <p>";
        // line 12
        yield ($context["not_found_link"] ?? null);
        yield "</p>
            </div>
        </div>
    </div>
</div>
</body>
";
        // line 18
        yield ($context["footer"] ?? null);
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "app\\admin\\theme\\default\\error\\not_found.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  71 => 18,  62 => 12,  58 => 11,  54 => 10,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "app\\admin\\theme\\default\\error\\not_found.twig", "D:\\ocExtended\\red\\app\\admin\\theme\\default\\error\\not_found.twig");
    }
}
