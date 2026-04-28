# karhu-view — Project Documentation

**Version:** 0.1.0 | **License:** MIT | **PHP:** >=8.3

Template engine bridge for the [karhu](https://github.com/bjornbasar/karhu) PHP microframework. Provides a stable `ViewInterface` so controllers can render templates without coupling to a specific engine. Ships with Twig and Plates adapters.

---

## Tech Stack

| Component | Technology |
|-----------|-----------|
| Language | PHP 8.3+ |
| Adapters shipped | Twig, Plates |
| Autoloading | Composer PSR-4 (`Karhu\View\`) |

Zero runtime dependencies. Engines are *suggested* — install only what you use.

---

## Directory Structure

```
karhu-view/
├── src/
│   ├── ViewInterface.php   # render(string $template, array $data = []): string
│   ├── TwigAdapter.php     # Wraps twig/twig — file-system loader + cache support
│   └── PlatesAdapter.php   # Wraps league/plates — folder-based template lookup
└── composer.json
```

---

## API Surface

### `Karhu\View\ViewInterface`

```php
interface ViewInterface {
    public function render(string $template, array $data = []): string;
}
```

The contract is intentionally tiny — one method, returns a string. Controllers pass the string into `Response::html(...)` themselves; karhu-view never builds a Response.

### `Karhu\View\TwigAdapter`

```php
$view = new TwigAdapter(__DIR__ . '/templates', cacheDir: __DIR__ . '/var/cache');
$html = $view->render('home.html.twig', ['name' => 'karhu']);
```

Constructor signature: `(string $templatesDir, ?string $cacheDir = null, bool $strictVariables = false)`. Wraps a `Twig\Environment` with a `FilesystemLoader` rooted at `$templatesDir`.

### `Karhu\View\PlatesAdapter`

```php
$view = new PlatesAdapter(__DIR__ . '/templates');
$html = $view->render('home', ['name' => 'karhu']);   // home.php
```

Plates uses `.php` templates by default — drop the extension when calling render.

---

## Container wiring

In a karhu app, register your chosen adapter against the interface so controllers depend on `ViewInterface`, not the engine:

```php
$app->container()->set(
    Karhu\View\ViewInterface::class,
    new Karhu\View\TwigAdapter(__DIR__ . '/templates'),
);
```

A controller then just type-hints the interface:

```php
final class HomeController {
    public function __construct(private ViewInterface $view) {}

    #[Route('GET', '/')]
    public function index(): Response {
        return Response::html($this->view->render('home.html.twig', []));
    }
}
```

Swapping engines is a one-line container change — no controller edits.

---

## Custom engines

Implement `ViewInterface`:

```php
use Karhu\View\ViewInterface;

final class BladeAdapter implements ViewInterface {
    public function render(string $template, array $data = []): string {
        // delegate to your Blade compiler …
    }
}
```

Helpful for engines karhu-view doesn't ship — Blade, Mustache, Latte, in-house template strings, etc.

---

## Key Design Decisions

- **Zero runtime deps** — both Twig and Plates are *suggested*; install only what you use.
- **One-method interface** — `render()` returns a string; the framework / controller decides what to do with it (HTML response, email body, file write, etc.). No coupling to HTTP.
- **No global state** — adapters are constructed per-application; no singletons or static template registries.
- **Engines stay engines** — the adapter is a thin pass-through. We don't try to abstract Twig features behind a lowest-common-denominator API; if you need Twig-specific filters, get the underlying Environment via composition.

---

## Related Repos

| Repo | Purpose |
|------|---------|
| [karhu](https://github.com/bjornbasar/karhu) | Parent microframework |
| [istrbuddy](https://github.com/bjornbasar/istrbuddy) | Reference app — *deliberately* does not use karhu-view, demonstrating that karhu can render HTML without it |
