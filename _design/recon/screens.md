# INTERA — screen inventory (recon)

Source: `/home/user/intera-roles/_design/` (15 mockups: 13 numbered pages + `site-nav` + `site-footer`;
`00-templates.dc.html` is the index card page, not a theme template).

Everything below is quoted verbatim from the files. Nothing is estimated.

---

## 0. Conventions that apply to every mockup

### Custom elements to resolve

| Element | Meaning | What the theme does |
| --- | --- | --- |
| `<x-import component-from-global-scope="INTERADesignSystem_430dc5.NAME" …>` | A React DS component from `_ds_bundle.js` | Re-implement as PHP partial / CSS class. Bundle is the reference implementation, never shipped. |
| `<dc-import name="site-nav">` / `<dc-import name="site-footer">` | Shared partial | `get_header()` / `get_footer()` |
| `<sc-if value="{{ x }}" hint-placeholder-val="{{ … }}">` | Conditional branch driven by `renderVals()` | PHP `if` or CSS/JS state |
| `style-hover="…"` | **Preview-only attribute, not CSS** | Must be re-implemented as a real `:hover` rule in `assets/css/intera.css`, keyed by a class |
| `hint-size="…"` | Preview placeholder sizing hint | Drop entirely |
| `dc-props="{{ lift }}"` etc. | Extra props merged into the DS component | Becomes a class / inline style on the PHP partial |

### The `<helmet>` block (near-identical in all 15 files)

Loads the 8 token sheets + `_ds/intera/styles.css` + `mobile.css`, Lucide 0.469.0 from unpkg, the DS
bundle, then an inline `<style>`. That style block is **identical across all 15 files apart from a
handful of per-page reset lines** (six distinct variants; the `.itr-*` interaction vocabulary below is
byte-identical everywhere):

| Variant | Files | Difference from the `01-main` baseline |
| --- | --- | --- |
| baseline | `01-main`, `02-product`, `08-blog` | `body { margin: 0; font-family: var(--font-sans); color: var(--text-primary); background: var(--surface-page); -webkit-font-smoothing: antialiased; }` + `h1, h2, h3, h4, p { margin: 0; }` + `img { max-width: 100%; }`. All 13 numbered pages share this `body` rule. |
| tables | `03-pricing` | `img` rule replaced by `table { border-collapse: collapse; width: 100%; }` |
| accordion | `04-faq` | `img` rule replaced by 5 `summary` rules — see below |
| no-img | `05-contacts`, `06-contact-request`, `10-blog-category`, `11-docs`, `13-docs-category` | `img` rule dropped |
| prose | `07-policy`, `09-blog-post` | `h1, h2, h3, h4, p, ul { margin: 0; }` + `ul { padding-left: 20px; }`, no `img` rule |
| prose + code | `12-docs-article` | as *prose*, plus `code { font-family: var(--font-mono); font-size: var(--text-sm); background: var(--surface-muted); border: 1px solid var(--border-hairline); border-radius: var(--radius-sm); padding: 1px 5px; }` |
| partials | `site-nav`, `site-footer`, `00-templates` | shortened `body` rule (no `background` / no smoothing; `site-footer` also drops `color`), no `h*`/`img` resets. `00-templates` alone uses `background: var(--surface-sunken)`. Nothing here needs porting. |

The `04-faq` accordion rules, verbatim — these carry the chevron rotation and must be ported:
```
summary { list-style: none; cursor: pointer; transition: color 140ms cubic-bezier(.2,.6,.25,1); }
summary:hover { color: var(--blue-600); }
summary::-webkit-details-marker { display: none; }
details[open] summary svg { transform: rotate(180deg); }
summary svg { transition: transform 160ms cubic-bezier(.2,.6,.25,1); }
```

Shared interaction classes defined there — all must move into `theme/assets/css/intera.css`:

| Class | Hover behaviour (exact) |
| --- | --- |
| `.itr-lift` | `translateY(-5px)`, `box-shadow: 0 18px 34px -18px rgba(14,26,43,.26), 0 2px 6px -2px rgba(14,26,43,.10)`, border-right/bottom/left → `var(--border-strong)` |
| `.itr-row` | `translateX(8px)`, `background: var(--white)`, `border-color: var(--blue-200)`, `box-shadow: 0 10px 22px -14px rgba(14,26,43,.28)` |
| `.itr-tile` | `translateY(-4px)`, `background: var(--blue-50)`, `border-color: var(--blue-200)`, `box-shadow: 0 12px 24px -16px rgba(26,79,214,.35)` |
| `.itr-panel` | `translateY(-4px)`, `background: rgba(255,255,255,.085)`, `border-color: rgba(255,255,255,.28)` |
| `.itr-frame` | `translateY(-6px)`, `box-shadow: 0 34px 64px -30px rgba(14,26,43,.42), 0 4px 10px -4px rgba(14,26,43,.14)` |
| `.itr-hl` | no transform — shadow `0 10px 24px -18px rgba(14,26,43,.30)` + border-strong on right/bottom/left |
| `.itr-hl-panel` | dark-band variant of `.itr-hl` (background + border only) |
| `.itr-live-dot` / `.itr-live-halo` | `@keyframes itr-live` (opacity 1 → .28) and `itr-halo` (scale 1 → 2.6, opacity .5 → 0), both `1.9s cubic-bezier(.2,.6,.25,1) infinite` |
| `.itr-btn`, `.itr-btn--primary/secondary/ghost/inverse/outline/outlineInverse/danger` | link-reset rules so DS buttons rendered as `<a>` keep their colour on hover |
| nesting guard | `.itr-lift:has(.itr-row:hover), .itr-lift:has(.itr-tile:hover), .itr-lift:has(.itr-lift:hover) { transform: none !important; }` |
| reduced motion | `@media (prefers-reduced-motion: reduce)` kills all animations and hover transforms — copy verbatim |

### `mobile.css` classes used as hooks in markup

`.itr-cols-4` (footer grid 4→2→1), `.itr-1col` (two-col split collapse), `.itr-float` (floating hero
card becomes a following block), `.itr-stagger` (stepped source rows go flush), `.itr-shot`
(screenshot crop height 420/440/360px → 220px, `img width: 680px`), `.itr-scroll-x` (pricing table
scrolls, `min-width: 480px`). Breakpoints **900px** and **760px**, plus `@media (hover: none)`.
CLAUDE.md says rewrite without `!important`, same breakpoints and behaviour.

### DS component → internal Lucide icons (from `_ds_bundle.js`)

Icons never named in markup but rendered by components — the theme must inline these too:

- `SIGNALS` map (drives `SignalBadge` + `SignalChain`):
  `event` → `activity` / `var(--signal-event)`; `reconciliation` → `scale` / `var(--signal-reconciliation)`;
  `incident` → `alert-triangle` / `var(--signal-incident)`; `pattern` → `git-branch` / `var(--signal-pattern)`
- `MetricTile` `DIRS`: `up` → `trending-up`, `down` → `trending-down`, `flat` → `minus`
- `Alert` `ALERT_TONES` default icons: `info` → `info`, `ok` → `check-circle`, `warning` → `alert-triangle`,
  `critical` → `alert-octagon`, `neutral` → `info`; dismiss button → `x`
- `Select` → `chevron-down`; `Checkbox` → `check` (and `minus` for indeterminate)

### Global link-graph facts

- Every page's breadcrumb first crumb is `href="01-main.dc.html"` → `home_url('/')`.
- Every CTA that says "Bring us a real problem" / "Get Early Access" / "Become an Early Adopter" /
  "Apply as Early Adopter" points at `06-contact-request.dc.html`.
- `mailto:sb@by-sky.net` appears in `site-footer`, `05-contacts`, `06-contact-request`, `07-policy`.
- All 13 numbered pages import both partials; `site-nav`/`site-footer`/`00-templates` import none.

### Images referenced (all must come from the media library, not hardcoded)

| File | Used in | Frame caption / alt |
| --- | --- | --- |
| `assets/shots/ship-5.webp` | `01-main` Hero | caption `Fleet Health Overview · Shipmanagement`; alt `INTERA role view: which vessels need attention`; `.itr-shot` height `420px` |
| `assets/shots/ship-2.webp` | `01-main` In action | caption `Attention Queue · what to work on first`; alt `INTERA attention queue, ranked by priority and time to impact`; height `420px` |
| `assets/shots/ship-4.webp` | `01-main` Working with IT | caption `Dependencies · vendors, parts, external commitments`; alt `INTERA dependency queue with vendors and delays`; height `440px` |
| `assets/shots/ship-3.webp` | `02-product` Pattern Studio | caption `Role view · readiness and upcoming dates`; alt `INTERA role view with readiness metrics and upcoming dates`; height `420px` |
| `assets/shots/ship-2.webp` | `09-blog-post` body | caption `Attention queue · ranked by priority and time to impact`; alt `Attention queue with priorities and impact dates`; height `360px` |

