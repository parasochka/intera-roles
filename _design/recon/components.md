# INTERA design system — component rendering spec

Extracted from `_design/_ds/intera/_ds_bundle.js` (compiled React, non-minified, 4950 lines) and
`_ds_manifest.json`. Every style declaration below is copied verbatim from the bundle: `var(--token)`
names are never resolved to a hex value. Line numbers refer to `_ds_bundle.js`.

Namespace: `window.INTERADesignSystem_430dc5`. Nothing else is exported.

**Full export list** (bundle lines 4896–4948, order as assigned):
`Badge`, `Button`, `Card`, `CardHeader`, `Icon`, `IconButton`, `Logo`, `Tag`, `DataTable`,
`MetricTile`, `SIGNALS`, `SignalBadge`, `SignalChain`, `Alert`, `Dialog`, `StatusDot`, `Toast`,
`ToastStack`, `Tooltip`, `Checkbox`, `Field`, `Input`, `Radio`, `Select`, `Switch`, `Textarea`, `Tabs`.

The `ui_kits/*` sources (AppShell, IncidentDetail, PatternStudio, RoleDashboard, HeroSections,
OfferSections, ProductFrame, SiteChrome) are compiled into the bundle but **not exported** and never
referenced by the mockups — the mockups hand-write that markup. Ignore them for the theme.

---

## 0. How to read a mockup call site

### 0.1 Attribute → prop mapping (`support.js`, `collectProps`, lines 415–441)

For `<x-import>` (a DS component) the runtime does:

- `sc-name`, `data-dc-tpl` — dropped.
- `hint-size="100%,180px"` — **preview-only**. Layout hint for the streaming placeholder. Not a prop,
  produces no markup. Ignore entirely.
- `style-<pseudo>="…"` (e.g. `style-hover`) — collected but **only applied to plain DOM elements**
  (`kind === "dom"`, line 792/809). On an `<x-import>` it is silently dropped. Verified: no
  `<x-import>` in the 13 mockups carries a `style-hover`. Where it appears on plain elements the
  runtime mints a throwaway class (`scpN`) with an `!important`-ified `:hover` rule — real CSS must be
  written by hand in `assets/css/intera.css`.
- Any other attribute containing `-` is camel-cased: `icon-left` → `iconLeft`, `accent-line` →
  `accentLine`, `html-for` → `htmlFor`, `show-icon` → `showIcon`. (`aria-*` and `data-*` are left alone.)
- `class="itr-lift"` is **not** renamed for `<x-import>` (only `kind === "dom"` maps `class`→`className`).
  It lands in `...rest` and React writes it out as a `class` attribute anyway.
- `dc-props="{{ expr }}"` — the resolved object is `Object.assign`-ed onto the props (line 750),
  in attribute order, so it can add `className` / `style` / anything else.
- `{{ … }}` values resolve against the page's `renderVals()`; `"{{ true }}"` yields the boolean `true`.

Values used by the mockups' `renderVals()` (`01-main.dc.html:594`):

```js
lift:     { className: "itr-lift" },
fillCard: { className: "itr-lift", style: { height: "100%", display: "flex", flexDirection: "column" } },
```

### 0.2 How React serialises these styles

All components style inline with a React style object. When porting to PHP:

- camelCase → kebab-case (`borderRadius` → `border-radius`).
- Bare numbers get `px` on non-unitless properties (`gap: 5` → `gap:5px`, `marginTop: 2` → `margin-top:2px`).
  Unitless properties stay bare (`opacity: .6`, `lineHeight: 1.5`, `flex: 1`, `zIndex: 80`).
- `...style` is always spread **last**, so a caller's `style` overrides the component's own declarations.
- Attribute values are **strings** (`size="18"` is `"18"`, not `18`), so any prop that lands directly
  in a style value loses its `px`. This bites exactly one place — `Logo`'s wordmark `fontSize` (§7.3).
