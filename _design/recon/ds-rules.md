# INTERA design system — rules that constrain the theme

Distilled from `_design/_ds/intera/readme.md` (271 lines, the full system guide), cross-checked
against `_design/README.md` (the handoff), `_design/CLAUDE.md` (project interaction rules) and the
eight token files in `_design/_ds/intera/tokens/`.

Every value below is quoted from those files. Where two files disagree, the conflict is flagged in
[§13 Conflicts](#13-conflicts-the-theme-build-must-resolve) rather than silently resolved.

`theme/_ds/intera/tokens/` is **byte-identical** to `_design/_ds/intera/tokens/` (verified with
`diff -rq`, no differences). Tokens are therefore already the source of truth in the theme; nothing
in this document should be re-encoded as a literal in PHP or `assets/css/intera.css`.

---

## 1. What the product is (needed to write correct copy)

INTERA is a **B2B operational-visibility product**, currently in **beta**, with an Early Adopter
programme offering **the first 12 months free**. It connects to systems a company already runs —
ERP, CRM, billing, mediation, spreadsheets, internal tools — applies the business's own logic on
top, and shows each manager what changed, what disagrees and what needs action. **It replaces
nothing.**

Audience: middle management — Operations Manager / COO, Finance, Billing, Revenue Assurance,
Customer Operations, Commercial, department heads, IT/System managers. The key visitor is the
**middle-management champion**. Secondary: CEOs/owners, systems integrators, consultants, resellers.

### Product vocabulary — use these words exactly

| Term | Meaning |
|---|---|
| **Role** | A ready-made business module built around a real responsibility (Finance Control, Operations Oversight, Revenue Assurance, Customer Health, System Integrity) |
| **Metric** | A watched number inside a role |
| **Event** | Something important changed |
| **Reconciliation** | Things that should agree — don't |
| **Incident** | Something requires attention and action |
| **Pattern** | What keeps happening, and under which conditions |
| **Market package** | A reusable bundle a partner builds for an industry |

The chain **Event → Reconciliation → Incident → Pattern** is the brand's signature diagram
(`SignalChain`). **Its order and colours are fixed.**

Tone references named by the client: digitate.com, unframe.ai, slack.com — "calm, white-dominant,
enterprise-serious, with a small number of saturated accent colours".

---

## 2. Voice and copy rules

> "The voice is a competent colleague explaining a system, not a vendor selling one. The product is
> deliberately unglamorous — it saves money by removing manual checking. The copy should sound like
> that: factual, specific, slightly understated."

- **Second person, present tense.** "Your systems stay." "See what needs attention." Never "we
  empower organisations to…". The subject of most sentences is *you* or *INTERA*, **never "the
  platform"**.
- **Sentence case everywhere** — headlines, buttons, labels, nav. The **only** uppercase is the
  wordmark, eyebrows and table headers (letterspaced, 12px).
- **Short declaratives, often in pairs.** "Less chasing. Fewer surprises." "Solve once. Adapt.
  Deploy again." Fragments are fine when they land.
- **No exclamation marks. No emoji. Ever.** Not in product, not on the site, not in error states.
- **Say the limit out loud.** "INTERA doesn't replace your team." "Your systems stay." "Do not start
  by implementing INTERA in your whole company." Naming what the product will *not* do is described
  as "the single most persuasive move in this brand".
- **Numbers are concrete and mono-set.** "4,812 records differ by more than 0.5%", not "significant
  discrepancies detected".
- **Headlines carry the argument; sub-heads carry the proof.** H2 states the promise, body gives the
  mechanism.
- **Buttons are verb phrases the visitor would actually say**: "Get Early Access", "Bring us a real
  problem", "I have a problem INTERA could solve", "Talk to us about your deployment".
  **Never "Learn more", never "Submit".**
- **Empty and error states state the fact, then the next step.** "No data received from ERP since
  03:20 UTC." — not "Oops! Something went wrong."
- **Banned hype vocabulary**: revolutionary, seamless, unlock, transform, next-generation,
  AI-powered, game-changing, synergy, 10x. Test: "If a sentence would survive being pasted into a
  competitor's site, rewrite it."
- Russian-language material follows the same rules; IBM Plex Sans carries Cyrillic.

### Write / don't write (verbatim from the readme)

| Write | Don't write |
|---|---|
| See what needs attention. Before someone has to ask. | Unlock real-time enterprise intelligence |
| Your systems stay. INTERA makes them more useful. | Replace your fragmented legacy stack |
| Start with one real problem. | Begin your digital transformation journey |
| 4,812 records differ by more than 0.5%. | Significant anomalies detected! |
| Bring us a real problem | Submit |

**Theme consequence:** any string the theme itself emits — 404 copy, search-empty state, comment
form labels, "read more", pagination, form validation, the success state of the contact form —
is brand copy and is bound by all of the above. Default WordPress strings ("Oops! That page can't
be found.", "Submit", "Learn more", "Read More →") violate it.

---

## 3. Palette — and what each colour is allowed to mean

The brief: "calm, not fashionable, slightly boring — a system that earns trust by being unexcitable
— with a small number of bright colour spots on white."

### Backgrounds
- The page is **white** (`--surface-page`).
- Secondary bands are `--surface-sunken` (#F8F9FB) — explicitly "not grey cards on grey".
- **At most two background values per page**, plus the dark bands.
- Dark bands: the **hero** is `--ink-950` (#07101C); the **Early Adopter section and the footer**
  are `--ink-900` (#0E1A2B).

### The hero
"White page, dark opening": full-bleed `--ink-950` carrying a hairline column grid at
`rgba(255,255,255,.07)` and the mark drawn very large as a low-opacity outline. The white product
frame sits on it with `--shadow-overlay`, "so the UI is the brightest thing on the page". On dark,
the primary button is `variant="inverse"` (white) and its partner is `outlineInverse`.

### Ink neutrals carry all text and structure
`--ink-900` headings/body · `--ink-600` secondary prose · `--ink-400` captions · `--ink-100`/`--ink-200` rules.

### The single interface accent
**Intera Blue `--blue-600` (#1A4FD6)** is the *only* interface accent: links, primary buttons, focus
rings, active tabs. "It is structural, not decorative."

### The four signal colours — meaning only, never decoration

| Signal | Colour | Token | Soft fill | Outline |
|---|---|---|---|---|
| Event | blue #1A4FD6 | `--signal-event` | `--signal-event-soft` | `--signal-event-line` |
| Reconciliation | teal #0E8F8A | `--signal-reconciliation` | `--signal-reconciliation-soft` | `--signal-reconciliation-line` |
| Incident | amber #C97A05 | `--signal-incident` | `--signal-incident-soft` | `--signal-incident-line` |
| Pattern | violet #6B4FE0 | `--signal-pattern` | `--signal-pattern-soft` | `--signal-pattern-line` |

Plus green/red for OK and critical (`--status-ok`, `--status-critical`).

> "A colour on this page always answers 'what kind of thing is this?' — never 'make this look nicer'."

**Roughly 85% of any screen is white/ink. Colour is the exception, which is why it works.**

### Gradients — one sanctioned use only
**No gradients on anything you can click or read.** Buttons, cards, badges, icons and text are flat
fills, always.

The single exception is the **ambient section wash**: one or two very large, very low-opacity radial
tints (`--wash-blue`, `--wash-teal`, `--wash-amber`, `--wash-violet` at 11–13%; `--wash-*-dark` at
20–30% on dark bands) behind a section. Rules that come with it:
- Washes go **only** on the dark bands and the cream (`--surface-sunken`) bands — **never on a plain
  white section**.
- They **alternate**: a washed section is always followed by an unwashed one.
- "They must never be strong enough to read as a coloured background — if you can name the colour at
  a glance, turn it down."
- **No mesh, no linear gradients, no two-colour blends.**

---

## 4. Typography

- **IBM Plex Sans** for everything; **IBM Plex Mono** for **every number, identifier, timestamp,
  source-system name and delta**. "The mono/sans split *is* the type system: prose vs. facts."
- Scale: Display 48/1.12/-0.02em · H1 38 · H2 31 · H3 25 · H4 21 — all **600 weight at -0.01em**.
  Body 16/1.6 · lead 18/1.6 · UI 15/1.45 · caption 12.
- Weights used: **400, 500, 600**. "**700 is reserved and effectively unused** — bold is not how this
  brand emphasises." (`--weight-bold:700` and the 700 `@font-face` exist but must not be applied.)
- **Eyebrows and table headers**: 12px, 600, +0.09em, uppercase, ink-500 (or blue-600 on marketing).
- **Measure caps at ~640px for prose** (`--layout-prose:640px`).
- Sizes are fixed px — "no fluid clamping — steady and predictable" (the mockups do clamp *spacing*,
  not type).

**Theme consequence:** `assets/css/intera.css` prose styles must set `--font-mono` on any number/date
run inside `the_content()` output where the design shows mono (post dates, versions, IDs, deltas),
and cap `.prose` measure at `var(--layout-prose)`.

---

## 5. Grid, container, rhythm

- **4px base grid** with a **2px half-step** for dense product tables.
- Page container: **1160px** with **24px** side padding — this is what all 13 mockups actually use
  (`max-width: 1160px` appears 50 times; horizontal padding is `clamp(20px, 5vw, 24px)`). The DS
  readme says "1160px, 32px gutters" and the token `--layout-max` is `1200px`; see §13.
- **Marketing sections: 88–96px vertical padding.** In the mockups this is written as
  `clamp(51px, 7vw, 88px)` and `clamp(53px, 7vw, 92px)`.
- Product screens: 20px padding, 12–16px gaps.
- Layout is **left-aligned and grid-based**. **Centred type appears once per page at most** (the
  pricing section head). **Nothing is diagonal, rotated or offset for effect.**
- Fixed elements: the site's top nav (sticky, translucent white, **blur 8px**, 1px bottom rule) and
  the product sidebar (232px, sunken, fixed).
- Responsive breakpoints (from `_design/mobile.css`): **900px** (4→2 column grids, two-column splits
  collapse, floating cards become the next block) and **760px** (single column, screenshots become a
  readable crop, wide tables scroll horizontally), plus `@media (hover: none)` to drop sticky hover
  transforms on touch. The handoff says to rewrite these as normal CSS **without `!important`**,
  keeping the same breakpoints and behaviour.
- Tile grids: a label must **never wrap** — size the columns to the longest label (e.g. "Market
  packages") and add `white-space: nowrap`, so every tile in a grid is the same height.

---

## 6. Borders, cards, elevation, radii

> "A 1px border is the primary separator, not a shadow — and it is drawn to be seen."

**The border ladder:**
`--border-hairline` (#E4E9F0, internal rules inside a component) → `--border-subtle` (#D3DBE6) →
`--border-card` (#CBD3DE, the outline of every card, tile and table) → `--border-default` (#B6C2D2,
controls) → `--border-strong` (#8390A2, hover).

**Cards:** white, 1px `--border-card`, **8px radius**, `--shadow-xs` (almost invisible), **24px
padding**. "The definition comes from the outline, never from a heavier shadow or a grey fill."

**Accent on a card** = a **3px rule along the top edge** in a signal colour, plus a full outline in
that signal's `*-line` token (`accent` + `accentLine` on `Card`).
**"A coloured *left* border is off-brand and must not appear."**

**Tinted components** (Badge, Alert, SignalBadge, Tag) keep their pale fill but take a **saturated
200-level outline** (`--status-*-line`, `--signal-*-line`) so they read at a glance on white.

**Elevation ladder:** xs (cards) → sm (hover) → md (menus) → lg (toasts) → overlay (dialogs).
"Only things that genuinely float get more than xs."

**Radii:** 2 (checkbox) · 3 (badges) · 5 (buttons, inputs) · 8 (cards) · 12/16 (large panels).
Full rounding (`--radius-round:999px`) **only** for status dots, switches and avatars.
**No pill buttons.**

---

## 7. Motion

DS baseline: **80–260ms, `cubic-bezier(.2,.6,.25,1)`**. Colour and opacity fades, small position
slides. "Content is present when the page loads. The switch knob slides; nothing else moves on its own."

**Forbidden:** no bounce, no overshoot, no spring, no parallax, no scroll-triggered reveals, no
counters animating up, no scale-up on text.

`prefers-reduced-motion` is honoured globally in `tokens/base.css`
(`*{animation-duration:.01ms!important;transition-duration:.01ms!important}`), **and** every mockup
ships its own reduced-motion block that kills hover transforms — the handoff says to carry that block
over whole.

### Site-level amendment (from `_design/CLAUDE.md`)
This site is allowed to be more interactive than the system's default "nothing moves" rule.
**Motion budget on the site: 150–260ms**, same easing, **transform + opacity + colour only.**

**Every card, tile, row and product frame reacts to hover.** Shared classes, declared in each
mockup's `<helmet>` and to be lifted into `theme/assets/css/intera.css`:

| Class | Where | Hover behaviour |
|---|---|---|
| `.itr-lift` | cards | 5px rise, stronger border and shadow |
| `.itr-row` | list rows | 8px slide right, white fill, `--blue-200` border |
| `.itr-tile` | compact label tiles | 4px rise, `--blue-50` fill |
| `.itr-panel` | panels on dark bands | 4px rise, lighter background and border |
| `.itr-frame` | product screenshot frames | 6px rise, deeper shadow |
| `.itr-live-dot` / `.itr-live-halo` | "in beta" / "live" indicator | pulsing green dot, `--green-500` |

---

## 8. Interactive states and focus

- **Hover:** buttons darken one step (blue-600 → blue-700); secondary/ghost surfaces pick up
  `--surface-hover` (#F1F4F8) and a stronger border; rows tint to `--surface-hover`; links darken and
  their underline strengthens. **Never opacity fades, never scale-up.**
- **Press:** one further step darker (`--blue-900` / `--surface-active`). **No shrink transform.**
- **Focus:** **2px `--focus-ring` outline at 2px offset** for keyboard focus; form controls
  *additionally* take a 1px blue border + 3px soft ring (`--ring-focus`). **Focus is always visible.**
  Already implemented in `tokens/base.css` for `a`, `button`, `input`, `select`, `textarea`,
  `[tabindex]` via `:focus-visible`.
- **Disabled:** 45% opacity, `not-allowed` cursor. **No greyed-out custom palette.**
- **Selected:** `--blue-50` fill + `--blue-200` border (tags, filters), **or** a 2px blue underline
  (tabs).
- **Links** (from `base.css`): `--text-link` with a `1px solid var(--blue-200)` bottom border;
  on hover `--text-link-hover` and the border becomes `--blue-600`.

**Theme consequence:** the export's `style-hover="…"` attribute is **preview-only, not CSS**. Every
hover/press/focus/selected state must be re-implemented in `theme/assets/css/intera.css`, keyed by a
class, using the tokens above.

### Transparency and blur — exactly two places
1. The sticky nav: **92% white + 8px blur**.
2. The dialog scrim: **`rgba(14,26,43,.38)`**.

"Nothing else is translucent; **no frosted cards**."

---

## 9. Imagery

- **Imagery is product UI, not stock photography.**
- The SOW allows **at most three product visuals on the homepage**: one Role/dashboard, one
  Incident/Reconciliation, one Pattern Studio — **no dashboard gallery**. In the export these are
  `ship-5` (fleet state, hero), `ship-2` (attention queue, signals section), `ship-4` (dependencies,
  IT section); plus `ship-3` on the product page and `ship-2` in the blog article.
- They sit in a **light browser frame with `--shadow-lg`**.
- If photography is ever introduced: cool, neutral, desaturated, **no grain, no colour wash**.
- **"There are no illustrations, textures or patterns in this brand, and none should be invented."**
  The handoff repeats it: no stock photography and no illustration exists or should appear.
- Final screenshots will be captured from the live system by the client — the theme must serve them
  through the media library / fields, **not hardcode them**.

---

## 10. Iconography

**Lucide v0.469.0** (a flagged substitution — no icon assets were supplied; swap when the client has
a house set).

- Stroke weight **1.75** (lighter than Lucide's default 2), `currentColor`, **round caps and joins**.
- Sizes: 12–13 (inside badges) · 14 (with caption text) · 15–16 (buttons, table cells, nav) · 18
  (default UI) · 20 (feature marks). **Nothing above 24** — use a larger surface instead.
- **Icons are annotation, never decoration.** "Every icon on a screen labels a thing that has a name;
  no icon appears purely to fill a card."
- **Fixed pairings that must not be remapped:** Event = `activity` · Reconciliation = `scale` ·
  Incident = `alert-triangle` · Pattern = `git-branch` · Connect = `plug` · Source system = `database`.
- **Role icons:** Finance Control = `circle-dollar-sign` · Operations Oversight = `gauge` ·
  Revenue Assurance = `scale` · Customer Health = `heart-pulse` · System Integrity = `shield-check`.
- **No emoji, ever. No unicode arrows/bullets used as icons** (`iconRight="arrow-right"`, not "→").
  **No filled or duotone icon styles.**

**Theme consequence:** the handoff says to **inline the needed SVGs** rather than load the whole CDN
library. Inlined paths must preserve `stroke-width="1.75"`, `stroke="currentColor"`,
`stroke-linecap="round"`, `stroke-linejoin="round"`.

---

## 11. Logo

An **original mark** designed for this system (no artwork was supplied): two overlapping frames whose
intersection is the only solid area — "two systems, and the place where they agree". Flat, geometric,
**no gradient or shadow**.

| File (export `assets/logo/`) | Use |
|---|---|
| `logo-horizontal.svg` | Default lockup — mark + wordmark |
| `logo-horizontal-inverse.svg` | Same, on the dark bands |
| `logo-mark.svg` / `logo-mark-inverse.svg` | Glyph only, for tight chrome |
| `logo-square.svg` | App-icon / avatar tile (ink-900, 14px radius) |

- Wordmark: **IBM Plex Sans 600, uppercase, +0.09em**.
- **Minimum mark size 16px.** Clear space = the mark's corner radius on all sides.
- **Never recolour outside the ink/blue pair, never rotate, never add a gradient or shadow.**

---

## 12. The forbidden list — what a reviewer will check

Everything the readme (or the project `CLAUDE.md`) forbids outright, in one place:

**Copy**
1. No exclamation marks. No emoji. Ever — product, site, error states.
2. No hype vocabulary: revolutionary, seamless, unlock, transform, next-generation, AI-powered,
   game-changing, synergy, 10x.
3. Never "Learn more". Never "Submit" on a button.
4. Never "we empower organisations to…"; never make "the platform" the subject.
5. No Title Case outside the wordmark, eyebrows and table headers.
6. No filler copy, no invented stats (project `CLAUDE.md`).
7. No "Oops! Something went wrong." style error/empty states.

**Colour**
8. No gradients on anything you can click or read — buttons, cards, badges, icons, text are flat.
9. No mesh gradients, no linear gradients, no two-colour blends anywhere.
10. Washes never on a plain white section; never two washed sections in a row; never strong enough
    to name the colour at a glance.
11. More than two background values on one page (plus the dark bands) is out of spec.
12. A colour used decoratively rather than to answer "what kind of thing is this?" is out of spec.
13. Signal colour/order remapping (Event blue, Reconciliation teal, Incident amber, Pattern violet)
    is forbidden — the chain's order and colours are fixed.

**Form**
14. **No pill buttons.** Full rounding only for status dots, switches, avatars.
15. **A coloured left border on a card must not appear** — accent is a 3px rule on the **top** edge.
16. Card definition must not come from a heavier shadow or a grey fill.
17. Nothing diagonal, rotated or offset for effect.
18. Centred type more than once per page.
19. No frosted cards; translucency exists only in the sticky nav and the dialog scrim.
20. 700 weight is reserved and effectively unused.

**Motion / states**
21. No bounce, overshoot, spring, parallax, scroll-triggered reveals, animated counters, scale-up on
    text.
22. Hover must not be an opacity fade or a scale-up; press must not shrink.
23. Focus must never be removed — it is always visible.
24. Disabled must not use a custom grey palette (45% opacity + `not-allowed`).
25. A missing `prefers-reduced-motion` block is a defect.
26. Sticky hover transforms on touch (`@media (hover: none)` must neutralise them).

**Icons / imagery / logo**
27. No emoji as icons; no unicode arrows or bullets standing in for icons; no filled or duotone icon
    styles; nothing above 24px.
28. No icon that labels nothing.
29. Fixed icon pairings must not be remapped.
30. No stock photography, illustrations, textures or patterns — none exist and none may be invented.
31. More than three product visuals on the homepage (no dashboard gallery).
32. Logo: no recolour outside ink/blue, no rotation, no gradient, no shadow, never below 16px.

**Build**
33. Tokens must not be duplicated as literals in PHP or `intera.css` (repo `CLAUDE.md`).
34. `style-hover="…"` is not CSS and must not be copied into the theme as if it were.
35. `!important`-driven mobile overrides should not survive into the theme.

---

## 13. Conflicts the theme build must resolve

| # | Conflict | Sources |
|---|---|---|
| A | **Container width.** DS token `--layout-max` is `1200px`; the DS readme says "Page container 1160px"; the mockups use `max-width: 1160px` (50 occurrences). | `tokens/spacing.css` vs `readme.md` vs `*.dc.html` |
| B | **Gutters.** DS readme says "32px gutters" and `--pad-page-x` is `var(--space-7)` = 32px; the handoff says "боковые отступы 24px" and the mockups use `clamp(20px, 5vw, 24px)`. | `readme.md` / `tokens/spacing.css` vs `_design/README.md` / mockups |
| C | **Motion floor.** DS says 80–260ms; the project `CLAUDE.md` narrows the site to 150–260ms. | `readme.md` vs `_design/CLAUDE.md` |
| D | **Section padding token.** `--pad-section-y` is `var(--space-12)` = 96px flat; the mockups use `clamp(51px, 7vw, 88px)` / `clamp(53px, 7vw, 92px)`. | `tokens/spacing.css` vs mockups |
| E | **Motion default.** The DS default is "nothing moves"; the site explicitly overrides it with hover on every card, tile, row and frame. | `readme.md` vs `_design/CLAUDE.md` |
| F | **Fonts and icons load from CDN** (`fonts.css` `@import` to Google Fonts; Lucide from unpkg). The handoff says to localise both for production; doing so means editing `fonts.css`, which the repo rules treat as byte-identical DS source. | `tokens/fonts.css` vs `_design/README.md` + repo `CLAUDE.md` |

---
## 14. Token reference

All **230** custom properties defined in `_design/_ds/intera/tokens/*.css`, by file.
`fonts.css` and `base.css` define **no** custom properties (they carry `@import`/`@font-face` and the
global element reset respectively). Values are quoted exactly; `→` marks an alias to another token.

`styles.css` is the only manifest, and the import order is load-bearing:
`fonts → colors → typography → spacing → radius → elevation → motion → base`.

### 14.1 `tokens/colors.css` — 113 tokens

#### Neutral ink ramp — "the calm white-paper base"

| Token | Value | For |
|---|---|---|
| `--white` | `#FFFFFF` | Page and card surface; inverse text on dark |
| `--ink-25` | `#F8F9FB` | The one secondary band colour (`--surface-sunken`) |
| `--ink-50` | `#F1F4F8` | Hover fill on secondary/ghost surfaces and rows |
| `--ink-100` | `#E4E9F0` | Hairline rules inside a component; pressed surface |
| `--ink-150` | `#D3DBE6` | Subtle border step |
| `--ink-200` | `#CBD3DE` | **The card/tile/table outline** |
| `--ink-250` | `#B6C2D2` | Control borders (inputs, secondary buttons) |
| `--ink-300` | `#A8B3C2` | Spare neutral step |
| `--ink-400` | `#8390A2` | Captions; strong border on hover |
| `--ink-500` | `#647285` | Eyebrows, table headers, neutral status |
| `--ink-600` | `#4B5A6E` | Secondary prose |
| `--ink-700` | `#354558` | Dark neutral step |
| `--ink-800` | `#213142` | Dark neutral step |
| `--ink-900` | `#0E1A2B` | Headings and body text; Early Adopter band and footer |
| `--ink-950` | `#07101C` | The hero band only |

#### Intera Blue — "primary, structural, unexciting on purpose"

| Token | Value | For |
|---|---|---|
| `--blue-50` | `#EEF3FE` | Selected fill (tags, filters); `.itr-tile` hover fill |
| `--blue-100` | `#D9E5FC` | `::selection` background |
| `--blue-200` | `#B4CBF8` | Link underline; selected border; Event outline; `.itr-row` hover border |
| `--blue-500` | `#2E6BF0` | Focus ring colour; wash source |
| `--blue-600` | `#1A4FD6` | **The only interface accent** — links, primary buttons, active tabs |
| `--blue-700` | `#12379E` | Hover step for primary action and links |
| `--blue-900` | `#0B2263` | Pressed step for primary action |

#### Signal accents — "the bright spots on white"

| Token | Value | For |
|---|---|---|
| `--amber-50` | `#FEF6E8` | Incident / warning soft fill |
| `--amber-100` | `#FBE7C2` | Amber tint step |
| `--amber-200` | `#F3C77E` | Incident / warning outline (200-level) |
| `--amber-500` | `#E8930C` | Amber wash source |
| `--amber-600` | `#C97A05` | **Incident** and warning |
| `--amber-700` | `#8F5602` | Deep amber step |
| `--teal-50` | `#E8F7F6` | Reconciliation soft fill |
| `--teal-100` | `#C9EBE9` | Teal tint step |
| `--teal-200` | `#8FD7D2` | Reconciliation outline |
| `--teal-500` | `#14ABA4` | Teal wash source |
| `--teal-600` | `#0E8F8A` | **Reconciliation** |
| `--teal-700` | `#0A605C` | Deep teal step |
| `--violet-50` | `#F2EFFE` | Pattern soft fill |
| `--violet-100` | `#E2DBFB` | Violet tint step |
| `--violet-200` | `#C2B4F5` | Pattern outline |
| `--violet-500` | `#8570EC` | Violet wash source |
| `--violet-600` | `#6B4FE0` | **Pattern** |
| `--violet-700` | `#4A34A8` | Deep violet step |
| `--green-50` | `#EBF8F0` | OK soft fill |
| `--green-100` | `#CFEEDD` | Green tint step |
| `--green-200` | `#9FD9BA` | OK outline |
| `--green-500` | `#2AA660` | **The live/beta pulsing dot** (`.itr-live-dot`) |
| `--green-600` | `#1E874B` | OK status |
| `--green-700` | `#146336` | Deep green step |
| `--red-50` | `#FDEDEC` | Critical soft fill |
| `--red-100` | `#F9D9D6` | Red tint step |
| `--red-200` | `#F0AFA9` | Critical outline |
| `--red-500` | `#E04B42` | Danger ring source |
| `--red-600` | `#C4342C` | Critical status |
| `--red-700` | `#8E211B` | Deep red step |

#### Semantic — text

| Token | Value | For |
|---|---|---|
| `--text-primary` | → `--ink-900` | Headings and body |
| `--text-secondary` | → `--ink-600` | Secondary prose |
| `--text-muted` | → `--ink-400` | Captions |
| `--text-inverse` | → `--white` | Text on dark bands |
| `--text-link` | → `--blue-600` | Link colour |
| `--text-link-hover` | → `--blue-700` | Link hover |
| `--text-on-accent` | → `--white` | Text on a blue fill |

#### Semantic — surfaces

| Token | Value | For |
|---|---|---|
| `--surface-page` | → `--white` | The page. Always white |
| `--surface-card` | → `--white` | Cards are white, defined by their outline |
| `--surface-sunken` | → `--ink-25` (#F8F9FB) | The second (and last) background value |
| `--surface-muted` | → `--ink-50` | Muted fill |
| `--surface-inverse` | → `--ink-900` | Early Adopter band, footer |
| `--surface-accent-soft` | → `--blue-50` | Soft blue fill |
| `--surface-hover` | → `--ink-50` (#F1F4F8) | Hover fill for secondary surfaces and rows |
| `--surface-active` | → `--ink-100` | Pressed fill |

#### Semantic — borders ("the primary separator in this system")

| Token | Value | For |
|---|---|---|
| `--border-hairline` | → `--ink-100` (#E4E9F0) | Internal rules inside a component |
| `--border-subtle` | → `--ink-150` (#D3DBE6) | Second step of the ladder; `hr` |
| `--border-default` | → `--ink-250` (#B6C2D2) | Controls |
| `--border-strong` | → `--ink-400` (#8390A2) | Hover |
| `--border-card` | → `--ink-200` (#CBD3DE) | Every card, tile and table outline |
| `--border-accent` | → `--blue-600` | Accented outline |
| `--border-inverse` | `rgba(255,255,255,.16)` | Borders on the dark bands |

#### Semantic — interactive

| Token | Value | For |
|---|---|---|
| `--action-primary` | → `--blue-600` | Primary button fill |
| `--action-primary-hover` | → `--blue-700` | One step darker on hover |
| `--action-primary-active` | → `--blue-900` | One further step on press |
| `--action-secondary-border` | → `--ink-250` | Secondary button border |
| `--focus-ring` | → `--blue-500` | The 2px keyboard focus outline |
| `--focus-ring-offset` | → `--white` | Offset colour behind the ring |

#### Product signal vocabulary — Event / Reconciliation / Incident / Pattern

| Token | Value | For |
|---|---|---|
| `--signal-event` | → `--blue-600` | Event |
| `--signal-event-soft` | → `--blue-50` | Event pale fill |
| `--signal-event-line` | → `--blue-200` | Event 200-level outline / 3px top rule |
| `--signal-reconciliation` | → `--teal-600` | Reconciliation |
| `--signal-reconciliation-soft` | → `--teal-50` | Reconciliation pale fill |
| `--signal-reconciliation-line` | → `--teal-200` | Reconciliation outline |
| `--signal-incident` | → `--amber-600` | Incident |
| `--signal-incident-soft` | → `--amber-50` | Incident pale fill |
| `--signal-incident-line` | → `--amber-200` | Incident outline |
| `--signal-pattern` | → `--violet-600` | Pattern |
| `--signal-pattern-soft` | → `--violet-50` | Pattern pale fill |
| `--signal-pattern-line` | → `--violet-200` | Pattern outline |

#### Ambient washes — the one sanctioned gradient

| Token | Value | For |
|---|---|---|
| `--wash-blue` | `rgba(46,107,240,.13)` | Section wash on sunken bands |
| `--wash-teal` | `rgba(20,171,164,.13)` | Section wash on sunken bands |
| `--wash-amber` | `rgba(232,147,12,.11)` | Section wash on sunken bands |
| `--wash-violet` | `rgba(133,112,236,.13)` | Section wash on sunken bands |
| `--wash-blue-dark` | `rgba(46,107,240,.30)` | Section wash on the dark bands |
| `--wash-teal-dark` | `rgba(20,171,164,.20)` | Section wash on the dark bands |

> Comment in the file: "very large, very low opacity, behind content only. The one sanctioned use of
> a gradient in this brand: never on a component, button, card or icon; only as a soft tint on a full
> section background."

#### Status

| Token | Value | For |
|---|---|---|
| `--status-ok` | → `--green-600` | OK |
| `--status-ok-soft` | → `--green-50` | OK fill |
| `--status-ok-line` | → `--green-200` | OK outline |
| `--status-warning` | → `--amber-600` | Warning |
| `--status-warning-soft` | → `--amber-50` | Warning fill |
| `--status-warning-line` | → `--amber-200` | Warning outline |
| `--status-critical` | → `--red-600` | Critical |
| `--status-critical-soft` | → `--red-50` | Critical fill |
| `--status-critical-line` | → `--red-200` | Critical outline |
| `--status-info` | → `--blue-600` | Info |
| `--status-info-soft` | → `--blue-50` | Info fill |
| `--status-info-line` | → `--blue-200` | Info outline |
| `--status-neutral` | → `--ink-500` | Neutral |
| `--status-neutral-soft` | → `--ink-50` | Neutral fill |
| `--status-neutral-line` | → `--ink-250` | Neutral outline |

### 14.2 `tokens/typography.css` — 52 tokens

#### Families

| Token | Value | For |
|---|---|---|
| `--font-sans` | `"IBM Plex Sans","Segoe UI",Helvetica,Arial,sans-serif` | Everything that is prose or UI |
| `--font-mono` | `"IBM Plex Mono",ui-monospace,SFMono-Regular,Menlo,monospace` | Every number, identifier, timestamp, source-system name, delta |

#### Size scale — px, "no fluid clamping — steady and predictable"

| Token | Value | For |
|---|---|---|
| `--text-2xs` | `11px` | Smallest label step |
| `--text-xs` | `12px` | Caption, eyebrow, table header |
| `--text-sm` | `13px` | Mono data size |
| `--text-md` | `15px` | UI text (buttons, nav, controls) |
| `--text-base` | `16px` | Body |
| `--text-lg` | `18px` | Lead paragraph |
| `--text-xl` | `21px` | H4 |
| `--text-2xl` | `25px` | H3 |
| `--text-3xl` | `31px` | H2 |
| `--text-4xl` | `38px` | H1 |
| `--text-5xl` | `48px` | Display |
| `--text-6xl` | `60px` | Largest step (above Display; unused by the scale spec) |

#### Weights

| Token | Value | For |
|---|---|---|
| `--weight-regular` | `400` | Body |
| `--weight-medium` | `500` | Emphasis, mono medium |
| `--weight-semibold` | `600` | All headings, eyebrows, wordmark |
| `--weight-bold` | `700` | **Reserved and effectively unused — do not apply** |

#### Leading and tracking

| Token | Value | For |
|---|---|---|
| `--leading-tight` | `1.12` | Display |
| `--leading-snug` | `1.25` | Headings |
| `--leading-normal` | `1.45` | UI text, captions |
| `--leading-relaxed` | `1.6` | Body and lead |
| `--tracking-tight` | `-0.02em` | Display |
| `--tracking-snug` | `-0.01em` | Headings |
| `--tracking-normal` | `0` | Mono data |
| `--tracking-wide` | `0.04em` | Wide label step |
| `--tracking-caps` | `0.09em` | Eyebrows, table headers, wordmark |

#### Semantic type roles

| Token | Value | For |
|---|---|---|
| `--type-display-family` | → `--font-sans` | Display family |
| `--type-display-size` | → `--text-5xl` (48px) | Hero display size |
| `--type-display-weight` | → `--weight-semibold` (600) | Display weight |
| `--type-display-leading` | → `--leading-tight` (1.12) | Display leading |
| `--type-display-tracking` | → `--tracking-tight` (-0.02em) | Display tracking |
| `--type-h1-size` | → `--text-4xl` (38px) | H1 |
| `--type-h2-size` | → `--text-3xl` (31px) | H2 |
| `--type-h3-size` | → `--text-2xl` (25px) | H3 |
| `--type-h4-size` | → `--text-xl` (21px) | H4 |
| `--type-heading-weight` | → `--weight-semibold` (600) | All headings |
| `--type-heading-leading` | → `--leading-snug` (1.25) | All headings |
| `--type-heading-tracking` | → `--tracking-snug` (-0.01em) | All headings |
| `--type-body-size` | → `--text-base` (16px) | Body |
| `--type-body-leading` | → `--leading-relaxed` (1.6) | Body |
| `--type-body-lead-size` | → `--text-lg` (18px) | Lead paragraph |
| `--type-ui-size` | → `--text-md` (15px) | UI text |
| `--type-ui-leading` | → `--leading-normal` (1.45) | UI text |
| `--type-caption-size` | → `--text-xs` (12px) | Captions |
| `--type-caption-leading` | → `--leading-normal` (1.45) | Captions |
| `--type-eyebrow-size` | → `--text-xs` (12px) | Eyebrows, table headers |
| `--type-eyebrow-weight` | → `--weight-semibold` (600) | Eyebrows |
| `--type-eyebrow-tracking` | → `--tracking-caps` (0.09em) | Eyebrows (uppercase) |
| `--type-data-family` | → `--font-mono` | Numbers and identifiers |
| `--type-data-size` | → `--text-sm` (13px) | Data size |
| `--type-data-tracking` | → `--tracking-normal` (0) | Data tracking |

### 14.3 `tokens/spacing.css` — 33 tokens

#### Scale — 4px grid with a 2px half-step for dense data UI

| Token | Value | For |
|---|---|---|
| `--space-0` | `0px` | Zero |
| `--space-half` | `2px` | Half-step, dense product tables only |
| `--space-1` | `4px` | Tight inline gap |
| `--space-2` | `8px` | Inline gap, tight stack |
| `--space-3` | `12px` | Product-screen gap |
| `--space-4` | `16px` | Default stack gap, compact card padding |
| `--space-5` | `20px` | Product-screen padding |
| `--space-6` | `24px` | Card padding, loose stack |
| `--space-7` | `32px` | Group gap, page gutter token |
| `--space-8` | `40px` | Large gap |
| `--space-9` | `48px` | Large gap |
| `--space-10` | `64px` | Block separation |
| `--space-11` | `80px` | Block separation |
| `--space-12` | `96px` | Section vertical padding |
| `--space-13` | `128px` | Largest step |

#### Semantic spacing

| Token | Value | For |
|---|---|---|
| `--gap-inline-tight` | → `--space-1` (4px) | Icon-to-label |
| `--gap-inline` | → `--space-2` (8px) | Inline items |
| `--gap-stack-tight` | → `--space-2` (8px) | Tight vertical stack |
| `--gap-stack` | → `--space-4` (16px) | Default vertical stack |
| `--gap-stack-loose` | → `--space-6` (24px) | Loose vertical stack |
| `--gap-group` | → `--space-7` (32px) | Between groups |
| `--pad-control-y` | `8px` | Control vertical padding |
| `--pad-control-x` | `14px` | Control horizontal padding |
| `--pad-card` | → `--space-6` (24px) | Card padding |
| `--pad-card-compact` | → `--space-4` (16px) | Compact card padding |
| `--pad-section-y` | → `--space-12` (96px) | Marketing section vertical padding (spec range 88–96px) |
| `--pad-page-x` | → `--space-7` (32px) | Page gutter — **conflicts with the 24px used in the mockups (§13 B)** |
| `--layout-max` | `1200px` | Max layout width — **conflicts with the 1160px container (§13 A)** |
| `--layout-prose` | `640px` | Prose measure cap |
| `--layout-narrow` | `880px` | Narrow column |
| `--control-height-sm` | `28px` | Small control |
| `--control-height-md` | `36px` | Default control |
| `--control-height-lg` | `44px` | Large control |

### 14.4 `tokens/radius.css` — 12 tokens

"Deliberately restrained. Nothing here is a pill except status dots and switches."

| Token | Value | For |
|---|---|---|
| `--radius-xs` | `2px` | Checkbox; focus-ring corner radius in `base.css` |
| `--radius-sm` | `3px` | Badges |
| `--radius-md` | `5px` | Buttons and inputs |
| `--radius-lg` | `8px` | Cards |
| `--radius-xl` | `12px` | Large panels |
| `--radius-2xl` | `16px` | Large panels |
| `--radius-round` | `999px` | **Only** status dots, switches, avatars |
| `--radius-control` | → `--radius-md` (5px) | Buttons, inputs |
| `--radius-card` | → `--radius-lg` (8px) | Cards |
| `--radius-panel` | → `--radius-lg` (8px) | Panels |
| `--radius-badge` | → `--radius-sm` (3px) | Badges |
| `--radius-media` | → `--radius-lg` (8px) | Images, screenshot frames |

### 14.5 `tokens/elevation.css` — 9 tokens

"Shadows are almost never the primary separator — a 1px border is. Elevation appears only for things
that genuinely float."

| Token | Value | For |
|---|---|---|
| `--shadow-none` | `none` | No elevation |
| `--shadow-xs` | `0 1px 1px rgba(14,26,43,.04)` | Cards (almost invisible) |
| `--shadow-sm` | `0 1px 2px rgba(14,26,43,.06),0 1px 1px rgba(14,26,43,.04)` | Hover |
| `--shadow-md` | `0 4px 10px rgba(14,26,43,.07),0 1px 2px rgba(14,26,43,.05)` | Menus |
| `--shadow-lg` | `0 12px 28px rgba(14,26,43,.10),0 2px 6px rgba(14,26,43,.05)` | Toasts; product screenshot frames |
| `--shadow-overlay` | `0 24px 56px rgba(7,16,28,.18)` | Dialogs; the hero product frame |
| `--shadow-inset-top` | `inset 0 1px 0 rgba(255,255,255,.6)` | Inner top highlight |
| `--ring-focus` | `0 0 0 3px rgba(46,107,240,.28)` | The 3px soft ring on form controls |
| `--ring-danger` | `0 0 0 3px rgba(224,75,66,.24)` | Error-state ring |

### 14.6 `tokens/motion.css` — 11 tokens

| Token | Value | For |
|---|---|---|
| `--duration-instant` | `80ms` | Fastest DS step (below the site's 150ms floor) |
| `--duration-fast` | `120ms` | Control transitions |
| `--duration-normal` | `180ms` | Surface transitions; the site's default |
| `--duration-slow` | `260ms` | Top of the motion budget |
| `--duration-deliberate` | `400ms` | Longest step; above the stated budget — use only where the DS itself does |
| `--ease-standard` | `cubic-bezier(.2,.6,.25,1)` | **The site easing.** "No bounce, no overshoot. Things move like a well-oiled drawer." |
| `--ease-out` | `cubic-bezier(.16,.84,.44,1)` | Exit-weighted easing |
| `--ease-in` | `cubic-bezier(.5,0,.9,.4)` | Entry-weighted easing |
| `--ease-linear` | `linear` | Pulses and spinners |
| `--transition-control` | `background-color var(--duration-fast) var(--ease-standard),border-color var(--duration-fast) var(--ease-standard),color var(--duration-fast) var(--ease-standard),box-shadow var(--duration-fast) var(--ease-standard)` | Buttons, links, inputs — colour/shadow only |
| `--transition-surface` | `background-color var(--duration-normal) var(--ease-standard),box-shadow var(--duration-normal) var(--ease-standard)` | Cards and panels |

### 14.7 `tokens/fonts.css` — 0 tokens

`@import` of IBM Plex Sans (400/500/600/700) + IBM Plex Mono (400/500) from Google Fonts, then six
explicit `@font-face` rules pinning the same families to `fonts.gstatic.com` woff2 URLs with
`local()` first and `font-display:swap`. **Both are network dependencies** — the handoff calls for
localising the files for production (§13 F).

### 14.8 `tokens/base.css` — 0 tokens

The global reset that the theme inherits, and which constrains what `assets/css/intera.css` may
restate:

- `body` gets `--surface-page`, `--text-primary`, `--font-sans`, `--type-body-size`,
  `--type-body-leading`, `--weight-regular`, antialiasing, `text-rendering:optimizeLegibility`.
- `h1`–`h6` already carry `--type-heading-weight`, `--type-heading-leading`,
  `--type-heading-tracking`, `--text-primary`; `h1`–`h4` take `--type-h1..h4-size`.
- `p{margin:0;text-wrap:pretty}` — **margins are zeroed**, so prose spacing is the theme's job.
- `a` is `--text-link` with `border-bottom:1px solid var(--blue-200)` and `--transition-control`;
  hover goes to `--text-link-hover` with a `--blue-600` underline. **`text-decoration:none`** — the
  underline is a border, not a text decoration.
- `:focus-visible` on `a`, `button`, `input`, `select`, `textarea`, `[tabindex]`:
  `outline:2px solid var(--focus-ring); outline-offset:2px; border-radius:var(--radius-xs)`.
- `code,kbd,samp,pre` → `--font-mono` at `.92em`.
- `hr` → `1px solid var(--border-subtle)`, `var(--space-7) 0` margin.
- `table{border-collapse:collapse}`; `::selection` → `--blue-100` on `--ink-900`.
- Global `@media (prefers-reduced-motion:reduce)` forcing animation/transition durations to `.01ms`.

---

## 15. Quick review checklist for the theme

1. No literal hex, px type size, radius, shadow or duration in `theme/*.php` or
   `theme/assets/css/intera.css` that duplicates a token — `var(--…)` only.
2. Container `1160px`, side padding `clamp(20px, 5vw, 24px)`, section padding
   `clamp(51px, 7vw, 88px)`–`clamp(53px, 7vw, 92px)` (decide §13 A/B/D first, then apply one way
   everywhere).
3. Every `style-hover="…"` in the export has a real CSS rule in `intera.css`, keyed by a class.
4. `.itr-lift` / `.itr-row` / `.itr-tile` / `.itr-panel` / `.itr-frame` / `.itr-live-dot` /
   `.itr-live-halo` exist once, with the exact hover behaviours in §7.
5. A `prefers-reduced-motion` block kills every hover transform and the live-dot pulse; a
   `@media (hover: none)` block drops hover transforms on touch.
6. Focus outlines are never removed anywhere in the theme.
7. Card accents are 3px **top** rules — grep for `border-left` on cards.
8. `border-radius:999px` appears only on status dots/switches/avatars — never on a button.
9. `font-weight:700` / `bold` appears nowhere.
10. No emoji, no "→" as an icon, no "Learn more", no "Submit", no exclamation mark in any
    theme-emitted string (including `404.php`, search-empty, comment form, pagination).
11. Washes only on sunken/dark bands, alternating; no other gradient anywhere.
12. Every number, date, version and identifier the theme prints is mono.
13. Nav uppercase is limited to the wordmark; menu items are sentence case.
14. All output escaped (`esc_html` / `esc_attr` / `esc_url` / `wp_kses_post`) per the repo rules.
