# Lazy Builder Overview

Lazy Builder is the visual drag-and-drop page builder built into Lazy CMS. It lets you build complex page layouts without writing any HTML — directly inside the admin dashboard.

## Structure

Every page built with Lazy Builder follows a three-level hierarchy:

```
Page
└── Container (full-width row)
    └── Column (responsive grid cell)
        └── Element (content block)
```

### Containers
A container is a full-width row. You choose how many columns it has and what layout they follow (e.g., 1/2 + 1/2, 1/3 + 2/3, etc.).

### Columns
Columns live inside containers. Each column holds one or more elements.

### Elements
Elements are the actual content blocks. Lazy Builder ships with 20+ built-in elements:

| Element | Description |
|---|---|
| **Text** | Rich text editor (WYSIWYG) |
| **Image** | Single image with link, alt, caption |
| **Gallery** | Image grid / slider |
| **Heading** | H1–H6 with styling options |
| **Button** | CTA button with styles |
| **Video** | YouTube / Vimeo / self-hosted |
| **Counter** | Animated number counter |
| **Accordion** | Collapsible FAQ sections |
| **Tabs** | Tabbed content panels |
| **Slider** | Image/content carousel |
| **Icon Box** | Icon + title + text |
| **Map** | Google Maps embed |
| **Form** | Contact / custom forms |
| **Shortcode** | Embed any shortcode |
| **HTML** | Raw HTML block |
| **Posts Grid** | Dynamic posts listing |
| **Products Grid** | WooCommerce-style product listing |
| **Testimonials** | Review/testimonial cards |
| **Team** | Team member cards |
| **Pricing** | Pricing table |

## Device Visibility

Every element has device visibility controls. Show or hide any element on:
- Desktop
- Tablet
- Mobile

## Global Sections

Save any container as a **Global Section** — reuse it across multiple pages. Edit once, update everywhere.

## Library

Save containers and columns to your **Library** for reuse across the site. Unlike Global Sections, library items are independent copies.