`assets/shots/ship-1.png` and `telecom-1.png` are **not referenced by any mockup**.
`assets/logo/*.svg` (5 files) are already copied into `theme/assets/img/logo/`.

---

## 1. `site-nav.dc.html` → `header.php`

**Sections:** none (`data-screen-label` count = 0). One `<header>`, fixed, `z-index: 60`,
`background: rgba(255,255,255,.92)`, `backdrop-filter: blur(8px)`,
`border-bottom: 1px solid var(--border-subtle)`; inner bar `max-width: 1160px; padding: 0 24px; height: 76px`.
Every page follows the import with a `<div style="height: 76px"></div>` spacer.

**DS components (5):**

| Component | Exact props |
| --- | --- |
| `Logo` | `size="26"` (wrapped in `<a href="01-main.dc.html">`) |
| `Badge` | `tone="info"` → text `Beta` |
| `Button` | `size="lg" href="06-contact-request.dc.html"` → `Get Early Access` (wide bar) |
| `Button` | `size="md" href="06-contact-request.dc.html"` → `Get Early Access` (narrow bar) |
| `IconButton` | `icon="{{ burgerIcon }}" label="Menu" variant="outline" onClick="{{ toggle }}"` |

**Lucide icons:** `menu` and `x`, supplied at runtime via `burgerIcon: this.state.open ? "x" : "menu"`.

**dc-imports:** none.

**Dynamic slots:**
- The whole nav list (6 links) → `wp_nav_menu( [ 'theme_location' => 'primary' ] )`. Items in order:
  `Product` → `02-product.dc.html`, `Pricing` → `03-pricing.dc.html`, `FAQ` → `04-faq.dc.html`,
  `Docs` → `11-docs.dc.html`, `Blog` → `08-blog.dc.html`, `Contacts` → `05-contacts.dc.html`.
- Logo link → `home_url('/')`; logo SVG is theme chrome (`assets/img/logo/logo-horizontal.svg`), stays fixed.
- The mobile panel repeats the same 6 items at `var(--text-lg)` with `padding: 14px 0` and hairline
  separators (last item has no border) — same menu, second render.

**Fixed marketing copy:** the `Beta` badge and both `Get Early Access` button labels.

**Interactive behaviour:**
- `state = { wide: true, open: false }`; a `resize` listener sets `wide = window.innerWidth >= 1040`
  and force-closes the panel when wide. **The 1040px switch is JS, not a media query** — in the theme
  it should become a CSS breakpoint at 1040px plus a JS toggle for `open`.
- `renderVals()` exposes `wide`, `narrow: !wide`, `open: !wide && open`, `burgerIcon`, `toggle`.
- Hover on nav links: `style-hover="color: var(--ink-900)"` from base `var(--ink-600)`.

**Hardcoded links → WP URLs:** all 12 hrefs above (6 desktop + 6 mobile) plus the two CTA hrefs and
the logo href.

---

## 2. `site-footer.dc.html` → `footer.php`

**Sections:** none. `<footer>` on `var(--ink-900)`, text `rgba(255,255,255,.72)`; a
`class="itr-cols-4"` grid `1.4fr 1fr 1fr 1fr`, gap `40px`, then a legal strip separated by
`border-top: 1px solid var(--border-inverse)`.

**DS components (2):** `Logo` with `size="18" tone="inverse"`; `Button` with
`variant="inverse" href="06-contact-request.dc.html"` → `Become an Early Adopter`.

**Lucide icons:** none. **dc-imports:** none.

**Column contents (all hardcoded hrefs):**

| Column heading | Links (label → href) |
| --- | --- |
| *(brand)* | tagline `One operating picture across the systems your teams already use.` + CTA |
| `Product` | `Product overview` → `02-product.dc.html`; `Pricing` → `03-pricing.dc.html`; then a hairline group: `INTERA Roles` → `02-product.dc.html#roles`; `Working with existing IT` → `02-product.dc.html#it`; `INTERA Method` → `02-product.dc.html#method`; `Partners and resellers` → `02-product.dc.html#partners` |
| `Resources` | `Documentation` → `11-docs.dc.html`; `Blog` → `08-blog.dc.html`; `FAQ` → `04-faq.dc.html`; then: `Life stories` → `10-blog-category.dc.html`; `Release information` → `10-blog-category.dc.html` |
| `Company` | `Contacts` → `05-contacts.dc.html`; then: `Privacy policy` → `07-policy.dc.html`; `Cookie policy` → `07-policy.dc.html#cookies`; `License Agreement` → `07-policy.dc.html` |

**Legal strip:** `© 2026 INTERA. In beta — Early Adopter programme open.` and a mono span
`intera-roles.com` + `mailto:sb@by-sky.net`.

**Dynamic slots:**
- The four link columns are **the natural second/third menu locations** — but README only mandates one
  menu area, and repo `inc/setup.php` already registers `primary` and `footer_legal`. The Product /
  Resources / Company columns are candidates for further locations (see §17 open questions).
- `Life stories` / `Release information` → `get_category_link()` for the two blog categories.
- Copyright year `© 2026` → either static per the handoff or `date('Y')`.
- Email address `sb@by-sky.net` and domain `intera-roles.com` are site-identity strings, not post data.

**Fixed marketing copy:** tagline, CTA label, all column headings, the legal sentence.

**Interactive behaviour:** hover only — `style-hover="color: var(--white)"` on every link.

---

## 3. `01-main.dc.html` → `front-page.php`

**Sections in order (11):**

| # | `data-screen-label` | `id` | Background |
| --- | --- | --- | --- |
| 1 | `Hero` | — | `var(--ink-950)` + 5 vertical hairlines, two radial washes (`--wash-blue-dark`, `--wash-teal-dark`), decorative SVG squares |
| 2 | `Problem` | — | `var(--surface-sunken)` + `--wash-amber` wash |
| 3 | `How it works` | `how` | `var(--surface-page)` |
| 4 | `Champion` | — | `var(--surface-sunken)` + `--wash-teal` |
| 5 | `In action` | `action` | `var(--surface-page)` |
| 6 | `Roles` | `roles` | `var(--surface-sunken)` + `--wash-violet` |
| 7 | `Working with IT` | `it` | `var(--surface-page)` |
| 8 | `Start small` | — | `var(--surface-page)`, hairline top |
| 9 | `Pricing` | `pricing` | `var(--surface-sunken)` |
| 10 | `Early Adopter` | `early` | `var(--ink-900)` + two dark washes |
| 11 | `Partners` | `partners` | `var(--surface-page)` |

**DS components (62 `x-import`), by section:**

- Hero: `Button size="lg" variant="inverse" href="06-contact-request.dc.html"` → `Get Early Access`;
  `Button size="lg" variant="outlineInverse" icon-right="arrow-right" href="#how"` → `See how INTERA works`;
  3 × `Icon` (`route-off`, `lock`, `circle-dot`, all `size="15" color="rgba(255,255,255,.45)"`);
  floating card: `SignalBadge type="incident" size="sm"` + `Icon name="git-branch" size="13" color="var(--signal-pattern)"`.
- Problem: 6 × `Icon size="16" color="var(--ink-500)"` — `boxes`, `contact`, `receipt`, `table-2`,
  `terminal`, plus `Icon name="corner-down-right" size="15"` (no colour).
- How it works: 3 × `Card padding="loose" dc-props="{{ lift }}"` each containing
  `Icon size="17"` — `plug`, `scale`, `eye`.
- Champion: 5 × `Card dc-props="{{ lift }}"` each with `Icon size="20"` — `bell`, `clock`,
  `clipboard-check`, `shield-check`, `repeat` (wrapper `color: var(--teal-600)`).
- In action: `SignalChain captions="{{ chainCaptions }}"`.
- Roles: 5 × `Card dc-props="{{ lift }}"` with `Icon size="18" color="var(--blue-600)"` —
  `circle-dollar-sign`, `gauge`, `scale`, `heart-pulse`, `shield-check`; plus
  `Button variant="secondary" icon-right="arrow-right" href="02-product.dc.html#roles"` → `See all Roles`.
- Working with IT: 5 × `Icon size="16" color="var(--ink-400)"` — `database`, `plug`, `lock`,
  `sliders-horizontal`, `route-off`.
- Start small: `Button size="lg" href="06-contact-request.dc.html"` → `Bring us a real problem`.
- Pricing: 3 × `Card padding="loose" dc-props="{{ fillCard }}"` (middle one also
  `elevated="{{ true }}" accent="var(--blue-600)" accent-line="var(--blue-200)"`);
  `Badge tone="info"` → `Beta`; 3 × `Button block="{{ true }}"` —
  `variant="secondary" href="03-pricing.dc.html"` → `Try INTERA`,
  `href="06-contact-request.dc.html"` → `Become an Early Adopter`,
  `variant="secondary" href="05-contacts.dc.html"` → `Talk to us about your deployment`.
- Early Adopter: `Button variant="inverse" size="lg" href="06-contact-request.dc.html" block="{{ true }}"`
  → `I have a problem INTERA could solve`.
