# INTERA Design System

Design system for **INTERA** and its marketing site **intera-roles.com**.

INTERA is a B2B operational-visibility product. It connects to the systems a company
already runs — ERP, CRM, billing, mediation, spreadsheets, internal tools — applies the
business's own logic on top, and shows each manager what changed, what disagrees and what
needs action. It replaces nothing. It is currently in **beta**, with an Early Adopter
programme offering the first 12 months free.

The audience is middle management: Operations Manager / COO, Finance, Billing, Revenue
Assurance, Customer Operations, Commercial, department heads, IT/System managers. The key
visitor is the **middle-management champion** — someone who sees practical value for their
own area and can push the idea internally. Secondary audiences: CEOs/owners, systems
integrators, consultants, resellers.

## Product vocabulary (use these words exactly)
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
(`SignalChain`). Its order and colours are fixed.

## Sources used
- `uploads/intera-roles.com (2026-08 sow).docx` — the site brief: goal, audience, section-by-section
  copy (hero, problem, how it works, champion section, in action, Roles, working with existing IT,
  start small, pricing, Early Adopter, partners), and design constraints. Plain-text extraction at
  `uploads/sow.txt`.
- Tone/visual references named by the client: digitate.com, unframe.ai, slack.com — calm,
  white-dominant, enterprise-serious, with a small number of saturated accent colours.

**No codebase, Figma file, screenshots or font files were provided, and no logo artwork existed.**
The mark in `assets/` is original work made for this system; every other gap is flagged below.

---

# CONTENT FUNDAMENTALS

**The voice is a competent colleague explaining a system, not a vendor selling one.** The product
is deliberately unglamorous — it saves money by removing manual checking. The copy should sound
like that: factual, specific, slightly understated.

### Rules
- **Second person, present tense.** "Your systems stay." "See what needs attention." Never "we
  empower organisations to…". The subject of most sentences is *you* or *INTERA*, never "the platform".
- **Sentence case everywhere** — headlines, buttons, labels, nav. The only uppercase is the
  wordmark, eyebrows and table headers (letterspaced, 12px).
- **Short declaratives, often in pairs.** "Less chasing. Fewer surprises." "Solve once. Adapt.
  Deploy again." Fragments are fine when they land.
- **No exclamation marks. No emoji. Ever.** Not in product, not on the site, not in error states.
- **Say the limit out loud.** "INTERA doesn't replace your team." "Your systems stay." "Do not
  start by implementing INTERA in your whole company." Naming what the product will *not* do is the
  single most persuasive move in this brand.
- **Numbers are concrete and mono-set.** "4,812 records differ by more than 0.5%", not "significant
  discrepancies detected".
- **No hype vocabulary**: revolutionary, seamless, unlock, transform, next-generation, AI-powered,
  game-changing, synergy, 10x. If a sentence would survive being pasted into a competitor's site,
  rewrite it.
- **Headlines carry the argument; sub-heads carry the proof.** H2 states the promise
  ("Get full visibility without changing how your company operates"), body gives the mechanism.
- **Buttons are verb phrases the visitor would actually say**: "Get Early Access", "Bring us a real
  problem", "I have a problem INTERA could solve", "Talk to us about your deployment". Never
  "Learn more", never "Submit".
- **Empty and error states state the fact, then the next step.** "No data received from ERP since
  03:20 UTC." — not "Oops! Something went wrong."

### Examples
| Write | Don't write |
|---|---|
| See what needs attention. Before someone has to ask. | Unlock real-time enterprise intelligence |
| Your systems stay. INTERA makes them more useful. | Replace your fragmented legacy stack |
| Start with one real problem. | Begin your digital transformation journey |
| 4,812 records differ by more than 0.5%. | Significant anomalies detected! |
| Bring us a real problem | Submit |

Russian-language material follows the same rules; IBM Plex Sans carries Cyrillic.

---

# VISUAL FOUNDATIONS

**The brief: calm, not fashionable, slightly boring — a system that earns trust by being
unexcitable — with a small number of bright colour spots on white.** Everything below serves that.

