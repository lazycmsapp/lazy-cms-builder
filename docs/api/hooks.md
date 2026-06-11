# Hooks API

Lazy CMS Builder uses a WordPress-like hook system that lets you extend or modify behaviour without editing core files.

## Actions

Actions let you execute code at specific points in the CMS lifecycle.

### `add_lazy_action`

```php
add_lazy_action(string $hook, callable $callback, int $priority = 10): void
```

**Example — inject content after post body:**

```php
add_lazy_action('lazy_after_post_content', function($post) {
    echo '<div class="related-posts">...</div>';
});
```

### `do_lazy_action`

```php
do_lazy_action(string $hook, ...$args): void
```

**Available action hooks:**

| Hook | When it fires |
|---|---|
| `lazy_after_post_content` | After single post content |
| `lazy_before_post_content` | Before single post content |
| `lazy_admin_head` | Inside admin `<head>` |
| `lazy_admin_footer` | Before admin `</body>` |
| `lazy_after_install` | After `lazy:install` completes |

---

## Filters

Filters let you modify data before it's used or displayed.

### `add_lazy_filter`

```php
add_lazy_filter(string $hook, callable $callback, int $priority = 10): void
```

**Example — add custom fields to General Settings:**

```php
add_lazy_filter('lazy_general_settings_fields', function(array $fields) {
    $fields['social_twitter'] = [
        'type'  => 'text',
        'label' => 'Twitter URL',
    ];
    return $fields;
});
```

### `apply_lazy_filters`

```php
apply_lazy_filters(string $hook, mixed $value, ...$args): mixed
```

**Available filter hooks:**

| Hook | What it filters |
|---|---|
| `lazy_general_settings_fields` | General settings field definitions |
| `lazy_post_title` | Post title before display |
| `lazy_post_content` | Post content before render |
| `lazy_nav_menu_items` | Menu items array |
| `lazy_seo_meta` | SEO meta tags array |

---

## Helper Functions

Commonly used template and utility functions:

```php
// Get CMS option
get_cms_option('active_theme', 'lazy-theme');

// Get active theme name
get_active_theme();

// Get posts by type
get_lazy_posts(['type' => 'post', 'limit' => 5]);

// Get all categories
get_lazy_categories();

// Check if current user has role
lazy_user_has_role('editor');
```
