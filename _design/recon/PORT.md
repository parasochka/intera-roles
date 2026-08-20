# Port contract — turning the mockups into templates

`CONTRACT.md` fixed the theme's shape and API. This file adds what the foundation
build actually settled, and it binds every template and partial written from here on.
Read both before writing anything.

## The one rule that breaks things silently

The mockups' hover states were written with `!important` in a `<helmet>` block. In the
theme they are ordinary CSS in `assets/css/intera.css`, which means **an inline style
now outranks them**. Emit `background`, `border`, `border-color`, `box-shadow` or
`transition` inline on an element that also carries a hover class, and that element is
dead on hover — silently, with nothing to see in the markup.

So on any element carrying `.itr-card`, `.itr-lift`, `.itr-hl`, `.itr-row`, `.itr-tile`,
`.itr-panel`, `.itr-hl-panel` or `.itr-frame`, those five properties are **never inline**.
The per-instance values arrive as custom properties the class reads:

| Property | Custom property | Default in the CSS |
| --- | --- | --- |
| resting border colour | `--itr-edge` | `var(--border-card)`, `var(--border-subtle)` on `.itr-row` |
| resting shadow | `--itr-shadow` | `var(--shadow-xs)`, `none` on `.itr-row` |
| resting background | `--itr-bg` | `var(--white)` on `.itr-row` and `.itr-tile` |

Everything else — padding, gap, font, colour, grid tracks, `border-top` accent stripes —
stays inline exactly as the mockup writes it. That is the handoff, preserved.

## Layout hooks that need a custom property instead of an inline value

| Class | Pass as | Do not write inline |
| --- | --- | --- |
| `.itr-1col` | `--itr-cols: <desktop tracks>` | `display`, `grid-template-columns` (gap stays inline) |
| `.itr-stagger` | `--itr-indent: 26px` / `52px` | `margin-left` |
| `.itr-shot` | `--itr-shot-h: 420px` / `440px` / `360px` | `height`, `overflow`; the `<img>` gets **no** inline style at all |
| `.itr-scroll-x` | — | `overflow`; add `tabindex="0" role="region" aria-label="…"` |
| `.itr-float` | `--itr-edge: var(--border-default)` | `position`, `right`, `bottom`, `width`, `box-shadow` (the wrapper keeps `position: relative`) |
| `.itr-cols-4` | — | `display`, `grid-template-columns`, `gap` |

## Links: drop the inline colour, add the class

`a:not(.itr-btn)` sets the base link colour, so a mockup's inline `color` on a link would
beat the `:hover` rule. Remove it and use the class, which carries both states:

`a.itr-crumb` · `a.itr-nav-link` · `a.itr-foot-link` / `--dim` · `a.itr-link-blue` ·
`a.itr-rail-row` · `a.itr-doc-title` · `a.itr-doc-row` · `a.itr-term-link` ·
`a.itr-link-strong`

Background-only hovers keep their inline padding/radius and just add the class:
`.itr-jump`, `.itr-list-row`, `.itr-lift-tint`. Card hovers: `.itr-feature-card`,
`.itr-post-card`. FAQ: `.itr-faq-summary` on `<summary>`, inline `color` removed.

## Content hooks

`.intera-prose` wraps `the_content()` (override its width with `--itr-prose-max`) ·
`.intera-lede` · `.intera-meta-rule` · `.intera-num` on numeric cells ·
`.intera-pagination` around `the_posts_pagination()` · `.intera-comments`.

## What header.php and footer.php already own

`<main id="intera-main" class="intera-main" tabindex="-1">` is opened by `header.php` and
closed by `footer.php`, and `header.php` emits the 76px `.intera-masthead-spacer`.
**No template opens `<main>`, closes it, or repeats the spacer.** Every template is
`get_header();` … sections … `get_footer();`.

## Calling components

```php
get_template_part( 'template-parts/components/card', null, array(
    'padding' => 'loose',
    'class'   => 'itr-lift',
    'content' => $markup,      // trusted, template-composed HTML — echoed unfiltered
) );
```

- `content` (HTML, you escape it) vs `text` (plain, the partial escapes it). `card`,
  `card-header` (`action`), `field`, `alert`, `metric-tile` (`sparkline`) take `content`;
  `badge`, `tag`, `alert` take `text`.
- Every partial also accepts `class`, `style` (appended last) and `attrs` (associative,
  escaped for you; `true` renders a bare attribute).
- Props are snake_case: `icon_left`, `icon_right`, `accent_line`, `show_icon`,
  `remove_label`, `dismiss_label`.
- A disabled Button renders `<button disabled>` and ignores `href`.
- `metric-tile` draws its own surface inline — do **not** add `.itr-lift` to it.

## Data: where every value comes from

```php
intera_option( $key )                 // header_badge, header_cta_label/url, footer_blurb,
                                      // footer_cta_label/url, contact_email, contact_response,
                                      // contact_languages, site_domain, copyright,
                                      // shot_hero, shot_signals, shot_it (attachment IDs),
                                      // contact_request_subject, contact_consent_label,
                                      // contact_success_title/body/answer,
                                      // contact_industries, contact_interests
intera_page_url( $key )               // home | blog | docs | product | pricing | faq |
                                      // contacts | contact-request | legal
                                      // resolves by the page template assigned in the editor
intera_icon( $name, $args )           // inlined Lucide; intera_icon_get() returns the string
intera_reading_time( $post_id )
intera_breadcrumbs( array $crumbs )   // crumb = [ 'label' => …, 'url' => … ]; empty url = current
intera_headings_get( $html )          // [ [ id, text, level ], … ]
intera_content_with_heading_ids( $html )
intera_term_icon_get( $term_id ) / intera_term_tone_get( $term_id )
```

Post meta keys: `_intera_role_icon`, `_intera_role_summary`, `_intera_role_tags`
(comma-separated — `explode`, trim, skip empties), `_intera_plan_price`,
`_intera_plan_period`, `_intera_plan_featured` (real boolean — cast, never compare to
`'1'`), `_intera_plan_cta_label`, `_intera_plan_cta_url`, `_intera_doc_version`.
Term meta: `_intera_term_icon`, `_intera_term_tone`.

`role` and `plan` are `public => false`: render them inline, never link to a single.

Form helpers, all from `inc/forms.php`: `intera_form_errors_get()`, `intera_form_old_get()`,
`intera_form_succeeded()`, `intera_form_hidden_fields()`, `intera_form_action_url_get()`,
`intera_form_reference_get()`, `intera_form_industries_get()`, `intera_form_interests_get()`.
The form wrapper must carry `id="intera-request-form"` — every redirect targets that fragment.

## A template that prints a table of contents

```php
$intera_html = intera_content_with_heading_ids( apply_filters( 'the_content', get_the_content() ) );
$intera_toc  = intera_headings_get( $intera_html );
// …render the rail from $intera_toc, then echo $intera_html — not the_content().
```

## Progressive enhancement

`data-intera-copy` (copy link) and `data-intera-print` (print the page) are the only JS
hooks. Render those controls with a `hidden` attribute — `intera.js` removes it — so a
visitor without JavaScript is never shown a control that cannot work.

## Non-negotiables, restated

Guard with `defined( 'ABSPATH' ) || exit;`. Escape on output. Prefix globals `intera_`.
No hardcoded `*.dc.html` link may survive. No raw hex — `var(--token)` only. No marketing
string that an editor would want to change may sit in PHP: it comes from a menu,
`intera_option()`, post content or a CPT. Run `php -l` on everything you write.