### Colour
- The page is **white** (`--surface-page`). Secondary bands are `--surface-sunken` (#F8F9FB), not
  grey cards on grey. At most two background values per page, plus the dark bands: the **hero**
  (`--ink-950`) and the Early Adopter section / footer (`--ink-900`).
- **The hero is dark.** White page, dark opening: full-bleed `--ink-950` carrying a hairline column
  grid at `rgba(255,255,255,.07)` and the mark drawn very large as a low-opacity outline. The white
  product frame sits on it with `--shadow-overlay`, so the UI is the brightest thing on the page.
  On dark, the primary button is `variant="inverse"` (white) and its partner is `outlineInverse`.
- **Ink neutrals** carry all text and structure: `--ink-900` for headings/body, `--ink-600` for
  secondary prose, `--ink-400` for captions, `--ink-100/200` for rules.
- **Intera Blue** (`--blue-600` #1A4FD6) is the only interface accent: links, primary buttons,
  focus rings, active tabs. It is structural, not decorative.
- The **bright spots** are the four signal colours, and they only appear where they mean something:
  Event = blue, Reconciliation = teal (#0E8F8A), Incident = amber (#C97A05), Pattern = violet
  (#6B4FE0). Plus green/red for OK and critical. A colour on this page always answers "what kind of
  thing is this?" — never "make this look nicer".
- Roughly 85% of any screen is white/ink. Colour is the exception, which is why it works.
- **No gradients on anything you can click or read.** Buttons, cards, badges, icons and text are
  flat fills, always. The single exception is the **ambient section wash**: one or two very large,
  very low-opacity radial tints (`--wash-blue`, `--wash-teal`, `--wash-amber`, `--wash-violet` at
  11–13%; `--wash-*-dark` at 20–30% on the dark bands) sitting behind a section so the page has
  quiet colour temperature. **Washes go only on the dark bands and the cream (`--surface-sunken`)
  bands — never on a plain white section**, and they alternate: a washed section is always followed
  by an unwashed one, so the page breathes. Pass them via `Section`'s `washes` prop. They
  must never be strong enough to read as a coloured background — if you can name the colour at a
  glance, turn it down. No mesh, no linear gradients, no two-colour blends.

### Typography
- **IBM Plex Sans** for everything, **IBM Plex Mono** for every number, identifier, timestamp,
  source-system name and delta. The mono/sans split *is* the type system: prose vs. facts.
- Display 48/1.12/-0.02em · H1 38 · H2 31 · H3 25 · H4 21, all 600 weight at -0.01em.
  Body 16/1.6, lead 18/1.6, UI 15/1.45, caption 12.
- Weights used: 400, 500, 600. **700 is reserved and effectively unused** — bold is not how this
  brand emphasises.
- Eyebrows and table headers: 12px, 600, +0.09em, uppercase, ink-500 (or blue-600 on marketing).
- Measure caps at ~640px for prose.

### Space & layout
- 4px grid with a 2px half-step for dense product tables. Page container 1160px, 32px gutters.
- Marketing sections: 88–96px vertical padding. Product screens: 20px padding, 12–16px gaps.
- Layout is **left-aligned and grid-based**. Centred type appears once per page at most (the pricing
  section head). Nothing is diagonal, rotated or offset for effect.
- Fixed elements: the site's top nav (sticky, translucent white, blur 8px, 1px bottom rule) and the
  product sidebar (232px, sunken, fixed).

### Borders, cards, elevation
- **A 1px border is the primary separator, not a shadow — and it is drawn to be seen.** The ladder:
  `--border-hairline` (#E4E9F0, internal rules inside a component) → `--border-subtle` (#D3DBE6) →
  `--border-card` (#CBD3DE, the outline of every card, tile and table) → `--border-default`
  (#B6C2D2, controls) → `--border-strong` (#8390A2, hover).
- Cards: white, 1px `--border-card`, **8px radius**, `--shadow-xs` (almost invisible), 24px padding.
  The definition comes from the outline, never from a heavier shadow or a grey fill.
- Accent on a card = a **3px rule along the top edge** in a signal colour, plus a full outline in
  that signal's `*-line` token (`accent` + `accentLine` on `Card`). A coloured *left* border is
  off-brand and must not appear.
- Tinted components (Badge, Alert, SignalBadge, Tag) keep their pale fill but take a **saturated
  200-level outline** (`--status-*-line`, `--signal-*-line`) so they read at a glance on white.
- Elevation ladder: xs (cards) → sm (hover) → md (menus) → lg (toasts) → overlay (dialogs). Only
  things that genuinely float get more than xs.
- Radii: 2 (checkbox) · 3 (badges) · 5 (buttons, inputs) · 8 (cards) · 12/16 (large panels).
  Full rounding only for status dots, switches and avatars. **No pill buttons.**

### Motion
- 80–260ms, `cubic-bezier(.2,.6,.25,1)`. Colour and opacity fades, small position slides.
- **No bounce, no overshoot, no spring, no parallax, no scroll-triggered reveals, no counters
  animating up.** Content is present when the page loads. The switch knob slides; nothing else moves
  on its own.
- `prefers-reduced-motion` is honoured globally in `tokens/base.css`.

### Interaction states
- **Hover:** buttons darken one step (blue-600 → blue-700); secondary/ghost surfaces pick up
  `--surface-hover` (#F1F4F8) and a stronger border; rows tint to `--surface-hover`; links darken and
  their underline strengthens. Never opacity fades, never scale-up.
- **Press:** one further step darker (blue-900 / `--surface-active`). No shrink transform.
- **Focus:** 2px `--focus-ring` outline at 2px offset for keyboard focus; form controls additionally
  take a 1px blue border + 3px soft ring (`--ring-focus`). Focus is always visible.
- **Disabled:** 45% opacity, `not-allowed` cursor. No greyed-out custom palette.
- **Selected:** blue-50 fill + blue-200 border (tags, filters), or a 2px blue underline (tabs).

### Transparency, blur, imagery
- Transparency is used in exactly two places: the sticky nav (92% white + 8px blur) and the dialog
  scrim (`rgba(14,26,43,.38)`). Nothing else is translucent; no frosted cards.
- **Imagery is product UI, not stock photography.** The SOW allows at most three product visuals on
  the homepage: one Role/dashboard, one Incident/Reconciliation, one Pattern Studio — no dashboard
  gallery. They sit in a light browser frame with an `--shadow-lg`.
- If photography is ever introduced: cool, neutral, desaturated, no grain, no colour wash. There
  are no illustrations, textures or patterns in this brand, and none should be invented.

---

# ICONOGRAPHY

**Substitution flagged:** no icon assets were supplied. The system uses **[Lucide](https://lucide.dev)**
(v0.469.0) loaded from CDN — a thin, geometric, unornamented open-source set that matches the calm
register of the references. Swap it for the client's own set the moment one exists; only
`components/core/Icon.jsx` would change.

- Loaded per page: `<script src="https://unpkg.com/lucide@0.469.0/dist/umd/lucide.js"></script>`,
  then `<Icon name="alert-triangle" />`.
- **Stroke weight 1.75** (lighter than Lucide's 2 default), `currentColor`, round caps and joins.
- Sizes: 12–13 (inside badges), 14 (with caption text), 15–16 (buttons, table cells, nav), 18
  (default UI), 20 (feature marks). Nothing above 24 — use a larger surface instead.
- Icons are **annotation, never decoration**. Every icon on a screen labels a thing that has a name;
  no icon appears purely to fill a card.
- Fixed pairings that must not be remapped: Event = `activity`, Reconciliation = `scale`,
  Incident = `alert-triangle`, Pattern = `git-branch`, Connect = `plug`, Source system = `database`.
- Role icons: Finance Control = `circle-dollar-sign`, Operations Oversight = `gauge`,
  Revenue Assurance = `scale`, Customer Health = `heart-pulse`, System Integrity = `shield-check`.
- **No emoji, ever.** No unicode arrows/bullets used as icons (`iconRight="arrow-right"`, not "→").
  No filled or duotone icon styles.

## Logo
No logo artwork was supplied with the brief, so an **original mark was designed for this system**
at the client's request. It is two overlapping frames whose intersection is the only solid area —
two systems, and the place where they agree. Flat, geometric, no gradient or shadow.

| File | Use |
|---|---|
| `assets/logo-horizontal.svg` | Default lockup — mark + wordmark |
| `assets/logo-horizontal-inverse.svg` | Same, on the dark bands |
| `assets/logo-mark.svg` / `-inverse` | Glyph only, for tight chrome |
| `assets/logo-square.svg` | App-icon / avatar tile (ink-900, 14px radius) |

The wordmark stays IBM Plex Sans 600, uppercase, +0.09em. Minimum mark size **16px**; clear space
equals the mark's corner radius on all sides. Never recolour outside the ink/blue pair, never
rotate, never add a gradient or shadow. In code use the `Logo` component
(`variant="horizontal" | "mark" | "square" | "wordmark"`).

---

# Components

All exported on `window.INTERADesignSystem_430dc5`. Each directory has a `@dsCard` demo HTML.

**Core** (`components/core/`) — `Button`, `IconButton`, `Icon`, `Logo`, `Card`, `CardHeader`, `Badge`, `Tag`
**Forms** (`components/forms/`) — `Field`, `Input`, `Textarea`, `Select`, `Checkbox`, `Radio`, `Switch`
**Feedback** (`components/feedback/`) — `Alert`, `Toast`, `ToastStack`, `Tooltip`, `Dialog`, `StatusDot`
**Navigation** (`components/navigation/`) — `Tabs`
**Data** (`components/data/`) — `SignalBadge`, `SignalChain`, `MetricTile`, `DataTable`

Each component ships `<Name>.jsx`, `<Name>.d.ts` (props contract) and `<Name>.prompt.md`
(what & when + usage example).

### Intentional additions
No component inventory was supplied, so the standard set was authored. Four additions are
product-specific rather than generic, and are justified here:
- **`Icon`** — wrapper so the Lucide dependency lives in one file and can be swapped.
- **`Logo`** — pins the mark + wordmark lockup so nobody redraws it.
- **`SignalBadge` / `SignalChain`** — the Event/Reconciliation/Incident/Pattern vocabulary is the
  product's core concept and appears on both the site and every product screen.
- **`MetricTile` / `DataTable`** — the two shapes every role dashboard is made of.

# UI kits
- `ui_kits/website/` — **intera-roles.com** homepage, full click-through, copy taken verbatim from
  the SOW. See its README for placeholders.
- `ui_kits/product/` — **INTERA app**: Role dashboard, Incident detail, Pattern Studio.
  ⚠️ An interpretation, not a recreation — no product access was available. See its README.

# Index
| Path | What it is |
|---|---|
| `styles.css` | Global entry point — `@import`s only |
| `tokens/` | `fonts.css`, `colors.css`, `typography.css`, `spacing.css`, `radius.css`, `elevation.css`, `motion.css`, `base.css` |
| `components/` | `core/`, `forms/`, `feedback/`, `navigation/`, `data/` |
| `ui_kits/website/` | Marketing site kit + README |
| `ui_kits/product/` | Product surfaces kit + README |
| `guidelines/` | 18 foundation specimen cards (Colors, Type, Spacing, Brand) |
| `assets/` | Logo SVGs (horizontal, inverse, mark, square) |
| `thumbnail.html` | Homepage tile |
| `SKILL.md` | Agent-skill entry point |
| `uploads/sow.txt` | Plain-text extraction of the client brief |

# Open items
1. **Logo** — original mark designed here, not client-supplied. Approve or replace.
2. **Fonts** — no font files supplied. IBM Plex Sans/Mono chosen and loaded from Google Fonts.
   Confirm or send the real faces.
3. **Icons** — Lucide substituted for an unknown house set.
4. **Product screenshots** — the SOW says the three homepage visuals will be captured from the live
   system jointly; placeholders stand in at the right density.
5. **Photography / illustration** — none exists and none was invented.