- `...rest` is spread after the explicit attributes in `_extends(...)`, so caller attributes override
  component defaults (e.g. a caller-supplied `type` beats Button's `type="button"`).

### 0.3 Injected stylesheets

Three components inject a `<style>` into `<head>` once, keyed by id. In the theme these become static
CSS in `assets/css/intera.css` (verbatim, they already use only tokens):

| id | injected by | content |
|---|---|---|
| `itr-btn-css` | Button (line 205) | `.itr-btn*` rules — §5 |
| `itr-iconbtn-css` | IconButton (line 271) | `.itr-iconbtn*` rules — §6 |
| `itr-field-css` | Input (1399), Select (1545), Textarea (1688) | `.itr-input*` rules — **all three bodies are byte-identical**; whichever mounts first wins. §21 |

Everything else is inline-styled, i.e. the PHP partial writes the same declarations inline or a class
that carries them.

---

## 1. Card  (`components/core/Card.jsx`, line 20)

```js
const pads = { none: 0, compact: "var(--pad-card-compact)", default: "var(--pad-card)", loose: "var(--space-7)" };
```

| prop | default | notes |
|---|---|---|
| `children` | — | |
| `padding` | `"default"` | key into `pads` above |
| `elevated` | `false` | |
| `accent` | — | colour string, drawn as the 3px top rule |
| `accentLine` | — | outline colour used *only when* `accent` is set; falls back to `var(--border-default)` |
| `interactive` | `false` | enables the JS hover state (mouseenter/leave) |
| `as` | `"div"` | tag name (destructured as `Tag`) |
| `style` | — | spread last |
| `...rest` | — | lands on the element (`class`, `id`, …) |

Element tree — a single element, tag = `as`:

```
<div style="…">{children}</div>
```

Style object (line 24–47), in source order:

```
background:      var(--surface-card)
border:          1px solid ${hover ? var(--border-strong)
                              : accent ? (accentLine || var(--border-default))
                              : var(--border-card)}
border-top:      3px solid ${accent}          // emitted ONLY when accent is set
border-radius:   var(--radius-card)
padding:         ${pads[padding]}
box-shadow:      ${elevated ? var(--shadow-md) : hover ? var(--shadow-sm) : var(--shadow-xs)}
transition:      var(--transition-surface), border-color var(--duration-fast) var(--ease-standard)
cursor:          ${interactive ? "pointer" : undefined}
…style
```

State table:

| state | border (all edges) | border-top | box-shadow |
|---|---|---|---|
| plain | `1px solid var(--border-card)` | — | `var(--shadow-xs)` |
| `elevated` | `1px solid var(--border-card)` | — | `var(--shadow-md)` |
| `accent` + `accentLine` | `1px solid ${accentLine}` | `3px solid ${accent}` | per `elevated` |
| `accent` only | `1px solid var(--border-default)` | `3px solid ${accent}` | per `elevated` |
| `interactive` + hover (JS) | `1px solid var(--border-strong)` | unchanged | `var(--shadow-sm)` unless `elevated` |

**Port note:** the hover state is React state, not CSS — reproduce it as `.itr-card:hover` in
`intera.css`. In the mockups the hover is in practice supplied by the page classes `.itr-lift` /
`.itr-hl` (defined in each mockup's `<helmet>`), so `interactive` mostly only adds `cursor:pointer`.
Accent is **always the top edge**; a left accent is explicitly off-brand (`readme.md`).

Rendered example (default, no accent):

```html
<div class="itr-lift" style="background:var(--surface-card);border:1px solid var(--border-card);border-radius:var(--radius-card);padding:var(--pad-card);box-shadow:var(--shadow-xs);transition:var(--transition-surface), border-color var(--duration-fast) var(--ease-standard)">…</div>
```

Mockup usage (all 51 call sites): `padding` is either omitted or `"loose"`; `class` is always
`itr-lift` or `itr-hl`; `interactive="{{ true }}"` on 8; `elevated="{{ true }}"` on 2 (both also
accented `var(--blue-600)` / `var(--blue-200)`); accent pairs used —
`var(--blue-600)`+`var(--blue-200)`, `var(--signal-event)`+`var(--signal-event-line)`,
`var(--signal-reconciliation)`+`…-line`, `var(--signal-incident)`+`…-line`,
`var(--signal-pattern)`+`…-line`, `var(--status-ok)`+`var(--status-ok-line)`.
`dc-props="{{ lift }}"` / `{{ fillCard }}` add `className` and, for `fillCard`,
`style="height:100%;display:flex;flex-direction:column"`.

---

## 2. CardHeader  (`components/core/Card.jsx`, line 51)

| prop | default |
|---|---|
| `title` | — |
| `description` | — |
| `action` | — (node, rendered as the last child, unwrapped) |
| `icon` | — (node, not a name) |
| `style`, `...rest` | — |

```
<div style="display:flex;align-items:flex-start;gap:var(--space-3);margin-bottom:var(--space-4)">
  [icon] <div style="margin-top:2px;color:var(--ink-500)">{icon}</div>
  <div style="flex:1;min-width:0">
    <div style="font-size:var(--text-md);font-weight:var(--weight-semibold);color:var(--text-primary);letter-spacing:var(--tracking-snug)">{title}</div>
    [description] <div style="font-size:var(--text-sm);color:var(--text-secondary);margin-top:2px;line-height:var(--leading-normal)">{description}</div>
  </div>
  {action}
</div>
```

Mockup usage: 2 call sites, `title` + `description` only (02-product: "IT owns access" /
"Business owns logic").

---

## 3. Icon  (`components/core/Icon.jsx`, line 107)

| prop | default |
|---|---|
| `name` | — (Lucide name, kebab-case) |
| `size` | `18` |
| `strokeWidth` | `1.75` |
| `color` | `"currentColor"` |
| `style`, `...rest` | — |

**Name resolution** (lines 98–99, 126–127):

```js
const toPascal = n => String(n).replace(/(^|[-_ ])([a-z0-9])/g, (_, __, c) => c.toUpperCase());
const set  = window.lucide && window.lucide.icons || null;
const node = set ? set[toPascal(name)] || set[name] : null;
```

So `"alert-triangle"` → `AlertTriangle`, `"table-2"` → `Table2`, `"circle-dollar-sign"` →
`CircleDollarSign`. A Lucide IconNode is `["svg", attrs, children]`; only `node[2]` (the children) is
used — Lucide's own svg attrs are discarded and replaced by the wrapper below.

Rendered element:

```html
<svg viewBox="0 0 24 24" width="{size}" height="{size}" fill="none" stroke="{color}"
     stroke-width="{strokeWidth}" stroke-linecap="round" stroke-linejoin="round"
     aria-hidden="true" focusable="false" style="display:block;flex:none">
  <!-- Lucide children verbatim: <path d="…"/>, <circle …/>, <rect …/>, <line …/> -->
</svg>
```

Notes:
- `stroke` carries `color`; default `currentColor` means the icon inherits the parent's text colour.
- Child attributes are kebab→camel→kebab round-tripped by React, i.e. identical to the Lucide source.
- If `window.lucide` has not loaded yet the component renders an **empty `<svg>`** and polls every 60ms.
  For the theme, inline the SVG bodies — see the **Icon appendix (§28)**, which carries the exact
  children for every icon name the mockups reference.
- Stroke width overrides used inside other components: Badge `2`, SignalBadge `2`, MetricTile `2`,
  DataTable sort chevron `2.2`, Tag remove `2`, Checkbox `3`. Everything else uses the `1.75` default.
- Sizes used inside components: 11/12/13/15/16/17/18/20 (never above 24 — `readme.md`).

---

## 4. Badge  (`components/core/Badge.jsx`, line 170)

```js
const BADGE_TONES = {
  neutral:  ["var(--status-neutral-soft)",  "var(--ink-700)",    "var(--status-neutral-line)"],
  info:     ["var(--status-info-soft)",     "var(--blue-700)",   "var(--status-info-line)"],
  ok:       ["var(--status-ok-soft)",       "var(--green-700)",  "var(--status-ok-line)"],
  warning:  ["var(--status-warning-soft)",  "var(--amber-700)",  "var(--status-warning-line)"],
  critical: ["var(--status-critical-soft)", "var(--red-700)",    "var(--status-critical-line)"],
  accent:   ["var(--violet-50)",            "var(--violet-700)", "var(--violet-200)"]
};   // [bg, fg, border]
```

| prop | default |
|---|---|
| `children` | — |
| `tone` | `"neutral"` (unknown tone falls back to `neutral`) |
| `icon` | — (Lucide name; rendered `size:12 strokeWidth:2`) |
| `solid` | `false` |
| `style`, `...rest` | — |

```html
<span style="display:inline-flex;align-items:center;gap:5px;
             background:{solid ? fg : bg};
             color:{solid ? var(--white) : fg};
             border:1px solid {solid ? transparent : bd};
             border-radius:var(--radius-badge);padding:2px 7px;
             font-size:var(--text-xs);font-weight:var(--weight-medium);
             line-height:1.5;white-space:nowrap">
  [icon svg 12px, stroke-width 2]{children}
</span>
```

Tone table (soft, i.e. `solid=false`):

| tone | background | color | border |
|---|---|---|---|
| neutral | `var(--status-neutral-soft)` | `var(--ink-700)` | `1px solid var(--status-neutral-line)` |
| info | `var(--status-info-soft)` | `var(--blue-700)` | `1px solid var(--status-info-line)` |
| ok | `var(--status-ok-soft)` | `var(--green-700)` | `1px solid var(--status-ok-line)` |
| warning | `var(--status-warning-soft)` | `var(--amber-700)` | `1px solid var(--status-warning-line)` |
| critical | `var(--status-critical-soft)` | `var(--red-700)` | `1px solid var(--status-critical-line)` |
| accent | `var(--violet-50)` | `var(--violet-700)` | `1px solid var(--violet-200)` |

`solid` swaps to `background: {fg}; color: var(--white); border:1px solid transparent`.

Mockup usage: 20 call sites — `tone="accent"` ×15, `tone="info"` ×5. No `icon`, no `solid`.

---

## 5. Button  (`components/core/Button.jsx`, line 239)

| prop | default |
|---|---|
| `children` | — |
| `variant` | `"primary"` |
| `size` | `"md"` |
| `iconLeft` | — (Lucide name) |
| `iconRight` | — (Lucide name) |
| `block` | `false` |
| `disabled` | `false` |
| `href` | — (presence switches the tag to `<a>`) |
| `className` | `""` |
| `...rest` | — (spread last, overrides everything above) |

Render (lines 250–268):

```js
const Tag   = href ? "a" : "button";
const gsize = size === "lg" ? 18 : 16;     // icon size for BOTH iconLeft and iconRight
className = `itr-btn itr-btn--${variant} itr-btn--${size}${block ? " itr-btn--block" : ""} ${className}`
href      = href
disabled  = href ? undefined : disabled
aria-disabled = disabled || undefined
type      = href ? undefined : (rest.type || "button")
children  = [iconLeft && <Icon name={iconLeft} size={gsize}/>, children, iconRight && <Icon name={iconRight} size={gsize}/>]
```

Note the class string always ends with a space when `className` is `""` (`"itr-btn itr-btn--primary itr-btn--md "`).
Icons inside a Button use the **default stroke width 1.75**.

```html
<a class="itr-btn itr-btn--secondary itr-btn--lg " href="06-contact-request.dc.html">
  Text<svg …16 or 18px…></svg>
</a>
<button class="itr-btn itr-btn--primary itr-btn--md " type="button">Text</button>
```

### 5.1 `itr-btn` stylesheet (`BTN_CSS`, lines 206–232) — verbatim

```css
.itr-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;font-family:var(--font-sans);font-weight:var(--weight-medium);border-radius:var(--radius-control);border:1px solid transparent;cursor:pointer;text-decoration:none;white-space:nowrap;transition:var(--transition-control);user-select:none}
.itr-btn:disabled,.itr-btn[aria-disabled="true"]{opacity:.45;cursor:not-allowed}
.itr-btn:focus-visible{outline:2px solid var(--focus-ring);outline-offset:2px}
.itr-btn--sm{height:var(--control-height-sm);padding:0 10px;font-size:var(--text-sm)}
.itr-btn--md{height:var(--control-height-md);padding:0 14px;font-size:var(--text-md)}
.itr-btn--lg{height:var(--control-height-lg);padding:0 20px;font-size:var(--text-base)}
.itr-btn--block{width:100%}
.itr-btn--primary{background:var(--action-primary);color:var(--text-on-accent);box-shadow:var(--shadow-xs)}
.itr-btn--primary:hover:not(:disabled){background:var(--action-primary-hover)}
.itr-btn--primary:active:not(:disabled){background:var(--action-primary-active)}
.itr-btn--secondary{background:var(--surface-card);color:var(--ink-800);border-color:var(--action-secondary-border);box-shadow:var(--shadow-xs)}
.itr-btn--secondary:hover:not(:disabled){background:var(--surface-hover);border-color:var(--border-strong)}
.itr-btn--secondary:active:not(:disabled){background:var(--surface-active)}
.itr-btn--ghost{background:transparent;color:var(--ink-700)}
.itr-btn--ghost:hover:not(:disabled){background:var(--surface-hover);color:var(--ink-900)}
.itr-btn--danger{background:var(--status-critical);color:var(--text-on-accent)}
.itr-btn--danger:hover:not(:disabled){background:var(--red-700)}
.itr-btn--link{background:transparent;color:var(--text-link);padding:0;height:auto;border:0}
.itr-btn--link:hover:not(:disabled){color:var(--text-link-hover);text-decoration:underline;text-underline-offset:3px}
.itr-btn--inverse{background:var(--white);color:var(--ink-900)}
.itr-btn--inverse:hover:not(:disabled){background:var(--ink-100)}
.itr-btn--outlineInverse{background:transparent;color:var(--white);border-color:rgba(255,255,255,.34)}
.itr-btn--outlineInverse:hover:not(:disabled){background:rgba(255,255,255,.09);border-color:rgba(255,255,255,.6)}
.itr-btn--outlineInverse:active:not(:disabled){background:rgba(255,255,255,.15)}
```

### 5.2 Variant × size matrix

Sizes are variant-independent:

| size | height | padding | font-size | icon size |
|---|---|---|---|---|
| `sm` | `var(--control-height-sm)` | `0 10px` | `var(--text-sm)` | 16 |
| `md` | `var(--control-height-md)` | `0 14px` | `var(--text-md)` | 16 |
| `lg` | `var(--control-height-lg)` | `0 20px` | `var(--text-base)` | 18 |

(`link` overrides `padding:0;height:auto` regardless of size.)

| variant | rest | hover | active |
|---|---|---|---|
| `primary` | bg `var(--action-primary)`, colour `var(--text-on-accent)`, `var(--shadow-xs)` | bg `var(--action-primary-hover)` | bg `var(--action-primary-active)` |
| `secondary` | bg `var(--surface-card)`, colour `var(--ink-800)`, border `var(--action-secondary-border)`, `var(--shadow-xs)` | bg `var(--surface-hover)`, border `var(--border-strong)` | bg `var(--surface-active)` |
| `ghost` | bg transparent, colour `var(--ink-700)` | bg `var(--surface-hover)`, colour `var(--ink-900)` | — |
| `danger` | bg `var(--status-critical)`, colour `var(--text-on-accent)` | bg `var(--red-700)` | — |
| `link` | transparent, colour `var(--text-link)`, `padding:0;height:auto;border:0` | colour `var(--text-link-hover)`, `underline`, `text-underline-offset:3px` | — |
| `inverse` | bg `var(--white)`, colour `var(--ink-900)` | bg `var(--ink-100)` | — |
| `outlineInverse` | transparent, colour `var(--white)`, border `rgba(255,255,255,.34)` | bg `rgba(255,255,255,.09)`, border `rgba(255,255,255,.6)` | bg `rgba(255,255,255,.15)` |

Shared states: focus-visible → `outline:2px solid var(--focus-ring); outline-offset:2px`;
disabled (`:disabled` **or** `[aria-disabled="true"]`, which is how the `<a>` form signals it) →
`opacity:.45; cursor:not-allowed`.

### 5.3 Link-button reset (required)

`tokens/base.css` gives every `<a>` a `border-bottom:1px solid var(--blue-200)` and a hover
`border-bottom-color:var(--blue-600)` at specificity (0,1,1) — which beats `.itr-btn`'s
`border:1px solid transparent` on hover. Each mockup's `<helmet>` therefore ships this reset, and the
theme must carry it (quoted from `01-main.dc.html:25–31`):

```css
a:not(.itr-btn) { color: var(--text-link); text-decoration: none; border-bottom: 0; transition: color 140ms cubic-bezier(.2,.6,.25,1); }
a:not(.itr-btn):hover { color: var(--text-link-hover); }
a.itr-btn:hover { border-bottom-color: transparent; }
a.itr-btn--primary:hover, a.itr-btn--danger:hover { color: var(--text-on-accent); }
a.itr-btn--secondary:hover { color: var(--ink-800); border-bottom-color: var(--border-strong); }
a.itr-btn--ghost:hover, a.itr-btn--inverse:hover { color: var(--ink-900); }
a.itr-btn--outlineInverse:hover { color: var(--white); border-bottom-color: rgba(255,255,255,.6); }
```

### 5.4 What the mockups actually use (46 call sites)

Variants: default `primary` (13), `secondary` (18), `link` (6), `inverse` (4), `ghost` (1),
`outlineInverse` (1). No `danger` anywhere.
Sizes: `md` default (17), `lg` (14), `sm` (11).
`block="{{ true }}"` on 8 (always with `href`, sizes md/lg).
`disabled="{{ true }}"` on 4 — all `size="sm" variant="secondary"` pager buttons with
`icon-left="chevron-left"` / `icon-right="chevron-right"` (blog + docs pagination).
`iconRight` values: `arrow-right` (12), `chevron-right` (2). `iconLeft` values: `chevron-left`,
`printer`, `link`, `thumbs-up`, `thumbs-down`.
`onClick="{{ submit }}"` (size lg, primary) and `onClick="{{ reset }}"` (ghost) on 06-contact-request
are the only two `<button>` renders; every other Button has an `href` and renders as `<a>`.

---

## 6. IconButton  (`components/core/IconButton.jsx`, line 291)

| prop | default |
|---|---|
| `icon` | — (Lucide name) |
| `label` | — (used for both `aria-label` and `title`) |
| `variant` | `"ghost"` |
| `size` | `"md"` |
| `className` | `""` |
| `...rest` | — |

```html
<button class="itr-iconbtn itr-iconbtn--{variant} itr-iconbtn--{size} {className}"
        aria-label="{label}" title="{label}" type="button">
  <svg …size 15 (sm) / 18 (md) / 20 (lg), stroke-width 1.75…></svg>
</button>
```

`ICONBTN_CSS` (lines 272–284) — verbatim:

```css
.itr-iconbtn{display:inline-flex;align-items:center;justify-content:center;border-radius:var(--radius-control);border:1px solid transparent;background:transparent;color:var(--ink-600);cursor:pointer;transition:var(--transition-control)}
.itr-iconbtn:hover:not(:disabled){background:var(--surface-hover);color:var(--ink-900)}
.itr-iconbtn:active:not(:disabled){background:var(--surface-active)}
.itr-iconbtn:disabled{opacity:.4;cursor:not-allowed}
.itr-iconbtn:focus-visible{outline:2px solid var(--focus-ring);outline-offset:2px}
.itr-iconbtn--outline{border-color:var(--border-default);background:var(--surface-card)}
.itr-iconbtn--outline:hover:not(:disabled){border-color:var(--border-strong)}
.itr-iconbtn--sm{width:28px;height:28px}
.itr-iconbtn--md{width:36px;height:36px}
.itr-iconbtn--lg{width:44px;height:44px}
```

Variants: `ghost` (base rules only) and `outline`. Sizes `sm|md|lg` → 28/36/44px square.

Mockup usage: exactly one — the mobile menu toggle in `site-nav.dc.html:80`,
`variant="outline"`, default size (36px), `label="Menu"`, `icon` toggles `"menu"` ⇄ `"x"`
(`site-nav.dc.html:113`, `burgerIcon: this.state.open ? "x" : "menu"`). Dialog also renders one
internally (`icon="x" label="Close" size="sm"`).

---

## 7. Logo  (`components/core/Logo.jsx`, lines 319 / 361)

| prop | default |
|---|---|
| `size` | `20` — the wordmark font-size in px; everything else scales off it |
| `tone` | `"ink"` (`"inverse"` is the only other value) |
| `variant` | `"horizontal"` (`"horizontal" \| "mark" \| "wordmark" \| "square"`) |
| `suffix` | — small uppercase word after the wordmark |
| `style`, `...rest` | — |

Derived: `color = tone === "inverse" ? var(--text-inverse) : var(--text-primary)`,
`markSize = Math.round(size * 1.42)`, square `box = Math.round(size * 2.4)`.

### 7.1 The mark (`Mark`, line 319) — two overlapping frames

```js
const inv  = tone === "inverse";
const a    = inv ? "#FFFFFF"          : "var(--ink-900)";        // outer/back frame stroke
const b    = inv ? "var(--blue-200)"  : "var(--action-primary)";  // front frame stroke
const fill = inv ? "#FFFFFF"          : "var(--action-primary)";  // solid intersection
```

```html
<svg viewBox="0 0 40 40" width="{markSize}" height="{markSize}" fill="none" aria-hidden="true" style="display:block;flex:none">
  <rect x="5"  y="5"  width="22" height="22" rx="3.5" stroke="{a}" stroke-width="2.6"/>
  <rect x="13" y="13" width="14" height="14" fill="{fill}"/>
  <rect x="13" y="13" width="22" height="22" rx="3.5" stroke="{b}" stroke-width="2.6"/>
</svg>
```

This is exactly `_design/assets/logo/logo-horizontal.svg` / `-inverse.svg` minus the `<text>`
(those files hard-code `#0E1A2B` / `#1A4FD6` / `#B4CBF8` / `#FFFFFF`).

### 7.2 `variant="horizontal"` (default) / `"mark"` / `"wordmark"`

```html
<span style="display:inline-flex;align-items:center;gap:{size*0.5}px">
  <!-- Mark, omitted when variant === "wordmark" -->
  <!-- Wordmark, omitted when variant === "mark": -->
  <span style="display:inline-flex;align-items:baseline;gap:0.55em;font-family:var(--font-sans);
               font-weight:600;font-size:{size}px;letter-spacing:0.09em;line-height:1;
               color:{color};white-space:nowrap">INTERA<!--
    [suffix] --><span style="font-weight:400;font-size:0.62em;letter-spacing:0.12em;
                 color:{tone==='inverse' ? 'rgba(255,255,255,.7)' : 'var(--text-muted)'};
                 text-transform:uppercase">{suffix}</span>
  </span>
</span>
```

Note `font-weight: 600` and `letter-spacing: 0.09em` are hard-coded numbers here, not tokens
(they correspond to `--weight-semibold` / `--tracking-caps`).

### 7.3 `variant="square"`

```html
<span style="display:inline-flex">
  <svg viewBox="0 0 64 64" width="{box}" height="{box}" fill="none" aria-label="INTERA" role="img" style="display:block">
    <rect width="64" height="64" rx="14" fill="var(--ink-900)"/>
    <g transform="translate(12 12)">
      <rect x="0" y="0" width="22" height="22" rx="3.5" stroke="#FFFFFF" stroke-width="2.8"/>
      <rect x="8" y="8" width="14" height="14" fill="#FFFFFF"/>
      <rect x="8" y="8" width="22" height="22" rx="3.5" stroke="var(--blue-200)" stroke-width="2.8"/>
    </g>
  </svg>
</span>
```

The square variant ignores `tone` and `suffix` entirely.

**Accessibility gap to fix in PHP:** only `variant="square"` gets `role="img" aria-label="INTERA"`.
The horizontal lockup's accessible name is the literal text "INTERA"; `variant="mark"` has **no
accessible name at all** (`aria-hidden` mark, no text).

Mockup usage: 3 — `site-nav.dc.html` `size="26"` (header, wrapped in the home link),
`site-footer.dc.html` `size="18" tone="inverse"`, `00-templates.dc.html` `size="18"`.
No `variant`, no `suffix` anywhere → always the horizontal lockup.
Minimum mark size is 16px (`readme.md`).

**Numeric-string quirk — `size` arrives as a string.** `support.js`'s `compileAttr` returns the raw
attribute text, so `size="26"` is the *string* `"26"`. The arithmetic still works
(`Math.round("26" * 1.42)` → `37`, `"26" * 0.5` → `13`), but `fontSize: size` becomes the string
`"26"`, and React only appends `px` to *numbers* — so the wordmark emits the invalid declaration
`font-size:26` and the browser drops it, leaving the wordmark at the inherited font size while the
mark renders at 37px. In the theme write the intended value explicitly:
header `font-size:26px` / mark 37px / gap 13px, footer + index `font-size:18px` / mark 26px / gap 9px.
(Same class of quirk applies nowhere else: every other string-valued prop feeds a class name, an SVG
`width`/`height` attribute, or a lookup key, all of which accept strings.)

---

## 8. Tag  (`components/core/Tag.jsx`, line 458)

| prop | default |
|---|---|
| `children` | — |
| `onRemove` | — (presence renders the ✕ button) |
| `selected` | `false` |
| `icon` | — (Lucide name, `size:12`, default stroke) |
| `style`, `...rest` | — |

```html
<span style="display:inline-flex;align-items:center;gap:6px;
             background:{selected ? var(--blue-50) : var(--surface-sunken)};
             color:{selected ? var(--blue-700) : var(--ink-700)};
             border:1px solid {selected ? var(--blue-500) : var(--border-default)};
             border-radius:var(--radius-badge);padding:3px 8px;
             font-size:var(--text-xs);font-family:var(--font-mono);line-height:1.5">
  [icon 12px]{children}
  [onRemove] <button aria-label="Remove" type="button"
        style="display:inline-flex;border:0;background:none;padding:0;margin-left:1px;cursor:pointer;color:inherit;opacity:.6">
        <svg …x, 12px, stroke-width 2…></svg></button>
</span>
```

| state | background | color | border |
|---|---|---|---|
| default | `var(--surface-sunken)` | `var(--ink-700)` | `1px solid var(--border-default)` |
| `selected` | `var(--blue-50)` | `var(--blue-700)` | `1px solid var(--blue-500)` |

Tag is the system's **mono chip** — `font-family: var(--font-mono)` is unconditional.
Mockup usage: 31 call sites, plain text children; `selected="{{ true }}"` on 2 (docs/blog filter
chips). No `icon`, no `onRemove` — so the ✕ button never appears in the site build.

---

## 9. DataTable  (`components/data/DataTable.jsx`, line 510)

| prop | default |
|---|---|
| `columns` | `[]` |
| `rows` | `[]` |
| `onRowClick` | — (enables row hover + `cursor:pointer`) |
| `dense` | `false` |
| `emptyMessage` | `"Nothing to show."` |
| `style`, `...rest` | — |

Column object: `{ key, header, align, width, mono, sorted, render }` —
`align` → `text-align` (default `"left"`), `width` → `<th style="width:…">`,
`sorted` is `"asc"|"desc"` and draws a chevron, `render(row)` replaces `row[key]`.

`pad = dense ? "6px 12px" : "10px 14px"` (used for both `th` and `td`).

```html
<div style="border:1px solid var(--border-card);border-radius:var(--radius-card);overflow:hidden;background:var(--surface-card)">
  <table style="width:100%;border-collapse:collapse;font-size:{dense ? var(--text-sm) : var(--text-md)}">
    <thead>
      <tr style="background:var(--surface-sunken)">
        <th style="text-align:{align};padding:{pad};font-size:var(--text-xs);font-weight:var(--weight-semibold);
                   color:var(--text-secondary);letter-spacing:var(--tracking-wide);text-transform:uppercase;
                   border-bottom:1px solid var(--border-default);white-space:nowrap;width:{c.width}">
          <span style="display:inline-flex;align-items:center;gap:4px">
            {c.header}[sorted → <svg chevron-down|chevron-up 12px stroke-width 2.2>]
          </span>
        </th>…
      </tr>
    </thead>
    <tbody>
      <!-- empty: -->
      <tr><td colspan="{columns.length}" style="padding:var(--space-8);text-align:center;color:var(--text-muted);font-size:var(--text-sm)">{emptyMessage}</td></tr>
      <!-- rows: -->
      <tr style="background:{hovered && onRowClick ? var(--surface-hover) : transparent};
                 cursor:{onRowClick ? pointer : undefined};
                 transition:background-color var(--duration-fast) var(--ease-standard)">
        <td style="padding:{pad};text-align:{align};
                   border-bottom:{last row ? none : 1px solid var(--border-subtle)};
                   color:var(--ink-800);
                   font-family:{c.mono ? var(--font-mono) : inherit};
                   font-size:{c.mono ? var(--text-sm) : undefined};
                   vertical-align:middle">{c.render ? c.render(row) : row[c.key]}</td>…
      </tr>
    </tbody>
  </table>
</div>
```

Row key is `row.id || index`. Sorted chevron: `"desc"` → `chevron-down`, anything else → `chevron-up`.
Row hover is React state and only tints **when `onRowClick` is set** — reproduce as
`tbody tr:hover { background: var(--surface-hover) }` scoped to clickable tables.

Mockup usage: **none** — no `<x-import …DataTable>` in any of the 13 pages. The pricing/comparison
tables in the mockups are hand-written `<table>` markup. Port only if a template needs it.

---

## 10. MetricTile  (`components/data/MetricTile.jsx`, line 611)

```js
const DIRS = { up: { icon: "trending-up" }, down: { icon: "trending-down" }, flat: { icon: "minus" } };
```

| prop | default |
|---|---|
| `label` | — |
| `value` | — |
| `unit` | — |
| `delta` | — |
| `direction` | `"flat"` (`up|down|flat`; an unknown value throws — `DIRS[direction].icon`) |
| `tone` | `"neutral"` |
| `note` | — |
| `sparkline` | — (node, rendered between value row and delta row) |
| `style`, `...rest` | — |

`color = tone === "ok" ? var(--status-ok) : tone === "warning" ? var(--status-warning) : tone === "critical" ? var(--status-critical) : var(--ink-500)`
— this colours **only the delta**, nothing else.

```html
<div style="background:var(--surface-card);border:1px solid var(--border-card);border-radius:var(--radius-card);
            padding:var(--space-4);box-shadow:var(--shadow-xs);display:flex;flex-direction:column;gap:6px;min-width:0">
  <div style="font-size:var(--text-xs);color:var(--text-secondary);letter-spacing:var(--tracking-normal);line-height:1.4">{label}</div>
  <div style="display:flex;align-items:baseline;gap:5px;min-width:0">
    <span style="font-family:var(--font-mono);font-size:var(--text-2xl);font-weight:var(--weight-medium);
                 color:var(--text-primary);letter-spacing:-0.01em;line-height:1.1">{value}</span>
    [unit] <span style="font-size:var(--text-sm);color:var(--text-muted)">{unit}</span>
  </div>
  {sparkline}
  [delta || note] <div style="display:flex;align-items:center;gap:6px;font-size:var(--text-xs);color:var(--text-muted)">
    [delta] <span style="display:inline-flex;align-items:center;gap:3px;color:{color};font-family:var(--font-mono)">
              <svg …DIRS[direction].icon, 13px, stroke-width 2…></svg>{delta}</span>
    [note]  <span>{note}</span>
  </div>
</div>
```

Note the hard-coded `letter-spacing:-0.01em` on the value (equals `--tracking-snug`).

Mockup usage: 2, both in `01-main.dc.html` —
`label="Open incidents" value="7" delta="+2" direction="up" tone="warning"` and
`label="Unreconciled" value="4,812" delta="-311" direction="down" tone="ok"`.

---

## 11. SIGNALS  (`components/data/SignalBadge.jsx`, line 692) — exported constant

The fixed brand vocabulary. Do not remap (`readme.md`).

| key | label | icon | color | soft | border |
|---|---|---|---|---|---|
| `event` | `Event` | `activity` | `var(--signal-event)` | `var(--signal-event-soft)` | `var(--signal-event-line)` |
| `reconciliation` | `Reconciliation` | `scale` | `var(--signal-reconciliation)` | `var(--signal-reconciliation-soft)` | `var(--signal-reconciliation-line)` |
| `incident` | `Incident` | `alert-triangle` | `var(--signal-incident)` | `var(--signal-incident-soft)` | `var(--signal-incident-line)` |
| `pattern` | `Pattern` | `git-branch` | `var(--signal-pattern)` | `var(--signal-pattern-soft)` | `var(--signal-pattern-line)` |

---

## 12. SignalBadge  (line 724)

| prop | default |
|---|---|
| `type` | `"event"` (unknown → `event`) |
| `label` | — (falls back to `SIGNALS[type].label`) |
| `size` | `"md"` (`"sm"` is the only other value) |
| `showIcon` | `true` |
| `style`, `...rest` | — |

```html
<span style="display:inline-flex;align-items:center;gap:{sm?4:6}px;
             background:{s.soft};color:{s.color};border:1px solid {s.border};
             border-radius:var(--radius-badge);padding:{sm ? '1px 6px' : '3px 8px'};
             font-size:{sm ? var(--text-2xs) : var(--text-xs)};font-weight:var(--weight-medium);
             letter-spacing:var(--tracking-wide);text-transform:uppercase;line-height:1.6;white-space:nowrap">
  [showIcon] <svg …s.icon, size {sm?11:13}, stroke-width 2…></svg>
  {label || s.label}
</span>
```

| size | gap | padding | font-size | icon |
|---|---|---|---|---|
| `md` | 6px | `3px 8px` | `var(--text-xs)` | 13 |
| `sm` | 4px | `1px 6px` | `var(--text-2xs)` | 11 |

Mockup usage: 8 — `type` = event/reconciliation/incident/pattern, all default size except one
`size="sm"` (`type="incident"`). No custom `label`, `showIcon` never disabled.

---

## 13. SignalChain  (`components/data/SignalChain.jsx`, line 767)

`const ORDER = ["event", "reconciliation", "incident", "pattern"];`

| prop | default |
|---|---|
| `active` | — (falsy = **all four** active; otherwise only the matching key is active) |
| `captions` | `{}` — map keyed by signal key |
| `compact` | `false` |
| `onSelect` | — (adds `cursor:pointer` + click) |
| `style`, `...rest` | — |

```html
<div style="display:flex;align-items:stretch;gap:{compact ? var(--space-2) : var(--space-3)};flex-wrap:wrap">
  <!-- for each of the 4 keys, in ORDER: -->
  <div style="flex:1 1 0;min-width:{compact ? 132 : 168}px;
              background:{on ? s.soft : var(--surface-sunken)};
              border:1px solid {on ? s.border : var(--border-subtle)};
              border-top:3px solid {on ? s.color : var(--border-default)};
              border-radius:var(--radius-md);
              padding:{compact ? var(--space-3) : var(--space-4)};
              opacity:{on ? 1 : .55};
              cursor:{onSelect ? pointer : undefined};
              transition:var(--transition-surface), opacity var(--duration-normal) var(--ease-standard)">
    <div style="display:flex;align-items:center;gap:7px;color:{on ? s.color : var(--ink-500)}">
      <svg …s.icon, size {compact ? 15 : 17}, stroke-width 1.75…></svg>
      <span style="font-size:{compact ? var(--text-sm) : var(--text-md)};font-weight:var(--weight-semibold);
                   color:var(--ink-900);letter-spacing:var(--tracking-snug)">{s.label}</span>
    </div>
    [captions[k]] <div style="font-size:var(--text-sm);color:var(--ink-600);margin-top:6px;line-height:var(--leading-normal)">{captions[k]}</div>
  </div>
  <!-- separator after items 0,1,2 (not after the last): -->
  <div style="display:flex;align-items:center;color:var(--ink-300);flex:none">
    <svg …chevron-right, 18px, stroke-width 1.75…></svg>
  </div>
</div>
```

Note the card radius here is `var(--radius-md)` (5px), **not** `--radius-card`. The 3px top rule is
the same accent pattern as Card.

Mockup usage: 2 (`01-main`, `02-product`), both `captions="{{ chainCaptions }}"` with no `active`,
no `compact`, no `onSelect` → all four panels rendered "on".

---

## 14. Alert  (`components/feedback/Alert.jsx`, line 852)

```js
const ALERT_TONES = {  // [bg, border, fg, defaultIcon]
  info:     ["var(--status-info-soft)",     "var(--status-info-line)",     "var(--status-info)",     "info"],
  ok:       ["var(--status-ok-soft)",       "var(--status-ok-line)",       "var(--status-ok)",       "check-circle"],
  warning:  ["var(--status-warning-soft)",  "var(--status-warning-line)",  "var(--status-warning)",  "alert-triangle"],
  critical: ["var(--status-critical-soft)", "var(--status-critical-line)", "var(--status-critical)", "alert-octagon"],
  neutral:  ["var(--surface-sunken)",       "var(--border-default)",       "var(--ink-500)",         "info"]
};
```

| prop | default |
|---|---|
| `tone` | `"info"` (unknown → info) |
| `title` | — |
| `children` | — |
| `icon` | — (overrides the tone's default icon name) |
| `action` | — (node) |
| `onDismiss` | — (renders the ✕ button) |
| `style`, `...rest` | — |

```html
<div role="status" style="display:flex;gap:var(--space-3);background:{bg};border:1px solid {bd};
                          border-radius:var(--radius-md);padding:var(--space-3) var(--space-4)">
  <span style="color:{fg};margin-top:1px"><svg …icon||defIcon, 18px, stroke-width 1.75…></svg></span>
  <div style="flex:1;min-width:0">
    [title]    <div style="font-size:var(--text-md);font-weight:var(--weight-semibold);color:var(--ink-900);line-height:1.4">{title}</div>
    [children] <div style="font-size:var(--text-sm);color:var(--ink-700);line-height:var(--leading-normal);margin-top:{title ? 3px : 0}">{children}</div>
    [action]   <div style="margin-top:var(--space-3)">{action}</div>
  </div>
  [onDismiss] <button aria-label="Dismiss" type="button"
      style="border:0;background:none;cursor:pointer;color:var(--ink-500);padding:0;height:18px">
      <svg …x, 16px…></svg></button>
</div>
```

Mockup usage: 1 — `12-docs-article.dc.html`, `tone="info" title="Naming changed in v0.003"` with body
text as children; no dismiss, no action.

---

## 15. Dialog  (`components/feedback/Dialog.jsx`, line 928)

| prop | default |
|---|---|
| `open` | `true` (returns `null` when false) |
| `title` | — |
| `description` | — |
| `children` | — |
| `footer` | — |
| `onClose` | — (adds Escape handler, scrim click, and the close IconButton) |
| `width` | `520` → `max-width` |
| `style`, `...rest` | — |

```html
<div style="position:absolute;inset:0;z-index:80;display:grid;place-items:center;padding:var(--space-6)">
  <div style="position:absolute;inset:0;background:rgba(14,26,43,.38)"></div>   <!-- scrim, click = onClose -->
  <div role="dialog" aria-modal="true" aria-label="{title if string}"
       style="position:relative;width:100%;max-width:{width}px;background:var(--surface-card);
              border:1px solid var(--border-default);border-radius:var(--radius-lg);
              box-shadow:var(--shadow-overlay);overflow:hidden">
    <div style="display:flex;align-items:flex-start;gap:var(--space-4);padding:var(--space-5) var(--space-6) var(--space-4)">
      <div style="flex:1;min-width:0">
        <div style="font-size:var(--text-lg);font-weight:var(--weight-semibold);letter-spacing:var(--tracking-snug);color:var(--text-primary)">{title}</div>
        [description] <div style="font-size:var(--text-sm);color:var(--text-secondary);margin-top:4px;line-height:var(--leading-normal)">{description}</div>
      </div>
      [onClose] <IconButton icon="x" label="Close" size="sm"/>
    </div>
    [children] <div style="padding:0 var(--space-6) var(--space-5);font-size:var(--text-md);color:var(--ink-700)">{children}</div>
    [footer]   <div style="display:flex;justify-content:flex-end;gap:var(--space-2);padding:var(--space-4) var(--space-6);
                           border-top:1px solid var(--border-subtle);background:var(--surface-sunken)">{footer}</div>
  </div>
</div>
```

Positioning is `position:absolute` (scoped to the preview frame), **not** `fixed` — a real theme
dialog would need `position:fixed`. Scrim `rgba(14,26,43,.38)` is one of only two sanctioned
translucencies (`readme.md`). Mockup usage: none.

---

## 16. StatusDot  (`components/feedback/StatusDot.jsx`, line 1044)

```js
const COLORS = {
  ok: "var(--status-ok)", warning: "var(--status-warning)", critical: "var(--status-critical)",
  info: "var(--status-info)", neutral: "var(--status-neutral)",
  event: "var(--signal-event)", reconciliation: "var(--signal-reconciliation)",
  incident: "var(--signal-incident)", pattern: "var(--signal-pattern)"
};
```

| prop | default |
|---|---|
| `tone` | `"neutral"` (unknown → neutral) |
| `size` | `8` (px, applied to both width and height) |
| `label` | — |
| `style`, `...rest` | — |

Dot element (always):

```html
<span aria-hidden="true" style="width:{size}px;height:{size}px;border-radius:var(--radius-round);
                                background:{COLORS[tone]};flex:none;display:block"></span>
```

Wrapper without `label`: `<span style="display:inline-flex">{dot}</span>`
Wrapper with `label`: `<span style="display:inline-flex;align-items:center;gap:7px;font-size:var(--text-sm);color:var(--ink-700)">{dot}{label}</span>`

Mockup usage: none via `<x-import>` — the pages hand-roll the pulsing green "live" dot with the
`.itr-live-dot` / `.itr-live-halo` classes (`--green-500`) from each page's `<helmet>`.

---

## 17. Toast / ToastStack  (`components/feedback/Toast.jsx`, lines 1097 / 1169)

```js
const FG    = { info:"var(--status-info)", ok:"var(--status-ok)", warning:"var(--status-warning)", critical:"var(--status-critical)" };
const ICONS = { info:"info", ok:"check-circle", warning:"alert-triangle", critical:"alert-octagon" };
```

**Toast** — props `tone="info"`, `title`, `children`, `onDismiss`, `action`, `style`, `...rest`.

```html
<div role="status" style="display:flex;gap:var(--space-3);align-items:flex-start;background:var(--surface-card);
                          border:1px solid var(--border-default);border-radius:var(--radius-md);
                          box-shadow:var(--shadow-lg);padding:var(--space-3) var(--space-4);min-width:320px;max-width:420px">
  <span style="color:{FG[tone]};margin-top:1px"><svg …ICONS[tone], 18px…></svg></span>
  <div style="flex:1;min-width:0">
    <div style="font-size:var(--text-md);font-weight:var(--weight-medium);color:var(--ink-900);line-height:1.4">{title}</div>
    [children] <div style="font-size:var(--text-sm);color:var(--text-secondary);margin-top:2px;line-height:var(--leading-normal)">{children}</div>
    [action]   <div style="margin-top:var(--space-2)">{action}</div>
  </div>
  [onDismiss] <button aria-label="Dismiss" type="button" style="border:0;background:none;cursor:pointer;color:var(--ink-400);padding:0;height:18px"><svg …x,16…></svg></button>
</div>
```

**ToastStack** — props `children`, `style`, `...rest`:

```html
<div style="position:fixed;right:var(--space-6);bottom:var(--space-6);display:flex;flex-direction:column;gap:var(--space-2);z-index:60">…</div>
```

Mockup usage: none.

---

## 18. Tooltip  (`components/feedback/Tooltip.jsx`, line 1193)

| prop | default |
|---|---|
| `label` | — |
| `children` | — (the trigger) |
| `placement` | `"top"` (`top|bottom|left|right`) |
| `delay` | `120` (ms before showing) |
| `style`, `...rest` | — |

Trigger wrapper: `<span style="position:relative;display:inline-flex">{children}…</span>` with
`onMouseEnter`/`onMouseLeave`/`onFocus`/`onBlur`.

Bubble (only while open):

```html
<span role="tooltip" style="position:absolute;{placement offsets};z-index:70;background:var(--surface-inverse);
      color:var(--text-inverse);font-size:var(--text-xs);line-height:1.45;padding:5px 8px;
      border-radius:var(--radius-sm);white-space:nowrap;box-shadow:var(--shadow-md);pointer-events:none">{label}</span>
```

Placement offsets:

| placement | declarations |
|---|---|
| `top` | `bottom:calc(100% + 6px);left:50%;transform:translateX(-50%)` |
| `bottom` | `top:calc(100% + 6px);left:50%;transform:translateX(-50%)` |
| `left` | `right:calc(100% + 6px);top:50%;transform:translateY(-50%)` |
| `right` | `left:calc(100% + 6px);top:50%;transform:translateY(-50%)` |

Mockup usage: none.

---

## 19. Checkbox  (`components/forms/Checkbox.jsx`, line 1267)

| prop | default |
|---|---|
| `label` | — |
| `description` | — |
| `checked` | — |
| `indeterminate` | `false` |
| `disabled` | `false` |
| `onChange` | — |
| `id` | — (`<label for>` target) |
| `style`, `...rest` (spread onto the real `<input>`) | — |

`on = checked || indeterminate` drives the box colours.

```html
<label for="{id}" style="display:inline-flex;align-items:flex-start;gap:9px;
                         cursor:{disabled ? not-allowed : pointer};opacity:{disabled ? .5 : 1}">
  <input id="{id}" type="checkbox" checked disabled
         style="position:absolute;opacity:0;width:16px;height:16px;margin:0">
  <span aria-hidden="true" style="width:16px;height:16px;margin-top:2px;flex:none;display:grid;place-items:center;
        border-radius:var(--radius-xs);
        border:1px solid {on ? var(--action-primary) : var(--border-strong)};
        background:{on ? var(--action-primary) : var(--surface-card)};
        color:var(--white);transition:var(--transition-control)">
    <!-- indeterminate → Icon "minus" 12px stroke-width 3; else checked → Icon "check" 12px stroke-width 3; else empty -->
  </span>
  [label] <span style="min-width:0">
    <span style="display:block;font-size:var(--text-md);color:var(--ink-800);line-height:1.4">{label}</span>
    [description] <span style="display:block;font-size:var(--text-xs);color:var(--text-muted);margin-top:2px">{description}</span>
  </span>
</label>
```

The visually-hidden input uses `position:absolute` **without** a positioned ancestor — in the theme
prefer a proper `.screen-reader-text`-style hide, or make the `<label>` `position:relative`.

Mockup usage: 1 — `06-contact-request.dc.html`,
`label="I agree that INTERA may contact me about this request."`, no id, no description.

---

## 20. Field  (`components/forms/Field.jsx`, line 1351)

| prop | default |
|---|---|
| `label` | — |
| `htmlFor` | — |
| `hint` | — |
| `error` | — (**takes precedence over `hint`; they never both render**) |
| `required` | `false` |
| `children` | — (the control) |
| `style`, `...rest` | — |

```html
<div style="display:flex;flex-direction:column;gap:6px">
  [label] <label for="{htmlFor}" style="font-size:var(--text-sm);font-weight:var(--weight-medium);color:var(--ink-800);line-height:1.3">
            {label}[required]<span style="color:var(--status-critical);margin-left:3px">*</span>
          </label>
  {children}
  <!-- error wins: -->
  [error] <div style="font-size:var(--text-xs);color:var(--status-critical);line-height:var(--leading-normal)">{error}</div>
  [else hint] <div style="font-size:var(--text-xs);color:var(--text-muted);line-height:var(--leading-normal)">{hint}</div>
</div>
```

Mockup usage: 7, all in `06-contact-request.dc.html` —
`Name`(required, for=name), `Work email`(required, for=email), `Company`(for=company),
`Your role`(for=role, hint="The area you are responsible for."), `Industry`(for=industry),
`What brings you here`(for=interest), `The problem, in your words`(required, for=problem,
hint="What is checked manually today, which systems are involved, and what happens when it is
noticed too late."). No `error` state is exercised.

---

## 21. The `.itr-input` stylesheet  (Input/Select/Textarea, identical bodies) — verbatim

```css
.itr-input{width:100%;font-family:var(--font-sans);font-size:var(--text-md);color:var(--text-primary);background:var(--surface-card);border:1px solid var(--border-default);border-radius:var(--radius-control);padding:0 12px;height:var(--control-height-md);transition:var(--transition-control)}
.itr-input::placeholder{color:var(--text-muted)}
.itr-input:hover:not(:disabled){border-color:var(--border-strong)}
.itr-input:focus{outline:none;border-color:var(--focus-ring);box-shadow:var(--ring-focus)}
.itr-input:disabled{background:var(--surface-muted);color:var(--text-muted);cursor:not-allowed}
.itr-input--invalid{border-color:var(--status-critical)}
.itr-input--invalid:focus{box-shadow:var(--ring-danger);border-color:var(--status-critical)}
.itr-input--sm{height:var(--control-height-sm);font-size:var(--text-sm);padding:0 9px}
.itr-input--lg{height:var(--control-height-lg);font-size:var(--text-base);padding:0 14px}
.itr-input--area{height:auto;padding:9px 12px;line-height:var(--leading-normal);resize:vertical;min-height:88px}
.itr-input--mono{font-family:var(--font-mono);font-size:var(--text-sm)}
```

Size table: `sm` 28px / `var(--text-sm)` / `0 9px`; `md` (base rule) 36px / `var(--text-md)` / `0 12px`;
`lg` 44px / `var(--text-base)` / `0 14px`.
Note `.itr-input--md` is **not** defined — the base `.itr-input` rule *is* the md size, but the class
is still emitted by the component.

## 22. Input  (`components/forms/Input.jsx`, line 1420)

| prop | default |
|---|---|
| `size` | `"md"` |
| `invalid` | `false` |
| `mono` | `false` |
| `iconLeft` | — (Lucide name) |
| `className` | `""` |
| `style` | — |
| `...rest` | — (`type`, `id`, `placeholder`, `name`, … land on the `<input>`) |

```html
<input class="itr-input itr-input--{size}[ itr-input--invalid][ itr-input--mono] {className}"
       aria-invalid="true"            <!-- only when invalid -->
       style="[padding-left:34px when iconLeft]{...style}">
```

With `iconLeft` the input is wrapped:

```html
<span style="position:relative;display:block">
  <span style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-muted);pointer-events:none">
    <svg …iconLeft, 16px, stroke-width 1.75…></svg>
  </span>
  <input … style="padding-left:34px">
</span>
```

Mockup usage: 6 — four plain (`id` + `placeholder`, one with `type="email"`), plus the docs search
inputs `size="lg" icon-left="search"` (11-docs) and `size="sm" icon-left="search"` (12/13 docs sidebar).

## 23. Select  (`components/forms/Select.jsx`, line 1566)

| prop | default |
|---|---|
| `options` | `[]` — array of `string` **or** `{ value, label }` |
| `size` | `"md"` |
| `invalid` | `false` |
| `placeholder` | — (renders a leading `<option value="">`) |
| `className` | `""` |
| `style`, `...rest` | — |

```html
<span style="position:relative;display:block">
  <select class="itr-input itr-input--{size}[ itr-input--invalid] {className}"
          style="appearance:none;padding-right:32px;cursor:pointer{...style}">
    [placeholder] <option value="">{placeholder}</option>
    <option value="{v}">{l}</option>…
  </select>
  <span style="position:absolute;right:10px;top:50%;transform:translateY(-50%);color:var(--ink-500);pointer-events:none">
    <svg …chevron-down, 16px, stroke-width 1.75…></svg>
  </span>
</span>
```

Note: Select does **not** set `aria-invalid` (unlike Input/Textarea).

Mockup usage: 2 — `id="industry" options="{{ industries }}" placeholder="Choose an industry"` and
`id="interest" options="{{ interests }}" placeholder="Choose one"` (06-contact-request; the arrays
live in that page's `renderVals()`).

## 24. Textarea  (`components/forms/Textarea.jsx`, line 1709)

| prop | default |
|---|---|
| `invalid` | `false` |
| `rows` | `4` |
| `className` | `""` |
| `...rest` | — (no `size`, no `style` destructuring — `style` passes through `rest`) |

```html
<textarea rows="{rows}" class="itr-input itr-input--area[ itr-input--invalid] {className}" aria-invalid="true"></textarea>
```

Mockup usage: 1 — `id="problem" rows="6"` with a long placeholder (06-contact-request).

---

## 25. Radio  (`components/forms/Radio.jsx`, line 1463)

Props: `label`, `description`, `checked`, `disabled=false`, `onChange`, `name`, `value`, `id`, `style`, `...rest`.

```html
<label for="{id}" style="display:inline-flex;align-items:flex-start;gap:9px;cursor:{disabled?not-allowed:pointer};opacity:{disabled?.5:1}">
  <input id type="radio" name value checked disabled style="position:absolute;opacity:0;width:16px;height:16px;margin:0">
  <span aria-hidden="true" style="width:16px;height:16px;margin-top:2px;flex:none;border-radius:var(--radius-round);
        border:1px solid {checked ? var(--action-primary) : var(--border-strong)};background:var(--surface-card);
        display:grid;place-items:center;transition:var(--transition-control)">
    [checked] <span style="width:8px;height:8px;border-radius:var(--radius-round);background:var(--action-primary)"></span>
  </span>
  [label] <span style="min-width:0">
    <span style="display:block;font-size:var(--text-md);color:var(--ink-800);line-height:1.4">{label}</span>
    [description] <span style="display:block;font-size:var(--text-xs);color:var(--text-muted);margin-top:2px">{description}</span>
  </span>
</label>
```

Unlike Checkbox, the ring keeps `background: var(--surface-card)` when checked. Mockup usage: none.

## 26. Switch  (`components/forms/Switch.jsx`, line 1617)

Props: `checked=false`, `onChange`, `label`, `disabled=false`, `size="md"`, `id`, `style`, `...rest`.
Geometry: `w = size==="sm" ? 30 : 38`, `h = size==="sm" ? 18 : 22`, `k = h - 6` (knob).

```html
<label for="{id}" style="display:inline-flex;align-items:center;gap:10px;cursor:{disabled?not-allowed:pointer};opacity:{disabled?.5:1}">
  <input id type="checkbox" role="switch" checked disabled style="position:absolute;opacity:0;width:{w}px;height:{h}px;margin:0">
  <span aria-hidden="true" style="width:{w}px;height:{h}px;flex:none;border-radius:var(--radius-round);
        background:{checked ? var(--action-primary) : var(--ink-200)};position:relative;
        transition:background-color var(--duration-normal) var(--ease-standard)">
    <span style="position:absolute;top:3px;left:{checked ? w-k-3 : 3}px;width:{k}px;height:{k}px;
          border-radius:var(--radius-round);background:var(--white);box-shadow:var(--shadow-sm);
          transition:left var(--duration-normal) var(--ease-standard)"></span>
  </span>
  [label] <span style="font-size:var(--text-md);color:var(--ink-800)">{label}</span>
</label>
```

| size | track | knob | knob left (off → on) |
|---|---|---|---|
| `sm` | 30×18 | 12 | 3px → 15px |
| `md` | 38×22 | 16 | 3px → 19px |

Mockup usage: none.

## 27. Tabs  (`components/navigation/Tabs.jsx`, line 1727)

| prop | default |
|---|---|
| `items` | `[]` — `{ value, label, icon?, count? }` |
| `value` | — (the active `item.value`) |
| `onChange` | — |
| `variant` | `"underline"` (the other is anything else, treated as the "pill/segmented" form) |
| `size` | `"md"` (`"sm"` shrinks the font) |
| `style`, `...rest` | — |

```html
<div role="tablist" style="display:flex;align-items:center;
     gap:{underline ? var(--space-6) : 2px};
     border-bottom:{underline ? '1px solid var(--border-subtle)' : none};
     background:{underline ? transparent : var(--surface-muted)};
     border-radius:{underline ? 0 : var(--radius-md)};
     padding:{underline ? 0 : 3px}">
  <button role="tab" aria-selected="{active}" type="button"
    style="display:inline-flex;align-items:center;gap:7px;border:0;cursor:pointer;font-family:var(--font-sans);
           font-size:{size==='sm' ? var(--text-sm) : var(--text-md)};
           font-weight:{active ? var(--weight-semibold) : var(--weight-medium)};
           color:{active ? (underline ? var(--text-primary) : var(--ink-900)) : var(--ink-500)};
           background:{underline ? transparent : (active ? var(--surface-card) : transparent)};
           box-shadow:{!underline && active ? var(--shadow-xs) : none};
           border-radius:{underline ? 0 : var(--radius-sm)};
           padding:{underline ? '0 0 10px' : '5px 12px'};
           margin-bottom:{underline ? -1px : 0};
           border-bottom:{underline ? '2px solid ' + (active ? var(--action-primary) : transparent) : none};
           transition:var(--transition-control)">
    [item.icon] <svg …15px, stroke-width 1.75…></svg>
    {item.label}
    [item.count != null] <span style="font-family:var(--font-mono);font-size:var(--text-2xs);
        color:{active ? var(--ink-600) : var(--ink-400)};background:var(--surface-muted);
        border-radius:var(--radius-xs);padding:1px 4px">{item.count}</span>
  </button>…
</div>
```

Mockup usage: none — the mockups' filter rows use `Tag` chips instead.

---

## 28. Icon appendix — exact SVG bodies (Lucide 0.469.0)

Resolved by loading `https://unpkg.com/lucide@0.469.0/dist/umd/lucide.js` and reading
`lucide.icons[toPascal(name)]` exactly as `Icon` does. Every name below resolved; drop these children
inside the `<svg>` wrapper from §3 (`viewBox="0 0 24 24" fill="none" stroke="currentColor"
stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"
focusable="false"`), sized per call site.

The list covers every name referenced by the mockups **and** every name hard-coded inside the
components (`activity`, `scale`, `alert-triangle`, `git-branch`, `check`, `minus`, `x`, `info`,
`check-circle`, `alert-octagon`, `trending-up`, `trending-down`, `chevron-up`, `chevron-down`,
`chevron-right`, `menu`, `search`).

| name | Lucide export | children |
|---|---|---|
| `activity` | `Activity` | `<path d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2" />` |
| `alert-octagon` | `AlertOctagon` | `<path d="M12 16h.01" /><path d="M12 8v4" /><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z" />` |
| `alert-triangle` | `AlertTriangle` | `<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" /><path d="M12 9v4" /><path d="M12 17h.01" />` |
| `arrow-right` | `ArrowRight` | `<path d="M5 12h14" /><path d="m12 5 7 7-7 7" />` |
| `bell` | `Bell` | `<path d="M10.268 21a2 2 0 0 0 3.464 0" /><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />` |
| `book-open` | `BookOpen` | `<path d="M12 7v14" /><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z" />` |
| `boxes` | `Boxes` | `<path d="M2.97 12.92A2 2 0 0 0 2 14.63v3.24a2 2 0 0 0 .97 1.71l3 1.8a2 2 0 0 0 2.06 0L12 19v-5.5l-5-3-4.03 2.42Z" /><path d="m7 16.5-4.74-2.85" /><path d="m7 16.5 5-3" /><path d="M7 16.5v5.17" /><path d="M12 13.5V19l3.97 2.38a2 2 0 0 0 2.06 0l3-1.8a2 2 0 0 0 .97-1.71v-3.24a2 2 0 0 0-.97-1.71L17 10.5l-5 3Z" /><path d="m17 16.5-5-3" /><path d="m17 16.5 4.74-2.85" /><path d="M17 16.5v5.17" /><path d="M7.97 4.42A2 2 0 0 0 7 6.13v4.37l5 3 5-3V6.13a2 2 0 0 0-.97-1.71l-3-1.8a2 2 0 0 0-2.06 0l-3 1.8Z" /><path d="M12 8 7.26 5.15" /><path d="m12 8 4.74-2.85" /><path d="M12 13.5V8" />` |
| `check` | `Check` | `<path d="M20 6 9 17l-5-5" />` |
| `check-circle` | `CheckCircle` | `<path d="M21.801 10A10 10 0 1 1 17 3.335" /><path d="m9 11 3 3L22 4" />` |
| `chevron-down` | `ChevronDown` | `<path d="m6 9 6 6 6-6" />` |
| `chevron-left` | `ChevronLeft` | `<path d="m15 18-6-6 6-6" />` |
| `chevron-right` | `ChevronRight` | `<path d="m9 18 6-6-6-6" />` |
| `chevron-up` | `ChevronUp` | `<path d="m18 15-6-6-6 6" />` |
| `circle-dollar-sign` | `CircleDollarSign` | `<circle cx="12" cy="12" r="10" /><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" /><path d="M12 18V6" />` |
| `circle-dot` | `CircleDot` | `<circle cx="12" cy="12" r="10" /><circle cx="12" cy="12" r="1" />` |
| `clipboard-check` | `ClipboardCheck` | `<rect width="8" height="4" x="8" y="2" rx="1" ry="1" /><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" /><path d="m9 14 2 2 4-4" />` |
| `clock` | `Clock` | `<circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" />` |
| `contact` | `Contact` | `<path d="M16 2v2" /><path d="M7 22v-2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2" /><path d="M8 2v2" /><circle cx="12" cy="11" r="3" /><rect x="3" y="4" width="18" height="18" rx="2" />` |
| `corner-down-right` | `CornerDownRight` | `<polyline points="15 10 20 15 15 20" /><path d="M4 4v7a4 4 0 0 0 4 4h12" />` |
| `database` | `Database` | `<ellipse cx="12" cy="5" rx="9" ry="3" /><path d="M3 5V19A9 3 0 0 0 21 19V5" /><path d="M3 12A9 3 0 0 0 21 12" />` |
| `eye` | `Eye` | `<path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" /><circle cx="12" cy="12" r="3" />` |
| `gauge` | `Gauge` | `<path d="m12 14 4-4" /><path d="M3.34 19a10 10 0 1 1 17.32 0" />` |
| `git-branch` | `GitBranch` | `<line x1="6" x2="6" y1="3" y2="15" /><circle cx="18" cy="6" r="3" /><circle cx="6" cy="18" r="3" /><path d="M18 9a9 9 0 0 1-9 9" />` |
| `globe` | `Globe` | `<circle cx="12" cy="12" r="10" /><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" /><path d="M2 12h20" />` |
| `heart-pulse` | `HeartPulse` | `<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z" /><path d="M3.22 12H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27" />` |
| `info` | `Info` | `<circle cx="12" cy="12" r="10" /><path d="M12 16v-4" /><path d="M12 8h.01" />` |
| `landmark` | `Landmark` | `<line x1="3" x2="21" y1="22" y2="22" /><line x1="6" x2="6" y1="18" y2="11" /><line x1="10" x2="10" y1="18" y2="11" /><line x1="14" x2="14" y1="18" y2="11" /><line x1="18" x2="18" y1="18" y2="11" /><polygon points="12 2 20 7 4 7" />` |
| `layers` | `Layers` | `<path d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83z" /><path d="M2 12a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 12" /><path d="M2 17a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 17" />` |
| `link` | `Link` | `<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" /><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />` |
| `lock` | `Lock` | `<rect width="18" height="11" x="3" y="11" rx="2" ry="2" /><path d="M7 11V7a5 5 0 0 1 10 0v4" />` |
| `mail` | `Mail` | `<rect width="20" height="16" x="2" y="4" rx="2" /><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />` |
| `menu` | `Menu` | `<line x1="4" x2="20" y1="12" y2="12" /><line x1="4" x2="20" y1="6" y2="6" /><line x1="4" x2="20" y1="18" y2="18" />` |
| `minus` | `Minus` | `<path d="M5 12h14" />` |
| `package` | `Package` | `<path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" /><path d="M12 22V12" /><path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7" /><path d="m7.5 4.27 9 5.15" />` |
| `plug` | `Plug` | `<path d="M12 22v-5" /><path d="M9 8V2" /><path d="M15 8V2" /><path d="M18 8v5a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4V8Z" />` |
| `printer` | `Printer` | `<path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" /><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6" /><rect x="6" y="14" width="12" height="8" rx="1" />` |
| `radio-tower` | `RadioTower` | `<path d="M4.9 16.1C1 12.2 1 5.8 4.9 1.9" /><path d="M7.8 4.7a6.14 6.14 0 0 0-.8 7.5" /><circle cx="12" cy="9" r="2" /><path d="M16.2 4.8c2 2 2.26 5.11.8 7.47" /><path d="M19.1 1.9a9.96 9.96 0 0 1 0 14.1" /><path d="M9.5 18h5" /><path d="m8 22 4-11 4 11" />` |
| `receipt` | `Receipt` | `<path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" /><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" /><path d="M12 17.5v-11" />` |
| `repeat` | `Repeat` | `<path d="m17 2 4 4-4 4" /><path d="M3 11v-1a4 4 0 0 1 4-4h14" /><path d="m7 22-4-4 4-4" /><path d="M21 13v1a4 4 0 0 1-4 4H3" />` |
| `rocket` | `Rocket` | `<path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z" /><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z" /><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0" /><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5" />` |
| `route-off` | `RouteOff` | `<circle cx="6" cy="19" r="3" /><path d="M9 19h8.5c.4 0 .9-.1 1.3-.2" /><path d="M5.2 5.2A3.5 3.53 0 0 0 6.5 12H12" /><path d="m2 2 20 20" /><path d="M21 15.3a3.5 3.5 0 0 0-3.3-3.3" /><path d="M15 5h-4.3" /><circle cx="18" cy="5" r="3" />` |
| `scale` | `Scale` | `<path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z" /><path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z" /><path d="M7 21h10" /><path d="M12 3v18" /><path d="M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2" />` |
| `search` | `Search` | `<circle cx="11" cy="11" r="8" /><path d="m21 21-4.3-4.3" />` |
| `settings` | `Settings` | `<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" /><circle cx="12" cy="12" r="3" />` |
| `shield-check` | `ShieldCheck` | `<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" /><path d="m9 12 2 2 4-4" />` |
| `ship` | `Ship` | `<path d="M12 10.189V14" /><path d="M12 2v3" /><path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6" /><path d="M19.38 20A11.6 11.6 0 0 0 21 14l-8.188-3.639a2 2 0 0 0-1.624 0L3 14a11.6 11.6 0 0 0 2.81 7.76" /><path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1s1.2 1 2.5 1c2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1" />` |
| `sliders-horizontal` | `SlidersHorizontal` | `<line x1="21" x2="14" y1="4" y2="4" /><line x1="10" x2="3" y1="4" y2="4" /><line x1="21" x2="12" y1="12" y2="12" /><line x1="8" x2="3" y1="12" y2="12" /><line x1="21" x2="16" y1="20" y2="20" /><line x1="12" x2="3" y1="20" y2="20" /><line x1="14" x2="14" y1="2" y2="6" /><line x1="8" x2="8" y1="10" y2="14" /><line x1="16" x2="16" y1="18" y2="22" />` |
| `table-2` | `Table2` | `<path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18" />` |
| `terminal` | `Terminal` | `<polyline points="4 17 10 11 4 5" /><line x1="12" x2="20" y1="19" y2="19" />` |
| `thumbs-down` | `ThumbsDown` | `<path d="M17 14V2" /><path d="M9 18.12 10 14H4.17a2 2 0 0 1-1.92-2.56l2.33-8A2 2 0 0 1 6.5 2H20a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.76a2 2 0 0 0-1.79 1.11L12 22a3.13 3.13 0 0 1-3-3.88Z" />` |
| `thumbs-up` | `ThumbsUp` | `<path d="M7 10v12" /><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2a3.13 3.13 0 0 1 3 3.88Z" />` |
| `trending-down` | `TrendingDown` | `<polyline points="22 17 13.5 8.5 8.5 13.5 2 7" /><polyline points="16 17 22 17 22 11" />` |
| `trending-up` | `TrendingUp` | `<polyline points="22 7 13.5 15.5 8.5 10.5 2 17" /><polyline points="16 7 22 7 22 13" />` |
| `x` | `X` | `<path d="M18 6 6 18" /><path d="m6 6 12 12" />` |

---

## 29. What the 13 mockups actually instantiate (build priority)

Counted across `01`–`13`, `site-nav`, `site-footer` (`00-templates` excluded from the per-prop notes
above where it is only an index page, but included in these totals).

| component | call sites | needed for the theme |
|---|---|---|
| `Icon` | 86 | yes — inline SVG partial |
| `Card` | 51 | yes |
| `Button` | 46 | yes |
| `Tag` | 31 | yes |
| `Badge` | 20 | yes (tones `accent`, `info` only) |
| `SignalBadge` | 8 | yes |
| `Field` | 7 | yes (form page) |
| `Input` | 6 | yes |
| `Logo` | 3 | yes (header, footer) |
| `SignalChain` | 2 | yes |
| `Select` | 2 | yes |
| `MetricTile` | 2 | yes |
| `CardHeader` | 2 | yes |
| `Textarea` | 1 | yes |
| `IconButton` | 1 | yes (mobile nav toggle) |
| `Checkbox` | 1 | yes |
| `Alert` | 1 | yes (docs article notice) |
| `DataTable`, `Dialog`, `StatusDot`, `Toast`, `ToastStack`, `Tooltip`, `Radio`, `Switch`, `Tabs` | 0 | not required |

Variant/size combos actually needed:

- **Button** — `primary|secondary|ghost|link|inverse|outlineInverse` × `sm|md|lg`, plus `block`,
  `disabled` (sm/secondary only), `iconLeft`, `iconRight`, `href` (→ `<a>`) and two `<button>` forms
  with `onClick`. `danger` is unused.
- **Card** — `padding` default/loose, `elevated`, `interactive`, and the six accent pairs listed in §1.
- **Badge** — `accent`, `info`. **Tag** — default + `selected`. **SignalBadge** — 4 types, `md` + one `sm`.
- **Input** — `md` default, `sm`/`lg` with `iconLeft="search"`. **Textarea** — `rows="6"`.
- **Logo** — horizontal, `size` 18 (ink and inverse) and 26.

---

## 30. Gotchas for the PHP port

1. **Hover states that live in JS, not CSS** — `Card` (interactive), `DataTable` (rows), `Tooltip`,
   `Icon`'s lucide poll. Reimplement in `assets/css/intera.css`, keyed by class. Everything else the
   mockups need is already real CSS (`.itr-btn*`, `.itr-iconbtn*`, `.itr-input*`) and can be pasted verbatim.
2. **The three injected stylesheets must ship as static CSS.** `.itr-input` is declared three times
   identically; ship it once.
3. **`hint-size` is not a prop.** Never emit it. `style-hover` is preview-only and never reaches a DS
   component.
4. **Link buttons need the `a:not(.itr-btn)` reset** from §5.3, otherwise `tokens/base.css` paints a
   blue underline border on every button rendered as `<a>`.
5. **`class` vs `className`** — mockups pass `class="itr-lift"`/`itr-hl` to Card; the resulting DOM has
   that class. In PHP just add the class.
6. **The trailing space** in `Button`/`Input`/`Select`/`Textarea` class strings is cosmetic; harmless
   to drop, but `itr-input--md` must still be emitted even though no such rule exists.
7. **Accessibility fixes worth making while porting:** `Logo variant="mark"` has no accessible name;
   the hidden `<input>` in Checkbox/Radio/Switch uses `position:absolute` without a positioned
   ancestor; `Select` omits `aria-invalid`; `Dialog` uses `position:absolute` rather than `fixed`.
8. **Escaping** — every one of these components interpolates caller text (`label`, `title`,
   `children`, `delta`, `value`, `emptyMessage`). In PHP each is an `esc_html`/`wp_kses_post` site.
9. **`Logo size` is a string in the mockups** — write the wordmark `font-size` in px explicitly (§7.3).
10. **Icons:** do not ship the Lucide CDN. Inline the 54 bodies in §28 through one
   `template-parts/icon.php` that reproduces the §3 wrapper (`stroke-width` default `1.75`,
   overrides 2 / 2.2 / 3 where listed).

---

## 31. Token reference (quoted from `_ds/intera/tokens/*.css`)

Only the tokens these components reference, for sanity-checking the port. **Do not copy values into
PHP or `intera.css`** — always reference `var(--…)`; the token files are the single source of truth.

`tokens/spacing.css`: `--space-2:8px` `--space-3:12px` `--space-4:16px` `--space-5:20px`
`--space-6:24px` `--space-7:32px` `--space-8:40px` · `--pad-card:var(--space-6)`
`--pad-card-compact:var(--space-4)` · `--control-height-sm:28px` `--control-height-md:36px`
`--control-height-lg:44px`

`tokens/radius.css`: `--radius-xs:2px` `--radius-sm:3px` `--radius-md:5px` `--radius-lg:8px`
`--radius-round:999px` · `--radius-control:var(--radius-md)` `--radius-card:var(--radius-lg)`
`--radius-badge:var(--radius-sm)`

`tokens/typography.css`: `--text-2xs:11px` `--text-xs:12px` `--text-sm:13px` `--text-md:15px`
`--text-base:16px` `--text-lg:18px` `--text-2xl:25px` · `--weight-medium:500` `--weight-semibold:600`
· `--leading-normal:1.45` · `--tracking-snug:-0.01em` `--tracking-normal:0` `--tracking-wide:0.04em`
· `--font-sans:"IBM Plex Sans",…` `--font-mono:"IBM Plex Mono",…`

`tokens/elevation.css`: `--shadow-xs:0 1px 1px rgba(14,26,43,.04)` ·
`--shadow-sm` · `--shadow-md` · `--shadow-lg` · `--shadow-overlay:0 24px 56px rgba(7,16,28,.18)` ·
`--ring-focus:0 0 0 3px rgba(46,107,240,.28)` · `--ring-danger:0 0 0 3px rgba(224,75,66,.24)`

`tokens/motion.css`: `--duration-fast:120ms` `--duration-normal:180ms` ·
`--ease-standard:cubic-bezier(.2,.6,.25,1)` ·
`--transition-control:background-color … ,border-color … ,color … ,box-shadow …` ·
`--transition-surface:background-color var(--duration-normal) var(--ease-standard),box-shadow var(--duration-normal) var(--ease-standard)`

`tokens/colors.css` semantic aliases used above: `--text-primary` `--text-secondary` `--text-muted`
`--text-inverse` `--text-link` `--text-link-hover` `--text-on-accent` · `--surface-card`
`--surface-sunken` `--surface-muted` `--surface-inverse` `--surface-hover` `--surface-active` ·
`--border-hairline` `--border-subtle` `--border-default` `--border-strong` `--border-card`
`--border-inverse` · `--action-primary` `--action-primary-hover` `--action-primary-active`
`--action-secondary-border` `--focus-ring` · `--signal-{event,reconciliation,incident,pattern}`
(+ `-soft`, `-line`) · `--status-{ok,warning,critical,info,neutral}` (+ `-soft`, `-line`) ·
raw ramps referenced directly by components: `--white` `--ink-{100,200,300,400,500,600,700,800,900}`
`--blue-{50,200,500,700}` `--green-700` `--amber-700` `--red-700` `--violet-{50,200,700}`.

Literal (non-token) colours that appear inside components — reproduce verbatim:
`rgba(255,255,255,.34)` / `.09` / `.6` / `.15` (Button `outlineInverse`), `rgba(255,255,255,.7)`
(Logo suffix, inverse), `#FFFFFF` (Logo mark/square), `rgba(14,26,43,.38)` (Dialog scrim).
