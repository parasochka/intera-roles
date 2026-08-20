# Shared CSS recon — what becomes `theme/assets/css/intera.css`

Source: `/home/user/intera-roles/_design/` (16 `.dc.html` files carry a `<helmet>`: the 13 page
mockups, `site-nav`, `site-footer`, plus the `00-templates` index — the brief says "15 mockups";
the 16th is the index screen, which has no theme file and is reported separately where it differs).

Everything below is quoted verbatim from the export. Nothing is estimated.

---

## 0. The one principle this whole document turns on

The mockups put layout and colour in **inline `style="…"`**. Inline styles beat *any* selector.
So a class rule can only win in three ways: `!important`, moving the property out of the inline
style into the class, or routing the value through a custom property.

The export already uses `!important` twice, for two *different* reasons:

| Where | Why | Theme decision |
| --- | --- | --- |
| `<helmet><style>` — on `.itr-lift` / `.itr-frame` / `.itr-hl` / `.itr-hl-panel` transition + hover | fights **inline** `box-shadow: var(--shadow-xs)` and `border: 1px solid var(--border-card)` on the same element | **keep** — the handoff inline styles are kept verbatim per repo `CLAUDE.md`, so the `!important` is load-bearing |
| `mobile.css` — every rule | fights **inline** `grid-template-columns`, `height`, `position`, `margin-left`, `overflow` | **remove** — move exactly those properties out of the inline style (§2) |

Working rule for the theme:

> **Any property that a hover state or a breakpoint changes moves from the inline style into a
> class. Every other inline declaration stays byte-identical to the handoff.**

The `!important`s in the helmet block are the documented exception, because their conflicting
inline declarations (`box-shadow`, `border`) are *not* the property being animated (`transform`)
and are needed at rest.

---

## 1. The `<helmet><style>` blocks — diffed across all 16 files

### 1a. The `<link>` head is byte-identical in all 16 files

```html
<link rel="stylesheet" href="_ds/intera/tokens/fonts.css">
<link rel="stylesheet" href="_ds/intera/tokens/colors.css">
<link rel="stylesheet" href="_ds/intera/tokens/typography.css">
<link rel="stylesheet" href="_ds/intera/tokens/spacing.css">
<link rel="stylesheet" href="_ds/intera/tokens/radius.css">
<link rel="stylesheet" href="_ds/intera/tokens/elevation.css">
<link rel="stylesheet" href="_ds/intera/tokens/motion.css">
<link rel="stylesheet" href="_ds/intera/styles.css">
<link rel="stylesheet" href="mobile.css">
<script src="https://unpkg.com/lucide@0.469.0/dist/umd/lucide.js"></script>
<script src="_ds/intera/_ds_bundle.js"></script>
```

Note the tokens are linked **twice** — individually, and again through `styles.css`, whose
`@import` list is the same eight files (including `tokens/base.css`, which the individual links
omit). `theme/inc/enqueue.php` already resolves this correctly: it parses the `styles.css`
manifest once. Nothing to port here.

### 1b. Rules common to **all 16** files — verbatim, byte-identical

These go into `intera.css` unchanged. Line order below is the order in the export.

```css
a:not(.itr-btn) { color: var(--text-link); text-decoration: none; border-bottom: 0; transition: color 140ms cubic-bezier(.2,.6,.25,1); }
a:not(.itr-btn):hover { color: var(--text-link-hover); }
a.itr-btn:hover { border-bottom-color: transparent; }
a.itr-btn--primary:hover, a.itr-btn--danger:hover { color: var(--text-on-accent); }
a.itr-btn--secondary:hover { color: var(--ink-800); border-bottom-color: var(--border-strong); }
a.itr-btn--ghost:hover, a.itr-btn--inverse:hover { color: var(--ink-900); }
a.itr-btn--outlineInverse:hover { color: var(--white); border-bottom-color: rgba(255,255,255,.6); }

@keyframes itr-live { 0%, 100% { opacity: 1; } 50% { opacity: .28; } }
@keyframes itr-halo { 0% { transform: scale(1); opacity: .5; } 70%, 100% { transform: scale(2.6); opacity: 0; } }
.itr-live-dot { animation: itr-live 1.9s cubic-bezier(.2,.6,.25,1) infinite; }
.itr-live-halo { animation: itr-halo 1.9s cubic-bezier(.2,.6,.25,1) infinite; }

.itr-lift { transition: transform 200ms cubic-bezier(.2,.6,.25,1), box-shadow 200ms cubic-bezier(.2,.6,.25,1), border-color 160ms cubic-bezier(.2,.6,.25,1), background-color 160ms cubic-bezier(.2,.6,.25,1) !important; }
.itr-lift:hover { transform: translateY(-5px); box-shadow: 0 18px 34px -18px rgba(14,26,43,.26), 0 2px 6px -2px rgba(14,26,43,.10) !important; border-right-color: var(--border-strong) !important; border-bottom-color: var(--border-strong) !important; border-left-color: var(--border-strong) !important; }

.itr-row { transition: transform 180ms cubic-bezier(.2,.6,.25,1), background-color 160ms, border-color 160ms, box-shadow 180ms; }
.itr-row:hover { transform: translateX(8px); background: var(--white); border-color: var(--blue-200); box-shadow: 0 10px 22px -14px rgba(14,26,43,.28); }

.itr-tile { transition: transform 170ms cubic-bezier(.2,.6,.25,1), background-color 150ms, border-color 150ms, box-shadow 170ms; }
.itr-tile:hover { transform: translateY(-4px); background: var(--blue-50); border-color: var(--blue-200); box-shadow: 0 12px 24px -16px rgba(26,79,214,.35); }

.itr-panel { transition: transform 200ms cubic-bezier(.2,.6,.25,1), background-color 180ms, border-color 180ms; }
.itr-panel:hover { transform: translateY(-4px); background: rgba(255,255,255,.085); border-color: rgba(255,255,255,.28); }

.itr-frame { transition: transform 260ms cubic-bezier(.2,.6,.25,1), box-shadow 260ms cubic-bezier(.2,.6,.25,1); }
.itr-frame:hover { transform: translateY(-6px); box-shadow: 0 34px 64px -30px rgba(14,26,43,.42), 0 4px 10px -4px rgba(14,26,43,.14) !important; }

.itr-hl { transition: border-color 160ms cubic-bezier(.2,.6,.25,1), box-shadow 160ms cubic-bezier(.2,.6,.25,1), background-color 160ms cubic-bezier(.2,.6,.25,1) !important; }
.itr-hl:hover { box-shadow: 0 10px 24px -18px rgba(14,26,43,.30) !important; border-right-color: var(--border-strong) !important; border-bottom-color: var(--border-strong) !important; border-left-color: var(--border-strong) !important; }

.itr-hl-panel { transition: border-color 180ms cubic-bezier(.2,.6,.25,1), background-color 180ms cubic-bezier(.2,.6,.25,1) !important; }
.itr-hl-panel:hover { background: rgba(255,255,255,.085); border-color: rgba(255,255,255,.28); }

.itr-lift:has(.itr-row:hover), .itr-lift:has(.itr-tile:hover), .itr-lift:has(.itr-lift:hover) { transform: none !important; }

@media (prefers-reduced-motion: reduce) {
  .itr-live-dot, .itr-live-halo { animation: none; }
  .itr-lift, .itr-row, .itr-tile, .itr-panel, .itr-frame { transition: border-color 160ms, background-color 160ms !important; }
  .itr-lift:hover, .itr-row:hover, .itr-tile:hover, .itr-panel:hover, .itr-frame:hover { transform: none; }
}
```

