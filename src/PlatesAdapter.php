<?php

declare(strict_types=1);

namespace Karhu\View;

/**
 * Plates adapter — requires league/plates as a dependency.
 *
 * Usage:
 *   composer require league/plates
 *   $view = new PlatesAdapter('/path/to/templates');
 *   $html = $view->render('page', ['title' => 'Hello']);
 */
final class PlatesAdapter implements ViewInterface
{
    private \League\Plates\Engine $engine;

    public function __construct(string $templateDir)
    {
        $this->engine = new \League\Plates\Engine($templateDir);
    }

    public function render(string $template, array $data = []): string
    {
        return $this->engine->render($template, $data);
    }

    /** Access the underlying Plates\Engine for extensions etc. */
    public function engine(): \League\Plates\Engine
    {
        return $this->engine;
    }
}
