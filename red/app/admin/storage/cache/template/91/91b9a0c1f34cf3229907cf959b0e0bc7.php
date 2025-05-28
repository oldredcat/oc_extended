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

/* app\admin\theme\default\common\footer.twig */
class __TwigTemplate_731164ea08db8d99b9d53a7d28a79a06 extends Template
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
        if (($context["styles"] ?? null)) {
            // line 2
            yield "    ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["styles"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["style"]) {
                // line 3
                yield "        <link rel=\"";
                yield CoreExtension::getAttribute($this->env, $this->source, $context["style"], "rel", [], "any", false, false, false, 3);
                yield "\" media=\"";
                yield CoreExtension::getAttribute($this->env, $this->source, $context["style"], "media", [], "any", false, false, false, 3);
                yield "\" href=\"";
                yield CoreExtension::getAttribute($this->env, $this->source, $context["style"], "href", [], "any", false, false, false, 3);
                yield "\">
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['style'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
        // line 6
        yield "  ";
        if (($context["scripts"] ?? null)) {
            // line 7
            yield "      ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["scripts"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["script"]) {
                // line 8
                yield "          <script src=\"";
                yield $context["script"];
                yield "\"></script>
      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['script'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 10
            yield "  ";
        }
        // line 11
        yield "</html>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "app\\admin\\theme\\default\\common\\footer.twig";
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
        return array (  83 => 11,  80 => 10,  71 => 8,  66 => 7,  63 => 6,  49 => 3,  44 => 2,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "app\\admin\\theme\\default\\common\\footer.twig", "D:\\ocExtended\\red\\app\\admin\\theme\\default\\common\\footer.twig");
    }
}