Details worth not losing:

- `a:not(.itr-btn) { border-bottom: 0 }` exists to cancel `tokens/base.css` line 7,
  `a{…border-bottom:1px solid var(--blue-200)…}`. Without it every link on the site grows an
  underline. Keep it.
- `.itr-lift:hover` / `.itr-hl:hover` deliberately set **right / bottom / left** border colours
  only — never `border-top-color`. That protects the 3px signal-coloured top accent
  (`border-top: 3px solid var(--signal-incident)` on the `01-main` float card, etc.).
- `.itr-hl` is the "no-transform" sibling of `.itr-lift` — same border/shadow response, no rise.
  It is used where a lift would collide with neighbouring content: `01-main` ×3, `02-product` ×5,
  `03-pricing` ×3, `05-contacts` ×4, `06-contact-request` ×3, `08-blog` ×2, `10-blog-category` ×2.
- `.itr-hl-panel` is used exactly once, in `01-main`. Keep it anyway; it is the dark-band variant.
- `.itr-lift:has(…)` cancels the parent lift while a nested row/tile/card is hovered. `:has()` has
  no fallback and needs none — the failure mode is a double transform, not a broken layout.
- The `prefers-reduced-motion` block is *narrower* than `tokens/base.css`'s
  `@media (prefers-reduced-motion:reduce){*{animation-duration:.01ms!important;transition-duration:.01ms!important}}`
  and than the block already sitting in `theme/assets/css/intera.css`. Both fire; the DS one wins
  on duration. Keep all three — the helmet block additionally kills the hover **transforms**,
  which duration alone does not.
- Reduced motion sets `.itr-*:hover { transform: none; }` **without** `!important`, and that is
  correct: `transform` is never inline, so source order inside `intera.css` decides. Put this
  block after the hover definitions.

### 1c. Page-specific rules — quote by page

**`body`** — declared per page, three shapes:

| Files | Declaration |
| --- | --- |
| `01-main`, `02-product`, `03-pricing`, `04-faq`, `05-contacts`, `06-contact-request`, `07-policy`, `08-blog`, `09-blog-post`, `10-blog-category`, `11-docs`, `12-docs-article`, `13-docs-category` (13 page mockups) | `body { margin: 0; font-family: var(--font-sans); color: var(--text-primary); background: var(--surface-page); -webkit-font-smoothing: antialiased; }` |
| `00-templates` | `body { margin: 0; font-family: var(--font-sans); color: var(--text-primary); background: var(--surface-sunken); -webkit-font-smoothing: antialiased; }` — sunken, because the index is a gallery, not a page |
| `site-nav` | `body { margin: 0; font-family: var(--font-sans); color: var(--text-primary); }` |
| `site-footer` | `body { margin: 0; font-family: var(--font-sans); }` |

→ The theme takes the 13-page form. It duplicates `tokens/base.css`
(`body{margin:0;background:var(--surface-page);color:var(--text-primary);font-family:var(--font-sans);…-webkit-font-smoothing:antialiased…}`)
almost exactly, so `intera.css` needs **no `body` rule at all** beyond what is already there.

**Heading/paragraph margin reset**

| Files | Declaration |
| --- | --- |
| `01-main`, `02-product`, `03-pricing`, `04-faq`, `05-contacts`, `06-contact-request`, `08-blog`, `10-blog-category`, `11-docs`, `13-docs-category` | `h1, h2, h3, h4, p { margin: 0; }` |
| `07-policy`, `09-blog-post`, `12-docs-article` | `h1, h2, h3, h4, p, ul { margin: 0; }` + `ul { padding-left: 20px; }` |
| `00-templates` | `h1, h2, h3, p { margin: 0; }` (no `h4`) |
| `site-nav`, `site-footer` | *absent* |

→ Superset for the theme: `h1, h2, h3, h4, p, ul { margin: 0; }` and `ul { padding-left: 20px; }`.
`tokens/base.css` already zeroes `h1..h6` and `p`; only the `ul` pair is new.
**Caution:** a blanket `ul { margin: 0 }` will flatten editor lists — scope the list rules to the
prose wrapper (§4) rather than globally.

**`img`**

