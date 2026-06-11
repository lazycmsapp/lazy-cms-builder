# Menus

Lazy CMS has a visual drag-and-drop menu builder for creating and managing navigation menus.

## Creating a Menu

1. Go to **Admin → Appearance → Menus**
2. Click **Create New Menu**
3. Give it a name (e.g., "Main Navigation") and a slug (e.g., `main-nav`)
4. Select a location: **Header** or **Footer**
5. Save

## Adding Menu Items

From the menu editor, add items from the panels on the left:

- **Pages** — Static pages
- **Posts** — Blog posts
- **Products** — Shop products
- **Categories** — Post categories and tags
- **Product Categories** — Product-specific categories and tags
- **Custom URL** — Any external or internal URL

Drag items to reorder. Drag items to the right (indent) to make them sub-items.

## Reading Menus in Templates

```php
// By location
$menu = get_lazy_menu('header');   // header menu
$menu = get_lazy_menu('footer');   // footer menu

// By slug
$menu = get_lazy_menu('main-nav');

// Render menu items
if ($menu && $menu->items) {
    foreach ($menu->items->whereNull('parent_id') as $item) {
        echo '<a href="' . $item->url . '">' . $item->title . '</a>';

        // Sub-items
        foreach ($item->children as $child) {
            echo '<a href="' . $child->url . '">' . $child->title . '</a>';
        }
    }
}
```

## Builder-based Header & Footer

For maximum flexibility, use the **Lazy Builder** to create your header and footer visually:

1. Go to **Admin → Appearance → Builder Sections**
2. Click **Edit Header** or **Edit Footer**
3. Build your layout using the full Lazy Builder
4. Save

Render in your theme's `layouts/app.blade.php`:

```php
<?php get_lazy_header(); ?>

<!-- page content -->

<?php get_lazy_footer(); ?>
```

The builder-based header/footer automatically applies sticky behavior and responsive visibility.

## Mega Menus

Sub-items support multiple levels. Build a mega menu by:
1. Adding a top-level item with no URL (use `#`)
2. Adding sub-items nested beneath it

Styling is handled by your theme's CSS.
