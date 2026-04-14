# karhu-view

Template engine bridge for the [karhu](https://github.com/bjornbasar/karhu) PHP microframework.

Provides a `ViewInterface` so controllers can render templates without coupling to a specific engine. Ships with Twig and Plates adapters.

## Install

```bash
composer require bjornbasar/karhu-view

# Then install your preferred engine:
composer require twig/twig        # for Twig
# or
composer require league/plates    # for Plates
```

## Usage

```php
use Karhu\View\TwigAdapter;

$view = new TwigAdapter(__DIR__ . '/templates');
$html = $view->render('home.html.twig', ['name' => 'karhu']);

return (new Response())->withHeader('Content-Type', 'text/html')->withBody($html);
```

## Custom engines

Implement `ViewInterface`:

```php
use Karhu\View\ViewInterface;

final class BladeAdapter implements ViewInterface {
    public function render(string $template, array $data = []): string { /* ... */ }
}
```