`img { max-width: 100%; }` — present in `01-main`, `02-product`, `08-blog`, `09-blog-post`
(the four mockups that actually contain `<img>`). Absent everywhere else.
The theme's existing `img, svg, video { max-width: 100%; height: auto; }` supersedes it.
`height: auto` is a theme-side addition and is safe: every mockup `<img>` sits inside an
`.itr-shot` box with an explicit height on the *wrapper*, not the image.

**`03-pricing` only**

```css
table { border-collapse: collapse; width: 100%; }
```
(`tokens/base.css` already has `table{border-collapse:collapse}`; `width: 100%` is the new part.)

**`04-faq` only** — the native `<details>` accordion:

```css
summary { list-style: none; cursor: pointer; transition: color 140ms cubic-bezier(.2,.6,.25,1); }
summary:hover { color: var(--blue-600); }
summary::-webkit-details-marker { display: none; }
details[open] summary svg { transform: rotate(180deg); }
summary svg { transition: transform 160ms cubic-bezier(.2,.6,.25,1); }
```

`summary:hover { color: var(--blue-600) }` **loses to** the inline
`style="…color: var(--ink-900)"` on every `<summary>` in `04-faq` (L98, L104, L110, L116, …).
Move that `color` into a `.itr-faq-summary` class per §0.

**`12-docs-article` only**

```css
code { font-family: var(--font-mono); font-size: var(--text-sm); background: var(--surface-muted); border: 1px solid var(--border-hairline); border-radius: var(--radius-sm); padding: 1px 5px; }
```

This is the theme's inline-code style and belongs in the shared prose block, not one template.

### 1d. What is *not* in any helmet and has to come from elsewhere

`.itr-btn`, `.itr-btn--primary|secondary|ghost|inverse|outlineInverse|danger|link|block|sm|md|lg`,
`.itr-iconbtn*` and `.itr-field*` are injected at runtime by `_ds/intera/_ds_bundle.js`
(`<style id="itr-btn-css">`, `#itr-iconbtn-css`, `#itr-field-css`). The bundle is **not ported**,
so the helmet's seven `a.itr-btn*` hover rules currently reference classes that would not exist.
Per the repo file map that CSS belongs in `theme/_ds/intera/components/*.css` (a new
`buttons.css` / `fields.css`, added to the `styles.css` `@import` manifest), extracted verbatim
from the bundle — **not** in `intera.css`. Sample of what is in there, for scale:

```css
.itr-iconbtn{display:inline-flex;align-items:center;justify-content:center;border-radius:var(--radius-control);border:1px solid transparent;background:transparent;color:var(--ink-600);cursor:pointer;transition:var(--transition-control)}
.itr-iconbtn:hover:not(:disabled){background:var(--surface-hover);color:var(--ink-900)}
.itr-iconbtn--outline{border-color:var(--border-default);background:var(--surface-card)}
.itr-iconbtn--sm{width:28px;height:28px} .itr-iconbtn--md{width:36px;height:36px} .itr-iconbtn--lg{width:44px;height:44px}
.itr-btn--outlineInverse:hover:not(:disabled){background:rgba(255,255,255,.09);border-color:rgba(255,255,255,.6)}
```

---

## 2. `mobile.css` rewritten without `!important`

`_design/mobile.css` is 40 lines, 3 blocks: `max-width: 900px`, `max-width: 760px`,
`(hover: none)`. Every `!important` is there to beat an inline declaration on the same element.
Removing them means moving *only the conflicting property* into the class.

Two mechanisms, picked per rule:

- **(A) property lives in the class.** Used when there is exactly one instance in the whole site.
- **(B) per-instance value passed as a CSS custom property in the inline style**, consumed by the
  class. Used when the same hook carries different desktop values on different pages. The
  handoff value stays at the markup where a reader expects it; the media query overrides the
  whole property, so no specificity fight ever happens.

Most grids in the export do **not** need any of this: **34 of the 40** `grid-template-columns`
declarations are `repeat(auto-fit, minmax(min(<N>px, 100%), 1fr))` (widths from 140px to 380px)
and collapse on their own. Of the remaining six, two are small in-card pairs with no hook —
`02-product` L86 `grid-template-columns: 1fr 1fr` and `01-main` L565
`grid-template-columns: repeat(2, minmax(0, 1fr))` — and are fine as they are.
Only six hooks exist, and none of them is a 4-column content grid — `.itr-cols-4` is the
**footer** grid only.

### Breakpoint 900px

**R1 — `.itr-cols-4`** · footer column grid · **1 instance**: `site-footer.dc.html` L59
`<div class="itr-cols-4" style="max-width: 1160px; margin: 0 auto; padding: clamp(32px, 7vw, 56px) clamp(20px, 5vw, 24px) 36px; display: grid; grid-template-columns: 1.4fr 1fr 1fr 1fr; gap: 40px">`

- mockup: `.itr-cols-4 { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; gap: 34px 32px !important; }`
- mechanism **A**. Move `display: grid; grid-template-columns: 1.4fr 1fr 1fr 1fr; gap: 40px` out
  of the inline style into `.itr-cols-4` in `intera.css`. `max-width` / `margin` / `padding` stay
  inline.
- **PHP hook** — `footer.php`: `<div class="itr-cols-4" style="max-width: 1160px; margin: 0 auto; padding: clamp(32px, 7vw, 56px) clamp(20px, 5vw, 24px) 36px">`
- theme CSS:
  ```css
  .itr-cols-4 { display: grid; grid-template-columns: 1.4fr 1fr 1fr 1fr; gap: 40px; }
  @media (max-width: 900px) { .itr-cols-4 { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 34px 32px; } }
  @media (max-width: 760px) { .itr-cols-4 { grid-template-columns: minmax(0, 1fr); gap: 30px; } }
  ```
  (`mobile.css` puts the 760px `.itr-cols-4` rule in its own block — same hook, listed here for
  completeness.)

**R2 — `.itr-1col`** · two-column splits · **3 instances, three different desktop tracks**:

