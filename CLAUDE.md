# CLAUDE.md — Intera Roles theme (classic PHP)

Guidance for AI agents (and humans) working in this repo. Read this first.

## What this is

A custom WordPress **classic PHP theme** for
[intera-roles.com](https://intera-roles.com), built from a Claude Design
project. The theme lives in **`theme/`** — WP Pusher installs from that
subdirectory, so docs, design sources and CI at the repo root are never deployed
to WordPress.

The **design system is the source of truth for styling.** Tokens live in
`theme/_ds/intera/tokens/*.css` and are enqueued **as-is** — they are the single
place colors, type, spacing, radii and elevation are defined. There is **no
`theme.json` token bridge**: two sources of tokens drift apart. Edit a token and
the whole site re-themes.

```
Claude Design  ─►  theme/ (_ds/intera + PHP)  ─►  GitHub  ─►  WP Pusher  ─►  WordPress
 export screens     this repo                     (main)     (subdir: theme)  live site
```

## File map (paths relative to `theme/`)

| Path | Purpose |
| --- | --- |
| `style.css` | WP theme header + a pointer note. No real CSS here. |
| `functions.php` | Thin loader: `INTERA_*` constants + `require inc/*`. |
| `inc/setup.php` | Theme supports, editor styles, image sizes, content width (1160), the five nav locations. |
| `inc/enqueue.php` | Parses the `_ds/intera/styles.css` `@import` manifest and inlines every sheet + `assets/css/intera.css` + `style.css` into `<head>`; enqueues `assets/js/intera.js` deferred. DS files stay byte-identical on disk. |
| `inc/tokens.php` | Parses `_ds/intera/tokens/*.css` into PHP arrays. This is how the block editor gets the brand palette **without a second copy of any value**. |
| `inc/post-types.php` | CPTs `docs`, `role`, `plan`; taxonomy `docs_category` + its term meta (icon, tone). |
| `inc/meta.php` | Post meta registration and the meta boxes that make it editable. |
| `inc/customizer.php` | Theme options + `intera_option()`. No colour or font setting lives here — those come from the tokens. |
| `inc/template-tags.php` | `intera_icon()` (inlined Lucide), reading time, breadcrumbs, heading ids and TOC data. |
| `inc/forms.php` | The contact-request handler: nonce, honeypot, validation, storage, mail. |
| `header.php` / `footer.php` | Shared chrome. The mobile menu is a CSS-only checkbox toggle; JS only mirrors it into ARIA. |
| `index.php` · `page.php` · `single.php` · `404.php` | Templates. |
| `template-parts/components/*.php` | One design-system component each, called via `get_template_part()` with an args array. |
| `template-parts/partials/*.php` | Repeated page markup — one source per block. |
| `page-templates/` | Named page templates (`Template Name:` header). |
| `_ds/intera/styles.css` | The **only** list of design-system sheets. Add a token file? Add it here. |
| `_ds/intera/tokens/*.css` | **Design-system source of truth**, byte-identical to the Claude Design export. Do not fork these values elsewhere. |
| `assets/css/intera.css` | Supplemental CSS only: page canvas, prose, real `:hover`/`:focus`, responsive stacking, reduced-motion. References DS tokens. |
| `assets/js/intera.js` | Optional progressive JS. The site must work without it. |
| `assets/img/*` | Theme chrome images (logo lock-ups). Editorial images live in the media library. |

Repo root (not deployed): `.github/workflows/validate.yml`, `_design/` (the
Claude Design export), `README.md`, `SETUP.md`, this file.

## How the templates are built

Each template is cut from a design-handoff **screen**. The rule: **keep the
screen's HTML and inline styles verbatim; swap only the dynamic slots** for
WordPress calls (`the_title`, `the_content`, `the_post_thumbnail`, `WP_Query`,
`get_category_link`, …). This is why templates carry inline `style="…"` with
`var(--token)` — that is the handoff, preserved 1:1.

- Preview-only attributes in the export (e.g. `style-hover="…"`) are **not real
  CSS** — reimplement every hover/focus/active state in `assets/css/intera.css`,
  keyed by a class.
- Repeated markup lives once in `template-parts/` so every grid, rail and
  "related" block stays identical.

## How to make design changes (order of preference)

1. **A token value** → `theme/_ds/intera/tokens/*.css`. One edit re-themes
   everywhere. This is the single source of truth.
2. **A shared style** (prose, hover, responsive) → `theme/assets/css/intera.css`,
   always via `var(--…)` — never raw hex/px that duplicates a token.
3. **Layout/structure** → the relevant `theme/*.php`, matching the handoff
   screen.

## Non-negotiable rules

- **Tokens are the source of truth.** No second copy of a color or spacing value
  in PHP or `intera.css` — reference `var(--…)`.
- **Escape everything on output.** `esc_html`, `esc_attr`, `esc_url`,
  `wp_kses_post`. Never echo raw user or meta content.
- **Guard every file** with `defined( 'ABSPATH' ) || exit;`.
- **Prefix every global** with `intera_` / `INTERA_`.
- **No build step.** The theme is shipped as source: WP Pusher copies `theme/`
  to the server as-is. Anything requiring compilation must be committed compiled.
- **Keep CI green.** `main` is what WP Pusher deploys; a broken push is a broken
  live site.
- **Content is not code.** Posts, pages, menus and media live in WordPress, not
  in this repo.
- **The template owns the layout, WordPress owns the words.** No marketing
  string, URL, image or list may be hardcoded in a template if an editor would
  ever want to change it: navigation comes from a menu location, repeated chrome
  from `intera_option()`, editorial text from post content, and repeated records
  (`role`, `plan`, `docs`) from their custom post type. Layout, spacing and
  colour stay in the template.

## Workflow

- Develop on a feature branch, keep `php -l` clean, then merge to `main`.
- Bump `Version:` in `theme/style.css` **and** `INTERA_VERSION` in
  `functions.php` together — the version busts asset caches.
- After merging: WP Pusher → Themes → **Update**.
