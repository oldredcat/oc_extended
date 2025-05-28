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

/* app\admin\theme\default\common\header.twig */
class __TwigTemplate_b9164898635fab234197bbbb5f086972 extends Template
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
        yield "<!DOCTYPE html>
<html lang=\"";
        // line 2
        yield ($context["lang"] ?? null);
        yield "\">
<head>
  <meta charset=\"UTF-8\"/>
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
  <title>";
        // line 6
        yield ($context["title"] ?? null);
        yield "</title>
  ";
        // line 7
        if (($context["styles"] ?? null)) {
            // line 8
            yield "    ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["styles"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["style"]) {
                // line 9
                yield "      <link rel=\"";
                yield CoreExtension::getAttribute($this->env, $this->source, $context["style"], "rel", [], "any", false, false, false, 9);
                yield "\" media=\"";
                yield CoreExtension::getAttribute($this->env, $this->source, $context["style"], "media", [], "any", false, false, false, 9);
                yield "\" href=\"";
                yield CoreExtension::getAttribute($this->env, $this->source, $context["style"], "href", [], "any", false, false, false, 9);
                yield "\">
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['style'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 11
            yield "  ";
        }
        // line 12
        yield "  ";
        if (($context["scripts"] ?? null)) {
            // line 13
            yield "    ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["scripts"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["script"]) {
                // line 14
                yield "      <script src=\"";
                yield $context["script"];
                yield "\"></script>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['script'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 16
            yield "  ";
        }
        // line 17
        yield "</head>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "app\\admin\\theme\\default\\common\\header.twig";
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
        return array (  99 => 17,  96 => 16,  87 => 14,  82 => 13,  79 => 12,  76 => 11,  63 => 9,  58 => 8,  56 => 7,  52 => 6,  45 => 2,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "app\\admin\\theme\\default\\common\\header.twig", "D:\\ocExtended\\red\\app\\admin\\theme\\default\\common\\header.twig");
    }
}
