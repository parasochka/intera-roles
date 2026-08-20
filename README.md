# Intera Roles — WordPress theme

A custom WordPress **classic PHP theme** for
[intera-roles.com](https://intera-roles.com), generated from a Claude Design
project. The design system is the source of truth for styling: its tokens live
in [`theme/_ds/intera/tokens/*.css`](./theme/_ds/intera) and are enqueued
**as-is**. Templates are plain PHP that reproduce the design-handoff screens
pixel-for-pixel.

**The theme is the `theme/` folder** — WP Pusher installs from that
subdirectory, so docs, design sources and CI at the repo root never reach the
server.

```
Claude Design  ─►  theme/ (_ds/intera + *.php)  ─►  GitHub  ─►  WP Pusher  ─►  WordPress
 export screens     this repo (theme dir)          (main)     (subdir: theme)   intera-roles.com
```

## Structure

```
intera-roles/
├── theme/                      # ← THE THEME (WP Pusher subdirectory)
│   ├── style.css               # WP theme header (metadata only, no real CSS)
│   ├── functions.php           # thin loader — requires inc/*
│   ├── inc/
│   │   ├── setup.php           # theme supports, nav locations, content width
│   │   └── enqueue.php         # inlines the DS + theme CSS; enqueues JS
│   ├── header.php · footer.php # shared chrome
│   ├── index.php · page.php · single.php · 404.php   # templates
│   ├── template-parts/         # repeated markup (cards, etc.)
│   ├── page-templates/         # named page templates
│   ├── _ds/intera/
│   │   ├── styles.css          # @import manifest — the only list of sheets
│   │   ├── tokens/*.css        # DESIGN-SYSTEM SOURCE OF TRUTH
│   │   └── components/*.css    # component patterns from the design system
│   └── assets/css · js · img   # supplemental styles, progressive JS, chrome images
├── _design/                    # Claude Design export (never deployed)
├── SETUP.md                    # WP Pusher / WordPress setup, in Russian
├── CLAUDE.md                   # rules for AI agents and humans working here
└── .github/workflows/          # validate.yml — php -l + required files/tokens
```

## Design changes

1. **A token** (color/space/radius/type/elevation) → edit
   `theme/_ds/intera/tokens/*.css`. One edit re-themes the whole site.
2. **A shared style** (prose/hover/responsive) → `theme/assets/css/intera.css`,
   via `var(--…)` tokens only.
3. **Layout** → the relevant `theme/*.php`, mirroring the handoff screen.

No raw hex or px that duplicates a token. Full rules: [`CLAUDE.md`](./CLAUDE.md).

## Deploy

WP Pusher pulls this repo (branch **main**, install from subdirectory
**`theme`**). Push to `main` → WP Pusher → **Update** → activate
**"Intera Roles"**. Step-by-step: [`SETUP.md`](./SETUP.md).

## Validation

`.github/workflows/validate.yml` runs on every push: `php -l` on every PHP file,
a check that the required theme files exist, and a check that every sheet the
design-system manifest `@import`s is actually on disk. Keep it green — WP Pusher
ships whatever is on `main`.

## Content

Content (posts, pages, menus, media) lives in WordPress, not in git — edited in
wp-admin or via the WordPress MCP. The theme renders whatever exists.

## Status

Bootstrap. The templates are functional placeholders; they are replaced screen
by screen once the Claude Design export lands in [`_design/`](./_design).
