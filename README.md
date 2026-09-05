# AdFlagger — flexible-content WordPress theme

A from-scratch WordPress theme built around **ACF Pro Flexible Content**: every page is a list of
sections that editors compose in the admin, and every section is one PHP field definition plus one
template part. Styled with **Tailwind CSS 4** (CLI build, no bundler), fonts self-hosted, sliders on
Splide loaded only where a page uses them. Practice project (Mate Academy, Tailwind practice) that
became a reusable starter.

## How the flexible content works

```
www/wp-content/themes/starter/
├── index.php                          # the loop: have_rows('page_sections') → get_template_part('parts/layouts/<layout>')
├── includes/acf/page-sections.php     # master registry — the list of section slugs
├── includes/acf/sections/_shared.php  # Settings tab added to EVERY layout (title, id, bg colour, padding, classes)
├── includes/acf/sections/<slug>.php   # per-section fields, registered in PHP (no JSON sync, no admin-created fields)
├── parts/layouts/<slug>.php           # the section template
├── includes/acf/header.php, footer.php# header / footer fields (Theme Settings options page)
├── includes/helpers.php, elements.php # small render helpers (buttons, headings, pictures)
├── assets/css/input.css → style.css   # Tailwind 4 entry → built stylesheet
├── assets/js/carousel.js              # Splide init, enqueued only when a page contains slider-hero
└── safelist.html                      # classes the ACF colour / padding pickers can emit
```

Adding a section = three steps: add the slug to the registry, create its field file, create its template.
Layouts today: `slider-hero`, `content-block`, `faq`.

Other theme features: Customizer font picker that scans `assets/fonts/`, Polylang string registration,
AVIF/SVG uploads, `[year]` shortcode, conditional asset loading.

## Local development

```bash
docker compose up -d          # WordPress (php8.1-apache + redis ext), MySQL 8, Redis, phpMyAdmin
# WordPress: http://localhost:8095   phpMyAdmin: http://localhost:8096
npm install
npm run dev                   # tailwind --watch
npm run build                 # minified style.css
```

`docker-compose.yml` mounts `./www` as the WordPress root; the core, plugins (ACF Pro, Polylang, Yoast, …)
and `wp-config.php` are **not** in this repository — install WordPress into `www/` first, then activate
the `starter` theme. Credentials in the compose file are local defaults only.

## Stack

WordPress · PHP 8.1 · ACF Pro (Flexible Content, Options Page) · Tailwind CSS 4 · Splide · Docker · Redis
