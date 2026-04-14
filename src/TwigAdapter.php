<?php

declare(strict_types=1);

namespace Karhu\View;

/**
 * Twig adapter — requires twig/twig as a dependency.
 *
 * Usage:
 *   composer require twig/twig
 *   $view = new TwigAdapter('/path/to/templates');
 *   $html = $view->render('page.html.twig', ['title' => 'Hello']);
 */
final class TwigAdapter implements ViewInterface
{
    private \Twig\Environment $twig;

    public function __construct(string $templateDir, bool $cache = false)
    {
        $loader = new \Twig\Loader\FilesystemLoader($templateDir);
        $this->twig = new \Twig\Environment($loader, [
            'cache' => $cache ? $templateDir . '/.cache' : false,
            'auto_reload' => true,
        ]);
    }

    public function render(string $template, array $data = []): string
    {
        return $this->twig->render($template, $data);
    }

    /** Access the underlying Twig\Environment for extensions etc. */
    public function twig(): \Twig\Environment
    {
        return $this->twig;
    }
}