| File · line | inline `grid-template-columns` | inline `gap` |
| --- | --- | --- |
| `02-product` L68 | `minmax(0, 1fr) minmax(0, 420px)` | `56px` |
| `02-product` L120 | `repeat(2, minmax(0, 1fr))` | `20px` |
| `08-blog` L101 | `minmax(0, 1fr) minmax(0, 340px)` | `44px` |

- mockup: `.itr-1col { grid-template-columns: minmax(0, 1fr) !important; }`
- mechanism **B** — the tracks differ per instance.
- **PHP hooks** — `page-product.php` (×2), `home.php` (×1): keep the class, replace the inline
  `display: grid; grid-template-columns: …` with `--itr-cols: …`, e.g.
  `<div class="itr-1col" style="margin-top: 24px; --itr-cols: minmax(0, 1fr) minmax(0, 420px); gap: 56px; align-items: start">`
- theme CSS:
  ```css
  .itr-1col { display: grid; grid-template-columns: var(--itr-cols, minmax(0, 1fr)); }
  @media (max-width: 900px) { .itr-1col { grid-template-columns: minmax(0, 1fr); } }
  ```
- `gap` is untouched by `mobile.css` and stays inline.
- `09-blog-post` L81 does the same split with `display: flex; flex-wrap: wrap; gap: 48px` +
  `flex: 1 1 520px; order: 1` on the `<article>` — it self-collapses and needs no hook. Keep it
  that way; do not add `.itr-1col` there.

**R3 — `.itr-float`** · card floating over the hero frame · **1 instance**: `01-main` L128
`<div class="itr-float itr-lift" style="position: absolute; right: -14px; bottom: -44px; width: 258px; background: var(--surface-card); border: 1px solid var(--border-default); border-top: 3px solid var(--signal-incident); border-radius: var(--radius-card); box-shadow: var(--shadow-overlay); padding: 16px">`

- mockup:
  ```css
  .itr-float { position: static !important; inset: auto !important; width: auto !important; margin-top: 12px !important; box-shadow: var(--shadow-sm) !important; }
  ```
- mechanism **A**. Move `position / right / bottom / width / box-shadow` into the class; leave
  `background`, both `border` declarations, `border-radius`, `padding` inline (they are the card's
  identity and the 3px `--signal-incident` top accent).
- **PHP hook** — `front-page.php`, class stays `itr-float itr-lift`; inline becomes
  `style="background: var(--surface-card); border: 1px solid var(--border-default); border-top: 3px solid var(--signal-incident); border-radius: var(--radius-card); padding: 16px"`
- theme CSS:
  ```css
  .itr-float { position: absolute; right: -14px; bottom: -44px; width: 258px; box-shadow: var(--shadow-overlay); }
  @media (max-width: 900px) { .itr-float { position: static; inset: auto; width: auto; margin-top: 12px; box-shadow: var(--shadow-sm); } }
  ```
  `inset: auto` is what clears `right`/`bottom`; keep it even though `position: static` already
  neutralises them, so the rule still reads correctly if the card is ever made `relative`.
- The float's containing block is the hero frame wrapper — that ancestor must keep
  `position: relative`, which is not a `mobile.css` concern but is a template requirement.

### Breakpoint 760px

**R4 — `body { overflow-wrap: break-word; }`** — no `!important`, no inline conflict, no hook.
Copy as-is. (Consider promoting it out of the media query; long unbroken IDs in mono type can
overflow at any width. Listed as an open question rather than changed silently.)

**R5 — `.itr-stagger`** · stepped source rows · **3 instances**, `01-main` L166 / L172 / L178,
inline `margin-left: 26px`, `52px`, `26px` respectively (all also carry `.itr-row`).

- mockup: `.itr-stagger { margin-left: 0 !important; }`
- mechanism **B** — the indents differ.
- **PHP hook** — `front-page.php`:
  `<div class="itr-row itr-stagger" style="display: flex; align-items: center; gap: 14px; background: var(--white); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 13px 16px; box-shadow: var(--shadow-xs); --itr-indent: 26px">`
- theme CSS:
  ```css
  .itr-stagger { margin-left: var(--itr-indent, 0); }
  @media (max-width: 760px) { .itr-stagger { margin-left: 0; } }
  ```

**R6 — `.itr-shot`** · product screenshot crops · **5 instances**:

| File · line | inline wrapper height |
| --- | --- |
| `01-main` L124 | `420px` |
| `01-main` L303 | `420px` |
| `01-main` L426 | `440px` |
| `02-product` L180 | `420px` |
| `09-blog-post` L98 | `360px` |

Every one wraps the identical image:
`<img src="…" alt="…" style="display: block; width: 100%; object-fit: cover; object-position: top left">`

- mockup:
  ```css
  .itr-shot { height: 220px !important; }
  .itr-shot > img { width: 680px !important; max-width: none !important; }
  ```
