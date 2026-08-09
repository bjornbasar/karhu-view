# karhu-view + karhu-skeleton — Code Tour

> A **reading-guide map** covering **two** small repos, and a **reference appendix** — outside the ten-tour sequence. Read [karhu](../karhu/CODE-TOUR.md) first. These are the smallest things in the workspace: a 3-file view bridge, and a starter app that is mostly *absence*. They're paired here because both answer the same question — **what does karhu deliberately not do?**
>
> **How to use it:** §1 the view bridge; §2 the skeleton; §3 what the emptiness teaches; §4 exercises. Fifteen minutes.

---

## 1. karhu-view — a bridge, not an engine

Three files, ~284 lines total, and the whole idea is in the [`ViewInterface`](src/ViewInterface.php) docblock: *"karhu core is view-agnostic. This package provides a common interface so controllers can render templates without coupling to a specific engine."*

- [src/ViewInterface.php](src/ViewInterface.php) (23 lines) — one method: `render(string $template, array $data): string`.
- [src/TwigAdapter.php](src/TwigAdapter.php) — [`render()`](src/TwigAdapter.php#L28) delegating to Twig.
- [src/PlatesAdapter.php](src/PlatesAdapter.php) — [`render()`](src/PlatesAdapter.php#L24) delegating to Plates.

That's the entire package. **Why it's worth five minutes anyway:** it is the cleanest example in the workspace of karhu's central move — *declare the shape, let the app choose the implementation*. You've now seen the same shape three times, and naming the repetition is the point:

| Interface | Declared by | Implemented by |
|---|---|---|
| `UserRepositoryInterface` | karhu core | mishka's `MishkaUserRepository` |
| `ErrorHandler` | karhu core (v0.1.4) | mishka's `MishkaErrorHandler` |
| `ViewInterface` | karhu-view | `TwigAdapter` / `PlatesAdapter` |
| `QueueInterface` | karhu-queue | `DatabaseQueue` |

**Gotcha to notice:** `render()` returns a **string**, not a `Response`. The adapter has no opinion about status codes or headers — the controller wraps it. That keeps the view layer usable for a fragment, an email body, or anything else that isn't a page. mishka's `MishkaErrorHandler` relies on exactly that when it renders a 404 body and attaches its own status.

---

## 2. karhu-skeleton — the starter, and what it *doesn't* contain

[karhu-skeleton](https://github.com/bjornbasar/karhu-skeleton) is a **project template**, not a program: `composer create-project` fodder. Its whole point is to be the smallest thing that boots.

Its entry point is nine lines — three of which matter:

```php
$app = new Karhu\App();
$app->router()->scanControllers(require __DIR__ . '/../config/controllers.php');
$app->run();
```

Compare that with [mishka's `bootstrap.php`](../mishka/CODE-TOUR.md), which is ~300 lines wiring 49 services. **Same framework, both ends of the range.** The skeleton ships `config/controllers.php` (the scan list), one `HomeController`, one `HelloCommand` to show the CLI path, and nothing else — no database, no view engine, no middleware.

**What the absence tells you:** every one of those omissions is a decision karhu pushed to the app. There is no default ORM to fight, no default template engine to rip out, no service-provider ceremony to learn before "hello world" renders. The cost is that a real app must supply all of it — which is precisely the question the karhu tour ends on and the mishka tour answers.

---

## 3. Reading the range

Put the three consumers side by side and the framework's shape becomes legible:

| | skeleton | istrbuddy | mishka |
|---|---|---|---|
| Wiring | 3 lines | modest | ~300 lines, 49 bindings |
| Persistence | none | karhu-db + SQLite | karhu-db + PostgreSQL repositories |
| View | none | minimal | karhu-view Twig + a CSRF extension |
| `ErrorHandler` bound | no | no | yes — branded 404s |

**The framework doesn't change across those columns.** That's the claim karhu makes about itself, and this is the cheapest way to check it: read the skeleton's `index.php`, then mishka's `bootstrap.php`, and note that `App::run()` is doing the same thing in both.

---

## 4. Active-recall exercises

1. **`render()` returns a string, not a Response.** Name two things that would break if it returned a `Response` instead — one of them is in mishka's error handler.
2. **Add a third adapter** (say, plain PHP includes). What must it implement, and what in karhu core would you need to touch? (The answer to the second half is the point.)
3. **The skeleton has no `.env` handling, no DB, no view.** For each, name the karhu-track project that supplies it and how.
4. **Why is `ViewInterface` in a separate package** rather than in karhu core, when `ErrorHandler` and `UserRepositoryInterface` both live *in* core? Argue from the zero-runtime-dependency rule.

---

*Tour covers karhu-view + karhu-skeleton @ `f665663`. A reference appendix — the ten-tour sequence ends at [koda-blast](../koda-blast/CODE-TOUR.md). Engine: [karhu](../karhu/CODE-TOUR.md). Siblings: [karhu-db](../karhu-db/CODE-TOUR.md), [karhu-queue](../karhu-queue/CODE-TOUR.md).*
