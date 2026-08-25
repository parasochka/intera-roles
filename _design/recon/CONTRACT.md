# Theme build contract — read this before writing any file

This is the agreed shape of the `theme/` directory. Several agents build parts of it in
parallel, so **every signature below is fixed**. Call the API as written even when the file
that provides it does not exist yet — another agent is writing it right now.

Repo rules in `/home/user/intera-roles/CLAUDE.md` still bind: guard every file with
`defined( 'ABSPATH' ) || exit;`, prefix every global with `intera_` / `INTERA_`, escape on
output, no build step, and never duplicate a token value — always `var(--…)`.

## Resolved decisions (do not re-litigate; the recon specs list these as open)

| Question | Decision |
| --- | --- |
| Container width | `1160px` — the mockups win over `--layout-max` |
| Page gutter | `clamp(20px, 5vw, 24px)` — the mockups win over `--pad-page-x` |
| Section rhythm | the mockups' `clamp()` values verbatim, not `--pad-section-y` |
| Motion | site budget 150–260ms for `.itr-*` hovers; DS control transitions ship unchanged |
| Motion posture | hover states also apply to theme-added chrome (pagination, comment list, archive rows) |
| Fonts | stay on the CDN for now; localising is a separate later step |
| Icons | inlined SVG via `intera_icon()`; the Lucide runtime is never loaded |
| Unused DS components | the 9 the mockups never instantiate are **not** ported |
| Logo | inline tokenised SVG so tokens re-theme it; a WP custom logo overrides it when set |
| Accessibility gaps in the export | fixed, not reproduced |
| `!important` from the mockups | rewritten as normal CSS keyed by a class |
| Contact form | handled in-theme, no plugin |
| Card hover | `.itr-lift` and `.itr-hl` both ship as CSS classes; `interactive` adds `cursor: pointer` |
| Reading time | computed from word count at render, never a manual field |
| Docs sub-groups | hierarchical child terms of `docs_category`; ordinals from `menu_order` |
| Headings / TOC | generated in PHP from the rendered content, no JS, no editor field |

## Everything is editable in WordPress

The user's standing requirement: **the template owns the layout, WordPress owns the words.**
No marketing string, URL, image or list may be hardcoded in a template if an editor would
ever want to change it. Layout, spacing and colour stay in the template.

- repeated chrome (badge text, CTA label + target, footer blurb, contact details, copyright)
  → Customizer, via `intera_option()`
- navigation → nav menu locations, never a hardcoded `<a>` list
- editorial text → post/page content
- structured, repeated records (roles, plans, docs) → custom post types
- product screenshots → media library, via Customizer image controls

## File layout

```
theme/
  functions.php            loader only — constants + require inc/*
  inc/
    setup.php              supports, menus, editor styles, image sizes
    enqueue.php            EXISTS — do not rewrite; extend only if asked
    tokens.php             parses _ds/intera/tokens/*.css -> PHP arrays (editor palette)
    post-types.php         CPTs + taxonomies + term meta
    meta.php               post meta registration + meta boxes
    customizer.php         theme options
    template-tags.php      the output helpers below
    patterns.php           block patterns + pattern categories
    forms.php              contact-request handler
  template-parts/
    components/*.php       one DS component each
    partials/*.php         repeated page markup
  assets/css/intera.css    the only supplemental stylesheet
  assets/js/intera.js      progressive enhancement only; the site works without it
```

## PHP API — fixed signatures

Every helper **echoes** escaped output unless its name ends in `_get`.

### `inc/template-tags.php`

```php
intera_icon( string $name, array $args = [] ): void
// $args: size (int px, default 18), color (string, a var(--…) or CSS colour, default currentColor),
//        stroke (float, default 1.75), class (string), aria_label (string — when set the svg gets
//        role="img" + <title>, otherwise aria-hidden="true")

intera_option( string $key, mixed $default = '' ): mixed   // one Customizer/theme-mod read
intera_reading_time( int $post_id = 0 ): int               // whole minutes, min 1
intera_breadcrumbs( array $crumbs = [] ): void             // auto-derives when $crumbs is empty
intera_headings_get( string $html ): array                 // [ ['id'=>…,'text'=>…,'level'=>2], … ]
intera_content_with_heading_ids( string $html ): string    // same HTML, h2/h3 given stable ids
intera_term_icon_get( int $term_id ): string               // docs_category icon name, '' if unset
intera_term_tone_get( int $term_id ): string               // blue|teal|violet|amber, 'blue' default
```

### `template-parts/components/*.php`

Loaded **only** through `get_template_part()` with an args array — never `include`:

```php
get_template_part( 'template-parts/components/button', null, [
    'label'   => 'Get Early Access',   // required, plain text
    'href'    => '',                   // renders <a> when set, else <button>
    'variant' => 'primary',            // primary|secondary|ghost|danger|link|inverse|outlineInverse
    'size'    => 'md',                 // sm|md|lg
    'icon_left' => '', 'icon_right' => '',
    'attrs'   => [],                   // extra HTML attributes, escaped by the partial
] );
```

Each component partial starts by normalising `$args` against its own defaults:

```php
$args = wp_parse_args( $args ?? [], [ /* defaults */ ] );
```

Components to build (only these — the rest are unused by the mockups):
`card`, `card-header`, `badge`, `button`, `icon-button`, `logo`, `tag`, `metric-tile`,
`signal-badge`, `signal-chain`, `alert`, `checkbox`, `field`, `input`, `select`, `textarea`.

`icon` is **not** a component partial — it is `intera_icon()`, because it is called from
inside other partials and from templates hundreds of times.

### Component markup rules

- Reproduce the bundle's element tree and every style declaration exactly, keeping the
  `var(--token)` names verbatim.
- The bundle injects three stylesheets (`itr-btn-css`, `itr-iconbtn-css`, `itr-field-css`).
  Those live in `assets/css/intera.css`, not in PHP.
- Inline `style="…"` is correct and expected in this theme — it is the preserved handoff.
  Hover/focus/active states are never inline: they are classes in `intera.css`.

## CSS conventions in `assets/css/intera.css`

- `.itr-*` — the mockups' own class names, kept as-is so the handoff stays greppable.
- `.intera-*` — anything the theme adds that the mockups do not have.
- Breakpoints: `1040px` (nav switches to burger), `900px`, `760px`. No other breakpoint.
- No `!important` except where a WordPress core style must be beaten.

## Editable-content sources

```php
intera_option( 'header_badge' )        // 'Beta'
intera_option( 'header_cta_label' )    // 'Get Early Access'
intera_option( 'header_cta_url' )
intera_option( 'footer_blurb' )
intera_option( 'footer_cta_label' ) / ( 'footer_cta_url' )
intera_option( 'contact_email' )       // sb@by-sky.net
intera_option( 'contact_response' )    // 'Same working day, in most cases'
intera_option( 'contact_languages' )   // 'English'
intera_option( 'site_domain' )         // 'intera-roles.com'
intera_option( 'copyright' )           // '© 2026 INTERA. In beta — Early Adopter programme open.'
intera_option( 'shot_hero' ) / ( 'shot_signals' ) / ( 'shot_it' )  // attachment IDs
```

Nav menu locations: `primary`, `footer_product`, `footer_resources`, `footer_company`,
`footer_legal`. Inside a footer column a menu item carrying the CSS class `group-break`
starts a new group with a `1px solid rgba(255,255,255,.12)` divider above it.

Custom post types: `docs` (+ `docs_category`), `role`, `plan`.
