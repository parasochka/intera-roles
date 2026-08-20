# Partial call signatures

Written by the agents that built `theme/template-parts/partials/`. These are the
exact calls the templates make — copy them rather than guessing an argument name.

## screenshot-frame.php, plan-card.php, role-card.php

```php
get_template_part( 'template-parts/partials/screenshot-frame', null, array( 'attachment' => intera_option( 'shot_hero' ), 'caption' => 'Fleet Health Overview · Shipmanagement', 'height' => '420px', 'size' => 'intera-shot', 'alt' => '', 'dots' => true, 'background' => 'var(--white)', 'shadow' => '', 'edge' => '', 'radius' => 'var(--radius-xl)', 'class' => '', 'style' => '', 'attrs' => array() ) );

// 01-main hero frame — the only one on --shadow-overlay:
get_template_part( 'template-parts/partials/screenshot-frame', null, array( 'attachment' => intera_option( 'shot_hero' ), 'caption' => 'Fleet Health Overview · Shipmanagement', 'height' => '420px', 'shadow' => 'var(--shadow-overlay)' ) );

// 01-main 'working with IT' frame — the 440px one:
get_template_part( 'template-parts/partials/screenshot-frame', null, array( 'attachment' => intera_option( 'shot_it' ), 'caption' => 'Dependencies · vendors, parts, external commitments', 'height' => '440px' ) );

// 09-blog-post in-article frame — narrower crop, no window dots, no frame fill:
get_template_part( 'template-parts/partials/screenshot-frame', null, array( 'attachment' => get_post_thumbnail_id(), 'caption' => 'Attention queue · ranked by priority and time to impact', 'height' => '360px', 'size' => 'intera-shot-inline', 'dots' => false, 'background' => '' ) );

get_template_part( 'template-parts/partials/plan-card', null, array( 'post' => $intera_plan, 'cta_label' => '', 'cta_url' => '', 'badge' => null, 'heading' => 'div', 'class' => '', 'style' => '', 'attrs' => array() ) );

// 01-main pricing: the Free card points at the pricing page instead of its own meta target:
get_template_part( 'template-parts/partials/plan-card', null, array( 'post' => $intera_plan, 'cta_url' => intera_page_url( 'pricing' ) ) );

// Grid wrapper the plan cards expect (both pages, verbatim):
// <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr)); gap: 20px; align-items: stretch">

get_template_part( 'template-parts/partials/role-card', null, array( 'post' => $intera_role, 'variant' => 'main', 'heading' => 'span', 'class' => '', 'style' => '', 'attrs' => array() ) );

// 02-product #roles — same shell, prose paragraph plus Tag chips:
get_template_part( 'template-parts/partials/role-card', null, array( 'post' => $intera_role, 'variant' => 'product' ) );

// Grid wrappers the role cards expect:
// 01-main:    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr)); gap: 20px">
// 02-product: <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(270px, 100%), 1fr)); gap: 20px">

```

## entry-meta.php, post-row.php, post-card.php, doc-row.php

```php
get_template_part( 'template-parts/partials/entry-meta', null, array( 'post_id' => 0, 'post' => null, 'variant' => 'inline', 'date' => true, 'modified' => false, 'category' => false, 'version' => false, 'reading_time' => true, 'read_label' => 'long', 'separator' => '·', 'class' => '', 'style' => '', 'attrs' => array() ) );  // variant: inline|pair|rule — 'rule' emits .intera-meta-rule, so the docs strip is array( 'variant' => 'rule', 'date' => false, 'modified' => true, 'version' => true, 'style' => 'margin-top:18px' ); read_label 'short' prints "6 min", 'long' prints "6 min read"

get_template_part( 'template-parts/partials/post-row', null, array( 'post_id' => 0, 'post' => null, 'date' => true, 'reading_time' => false, 'category' => true, 'excerpt' => true, 'divider' => true, 'class' => '', 'style' => '', 'attrs' => array() ) );  // 08-blog row; renders <a class="itr-list-row">, put it inside a wrapper carrying border-top: 1px solid var(--border-hairline)

get_template_part( 'template-parts/partials/post-card', null, array( 'post_id' => 0, 'post' => null, 'date' => true, 'reading_time' => true, 'excerpt' => true, 'heading' => 'h2', 'cta_label' => '', 'cta' => true, 'edge' => '', 'shadow' => '', 'class' => '', 'style' => '', 'attrs' => array() ) );  // 10-blog-category card; renders <a class="itr-lift itr-post-card">; 'edge'/'shadow' become --itr-edge/--itr-shadow, defaults come from the CSS

get_template_part( 'template-parts/partials/doc-row', null, array( 'post_id' => 0, 'post' => null, 'variant' => 'ordinal', 'ordinal' => null, 'summary' => true, 'reading_time' => true, 'divider' => true, 'class' => '', 'style' => '', 'attrs' => array() ) );  // 13-docs-category row, <a class="itr-list-row">; ordinal defaults to menu_order printed %02d, pass 0 or '' to hide it

get_template_part( 'template-parts/partials/doc-row', null, array( 'post_id' => 0, 'variant' => 'compact', 'divider' => true, 'class' => '', 'style' => '', 'attrs' => array() ) );  // 11-docs 38px article link, <a class="itr-doc-row">; pass 'divider' => false on the last row of a card

get_template_part( 'template-parts/partials/doc-row', null, array( 'variant' => 'heading', 'term' => $term, 'href' => '', 'count' => null, 'icon' => '', 'class' => '', 'style' => '', 'attrs' => array() ) );  // 11-docs category card header: tone chip + <a class="itr-doc-title"> + mono count; term = WP_Term|int, href/count default to the term link and term count

```

