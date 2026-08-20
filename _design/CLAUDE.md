# Project instructions — INTERA site mockups

## Interaction & motion (applies to every page in this project)
The INTERA design system stays the base, but this site is allowed to be more interactive than the
system's default "nothing moves" rule. Standing rules:

- **Every card, tile, row and product frame reacts to hover.** Use the shared classes defined in
  each page's `<helmet>`: `.itr-lift` (cards — 5px rise + stronger border/shadow), `.itr-row`
  (list rows — 8px slide right, white fill, blue-200 border), `.itr-tile` (compact label tiles —
  4px rise, blue-50 fill), `.itr-panel` (panels on dark bands), `.itr-frame` (product screenshot
  frames — 6px rise, deeper shadow). Pass them to design-system `Card` via
  `dc-props="{{ lift }}"` (`lift: { className: "itr-lift" }` in `renderVals()`).
- **Motion budget:** 150–260ms, `cubic-bezier(.2,.6,.25,1)`. Transform + opacity + colour only.
  No bounce, no spring, no scale-up on text, no scroll-triggered reveals, no counting numbers.
- **Live status = pulsing green dot** (`.itr-live-dot` + `.itr-live-halo`, `--green-500`), used for
  "in beta" / "live" indicators on both site and product screens.
- Always ship a `prefers-reduced-motion` block that kills the animations and the hover transforms.

## Layout
- Tile grids must never let a label wrap: size the columns to the longest label
  (e.g. "Market packages") and add `white-space: nowrap`, so every tile in the grid is the same height.

## Craft checks before delivering
No filler copy, no invented stats, no emoji, no gradients on anything clickable or readable,
sentence case everywhere, mono type for every number/identifier, hover states on everything
interactive, focus rings intact, and nothing that would survive being pasted onto a competitor's site.