- mechanism **B** for the wrapper height, **A** for the image (the image's inline `width: 100%`
  must move to the class so the 760px `width: 680px` can win; `max-width: none` is needed to
  beat the theme's global `img { max-width: 100% }`).
- **PHP hooks** — `front-page.php` (×3), `page-product.php`, `single.php`:
  `<div class="itr-shot" style="--itr-shot-h: 420px"><img src="…" alt="…"></div>`
  (`alt` text must come from the media library / `the_post_thumbnail`, per `_design/README.md`:
  the screenshots are placeholders and must not be hardcoded.)
- theme CSS:
  ```css
  .itr-shot { height: var(--itr-shot-h, 420px); overflow: hidden; }
  .itr-shot > img { display: block; width: 100%; object-fit: cover; object-position: top left; }
  @media (max-width: 760px) {
    .itr-shot { height: 220px; }
    .itr-shot > img { width: 680px; max-width: none; }
  }
  ```

**R7 — `.itr-scroll-x`** · pricing comparison table · **1 instance**: `03-pricing` L135
`<div class="itr-scroll-x" style="margin-top: 24px; background: var(--white); border: 1px solid var(--border-card); border-radius: var(--radius-card); overflow: hidden">`
wrapping `<table style="width: 100%; border-collapse: collapse">`.

- mockup:
  ```css
  .itr-scroll-x { overflow-x: auto !important; overflow-y: hidden !important; }
  .itr-scroll-x > table { min-width: 480px; }
  ```
  (the `min-width` already has no `!important` — the table's inline style sets `width`, not
  `min-width`, so there was never a conflict.)
- mechanism **A** — move the inline `overflow: hidden` into the class. It exists to clip the
  table's square corners inside the `var(--radius-card)` wrapper, so it must survive at desktop.
- **PHP hook** — `page-pricing.php`:
  `<div class="itr-scroll-x" tabindex="0" role="region" aria-label="Plan comparison" style="margin-top: 24px; background: var(--white); border: 1px solid var(--border-card); border-radius: var(--radius-card)">`
  (`tabindex`/`role`/`aria-label` are a theme-side accessibility addition — a scroll container
  with no keyboard focus is unreachable. It also gets a focus ring for free from
  `tokens/base.css` `[tabindex]:focus-visible`.)
- theme CSS:
  ```css
  .itr-scroll-x { overflow: hidden; }
  @media (max-width: 760px) {
    .itr-scroll-x { overflow-x: auto; overflow-y: hidden; }
    .itr-scroll-x > table { min-width: 480px; }
  }
  ```
- **Also apply `.itr-scroll-x` to editor tables** — see §4.

### `@media (hover: none)`

```css
.itr-lift:hover, .itr-row:hover, .itr-tile:hover, .itr-panel:hover, .itr-frame:hover { transform: none !important; }
```

No inline conflict at all — `transform` is only ever set by `intera.css` itself. Two clean ways,
both `!important`-free:

- **Preferred:** wrap the five transform-bearing hover rules in
  `@media (hover: hover) and (pointer: fine) { … }`, so touch devices never receive the transform
  in the first place. The colour/border/shadow half of each hover stays unconditional, matching
  the mockup (which only kills `transform`, not the fill).
- **Minimal:** keep the block verbatim, minus `!important`, placed **after** the hover rules in
  the file. Equal specificity, later wins.

Note `.itr-hl` / `.itr-hl-panel` are deliberately absent from this block — they have no transform.

### The breakpoint `mobile.css` does not contain: **1040px**

`site-nav.dc.html` switches between the desktop bar and the burger drawer in JavaScript, not CSS:

```js
const wide = window.innerWidth >= 1040;
```

with `<sc-if value="{{ wide }}">` around the six-link `<nav>` + Beta badge + "Get Early Access",
and `<sc-if value="{{ narrow }}">` around the compact button + `IconButton icon="menu"`.
`support.js` is not ported, so **the theme has to do this in CSS** at `min-width: 1040px` /
`max-width: 1039.98px`, with `theme/assets/js/intera.js` handling only the drawer's open/close
(`aria-expanded`, `menu` ↔ `x` icon). Per repo `CLAUDE.md` the site must work without JS: render
both navs, show/hide by media query, and make the drawer a `<details>` or a checkbox toggle if a
no-JS fallback is wanted.

Related header facts the CSS needs: the masthead is
`position: fixed; top: 0; left: 0; right: 0; z-index: 60; background: rgba(255,255,255,.92); backdrop-filter: blur(8px); border-bottom: 1px solid var(--border-subtle)`
with an inner bar of `height: 76px`, and every page compensates with a literal
`<div style="height: 76px"></div>` spacer right after `<dc-import name="site-nav">`.

---

## 3. `style-hover="…"` → real CSS classes

All **160** `style-hover` attributes in the export sit on `<a>` elements. They carry only **10
distinct declaration strings**, which split into **11 class roles** (the `background:
var(--surface-hover)` string covers three unrelated components). The table gives the verbatim declarations, every page and line, whether the
declaration collides with an inline style on the same element (§0), and the proposed class.

| # | Declarations (verbatim) | Where | Collides with inline? | Proposed class |
| --- | --- | --- | --- | --- |
| 1 | `color: var(--ink-600)` | breadcrumb links, base `style="color: var(--ink-400)"`. `02-product` L66 ·`03-pricing` L66 · `04-faq` L70 · `05-contacts` L65 · `06-contact-request` L65 (×2) · `07-policy` L66 · `08-blog` L66 · `09-blog-post` L67 (×3) · `10-blog-category` L65 (×2) · `11-docs` L65 · `12-docs-article` L98 (×2) · `13-docs-category` L65 (×2) | **yes** — inline `color` | `.itr-crumb` |
| 2 | `color: var(--ink-900)` | header nav. `site-nav` L65–L70 desktop (base `color: var(--ink-600)`, `font-size: var(--text-md)`, `white-space: nowrap`); L86–L91 drawer (base `color: var(--ink-800)`, `font-size: var(--text-lg)`, `padding: 14px 0`, hairline rule) | **yes** — inline `color` | `.itr-nav-link` (one rule serves both; drawer keeps its own base via `.itr-nav-link--drawer`) |
| 3 | `color: var(--white)` | footer links. `site-footer` L70–L100 (15 links, base `color: rgba(255,255,255,.72)`, `font-size: var(--text-md)`) and L109 mailto (base `color: rgba(255,255,255,.45)`) | **yes** — inline `color` | `.itr-foot-link` |
| 4 | `background: var(--surface-hover)` — padded pill/jump links | `04-faq` L84–L86 (`padding: 8px 12px; border-radius: var(--radius-md)`) · `07-policy` L86–L95 (10, `padding: 7px 12px`) · `09-blog-post` L147–L149 (TOC, `padding: 7px 12px`) · `12-docs-article` L70–L91 (11, docs sidebar, `padding: 6px 10px`) | **no** — no inline `background` | `.itr-jump` |
| 5 | `background: var(--surface-hover)` — full-bleed list rows | `08-blog` L103/111/119/127/135 (post list, `display: flex; …; padding: 24px 0; border-bottom: 1px solid var(--border-hairline)`) · `13-docs-category` L85–L165 (10, `display: flex; gap: 16px; …; padding: 16px 4px; border-bottom: 1px solid var(--border-hairline)`) | **no** | `.itr-list-row` |
| 6 | `background: var(--surface-hover)` — on a card that already lifts | `09-blog-post` L133/137 (`class="itr-lift"`, `border: 1px solid var(--border-card); border-radius: var(--radius-card); padding: 18px`) · `12-docs-article` L163/167 (same, `padding: 16px`) | **no** | `.itr-lift-tint` (added alongside `itr-lift`) |
| 7 | `box-shadow: var(--shadow-sm)` | `08-blog` L84 — featured post card, `class="itr-lift"`, inline `box-shadow: var(--shadow-xs)`, `border-radius: var(--radius-xl)` | **yes** — inline `box-shadow` | `.itr-feature-card` |
| 8 | `box-shadow: var(--shadow-sm); border-color: var(--border-strong)` | `10-blog-category` L88/99/110 — archive post cards, `class="itr-lift"`, inline `border: 1px solid var(--border-card); box-shadow: var(--shadow-xs)` | **yes** — both | `.itr-post-card` |
| 9 | `color: var(--blue-600)` | `07-policy` L98/99 (related legal, base `--ink-600`) · `08-blog` L156/157 + `10-blog-category` L134/135 + `13-docs-category` L180–L182 (category rail rows, base `--ink-800`, `display: flex; justify-content: space-between; padding: 11px 0`, mono count on the right) · `09-blog-post` L153 · `10-blog-category` L80 ("All posts") · `11-docs` L75–L77 (quick links) · `11-docs` L93/115/137/159 (category headings, `font-size: var(--text-xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900); line-height: 34px`) · `11-docs` L98–L165 (article rows, `height: 38px; line-height: 38px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap`) · `12-docs-article` L177–L185 (term nav, base `--ink-600`, `padding: 5px 12px`) | **yes** — inline `color` in every case | one hover rule `.itr-link-blue`, with four semantic aliases sharing it: `.itr-rail-row`, `.itr-doc-title`, `.itr-doc-row`, `.itr-term-link` |
| 10 | `color: var(--blue-700)` | `05-contacts` L83 (mono mailto, base `color: var(--blue-600)`) · `11-docs` L104/126/148/168 ("N more articles", base `color: var(--blue-600); font-weight: 500`) | **yes** — inline `color` | `.itr-link-strong` — write it as `color: var(--text-link-hover)` (`--text-link-hover: var(--blue-700)`), and set the base to `var(--text-link)` (`= var(--blue-600)`), so these ride the DS link tokens instead of raw blues |
| 11 | `border-color: var(--border-strong); box-shadow: var(--shadow-sm)` (13×) and `border-color: var(--border-strong)` (2×) | `00-templates` L72/81/90/99/108/117/… (template gallery cards, `class="itr-lift"`, inline `border: 1px solid var(--border-card); box-shadow: var(--shadow-xs)`) and L192/198 (`border: 1px solid var(--border-subtle)`, no shadow) | **yes** | `.itr-card-link` / `.itr-card-link--quiet` — **but `00-templates` is the export index and has no theme file.** Ship these only if a similar card link appears in a real template; otherwise drop both. |

Fixing the collisions, per §0: the colliding property (`color`, `box-shadow`, `border-color`)
moves from the inline `style` into the class, e.g.

```css
.itr-crumb       { color: var(--ink-400); }
.itr-crumb:hover { color: var(--ink-600); }

.itr-nav-link          { color: var(--ink-600); }
.itr-nav-link--drawer  { color: var(--ink-800); }
.itr-nav-link:hover    { color: var(--ink-900); }

.itr-foot-link       { color: rgba(255,255,255,.72); }
.itr-foot-link--dim  { color: rgba(255,255,255,.45); }
.itr-foot-link:hover { color: var(--white); }

.itr-jump:hover,
.itr-list-row:hover,
.itr-lift-tint:hover { background: var(--surface-hover); }

.itr-feature-card       { box-shadow: var(--shadow-xs); }
.itr-feature-card:hover { box-shadow: var(--shadow-sm); }

.itr-post-card       { border: 1px solid var(--border-card); box-shadow: var(--shadow-xs); }
.itr-post-card:hover { border-color: var(--border-strong); box-shadow: var(--shadow-sm); }

.itr-link-blue, .itr-rail-row, .itr-doc-title, .itr-doc-row, .itr-term-link { /* base colour, per role */ }
.itr-link-blue:hover, .itr-rail-row:hover, .itr-doc-title:hover,
.itr-doc-row:hover,  .itr-term-link:hover { color: var(--blue-600); }

.itr-link-strong       { color: var(--text-link); }
.itr-link-strong:hover { color: var(--text-link-hover); }
```

Two cross-checks:

- The `.itr-post-card` / `.itr-feature-card` hovers coexist with `.itr-lift:hover`, which already
  sets `border-right/bottom/left-color: var(--border-strong) !important` and a bespoke shadow
  with `!important`. On those three `10-blog-category` cards the `.itr-lift` shadow wins (it is
  `!important`), so the `var(--shadow-sm)` in `style-hover` is effectively dead in the mockup.
  **Decide explicitly** which one the theme ships — see open questions.
- Every hover here should be inside the `@media (hover: hover)` guard only if it moves; pure
  colour/background hovers stay unconditional (`mobile.css`'s touch block only kills `transform`).

Also needs a class, same mechanism, though it is not a `style-hover` attribute:
`04-faq`'s `summary:hover { color: var(--blue-600) }` (§1c) — `.itr-faq-summary`, with the base
`color: var(--ink-900)` moved out of the inline style.

---

## 4. What the theme needs that the mockups do not cover

The export is a marketing site rendered from fixed markup. It contains no WordPress output at
all — no `.alignwide`, no captions, no comment list, no search form markup, no pagination markup
(only a disabled `Button` pair and a mono `Page 1 of 1` in `10-blog-category` L122), no
screen-reader utility, no focus styling beyond `tokens/base.css`. All of that has to be written.

### 4.1 WordPress core classes

- **`.screen-reader-text`** — `header.php` already emits
  `<a class="screen-reader-text" href="#intera-main">Skip to content</a>` but nothing defines the
  class; without CSS that link is visibly on the page. Needs the standard clip-rect utility plus
  a `:focus` state that reveals it. The reveal must clear the fixed masthead:
  `z-index: 70` (the header is `z-index: 60`) and enough `top` to sit below/over the 76px bar.
  Style it with tokens — `background: var(--surface-card)`, `border: 1px solid var(--border-card)`,
  `border-radius: var(--radius-control)`, `box-shadow: var(--shadow-lg)`,
  `color: var(--text-primary)`.
- **`.alignwide` / `.alignfull` / `.alignleft` / `.alignright` / `.aligncenter`** — the site grid
  is `max-width: 1160px` with `padding: 0 clamp(20px, 5vw, 24px)` (24px at desktop). `alignwide`
  should break out to roughly the container plus gutters; `alignfull` to `100vw`. `content_width`
  is currently `1200` in `inc/setup.php` but the design container is `1160` — reconcile.
- **`.wp-caption` / `.wp-caption-text` / `figcaption`** — the mockups' caption style is
  `09-blog-post` L101: `font-size: var(--text-xs); color: var(--ink-500); font-family: var(--font-mono)`.
  Use exactly that.
- **`.sticky`** — no sticky-post treatment exists in the design. The DS accent vocabulary is a
  3px top border in a signal colour (`_design/README.md`: *"Акцент на карточке — полоса 3px по
  верхнему краю, никогда слева"*), so `border-top: 3px solid var(--blue-600)` on the card is the
  in-system answer. Needs a decision.
- **`.bypostauthor`**, `.wp-block-*` gallery/embed defaults, `.gallery-caption`, `.post-password-form`
  — required for the WordPress theme-check baseline, none present in the design.
- **Comment list** — `wp_list_comments` output (`.comment-list`, `.comment-body`, `.comment-meta`,
  `.comment-author`, `.comment-reply-link`, `.children`, `.comment-form`, `.comment-respond`).
  Nothing in the export resembles a comment thread. `inc/enqueue.php` already enqueues
  `comment-reply`, so the markup is expected. The nearest visual precedent is the
  `.itr-list-row` hairline list (`border-bottom: 1px solid var(--border-hairline)`) with the mono
  metadata treatment (`font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-500)`).
- **Pagination** — `the_posts_pagination()` emits `.nav-links`, `.page-numbers`, `.current`,
  `.prev`, `.next`. The design shows only `10-blog-category` L121–L126: a hairline-topped row,
  `font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-400)` on the left,
  two `secondary sm` buttons on the right. Numbered pages have to be invented within that.
- **Search form** — `11-docs` L73 and `12-docs-article` L66 use the DS `Input` component with
  `icon-left="search"`. `.itr-field*` CSS lives only in `_ds_bundle.js` (§1d), so search form
  styling depends on porting that sheet first.

### 4.2 Editor prose (`.entry-content` / `.intera-prose`)

The mockups hand-style every paragraph inline; `the_content()` cannot. Extract the article
typography into one prose block. Verbatim values from the two long-form screens:

Container — `09-blog-post` L82:
`flex: 1 1 520px; min-width: 0; max-width: 680px; display: flex; flex-direction: column; gap: 24px`

| Element | Declarations (quoted) | Source |
| --- | --- | --- |
| `p` | `font-size: var(--text-base); line-height: 1.75; color: var(--ink-700)` | `09-blog-post` L83 |
| `p` (docs) | `font-size: var(--text-base); line-height: 1.7; color: var(--ink-700); margin-top: 10px` | `12-docs-article` L111 |
| `h2` (blog) | `font-size: var(--text-2xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900); margin-top: 12px` | `09-blog-post` L85 |
| `h2` (docs) | `font-size: var(--text-xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900)` | `12-docs-article` L110 |
| `h1` | `font-size: clamp(26px, 2.8vw, 34px); font-weight: 600; letter-spacing: -0.02em; line-height: 1.18; color: var(--ink-900)` | `12-docs-article` L100 |
| lede `p` | `font-size: var(--text-lg); line-height: 1.6; color: var(--ink-600)` | `12-docs-article` L101 |
| `ul` | `font-size: var(--text-base); line-height: 1.75; color: var(--ink-700); display: flex; flex-direction: column; gap: 6px` + `padding-left: 20px` | `09-blog-post` L87, helmet |
| `blockquote` | wrapper `border-left: 3px solid var(--blue-600); padding: 4px 0 4px 24px`; quote `font-size: var(--text-xl); line-height: 1.5; letter-spacing: -0.01em; color: var(--ink-900)`; attribution `font-size: var(--text-sm); color: var(--ink-500); margin-top: 10px` | `09-blog-post` L104–L107 |
| `code` | `font-family: var(--font-mono); font-size: var(--text-sm); background: var(--surface-muted); border: 1px solid var(--border-hairline); border-radius: var(--radius-sm); padding: 1px 5px` | `12-docs-article` helmet |
| `pre` / output block | `background: var(--surface-sunken); border: 1px solid var(--border-hairline); border-radius: var(--radius-md); padding: 14px 16px; font-family: var(--font-mono); font-size: var(--text-sm); color: var(--ink-700)` | `12-docs-article` L118 |
| meta rule | `display: flex; gap: 20px; padding: 12px 0; border-top: 1px solid var(--border-hairline); border-bottom: 1px solid var(--border-hairline); font-family: var(--font-mono); font-size: var(--text-xs); color: var(--ink-500)` | `12-docs-article` L102 |
| figure caption | `font-size: var(--text-xs); color: var(--ink-500); font-family: var(--font-mono)` | `09-blog-post` L101 |
| `table` | `width: 100%; border-collapse: collapse`; `th` `text-align: left; padding: 12px 20px; font-size: var(--text-xs); font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: var(--ink-500); border-bottom: 1px solid var(--border-hairline)` on `background: var(--surface-sunken)`; `td` `padding: 13px 20px; border-bottom: 1px solid var(--border-hairline)`, numeric columns `text-align: right` and the `tbody` in `font-family: var(--font-mono); font-size: var(--text-sm); color: var(--ink-800)` | `03-pricing` L136–L152 |
| `hr` | `tokens/base.css`: `border:0;border-top:1px solid var(--border-subtle);margin:var(--space-7) 0` | DS |

Extras the prose block has to add on its own:

- `h3`–`h6` — the design only shows `h1`/`h2` in body copy; `tokens/base.css` covers `h1`–`h4`
  via `--type-h*-size`, `h5`/`h6` inherit nothing sized.
- **Links inside prose must underline.** `intera.css`'s `a:not(.itr-btn) { border-bottom: 0 }`
  strips the DS underline site-wide, which is right for nav and cards and wrong inside an
  article. Restore `tokens/base.css`'s treatment scoped to the prose wrapper:
  `border-bottom: 1px solid var(--blue-200)` / `:hover { border-bottom-color: var(--blue-600) }`.
- The mono rule from `_design/CLAUDE.md` — *"mono type for every number/identifier"* — cannot be
  enforced from CSS on editor content; it is an editorial instruction plus, optionally, a
  `<code>`-based convention.
- Wrap `the_content()` tables so they scroll on mobile: either reuse `.itr-scroll-x` in the
  template via a `the_content` filter, or add
  `@media (max-width: 760px) { .intera-prose > table { display: block; overflow-x: auto; } }`.

### 4.3 Focus, skip link, anchors

- `tokens/base.css` already ships
  `a:focus-visible,button:focus-visible,input:focus-visible,select:focus-visible,textarea:focus-visible,[tabindex]:focus-visible{outline:2px solid var(--focus-ring);outline-offset:2px;border-radius:var(--radius-xs)}`
  — inherited free. Do **not** re-declare it in `intera.css`; extend it only for elements the DS
  selector misses: `summary:focus-visible`, `details:focus-visible`, `.itr-scroll-x:focus-visible`,
  `[role="button"]:focus-visible`, and `.itr-lift`/`.itr-row` cards that are `<a>` (covered).
- On the dark bands (`--ink-950` hero, `--ink-900` Early Adopter / footer) `--focus-ring`
  (`--blue-500`) needs checking for contrast; `--focus-ring-offset: var(--white)` exists as a
  token but nothing uses it. Likely needs an inverse focus rule for `.itr-foot-link` and
  `.itr-panel` links.
- **Anchor offset.** The masthead is fixed at 76px. `04-faq` (3×), `07-policy` (10×) and
  `12-docs-article` (9×) set `scroll-margin-top: 100px` inline on each target. WordPress-generated
  heading anchors have no such inline style, so `intera.css` needs a global
  `[id] { scroll-margin-top: 100px; }` (or on `:target`). Note `01-main` (`#roles`, `#it`,
  `#how`, `#action`, `#early`, `#pricing`, `#partners`) and `02-product`
  (`#roles`, `#method`, `#packages`, `#integrations`) have anchor targets with **no**
  `scroll-margin-top` at all — those anchors currently land under the fixed header. A global rule
  fixes the mockups' own bug.
- `#intera-main` is the skip-link target and needs the same `scroll-margin-top`, plus
  `outline: none` guarded by a `tabindex="-1"` focus style so the jump is announced without a
  stray ring on click.

### 4.4 Two DS-level gaps that are not `intera.css`'s job (flagging so they are not lost)

1. `.itr-btn*` / `.itr-iconbtn*` / `.itr-field*` CSS exists only inside `_ds_bundle.js` (§1d) and
   must be extracted into `theme/_ds/intera/components/*.css` + added to the `styles.css`
   `@import` manifest.
2. Fonts and icons load from CDNs (`fonts.gstatic.com` via `tokens/fonts.css`; Lucide from
   `unpkg.com`). `_design/README.md` §"Что не сделано" item 4 says to localise both for
   production; the repo has a no-build-step rule, so the icon set must be inlined SVG. Fixed
   icon pairs from the README: Event = `activity`, Reconciliation = `scale`,
   Incident = `alert-triangle`, Pattern = `git-branch`, Connect = `plug`, Source = `database`;
   plus `chevron-down` (FAQ), `menu`/`x` (nav), `search`, `arrow-right`, `chevron-left`/`right`,
   `link`, `thumbs-up`/`thumbs-down`. Stroke width 1.75, `currentColor`.

---

## 5. Proposed file order for `theme/assets/css/intera.css`

`inc/enqueue.php` inlines DS sheets → `intera.css` → `style.css`, so `intera.css` sits after
`tokens/base.css` and needs no `!important` to override the DS itself.

1. Reset deltas the DS does not cover (`img/svg/video`, `ul` margin/padding — scoped).
2. Link vocabulary: the seven `a:not(.itr-btn)` / `a.itr-btn*` rules, verbatim (§1b).
3. Motion: `@keyframes itr-live` / `itr-halo`, `.itr-live-dot`, `.itr-live-halo` (§1b).
4. Hover vocabulary: `.itr-lift`, `.itr-row`, `.itr-tile`, `.itr-panel`, `.itr-frame`,
   `.itr-hl`, `.itr-hl-panel`, `.itr-lift:has(…)`, verbatim `!important`s intact (§1b).
5. Named hover classes replacing `style-hover` (§3).
6. Layout hooks at their desktop values: `.itr-cols-4`, `.itr-1col`, `.itr-float`,
   `.itr-stagger`, `.itr-shot`, `.itr-scroll-x` (§2).
7. Prose block + WordPress core classes + skip link/focus/anchor offset (§4).
8. Media queries last, in this order: `1040px` (nav), `900px`, `760px`, `(hover: none)`,
   `(prefers-reduced-motion: reduce)`.