- Partners: `Button variant="secondary" size="lg" icon-right="arrow-right" href="06-contact-request.dc.html"`
  → `Become an INTERA partner`; 6 `.itr-tile` tiles with `Icon size="16"` — `layers`
  (`var(--blue-600)`), `scale` (`var(--signal-reconciliation)`), `sliders-horizontal` (`var(--ink-500)`),
  `git-branch` (`var(--signal-pattern)`), `plug` (`var(--ink-500)`), `package` (`var(--ink-500)`).

**Full Lucide icon set on this page (24 named + 2 via components):**
`bell, boxes, circle-dollar-sign, circle-dot, clipboard-check, clock, contact, corner-down-right,
database, eye, gauge, git-branch, heart-pulse, layers, lock, package, plug, receipt, repeat,
route-off, scale, shield-check, sliders-horizontal, table-2, terminal` + `arrow-right` (via
`icon-right`) + `activity` / `alert-triangle` (inside `SignalChain` / `SignalBadge`).

**dc-imports:** `site-nav`, `site-footer`.

**`renderVals()`:**
```
lift:        { className: "itr-lift" }
fillCard:    { className: "itr-lift", style: { height: "100%", display: "flex", flexDirection: "column" } }
chainCaptions: { event: "Something important changed.",
                 reconciliation: "Things that should agree — don't.",
                 incident: "Something requires attention and action.",
                 pattern: "Understand what keeps happening, and under which conditions." }
```

**Dynamic slots:** essentially **none**. This is a fully static marketing page. Every heading, body
paragraph, plan price, role card, tile label and stat is fixed copy from the brief. The only things
that are not literal template text:

- the three product screenshots (media library / theme options — README: "в теме их нужно отдавать
  через медиатеку/поля, а не хардкодить"),
- header and footer (menus),
- the hero live-status pill copy `In beta — Early Adopter programme open` — a site-wide state string
  that also appears in the footer legal line; a single source is worth considering.

**Fixed marketing copy (do not make editable):** hero H1 `See what needs attention. Before someone
has to ask.`; kicker `Your business, clearly`; "Reads from" list `ERP / CRM / Billing / Excel /
Internal tools`; the floating incident card (`Critical maintenance task overdue`, `5 days`,
`09:14 UTC`, `4th occurrence — always after a delayed spare`); the five source rows with mono
identifiers (`erp.orders`, `crm.accounts`, `billing.invoices`, `ops_checks.xlsx`, `provisioning.api`);
all three plan cards; the Early Adopter give/get lists.

**Interactive behaviour:** hover only (`.itr-lift`, `.itr-row`, `.itr-tile`, `.itr-panel`,
`.itr-frame`, `.itr-hl`, `.itr-hl-panel`) plus the pulsing `.itr-live-dot`/`.itr-live-halo` pair in
the hero pill. No accordion, no tabs, no form. The `SignalChain` DS component accepts
`active`/`onSelect` but this page passes neither, so it is presentational here.

**Hardcoded links → WP URLs:**
`06-contact-request.dc.html` (×4), `03-pricing.dc.html`, `05-contacts.dc.html`,
`02-product.dc.html#roles`, and the in-page anchor `#how` (stays an anchor).

---

## 4. `02-product.dc.html` → `page-product.php`

**Sections in order (8):**

| # | `data-screen-label` | `id` | Background |
| --- | --- | --- | --- |
| 1 | `Product header` | — | `var(--surface-page)`, hairline bottom |
| 2 | `What INTERA watches` | — | `var(--surface-sunken)` + `--wash-blue` |
| 3 | `Pattern Studio` | — | `var(--surface-page)` |
| 4 | `Integrations` | `integrations` | `var(--surface-sunken)` + `--wash-teal` |
| 5 | `Roles` | `roles` | `var(--surface-page)` |
| 6 | `Market packages` | `packages` | `var(--surface-sunken)` + `--wash-violet` |
| 7 | `Method` | `method` | `var(--surface-page)` |
| 8 | `CTA` | — | `var(--surface-page)` |

The footer's deep links `#roles`, `#it`, `#method`, `#partners` target this page — note **`#it` and
`#partners` do not exist here**; they exist on `01-main.dc.html`. That is a link bug in the export to
resolve when the footer becomes a WP menu.

**DS components (71 `x-import`):**

- Product header: `Button size="lg" href="06-contact-request.dc.html"` → `Get Early Access`;
  `Button size="lg" variant="secondary" icon-right="arrow-right" href="11-docs.dc.html"` → `Read the docs`;
  2 × `MetricTile` —
  `label="Open incidents" value="7" delta="+2" direction="up" tone="warning"` and
  `label="Unreconciled" value="4,812" delta="-311" direction="down" tone="ok"`;
  3 × `SignalBadge` — `type="reconciliation"`, `type="incident"`, `type="pattern"`.
  The live pill uses `.itr-live-halo` + `.itr-live-dot` with the label `live`.
- What INTERA watches: `SignalChain captions="{{ chainCaptions }}"`; 4 × `Card` with
  `accent`/`accent-line` pairs `var(--signal-event)`/`var(--signal-event-line)`,
  `var(--signal-reconciliation)`/`…-line`, `var(--signal-incident)`/`…-line`,
  `var(--signal-pattern)`/`…-line`, each containing a matching `SignalBadge type="…"`.
- Pattern Studio: `Button variant="secondary" icon-right="arrow-right" href="12-docs-article.dc.html"`
  → `How Patterns are defined`. The if/and/then rule rows are plain divs (last one `.itr-row`).
- Integrations: 8 `.itr-row` source rows with `Icon size="16" color="var(--ink-500)"` —
  `database`, `contact`, `receipt`, `activity`, `table-2`, `terminal`, `landmark`, `mail`;
  then 2 × `Card` each with `CardHeader title="…" description="…"`:
  `title="IT owns access" description="Which system, which credentials, which refresh window."` and
  `title="Business owns logic" description="Metrics, Events, Incidents, Reconciliations, Patterns."`.
- Roles: 5 × `Card interactive="{{ true }}"` with `Icon size="18" color="var(--blue-600)"` —
  `circle-dollar-sign`, `gauge`, `scale`, `heart-pulse`, `shield-check`; each with 2 × `Tag`
  (`12 metrics`/`4 reconciliations`, `14 metrics`/`attention queue`, `10 metrics`/`6 reconciliations`,
  `9 metrics`/`patterns`, `8 metrics`/`data status`).
- Market packages: 2 × `Card padding="loose"`, each with `Badge tone="info"` → `Beta`, an
  `Icon size="18" color="var(--ink-700)"` (`radio-tower` / `ship`), a `Tag` list, and a
  `Button variant="link" icon-right="arrow-right"` (`href="12-docs-article.dc.html"` →
  `Telecommunications package`; `href="13-docs-category.dc.html"` → `Shipmanagement package`).
  Telecom tags (7): `Revenue Assurance Manager`, `Billing Operations Manager`,
  `Network Operations Manager`, `Partner / Wholesale Manager`, `Commercial Director`,
  `CFO / Finance Controller`, `COO / Head of Operations`.
  Shipmanagement tags (4): `Technical Superintendent`, `Fleet Manager`, `Procurement and parts`,
  `Compliance and audit`.
- Method: `Card padding="loose" accent="var(--blue-600)" accent-line="var(--blue-200)"` +
  `Button block="{{ true }}" href="06-contact-request.dc.html"` → `Talk to us about the Method`.
- CTA: `Button size="lg" href="06-contact-request.dc.html"` → `Bring us a real problem`,
  inside a `.itr-hl` box with `border-top: 3px solid var(--blue-600)`.

**Lucide icons named in markup (15):** `activity, circle-dollar-sign, contact, database, gauge,
heart-pulse, landmark, mail, radio-tower, receipt, scale, shield-check, ship, table-2, terminal`
+ `arrow-right` + component-internal `alert-triangle`, `git-branch`, `trending-up`, `trending-down`.

**dc-imports:** `site-nav`, `site-footer`. **`renderVals()`:** `chainCaptions` only (identical text to `01-main`).

**Dynamic slots:** none — fully static marketing page. Only `ship-3.webp` is media.
Everything else (metrics `7` / `4,812`, deltas `+2` / `-311`, role tag counts, package role lists)
is fixed demo copy per the brief.

**Interactive behaviour:** hover classes only; `Card interactive="{{ true }}"` adds the DS interactive
treatment. Pulsing live dot in the header panel. No accordion/tabs/forms.

**Hardcoded links → WP URLs:** `01-main.dc.html`, `06-contact-request.dc.html` (×3),
`11-docs.dc.html`, `12-docs-article.dc.html` (×2), `13-docs-category.dc.html`.

---

## 5. `03-pricing.dc.html` → `page-pricing.php`

**Sections in order (5):** `Pricing header` (`var(--surface-page)`) · `Plans` (`var(--surface-page)`) ·
`Comparison` (`var(--surface-sunken)`, hairlines top and bottom) · `Early Adopter`
(`var(--ink-900)` + `--wash-blue-dark` radial) · `Pricing questions` (`var(--surface-page)`).

**DS components (9):**

| Section | Component + exact props | Label |
| --- | --- | --- |
| Plans | `Card padding="loose" dc-props="{{ fillCard }}"` | Free |
| Plans | `Card padding="loose" elevated="{{ true }}" accent="var(--blue-600)" accent-line="var(--blue-200)" dc-props="{{ fillCard }}"` | Early Adopter |
| Plans | `Card padding="loose" dc-props="{{ fillCard }}"` | Commercial |
| Plans | `Badge tone="info"` | `Beta` |
| Plans | `Button variant="secondary" block="{{ true }}" href="06-contact-request.dc.html"` | `Try INTERA` |
| Plans | `Button block="{{ true }}" href="06-contact-request.dc.html"` | `Become an Early Adopter` |
| Plans | `Button variant="secondary" block="{{ true }}" href="05-contacts.dc.html"` | `Talk to us about your deployment` |
| Early Adopter | `Button variant="inverse" size="lg" href="06-contact-request.dc.html"` | `I have a problem INTERA could solve` |
| Pricing questions | `Button variant="secondary" icon-right="arrow-right" href="04-faq.dc.html"` | `Read the full FAQ` |

**Lucide icons:** only `arrow-right` (via `icon-right`). **dc-imports:** `site-nav`, `site-footer`.

**`renderVals()`:** `fillCard: { style: { height: "100%", display: "flex", flexDirection: "column" } }`
— note this page's `fillCard` has **no** `className: "itr-lift"` (unlike `01-main`); the lift comes
from `class="itr-hl"` on the `x-import` instead.

**Comparison table:** `class="itr-scroll-x"` wrapper, `<table>` with 4 columns
(`Capability` / `Free` / `Early Adopter` / `Commercial`) and 6 rows: Roles `3/unlimited/unlimited`;
Users `10/25/by agreement`; Integrations `3/5/by agreement`; History `30 days/unlimited/unlimited`;
Onboarding `self-serve/custom/from €4,500`; Market package `—/1 included/quoted`. The Early Adopter
`<th>` is `color: var(--blue-600)`.

**Early Adopter band stats:** three `.itr-panel` tiles — `12` months free, `1` market package
included, `25` users.

**Dynamic slots:** none. All prices (`€0`, `€750/year`, `from €4,500/year`, `from €15,000/year`,
`from €4,500`), plan feature bullets, table cells and the four pre-signing Q&A pairs are fixed copy.
Note `theme/CLAUDE.md`'s "content is not code" rule and that prices *are* content that will change —
see open questions.

**Interactive behaviour:** hover only. No accordion or tabs. Horizontal table scroll below 760px.

**Hardcoded links → WP URLs:** `01-main.dc.html`, `06-contact-request.dc.html` (×3),
`05-contacts.dc.html`, `04-faq.dc.html`.

---

## 6. `04-faq.dc.html` → `page-faq.php`

**Sections in order (2):** `FAQ header` (`var(--surface-page)`, hairline bottom) ·
`FAQ content` (`var(--surface-page)`, flex-wrap two-column with sticky aside).

**DS components (17):** 16 × `Icon name="chevron-down" size="18" color="var(--ink-400)"` — one per
`<summary>` — plus 1 × `Button variant="secondary" size="sm" href="06-contact-request.dc.html"` →
`Ask a question`.

**Lucide icons:** `chevron-down` only. **dc-imports:** `site-nav`, `site-footer`. **No `renderVals()`.**

**Structure:** sticky aside (`position: sticky; top: 100px`) with an "On this page" list linking
`#roles`, `#method`, `#it`; then three `<h2 id="…">` groups, each `scroll-margin-top: 100px`:

| Group `id` | Heading | Questions |
| --- | --- | --- |
| `roles` | `INTERA Roles` | 8 |
| `method` | `INTERA Method` | 5 |
| `it` | `IT and data` | 3 |

Total **16 `<details>`**, each `border-bottom: 1px solid var(--border-hairline)`, summary
`padding: 18px 0; font-size: var(--text-lg); font-weight: 500; color: var(--ink-900)`, answer `<p>`
`padding-bottom: 20px; max-width: 640px`.

**Interactive behaviour:** **native `<details>`/`<summary>` accordion** — README explicitly says
keep it native ("Аккордеон FAQ в макете — нативный `<details>`; так же можно оставить в теме").
This page's `<helmet>` carries five extra `summary` rules (quoted in §0) that hide the native marker,
turn the summary blue on hover, and rotate the chevron `180deg` when `details[open]` — port them into
`intera.css` scoped to the FAQ, since the rest of the site has no `<details>`.
No `open` attribute anywhere — all 16 are closed by default, so there is no "first one open" state.

**Dynamic slots:** none in the mockup. The 16 Q&A pairs are hardcoded marketing copy. If the client
wants editable FAQs this needs a repeater/CPT decision (see open questions) — the export does not
imply one.

**Hardcoded links → WP URLs:** `01-main.dc.html`, `06-contact-request.dc.html`,
`03-pricing.dc.html` (inline in the "Is it free?" answer), `12-docs-article.dc.html` (inline in the
"What exactly do we ask IT for?" answer, anchor text `integration contract`).

---

## 7. `05-contacts.dc.html` → `page-contacts.php`

**Sections in order (3):** `Contacts header` (`var(--surface-page)`, hairline bottom) ·
`Contact routes` (`var(--surface-page)`) · `Who to talk to` (`var(--surface-sunken)`,
`border-top: 1px solid var(--border-subtle); margin-top: 40px`).

**DS components (15):**

- Direct block: 3 × `Icon size="17" color="var(--ink-500)"` — `mail`, `clock`, `globe`;
  `Button size="lg" href="06-contact-request.dc.html"` → `Bring us a real problem`;
  `Button size="lg" variant="secondary" href="mailto:sb@by-sky.net"` → `Send an email`.
- `Card padding="loose"` "What to send us" (three numbered `01/02/03` items).
- Who to talk to: 3 × `Card interactive="{{ true }}"`, each with an `Icon size="18" color="var(--blue-600)"`
  (`circle-dot`, `git-branch`, `settings`) and a `Button variant="link" icon-right="arrow-right"`:
  `href="06-contact-request.dc.html"` → `Apply as Early Adopter`;
  `href="06-contact-request.dc.html"` → `Talk about partnership`;
  `href="03-pricing.dc.html"` → `See pricing first`.

**Lucide icons:** `circle-dot, clock, git-branch, globe, mail, settings` + `arrow-right`.

**dc-imports:** `site-nav`, `site-footer`. **No `renderVals()`.**

**Dynamic slots:** the contact facts are site-identity data rather than post content —
`sb@by-sky.net` (twice, plus the `mailto:` button), `Same working day, in most cases`,
`English, Russian`. Good candidates for theme options/customizer so the footer and 06/07 stay in sync.
Everything else is fixed copy.

**Interactive behaviour:** hover only.

**Hardcoded links → WP URLs:** `01-main.dc.html`, `06-contact-request.dc.html` (×3),
`03-pricing.dc.html`, `11-docs.dc.html`, `04-faq.dc.html`, `mailto:sb@by-sky.net` (×2).

---

## 8. `06-contact-request.dc.html` → `page-contact-request.php`

**Sections in order (2):** `Request header` (`var(--surface-page)`, hairline bottom) ·
`Request form` (`var(--surface-page)`, two-column grid `minmax(min(320px,100%),1fr)`).

**DS components (23):**

Form branch (`<sc-if value="{{ showForm }}" hint-placeholder-val="{{ true }}">`), inside a
`Card padding="loose"`:

| # | `Field` props | Control |
| --- | --- | --- |
| 1 | `label="Name" required="{{ true }}" html-for="name"` | `Input id="name" placeholder="Anna Kovalenko"` |
| 2 | `label="Work email" required="{{ true }}" html-for="email"` | `Input id="email" type="email" placeholder="a.kovalenko@company.com"` |
| 3 | `label="Company" html-for="company"` | `Input id="company" placeholder="Company name"` |
| 4 | `label="Your role" html-for="role" hint="The area you are responsible for."` | `Input id="role" placeholder="Billing Operations Manager"` |
| 5 | `label="Industry" html-for="industry"` | `Select id="industry" placeholder="Choose an industry" options="{{ industries }}"` |
| 6 | `label="What brings you here" html-for="interest"` | `Select id="interest" placeholder="Choose one" options="{{ interests }}"` |
| 7 | `label="The problem, in your words" required="{{ true }}" html-for="problem" hint="What is checked manually today, which systems are involved, and what happens when it is noticed too late."` | `Textarea id="problem" rows="6" placeholder="Every month someone exports usage from mediation and compares it with billing by hand. Last quarter we found unbilled usage six weeks late."` |

Plus `Checkbox label="I agree that INTERA may contact me about this request."` and
`Button size="lg" onClick="{{ submit }}"` → `Send request`.

Success branch (`<sc-if value="{{ showSuccess }}" hint-placeholder-val="{{ false }}">`):
`Card padding="loose" accent="var(--status-ok)" accent-line="var(--status-ok-line)"` containing
`Icon name="check-circle" size="20"`, heading `Request received`, a reference block
(`REQ-2026-0148`, `within 1 working day`), and two buttons:
`Button variant="secondary" href="11-docs.dc.html"` → `Read the docs`;
`Button variant="ghost" onClick="{{ reset }}"` → `Send another request`.

Aside: a three-step "What happens next" list (01/02/03) and
`Card` "Early Adopter programme" with
`Button variant="link" icon-right="arrow-right" href="03-pricing.dc.html"` → `See what is included`.

**Lucide icons:** `check-circle` + `arrow-right`; component-internal `chevron-down` (Select) and
`check` (Checkbox).

**dc-imports:** `site-nav`, `site-footer`.

**`renderVals()` — the select option lists, verbatim:**
```
industries: ["Telecommunications", "Shipmanagement / maritime", "Logistics and transport",
             "Manufacturing", "Food production", "Financial services", "Other"]
interests:  ["Early Adopter programme", "Evaluation on the free plan", "Commercial deployment",
             "INTERA Method engagement", "Partner or reseller"]
state = { sent: false }; submit → sent: true; reset → sent: false
```

**Dynamic slots:** the whole form is dynamic in the WordPress sense — it must be produced by a form
plugin or a custom handler (README §"Что не сделано": "Формы (`06`) в макете не подключены к бэкенду:
это шесть полей + состояние успеха. Обработку выбрать по стеку проекта (CF7 / Gravity / собственный
обработчик) и сохранить тексты и состояния из макета."). The reference number `REQ-2026-0148` is a
placeholder that a real handler would generate; `within 1 working day` is fixed copy.

**Interactive behaviour (the only real state machine in the set):**
- Two mutually exclusive branches driven by `sent`.
- `Send request` → success card; `Send another request` → back to the form.
- No client-side validation shown beyond `required` markers on fields 1, 2, 7.
- Error states are **not** designed — see open questions.

**Hardcoded links → WP URLs:** `01-main.dc.html`, `05-contacts.dc.html`, `07-policy.dc.html`
(inline "privacy policy"), `11-docs.dc.html`, `03-pricing.dc.html`, `mailto:sb@by-sky.net`.

---

## 9. `07-policy.dc.html` → `page-legal.php`

**Sections in order (2):** `Policy header` (`var(--surface-sunken)`, `border-bottom: 1px solid
var(--border-subtle)`) · `Policy body` (`var(--surface-page)`, sticky aside + article).

**DS components (1):** `Button variant="secondary" size="sm" icon-left="printer"` → `Print this page`.

**Lucide icons:** `printer`. **dc-imports:** `site-nav`, `site-footer`. **No `renderVals()`.**

**Structure:** sticky "Contents" aside listing ten numbered anchors —
`#scope 1. Scope`, `#data 2. What we collect`, `#use 3. How we use it`, `#product 4. Data inside INTERA`,
`#sharing 5. Sharing`, `#retention 6. Retention`, `#rights 7. Your rights`, `#cookies 8. Cookies`,
`#changes 9. Changes`, `#contact 10. Contact` — followed by sibling links `Cookie policy` and
`Software licence` (both currently `href="07-policy.dc.html"`). Article is ten
`<div id="…" style="scroll-margin-top: 100px">` blocks, each an `<h2>` + prose.

**Dynamic slots (this is the most WP-driven of the static pages):**
- H1 `Privacy policy`, the standfirst paragraph, and all ten numbered sections → `the_title()` +
  `the_content()` (with `wp_kses_post`). One template serves privacy / cookies / licence, so the body
  **must** come from the editor, not the template.
- The mono version stamp `Version 1.0` and `Updated 2026-08-13` → post meta (or `the_modified_date()`).
  It is repeated in the footer of the article as `© 2026 INTERA · version 1.0`.
- The breadcrumb third crumb `Privacy policy` → `the_title()`.
- The "Contents" aside → generated from the `<h2>`s in `the_content()` (JS or a PHP heading parse),
  or a manual field. It cannot stay hardcoded because the three legal pages have different sections.
- The sibling links `Cookie policy` / `Software licence` → the other pages using this template
  (`get_pages()` filtered by template, or a small menu).

**Fixed template copy:** the "Contents" and "Print this page" labels.

**Interactive behaviour:** `Print this page` should call `window.print()` (progressive JS; the export
has no handler). Sticky aside. Anchor scroll with `scroll-margin-top: 100px`.

**Hardcoded links → WP URLs:** `01-main.dc.html`, `07-policy.dc.html` (×2, sibling legal pages),
`mailto:sb@by-sky.net`. The footer also links `07-policy.dc.html#cookies` — an anchor that does not
exist in this file's *policy* variant beyond section 8; the cookie policy is meant to be a separate page.

---

## 10. `08-blog.dc.html` → `home.php`

**Sections in order (3):** `Blog header` (`var(--surface-page)`, hairline bottom) ·
`Featured` (`var(--surface-page)`) · `Post list` (`var(--surface-page)`, `class="itr-1col"` grid
`minmax(0,1fr) minmax(0,340px)`, gap `44px`).

**DS components (15):**

- Header: 3 × `Tag` — `selected="{{ true }}"` → `All`; `Life stories`; `Release information`.
- Featured: `Badge tone="accent"` → `Life stories`; `Icon name="arrow-right" size="16"`.
- List: 5 × `Tag` (one per row) — `Life stories` ×2, `Release information` ×3.
- Pagination: `Button variant="secondary" size="sm" disabled="{{ true }}" icon-left="chevron-left"`
  → `Previous`; `Button variant="secondary" size="sm" disabled="{{ true }}" icon-right="chevron-right"`
  → `Next`.
- Sidebar: `Card` (Categories); `Card accent="var(--blue-600)" accent-line="var(--blue-200)"`
  with `Button size="sm" href="06-contact-request.dc.html"` → `Bring us a real problem`.

**Lucide icons:** `arrow-right`, `chevron-left`, `chevron-right`.

**dc-imports:** `site-nav`, `site-footer`. **No `renderVals()`.**

**Dynamic slots — this is the first genuinely data-driven template:**

| Slot | Mockup value | WP source |
| --- | --- | --- |
| Breadcrumb `Home / Blog` | static | `home_url()` + blog page title |
| H1 `Blog` + standfirst | fixed copy | archive title / editable page |
| Filter tags `All` / `Life stories` / `Release information` | 3 tags, `All` selected | `get_categories()` + `get_category_link()`; selected state = current query |
| Featured card | badge `Life stories`, mono `2026-08-04 · 6 min`, H2, excerpt, `Read the story` | sticky post or first `WP_Query` post — `get_the_category()`, `get_the_date('Y-m-d')`, reading time (**meta, see open questions**), `the_title()`, `the_excerpt()`, `the_permalink()` |
| 5 list rows | mono date `min-width: 96px`, title, excerpt, category `Tag` | the loop; each row is `<a>` wrapping date + title + excerpt + tag |
| Counter `6 of 6 posts` | mono | `$wp_query->found_posts` |
| `Previous` / `Next` | both `disabled` | `get_previous_posts_link()` / `get_next_posts_link()` (or `paginate_links`) |
| Sidebar `Categories` list | `Life stories 3`, `Release information 3` | `wp_list_categories()` with counts, or `get_categories()` |
| `documentation` inline link | `href="11-docs.dc.html"` | docs archive URL |

Post data present in the mockup (dates are `Y-m-d`, all mono):
`2026-08-04` featured; then `2026-07-22`, `2026-07-08`, `2026-08-13`, `2026-07-07`, `2026-03-15`
— note the list is **not** date-sorted in the export (life stories first, then releases), so the
theme must decide sort order or use two queries.

**Fixed marketing copy:** H1 `Blog`, standfirst `Operational stories from real companies, and what
changes in INTERA with each release.`; sidebar CTA card (`Have a story like these?` + body + button);
the `Release notes are also listed in the documentation.` line.

**Interactive behaviour:** hover only (`.itr-lift` on the featured card, `style-hover="background:
var(--surface-hover)"` on list rows, `.itr-hl` on sidebar cards). Tags are visual filters, not JS.

**Hardcoded links → WP URLs:** `01-main.dc.html`, `09-blog-post.dc.html` (×6),
`10-blog-category.dc.html` (×2), `06-contact-request.dc.html`, `11-docs.dc.html`.

---

## 11. `09-blog-post.dc.html` → `single.php`

**Sections in order (2):** `Post header` (`var(--surface-page)`, hairline bottom) ·
`Post body` (`var(--surface-page)`, `display: flex; flex-wrap: wrap; gap: 48px` — article
`flex: 1 1 520px; max-width: 680px; order: 1`, aside `position: sticky; top: 100px; flex: 0 1 260px; order: 2`).

**DS components (5):**
`Badge tone="accent"` → `Life stories`;
`Card class="itr-lift"` → "What made it work" (3 lines);
`Button variant="secondary" size="sm" icon-left="link"` → `Copy link`;
`Button size="sm" href="06-contact-request.dc.html"` → `Bring us a real problem`;
`Icon name="gauge" size="17" color="var(--blue-600)"` in the "Role in this story" aside block.

**Lucide icons:** `gauge`, `link`. **dc-imports:** `site-nav`, `site-footer`. **No `renderVals()`.**

**Dynamic slots:**

| Slot | Mockup value | WP source |
| --- | --- | --- |
| Breadcrumb | `Home / Blog / Life stories` (third is a link) | `home_url()`, blog URL, `get_the_category()` + `get_category_link()` |
| Category badge | `Life stories` | `get_the_category()` |
| Meta line | `2026-08-04 · 6 min read` (mono) | `get_the_date('Y-m-d')` + reading-time meta |
| H1 | `Cargo Overseas: the readiness number nobody could explain` | `the_title()` |
| Standfirst `<p>` | `Four systems, one weekly spreadsheet…` | `the_excerpt()` or a subtitle meta field |
| Article body | 2 intro `<p>`, `<h2>` ×3, a `<ul>` of 4, screenshot frame, mono caption, blockquote + attribution, more `<p>`s | `the_content()` — needs matching prose CSS in `intera.css` for `p / h2 / ul / li / blockquote / figure / figcaption` |
| Inline screenshot | `assets/shots/ship-2.webp` in an `.itr-frame` with a mono caption bar | media library image inside `the_content()`, or the featured image |
| Pull quote | `"The spreadsheet was never wrong…"` + `Fleet manager, Cargo Overseas` in a `border-left: 3px solid var(--blue-600)` block | a `blockquote` style rule in `intera.css` |
| "What made it work" card | 3 takeaway lines | still content — either the end of `the_content()` or a meta field |
| Anonymisation note | `Names and figures are anonymised at the customer's request.` | fixed template copy |
| Prev / next cards | `Previous` → `Hadjipetrou family: three generations, one unreconciled ledger`; `Next` → `v0.003 — Metrics gain trend, threshold and data status` | `previous_post_link()` / `next_post_link()` — note prev/next cross categories |
| Aside "In this story" | 3 `href="#"` links matching the three `<h2>`s | generated from `the_content()` headings (the export's hrefs are literally `#`, and the `<h2>`s carry **no ids** — the theme must add them) |
| Aside "Role in this story" | icon `gauge` + `Operations Oversight` → `02-product.dc.html#roles`, plus a one-line description | post meta (role name + icon + blurb), or a `role` taxonomy |

**Interactive behaviour:**
- **`Copy link` button** — a real copy-to-clipboard control, no handler in the export. Progressive JS
  in `assets/js/intera.js`; must have a non-JS fallback (the page must work without JS per CLAUDE.md).
- Sticky aside; `.itr-lift` on the takeaways card and both prev/next cards; `.itr-frame` on the shot.

**Hardcoded links → WP URLs:** `01-main.dc.html`, `08-blog.dc.html`, `10-blog-category.dc.html`,
`09-blog-post.dc.html` (×2 prev/next), `06-contact-request.dc.html`, `02-product.dc.html#roles`, `#` ×3.

---

## 12. `10-blog-category.dc.html` → `category.php`

**Sections in order (2):** `Category header` (`var(--surface-sunken)`,
`border-bottom: 1px solid var(--border-subtle)`) · `Category posts` (`var(--surface-page)`,
`repeat(auto-fit, minmax(min(300px,100%),1fr))` gap `44px`).

**DS components (10):**
2 × `Tag` — `selected="{{ true }}"` → `Life stories`, and `Release information`;
3 × `Icon name="arrow-right" size="16"` (one per post card);
2 × pagination `Button variant="secondary" size="sm" disabled="{{ true }}"` (`icon-left="chevron-left"`
→ `Previous`; `icon-right="chevron-right"` → `Next`);
`Card` (Other categories);
`Card accent="var(--signal-pattern)" accent-line="var(--signal-pattern-line)"` with
`Button size="sm" href="06-contact-request.dc.html"` → `Bring us a real problem`.

**Lucide icons:** `arrow-right`, `chevron-left`, `chevron-right`.

**dc-imports:** `site-nav`, `site-footer`. **No `renderVals()`.**

**Dynamic slots:**

| Slot | Mockup value | WP source |
| --- | --- | --- |
| Breadcrumb third crumb | `Life stories` (plain span) | `single_cat_title()` |
| Eyebrow | `Category` (uppercase, `var(--blue-600)`) | fixed |
| H1 | `Life stories` | `single_cat_title()` |
| Description | `How real companies noticed something too late…` | `category_description()` |
| Post count | `3 posts` (mono) | `$wp_query->found_posts` |
| Filter tag row | `Life stories` (selected) + `Release information` + `All posts` link → `08-blog.dc.html` | `get_categories()` + current-term state |
| 3 post cards | mono `2026-08-04` / `6 min`, `2026-07-22` / `5 min`, `2026-07-08` / `4 min`; H2 title; excerpt (`max-width: 620px`); `Read the story` + arrow | the loop |
| Pager | `Page 1 of 1` + disabled Previous/Next | `paginate_links()` / `$wp_query->max_num_pages` |
| Sidebar "Other categories" | `Release information 3`, `All posts 6` | `get_categories()` excluding current + total post count |

**Fixed marketing copy:** the sidebar CTA card `Every story starts the same way` + body + button label.

**Interactive behaviour:** hover only.

**Hardcoded links → WP URLs:** `01-main.dc.html`, `08-blog.dc.html` (×2),
`09-blog-post.dc.html` (×3), `10-blog-category.dc.html` (self, sidebar), `06-contact-request.dc.html`.

---

## 13. `11-docs.dc.html` → `archive-docs.php`

**Sections in order (3):** `Docs header` (`var(--surface-sunken)`,
`border-bottom: 1px solid var(--border-subtle)`) · `Docs categories` (`var(--surface-page)`) ·
`Docs help` (`var(--surface-page)`).

**DS components (13):**
`Input size="lg" icon-left="search" placeholder="Search the documentation"`;
4 × `Card padding="loose" class="itr-hl"` — one per docs category;
inside each, an `Icon size="17"` in a coloured 34×34 chip and an `Icon name="arrow-right" size="15"`
in the "N more articles" link.

Category chips (exact colours):

| Category | Icon | Chip background / border / colour | Count |
| --- | --- | --- | --- |
| `Getting started` | `rocket` | `var(--blue-50)` / `var(--blue-100)` / `var(--blue-600)` | `10` |
| `Building with INTERA` | `plug` | `var(--teal-50)` / `var(--teal-100)` / `var(--teal-600)` | `8` |
| `Solutions and reference` | `book-open` | `var(--violet-50)` / `var(--violet-100)` / `var(--violet-600)` | `6` |
| `Integrations` | `database` | `var(--amber-50)` / `var(--amber-100)` / `var(--amber-600)` | `2` |

**Lucide icons:** `arrow-right, book-open, database, plug, rocket` + `search` (via `icon-left`).

**dc-imports:** `site-nav`, `site-footer`. **No `renderVals()`.**

**Dynamic slots:**

| Slot | Mockup value | WP source |
| --- | --- | --- |
| Breadcrumb `Home / Docs` | static | `home_url()` + archive title |
| H1 `Documentation` + standfirst | fixed copy (or archive description) | `post_type_archive_title()` |
| Search field | `Search the documentation` | `get_search_form()` scoped to `post_type=docs` |
| Quick links `Start here` / `Installation` / `Integration contract` | 3 `docs` posts | curated meta or 3 hand-picked posts |
| 4 category cards | title, icon, count, first 5 article links, "N more articles" | `get_terms('docs_category')` + a `WP_Query` per term (`posts_per_page=5`, `$term->count`) |
| Article link lists | 5 rows max, ellipsised, `height: 38px` each | `the_title()` / `the_permalink()` |
| "N more articles" copy | `5 more articles`, `3 more articles`, `1 more article`, and for Integrations the different label `Read the integration contract` | `$term->count - 5` with `_n()` — **the Integrations card breaks the pattern** (2 articles, no overflow link, plus a paragraph and a differently-labelled CTA) |
| `Docs help` block | 3 fixed panels | `Alpha-stage limitations` → `12-docs-article.dc.html`; `Release information` → `10-blog-category.dc.html` (the release-information **blog** category); `Question not answered here?` → `06-contact-request.dc.html` |

**Article titles listed (useful for seeding content):**
Getting started — `Start here`, `First setup journey`, `Installation`, `Administration`,
`Integrations and data`. Building with INTERA — `Integration architecture`,
`Build your first integration`, `Integration contract`, `Integration types`, `Testing and validation`.
Solutions and reference — `Concepts and terminology`, `Asset types`, `Widgets`, `Role packages`,
`Telecommunications package`. Integrations — `Revolut bank`, `Business Central`.

**Interactive behaviour:** search input (real form); hover only otherwise. The icon chips and counts
are static.

**Hardcoded links → WP URLs:** `01-main.dc.html`, `12-docs-article.dc.html` (×21),
`13-docs-category.dc.html` (×8), `10-blog-category.dc.html`, `06-contact-request.dc.html`.

---

## 14. `12-docs-article.dc.html` → `single-docs.php`

**Sections:** **one** `data-screen-label="Docs article"` on the outermost `<div>` —
`max-width: 1280px` (wider than the site's 1160px), `display: flex; flex-wrap: wrap; gap: 40px`.
Three columns: left docs tree (sticky, `flex: 0 1 240px`), article (`flex: 1 1 520px; max-width: 680px`),
right "On this page" rail (sticky, `flex: 0 1 200px`).

**DS components (4):**
`Input size="sm" icon-left="search" placeholder="Search docs"`;
`Alert tone="info" title="Naming changed in v0.003"` with body
`KPI is now Metric across the product and the docs. Existing roles were migrated automatically;
saved dashboards keep working.`;
2 × `Button variant="secondary" size="sm"` — `icon-left="thumbs-up"` → `Yes`,
`icon-left="thumbs-down"` → `No`.

**Lucide icons:** `search`, `thumbs-up`, `thumbs-down` (via `icon-left`) + `info` (Alert default for
`tone="info"`). No `<x-import … Icon name=…>` on this page.

**dc-imports:** `site-nav`, `site-footer`. **No `renderVals()`.**

**Dynamic slots:**

| Slot | Mockup value | WP source |
| --- | --- | --- |
| Left tree | 3 groups — `Getting started` (4 links), `Solutions and reference` (5, current item is a **`<span>`**, not a link, with `background: var(--blue-50); border-left: 2px solid var(--blue-600)`), `Building with INTERA` (3) | `get_terms('docs_category')` + per-term `WP_Query`; current item detection via `get_the_ID()` |
| Docs search | `Search docs` | `get_search_form()` scoped to `docs` |
| Breadcrumb | `Docs / Solutions and reference` (no Home crumb here) | archive link + `get_the_terms()` |
| H1 | `Concepts and terminology` | `the_title()` |
| Standfirst | `Nine words carry the whole model…` | excerpt / subtitle meta |
| Meta strip (mono, hairlines top and bottom) | `Updated 2026-08-13` · `v0.003` · `4 min read` | `the_modified_date('Y-m-d')`, a version meta field, reading-time meta |
| Body | 9 `<div id="…" style="scroll-margin-top: 100px">` blocks, each `<h2>` + `<p>`, some with `<code>` chips or a mono callout box | `the_content()`; the ids are `metric, event, reconciliation, incident, pattern, role, datasource, asset, package` |
| Right rail "On this page" | 9 anchors matching those ids, inside `border-left: 1px solid var(--border-hairline)` | generated from `the_content()` headings |
| Alert | version-change notice | either part of `the_content()` (a block) or a meta-driven notice |
| Prev / next | `Previous` → `Role packages`; `Next` → `Asset types` | `get_adjacent_post()` scoped to the same `docs_category` |

**Fixed template copy:** `Was this page useful?` / `Yes` / `No`;
`Something unclear or wrong here? Tell us — docs are part of the product.` + `Report an issue`.

**Interactive behaviour:**
- **Feedback buttons** (`Yes` / `No`) — no handler in the export. Needs a decision: JS + REST endpoint,
  or turn them into links to the contact request page.
- Docs search form.
- Sticky left tree and right rail (`top: 100px`).
- Anchor navigation with `scroll-margin-top: 100px`. A scroll-spy "current section" highlight is
  **not** in the export.

**Hardcoded links → WP URLs:** `12-docs-article.dc.html` (×13 — tree + prev/next),
`11-docs.dc.html`, `13-docs-category.dc.html`, `06-contact-request.dc.html`, plus 9 in-page anchors.

---

## 15. `13-docs-category.dc.html` → `taxonomy-docs_category.php`

**Sections in order (2):** `Docs category header` (`var(--surface-sunken)`,
`border-bottom: 1px solid var(--border-subtle)`) · `Docs category list` (`var(--surface-page)`,
`repeat(auto-fit, minmax(min(300px,100%),1fr))` gap `44px`).

**DS components (4):**
`Icon name="rocket" size="19"` inside a 40×40 `var(--white)` chip with `border: 1px solid var(--blue-100)`;
`Card class="itr-hl"` (Other categories);
`Card accent="var(--signal-reconciliation)" accent-line="var(--signal-reconciliation-line)"` with
`Button size="sm" href="06-contact-request.dc.html"` → `Apply as Early Adopter`.

**Lucide icons:** `rocket`. **dc-imports:** `site-nav`, `site-footer`. **No `renderVals()`.**

**Dynamic slots:**

| Slot | Mockup value | WP source |
| --- | --- | --- |
| Breadcrumb | `Home / Docs / Getting started` | `home_url()`, archive link, `single_term_title()` |
| Category icon | `rocket` in a blue-bordered chip | **term meta** (icon name + colour) — the same mapping the docs root uses |
| H1 | `Getting started` | `single_term_title()` |
| Article count | `10 articles` (mono) | `$term->count` |
| Description | `From installation to the first working role…` | `term_description()` |
| Grouped article list | **three sub-groups** — `First steps` (01–04), `Data and modelling` (05–08), `Good to know` (09–10) | see open questions: needs a second grouping level (child terms, or a per-post "section" meta), plus per-post ordering |
| Each row | mono ordinal `01`…`10`, title, one-line summary, mono reading time (`3 min`, `7 min`, `6 min`, `5 min`, `8 min`, `9 min`, `6 min`, `5 min`, `4 min`, `2 min`) | loop index or `menu_order`; `the_title()`; excerpt; reading-time meta |
| Sidebar "Other categories" | `Building with INTERA 8`, `Solutions and reference 6`, `Integrations 2` | `get_terms()` excluding current, with counts |

**Article titles listed:** `Start here`, `First setup journey`, `Installation`, `Administration`,
`Integrations and data`, `Assets and data modelling`, `Metrics`, `Dashboards`,
`Alpha-stage capabilities and limitations`, `Uninstalling INTERA`.

**Fixed marketing copy:** sidebar card `Setting up the first role with us` + body + button label.

**Interactive behaviour:** hover only (`style-hover="background: var(--surface-hover)"` on rows,
`.itr-hl` on sidebar cards).

**Hardcoded links → WP URLs:** `01-main.dc.html`, `11-docs.dc.html`,
`12-docs-article.dc.html` (×10), `13-docs-category.dc.html` (×2, sidebar), `06-contact-request.dc.html`.

---

## 16. `00-templates.dc.html` — index, **not a theme file**

No `data-screen-label` sections and no `dc-import`s. 13 template cards + 2 partial cards + two notes
blocks. Uses `Logo size="18"` and 13 × `Badge tone="accent"` → `Unique`. Each card carries the target
PHP filename in mono `var(--text-2xs)` — this is the authoritative restatement of the README map, and
it agrees with it exactly. Its "Notes for the theme build" panel repeats the WordPress-structure
notes. Do not port this page.

---

## 17. WordPress structure the theme needs

Cross-checked against README §"Заметки по структуре WordPress" (lines 53–62) and the
`00-templates.dc.html` notes panel. **Everything below is either stated there or forced by the markup.**

### Custom post types

| CPT | Slug | Supports | Driven by | README support |
| --- | --- | --- | --- | --- |
| Documentation | `docs` | title, editor, excerpt, page-attributes (`menu_order` for the `01…10` ordinals), revisions, custom-fields | `11-docs`, `12-docs-article`, `13-docs-category` | ✅ stated: "Документация — custom post type `docs` с одной таксономией `docs_category`" |

Template names required by the map: `archive-docs.php`, `single-docs.php`,
`taxonomy-docs_category.php` — these filenames imply `'has_archive' => true` and a public,
`with_front => false`-style rewrite. Nothing in the export requires any other CPT.

### Taxonomies

| Taxonomy | Attached to | Terms visible in the mockups | README support |
| --- | --- | --- | --- |
| `docs_category` | `docs` | `Getting started` (10), `Building with INTERA` (8), `Solutions and reference` (6), `Integrations` (2) | ✅ stated: "с одной таксономией `docs_category`" |
| built-in `category` | `post` | `life-stories`, `release-information` | ✅ stated: "Блог — две категории: `life-stories` и `release-information`" |

Term-level data the templates need but WP does not provide out of the box — **term meta**:
- an icon name per `docs_category` (`rocket`, `plug`, `book-open`, `database`) — used on both
  `11-docs` and `13-docs-category`,
- a colour family per term (`blue`, `teal`, `violet`, `amber` — the `-50 / -100 / -600` triple).

### Nav menu locations

`theme/inc/setup.php` already registers `primary` and `footer_legal`. The mockups need:

| Location | Items in the export | Source |
| --- | --- | --- |
| `primary` | Product, Pricing, FAQ, Docs, Blog, Contacts (6) — plus the fixed `Beta` badge and `Get Early Access` button, which are **template chrome, not menu items** | `site-nav`, both desktop and mobile renders |
| `footer_legal` | Privacy policy, Cookie policy, License Agreement (3) | `site-footer` Company column, lower group |
| *(needed, not yet registered)* `footer_product` | Product overview, Pricing + INTERA Roles, Working with existing IT, INTERA Method, Partners and resellers | `site-footer` col 2 |
| *(needed)* `footer_resources` | Documentation, Blog, FAQ + Life stories, Release information | `site-footer` col 3 |
| *(needed)* `footer_company` | Contacts | `site-footer` col 4, upper group |

README only says "Меню — область меню WordPress (`register_nav_menus`), не хардкод" without
enumerating footer areas, so the number of footer locations is a build decision (see open questions).

### Page templates (`Template Name:` header) and the standard hierarchy

| Theme file | Kind | Driven by | Assigned to |
| --- | --- | --- | --- |
| `front-page.php` | hierarchy | `01-main` | the static front page |
| `page-product.php` | page template `Product` | `02-product` | one page |
| `page-pricing.php` | page template `Pricing` | `03-pricing` | one page |
| `page-faq.php` | page template `FAQ` | `04-faq` | one page |
| `page-contacts.php` | page template `Contacts` | `05-contacts` | one page |
| `page-contact-request.php` | page template `Contacts — request` | `06-contact-request` | one page |
| `page-legal.php` | page template `Legal` | `07-policy` | **three** pages: privacy, cookies, licence — ✅ README: "Юридические страницы … используют один шаблон `page-legal.php`" |
| `home.php` | hierarchy (posts page) | `08-blog` | the posts page |
| `single.php` | hierarchy | `09-blog-post` | blog posts (already stubbed in repo) |
| `category.php` | hierarchy | `10-blog-category` | both blog categories |
| `archive-docs.php` | hierarchy | `11-docs` | `docs` archive |
| `single-docs.php` | hierarchy | `12-docs-article` | `docs` posts |
| `taxonomy-docs_category.php` | hierarchy | `13-docs-category` | all four docs terms |
| `header.php` / `footer.php` | partials | `site-nav` / `site-footer` | every template |
| `index.php` / `404.php` | fallbacks | not designed | — |

Note the filenames `page-product.php` etc. are **not** WP's `page-{slug}.php` convention here: the
README calls them templates for named pages, and `00-templates.dc.html` labels them `02_Page`. Either
they carry a `Template Name:` header (editor picks them) or the pages must literally be slugged
`product`, `pricing`, `faq`, `contacts`, `contact-request`. `page-legal.php` **must** be a
`Template Name:` file, since three different pages use it.

### Reusable `template-parts/` (repeated markup, one source each)

- `partials/screenshot-frame.php` — the `.itr-frame` + mono caption bar + `.itr-shot` crop
  (5 uses across `01`, `02`, `09`)
- `partials/plan-card.php` — three pricing cards, used identically on `01-main` and `03-pricing`
  (only the Free-card CTA href and two Commercial bullet lines differ)
- `partials/role-card.php` — the five role cards, on `01-main` and `02-product` (different bodies,
  same shell + icon)
- `partials/signal-chain.php` and `partials/signal-badge.php` — `01-main`, `02-product`
- `partials/post-row.php` (blog list row), `partials/post-card.php` (category card),
  `partials/doc-row.php` (docs list row), `partials/breadcrumbs.php` (11 pages),
  `partials/sidebar-cta.php` (accented sidebar card on `08`, `10`, `13`),
  `partials/prev-next.php` (`09`, `12`), `partials/toc-rail.php` (`04`, `07`, `09`, `12`)
- `partials/icon.php` — an inline-SVG Lucide helper; README recommends inlining the needed SVGs
  rather than loading the whole library

### Complete Lucide icon set to inline (52)

**44 named in markup** via `Icon name="…"` or `icon-left`/`icon-right`:
`activity, arrow-right, bell, book-open, boxes, check-circle, chevron-down, chevron-left,
chevron-right, circle-dollar-sign, circle-dot, clipboard-check, clock, contact, corner-down-right,
database, eye, gauge, git-branch, globe, heart-pulse, landmark, layers, link, lock, mail, package,
plug, printer, radio-tower, receipt, repeat, rocket, route-off, scale, search, settings, ship,
shield-check, sliders-horizontal, table-2, terminal, thumbs-down, thumbs-up`

**2 supplied at runtime** by `site-nav`'s `burgerIcon`: `menu`, `x`.

**6 rendered from inside DS components:** `alert-triangle` (SignalBadge `incident`, Alert `warning`),
`info` (Alert `info` — used by the Alert on `12-docs-article`), `check` and `minus` (Checkbox on
`06-contact-request`), `trending-up` / `trending-down` (MetricTile `direction` on `02-product`).
`chevron-down` (Select) and `x` (Alert dismiss) are already in the list above.
`alert-octagon` (Alert `critical`) is defined in the bundle but **unused** in these mockups.

Stroke width `1.75`, `currentColor`, per README.

### Assets / options

- Three hero/section screenshots + one product shot + one in-article shot → media library, surfaced
  through theme options or post meta (README says do not hardcode).
- Logo SVGs are theme chrome, already in `theme/assets/img/logo/`.
- Site-identity strings that recur across templates and should have one source:
  `sb@by-sky.net`, `intera-roles.com`, `In beta — Early Adopter programme open`,
  `© 2026 INTERA`, `Same working day, in most cases`, `English, Russian`.

---

## 18. Open questions the export does not settle

1. **Reading time** (`6 min`, `4 min read`, `3 min`…) appears on 08, 09, 10, 12 and 13 with no source.
   Post meta field, or computed from word count at render time?
2. **Doc article version stamp** (`v0.003`) and `Updated 2026-08-13` on `12` — meta field, or
   `the_modified_date()` plus a version taxonomy/meta?
3. **`13-docs-category` sub-groups** (`First steps` / `Data and modelling` / `Good to know`) need a
   second grouping level under `docs_category`. Hierarchical child terms, or a per-post section meta?
   Ordinals `01`…`10` also need `menu_order` (or a loop index — but the numbering continues across
   groups, so it is per-term, not per-group).
4. **FAQ content ownership** — 16 hardcoded `<details>`. Editor content parsed into `<details>`,
   an ACF repeater, or a `faq` CPT? README does not mention one, and the map has no `single-faq.php`.
5. **Pricing figures** are template copy today but are business data that will change. Editor content,
   options, or a `plan` CPT?
6. **Legal page "Contents" aside and article `<h2>` ids** must be generated from `the_content()` —
   PHP heading parse, JS, or an editor-maintained field?
7. **`09-blog-post` aside anchors are literally `href="#"`** and its `<h2>`s carry no ids. Same
   generation question as (6), plus deciding whether the aside is auto or manual.
8. **`Copy link` (09) and `Yes` / `No` feedback (12)** have no handlers. Progressive JS + what
   no-JS fallback? Does feedback persist anywhere (REST endpoint, or just a link to the contact form)?
9. **`Print this page` (07)** — `window.print()` in `intera.js`, or a print stylesheet plus a real link?
10. **Form backend for `06`** — README defers this ("CF7 / Gravity / собственный обработчик").
    Also undesigned: validation error states, spam protection, and where `REQ-2026-0148` comes from.
11. **Footer menu locations** — one menu with three columns derived from structure, or three (four)
    separate `register_nav_menus` areas? Only `primary` and `footer_legal` exist in `inc/setup.php`.
12. **The nav's 1040px switch is JavaScript** (`window.innerWidth >= 1040`), while `mobile.css`
    breaks at 900/760. Re-express as a CSS media query at 1040px, or keep a JS switch?
13. **`docs_category` term meta** (icon name + colour family) has no storage mechanism yet —
    term meta + an admin field, or a hardcoded slug→icon map in PHP?
14. **Footer links `02-product.dc.html#it` and `#partners` point at anchors that only exist on
    `01-main.dc.html`.** Decide the correct destination before wiring the menu.
15. **Blog list sort order** — `08-blog` lists 3 life stories then 3 release notes, not by date.
    Two queries, or plain date order?
16. **Multilingual (RU/EN)** — README §162 says it is not designed in and must be decided before
    template assembly.
17. **Fonts and icons from CDN** — README §160 says move IBM Plex to local files for production;
    `tokens/fonts.css` currently points at Google Fonts, and the theme ships DS files byte-identical.
    Changing it means editing a token file, which the repo rules treat as the source of truth.