## toc-rail.php, sidebar-cta.php, prev-next.php, pagination.php

```php
get_template_part( 'template-parts/partials/toc-rail', null, array( 'headings' => intera_headings_get( $html ), 'content' => '', 'title' => '', 'variant' => 'jump', 'size' => 'sm', 'levels' => array( 2 ), 'footer' => '', 'footer_style' => '', 'width' => '240px', 'min_width' => '220px', 'sticky' => true, 'top' => '100px', 'tag' => 'aside', 'class' => '', 'style' => '', 'attrs' => array() ) );  // variant: jump|rail · size: sm|md · headings null + content = parse it here · renders NOTHING when fewer than 2 headings · footer is trusted HTML · 04-faq: title 'On this page', variant jump, size md, width 260px · 07-policy: title 'Contents', jump, sm, width 240px · 09-blog-post: title 'In this story', jump, sm, width 260px, style 'order: 2' · 12-docs-article: title 'On this page', variant rail, width 200px, min_width 180px

get_template_part( 'template-parts/partials/sidebar-cta', null, array( 'option_prefix' => 'sidebar_cta', 'heading' => '', 'body' => '', 'cta_label' => '', 'cta_url' => '', 'accent' => 'var(--blue-600)', 'accent_line' => 'var(--blue-200)', 'variant' => 'primary', 'heading_tag' => 'div', 'padding' => 'default', 'class' => '', 'style' => '', 'attrs' => array() ) );  // reads <prefix>_heading/_body/_label/_url via intera_option(); explicit args win; url falls back to intera_page_url( 'contact-request' ); renders nothing with no heading and no body · 08-blog: accent var(--blue-600) / var(--blue-200) · 10-blog-category: accent var(--signal-pattern) / var(--signal-pattern-line) · 13-docs-category: accent var(--signal-reconciliation) / var(--signal-reconciliation-line)

get_template_part( 'template-parts/partials/prev-next', null, array( 'previous' => null, 'next' => null, 'in_same_term' => false, 'taxonomy' => 'category', 'prev_label' => '', 'next_label' => '', 'label' => '', 'padding' => '18px', 'min' => '240px', 'gap' => '12px', 'edge' => '', 'class' => '', 'style' => '', 'attrs' => array() ) );  // previous/next: null = auto from the loop, WP_Post|int = explicit, false = suppress that side · renders nothing when both are absent · 09-blog-post: padding 18px, min 240px · 12-docs-article: padding 16px, min 220px, pass previous/next explicitly (menu_order inside docs_category)

get_template_part( 'template-parts/partials/pagination', null, array( 'query' => null, 'total' => 0, 'current' => 0, 'label' => '', 'count' => true, 'numbers' => true, 'prev_label' => '', 'next_label' => '', 'hide_single' => false, 'mid_size' => 1, 'end_size' => 1, 'base' => '', 'format' => '', 'prev_url' => '', 'next_url' => '', 'class' => '', 'style' => '', 'attrs' => array() ) );  // query null = main query; pass a WP_Query for a custom loop · total/current override the query · hide_single true drops the pager on a one-page archive (the design keeps it, disabled) · do NOT also call the_posts_pagination()

```

