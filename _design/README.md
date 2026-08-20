# `_design/` — the Claude Design handoff

Drop the **Claude Design export here** (unzipped): the artboard/screen files and
whatever the export ships alongside them — tokens, fonts, images.

This folder is at the repo root, so it is **never deployed**: WP Pusher installs
only the `theme/` subdirectory.

## Expected shape

```
_design/
├── screens/            # one file per artboard (home, article, page, 404, …)
├── tokens/             # colors / typography / spacing / radius / elevation / motion
└── assets/             # logo, illustrations, icons
```

Anything is fine as long as it is unzipped and committed — the exact layout is
adapted once the export lands.

## What happens next

1. **Tokens** are copied verbatim into `theme/_ds/intera/tokens/*.css` and listed
   in `theme/_ds/intera/styles.css`. They become the single source of truth for
   styling — no value is ever duplicated in PHP or `assets/css/intera.css`.
2. **Screens** are cut into the matching `theme/*.php` templates: the screen's
   HTML and inline styles are kept verbatim, and only the dynamic slots are
   swapped for WordPress calls (`the_title`, `the_content`, `WP_Query`, …).
3. **Preview-only attributes** in the export (e.g. `style-hover="…"`) are not
   real CSS — every hover/focus state is reimplemented in
   `theme/assets/css/intera.css`, keyed by a class.
4. **Assets** that belong to the theme chrome (logo, illustrations) move to
   `theme/assets/img/`. Editorial images stay in the WordPress media library.
