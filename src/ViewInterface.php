<?php

declare(strict_types=1);

namespace Karhu\View;

/**
 * Bridge interface for template engines.
 *
 * karhu core is view-agnostic. This package provides a common interface
 * so controllers can render templates without coupling to a specific engine.
 */
interface ViewInterface
{
    /**
     * Render a template with the given data.
     *
     * @param string               $template Template name/path (engine-specific)
     * @param array<string, mixed> $data     Variables passed to the template
     * @return string Rendered HTML
     */
    public function render(string $template, array $data = []): string;
}
