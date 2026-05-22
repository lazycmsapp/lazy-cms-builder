<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomizerController extends \Illuminate\Routing\Controller
{
    private function sections(): array
    {
        return [
            'layout' => [
                'title' => 'Layout',
                'icon'  => 'dashboard_customize',
                'fields' => [
                    'theme_layout_type' => [
                        'type'    => 'button_group',
                        'label'   => 'Layout',
                        'desc'    => 'Controls the site layout.',
                        'default' => 'boxed',
                        'options' => ['boxed' => 'Boxed', 'wide' => 'Wide'],
                    ],
                    'theme_site_width' => [
                        'type'        => 'text',
                        'label'       => 'Site Width',
                        'desc'        => 'Controls the overall site width. Enter value including any valid CSS unit, ex: 1200px.',
                        'default'     => '1240px',
                        'placeholder' => '1240px',
                    ],
                    'theme_page_padding_top' => [
                        'type'        => 'text',
                        'label'       => 'Page Content Padding Top',
                        'desc'        => 'Controls the top padding for page content. Enter value including any valid CSS unit, ex: 60px.',
                        'default'     => '60px',
                        'placeholder' => '60px',
                    ],
                    'theme_page_padding_bottom' => [
                        'type'        => 'text',
                        'label'       => 'Page Content Padding Bottom',
                        'desc'        => 'Controls the bottom padding for page content.',
                        'default'     => '60px',
                        'placeholder' => '60px',
                    ],
                    'theme_100_width_padding' => [
                        'type'        => 'text',
                        'label'       => '100% Width Padding',
                        'desc'        => 'Controls the left and right padding for page content when using 100% site width.',
                        'default'     => '30px',
                        'placeholder' => '30px',
                    ],
                ],
            ],
            'responsive' => [
                'title' => 'Responsive',
                'icon'  => 'devices',
                'fields' => [
                    'theme_responsive_design' => [
                        'type'    => 'toggle',
                        'label'   => 'Responsive Design',
                        'desc'    => 'Turn on to use the responsive design features. If set to off, the fixed layout is used.',
                        'default' => '1',
                    ],
                    'theme_grid_breakpoint' => [
                        'type'    => 'range',
                        'label'   => 'Grid Responsive Breakpoint',
                        'desc'    => 'Controls when grid layouts (blog/portfolio) start to break into smaller columns. In pixels.',
                        'default' => '1000',
                        'min'     => 400,
                        'max'     => 1400,
                    ],
                    'theme_header_breakpoint' => [
                        'type'    => 'range',
                        'label'   => 'Header Responsive Breakpoint',
                        'desc'    => 'Controls when the desktop header changes to the mobile header. In pixels.',
                        'default' => '800',
                        'min'     => 400,
                        'max'     => 1400,
                    ],
                    'theme_content_breakpoint' => [
                        'type'    => 'range',
                        'label'   => 'Site Content Responsive Breakpoint',
                        'desc'    => 'Controls when the site content area changes to the mobile layout. In pixels.',
                        'default' => '800',
                        'min'     => 400,
                        'max'     => 1400,
                    ],
                    'theme_sidebar_breakpoint' => [
                        'type'    => 'range',
                        'label'   => 'Sidebar Responsive Breakpoint',
                        'desc'    => 'Controls when sidebars change to the mobile layout. In pixels.',
                        'default' => '800',
                        'min'     => 400,
                        'max'     => 1400,
                    ],
                    'theme_mobile_zoom' => [
                        'type'    => 'toggle',
                        'label'   => 'Mobile Device Zoom',
                        'desc'    => 'Turn on to enable pinch to zoom on mobile devices.',
                        'default' => '1',
                    ],
                    // Element Responsive Breakpoints
                    'hr1' => ['type' => 'heading', 'label' => 'Element Responsive Breakpoints'],
                    'theme_small_screen_breakpoint' => [
                        'type'    => 'range',
                        'label'   => 'Small Screen',
                        'desc'    => 'Controls when the small screen options and visibility should take effect. In pixels.',
                        'default' => '800',
                        'min'     => 320,
                        'max'     => 1024,
                    ],
                    'theme_medium_screen_breakpoint' => [
                        'type'    => 'range',
                        'label'   => 'Medium Screen',
                        'desc'    => 'Controls when the medium screen options and visibility should take effect. In pixels.',
                        'default' => '1100',
                        'min'     => 800,
                        'max'     => 1400,
                    ],
                    'info_large_screen' => [
                        'type' => 'info',
                        'label' => 'Large Screen',
                        'desc' => 'Any screen larger than that which is defined as the medium screen will be counted as a large screen (> <span id="large-screen-bp-val">' . get_cms_option('theme_medium_screen_breakpoint', '1100') . '</span>px).',
                    ],
                    // Responsive Typography
                    'hr2' => ['type' => 'heading', 'label' => 'Responsive Typography'],
                    'theme_typography_sensitivity' => [
                        'type'    => 'range',
                        'label'   => 'Responsive Typography Sensitivity',
                        'desc'    => 'Set to 0 to disable responsive typography. Increase the value for a greater effect.',
                        'default' => '0.6',
                        'min'     => 0,
                        'max'     => 1,
                        'step'    => 0.01,
                    ],
                    'theme_font_size_factor' => [
                        'type'    => 'range',
                        'label'   => 'Minimum Font Size Factor',
                        'desc'    => 'The minimum font-size of elements affected by responsive typography is body font-size multiplied by this factor.',
                        'default' => '1.50',
                        'min'     => 0.5,
                        'max'     => 3,
                        'step'    => 0.1,
                    ],
                ],
            ],
            'colors' => [
                'title' => 'Colors',
                'icon'  => 'palette',
                'fields' => [
                    'theme_primary_color' => [
                        'type'    => 'color',
                        'label'   => 'Primary Color',
                        'desc'    => 'The primary accent color used throughout the site.',
                        'default' => '#0091ea',
                    ],
                    'theme_secondary_color' => [
                        'type'    => 'color',
                        'label'   => 'Secondary Color',
                        'desc'    => 'Used for secondary accents and headings.',
                        'default' => '#1d2327',
                    ],
                    'theme_body_bg_color' => [
                        'type'    => 'color',
                        'label'   => 'Body Background Color',
                        'desc'    => 'The main background color of the site.',
                        'default' => '#ffffff',
                    ],
                    'theme_text_color' => [
                        'type'    => 'color',
                        'label'   => 'Body Text Color',
                        'desc'    => 'The default text color.',
                        'default' => '#1d2327',
                    ],
                    'theme_link_color' => [
                        'type'    => 'color',
                        'label'   => 'Link Color',
                        'desc'    => 'The default color for links.',
                        'default' => '#0091ea',
                    ],
                    'theme_link_hover_color' => [
                        'type'    => 'color',
                        'label'   => 'Link Hover Color',
                        'desc'    => 'The color for links on hover.',
                        'default' => '#007ac1',
                    ],
                    'theme_heading_color' => [
                        'type'    => 'color',
                        'label'   => 'Heading Color',
                        'desc'    => 'Color applied to H1–H6 headings.',
                        'default' => '#1d2327',
                    ],
                ],
            ],
            'typography' => [
                'title' => 'Typography',
                'icon'  => 'text_fields',
                'fields' => [
                    // Body Typography
                    'theme_typography_body' => [
                        'type'    => 'typography',
                        'label'   => 'Body Typography',
                        'desc'    => 'Controls the main body text styles.',
                        'default' => [
                            'family' => 'Inter',
                            'variant' => '400',
                            'size' => '15px',
                            'line_height' => '1.6',
                            'letter_spacing' => '0px',
                            'text_transform' => 'none',
                            'text_decoration' => 'none',
                            'font_style' => 'normal',
                        ],
                    ],
                    // Headings Typography
                    'theme_typography_h1' => [
                        'type'    => 'typography',
                        'label'   => 'Heading H1',
                        'desc'    => 'Styles for <h1> tags.',
                        'default' => ['family' => 'Inter', 'variant' => '700', 'size' => '40px', 'line_height' => '1.2', 'letter_spacing' => '-0.02em', 'text_transform' => 'none', 'text_decoration' => 'none', 'font_style' => 'normal'],
                    ],
                    'theme_typography_h2' => [
                        'type'    => 'typography',
                        'label'   => 'Heading H2',
                        'desc'    => 'Styles for <h2> tags.',
                        'default' => ['family' => 'Inter', 'variant' => '700', 'size' => '32px', 'line_height' => '1.3', 'letter_spacing' => '-0.01em', 'text_transform' => 'none', 'text_decoration' => 'none', 'font_style' => 'normal'],
                    ],
                    'theme_typography_h3' => [
                        'type'    => 'typography',
                        'label'   => 'Heading H3',
                        'desc'    => 'Styles for <h3> tags.',
                        'default' => ['family' => 'Inter', 'variant' => '600', 'size' => '24px', 'line_height' => '1.4', 'letter_spacing' => '0px', 'text_transform' => 'none', 'text_decoration' => 'none', 'font_style' => 'normal'],
                    ],
                    'theme_typography_h4' => [
                        'type'    => 'typography',
                        'label'   => 'Heading H4',
                        'desc'    => 'Styles for <h4> tags.',
                        'default' => ['family' => 'Inter', 'variant' => '600', 'size' => '20px', 'line_height' => '1.4', 'letter_spacing' => '0px', 'text_transform' => 'none', 'text_decoration' => 'none', 'font_style' => 'normal'],
                    ],
                    'theme_typography_h5' => [
                        'type'    => 'typography',
                        'label'   => 'Heading H5',
                        'desc'    => 'Styles for <h5> tags.',
                        'default' => ['family' => 'Inter', 'variant' => '600', 'size' => '18px', 'line_height' => '1.4', 'letter_spacing' => '0px', 'text_transform' => 'none', 'text_decoration' => 'none', 'font_style' => 'normal'],
                    ],
                    'theme_typography_h6' => [
                        'type'    => 'typography',
                        'label'   => 'Heading H6',
                        'desc'    => 'Styles for <h6> tags.',
                        'default' => ['family' => 'Inter', 'variant' => '600', 'size' => '16px', 'line_height' => '1.4', 'letter_spacing' => '0px', 'text_transform' => 'none', 'text_decoration' => 'none', 'font_style' => 'normal'],
                    ],
                    // Navigation
                    'theme_typography_nav' => [
                        'type'    => 'typography',
                        'label'   => 'Navigation',
                        'desc'    => 'Styles for main navigation menu items.',
                        'default' => ['family' => 'Inter', 'variant' => '500', 'size' => '14px', 'line_height' => '1', 'letter_spacing' => '0px', 'text_transform' => 'none', 'text_decoration' => 'none', 'font_style' => 'normal'],
                    ],
                ],
            ],
            'header' => [
                'title' => 'Header',
                'icon'  => 'web_asset',
                'fields' => [
                    'theme_header_height' => [
                        'type'        => 'text',
                        'label'       => 'Header Height',
                        'desc'        => 'Controls the header height. Enter value with CSS unit, ex: 80px.',
                        'default'     => '80px',
                        'placeholder' => '80px',
                    ],
                    'theme_header_bg_color' => [
                        'type'    => 'color',
                        'label'   => 'Header Background Color',
                        'desc'    => 'Background color of the header area.',
                        'default' => '#ffffff',
                    ],
                    'theme_header_text_color' => [
                        'type'    => 'color',
                        'label'   => 'Header Text Color',
                        'desc'    => 'Text color for header elements.',
                        'default' => '#1d2327',
                    ],
                    'theme_header_sticky' => [
                        'type'    => 'toggle',
                        'label'   => 'Sticky Header',
                        'desc'    => 'Makes the header stick to the top when scrolling.',
                        'default' => '0',
                    ],
                    'theme_header_border_bottom' => [
                        'type'    => 'toggle',
                        'label'   => 'Header Border Bottom',
                        'desc'    => 'Show a border below the header.',
                        'default' => '1',
                    ],
                    'theme_header_border_color' => [
                        'type'    => 'color',
                        'label'   => 'Header Border Color',
                        'desc'    => 'Color of the header bottom border.',
                        'default' => '#e5e7eb',
                    ],
                    'theme_header_padding_top' => [
                        'type'        => 'text',
                        'label'       => 'Header Padding Top',
                        'desc'        => 'Top padding inside the header.',
                        'default'     => '0px',
                        'placeholder' => '0px',
                    ],
                    'theme_header_padding_bottom' => [
                        'type'        => 'text',
                        'label'       => 'Header Padding Bottom',
                        'desc'        => 'Bottom padding inside the header.',
                        'default'     => '0px',
                        'placeholder' => '0px',
                    ],
                ],
            ],
            'logo' => [
                'title' => 'Logo & Favicon',
                'icon'  => 'image',
                'fields' => [
                    'theme_site_logo' => [
                        'type'    => 'image',
                        'label'   => 'Site Logo',
                        'desc'    => 'Upload your site logo. Recommended format: PNG or SVG.',
                        'default' => '',
                    ],
                    'theme_site_favicon' => [
                        'type'    => 'image',
                        'label'   => 'Favicon',
                        'desc'    => 'Upload a square icon for the browser tab. Recommended size: 32x32px or 64x64px.',
                        'default' => '',
                    ],
                ],
            ],
            'menu' => [
                'title' => 'Menu',
                'icon'  => 'menu',
                'fields' => [
                    'theme_menu_font_size' => [
                        'type'        => 'text',
                        'label'       => 'Navigation Font Size',
                        'desc'        => 'Font size for main navigation links.',
                        'default'     => '13px',
                        'placeholder' => '13px',
                    ],
                    'theme_menu_font_weight' => [
                        'type'    => 'select',
                        'label'   => 'Navigation Font Weight',
                        'desc'    => 'Font weight for main navigation links.',
                        'default' => '600',
                        'options' => ['400' => 'Normal (400)', '500' => 'Medium (500)', '600' => 'Semi Bold (600)', '700' => 'Bold (700)'],
                    ],
                    'theme_menu_color' => [
                        'type'    => 'color',
                        'label'   => 'Navigation Text Color',
                        'desc'    => 'Color of navigation menu links.',
                        'default' => '#1d2327',
                    ],
                    'theme_menu_hover_color' => [
                        'type'    => 'color',
                        'label'   => 'Navigation Hover Color',
                        'desc'    => 'Color of navigation links on hover.',
                        'default' => '#0091ea',
                    ],
                    'theme_dropdown_bg' => [
                        'type'    => 'color',
                        'label'   => 'Dropdown Background',
                        'desc'    => 'Background color of dropdown menus.',
                        'default' => '#ffffff',
                    ],
                    'theme_dropdown_text_color' => [
                        'type'    => 'color',
                        'label'   => 'Dropdown Text Color',
                        'desc'    => 'Text color for dropdown menu items.',
                        'default' => '#1d2327',
                    ],
                    'theme_menu_item_padding' => [
                        'type'        => 'text',
                        'label'       => 'Menu Item Padding',
                        'desc'        => 'Horizontal padding for each menu item. Ex: 15px.',
                        'default'     => '15px',
                        'placeholder' => '15px',
                    ],
                ],
            ],
            'footer' => [
                'title' => 'Footer',
                'icon'  => 'web_asset_off',
                'fields' => [
                    'theme_footer_columns' => [
                        'type'    => 'select',
                        'label'   => 'Footer Columns',
                        'desc'    => 'Number of columns in the footer widget area.',
                        'default' => '4',
                        'options' => ['1' => '1 Column', '2' => '2 Columns', '3' => '3 Columns', '4' => '4 Columns'],
                    ],
                    'theme_footer_bg_color' => [
                        'type'    => 'color',
                        'label'   => 'Footer Background Color',
                        'desc'    => 'Background color of the footer area.',
                        'default' => '#1d2327',
                    ],
                    'theme_footer_text_color' => [
                        'type'    => 'color',
                        'label'   => 'Footer Text Color',
                        'desc'    => 'Text color for footer content.',
                        'default' => '#c3c4c7',
                    ],
                    'theme_footer_link_color' => [
                        'type'    => 'color',
                        'label'   => 'Footer Link Color',
                        'default' => '#72aee6',
                    ],
                    'theme_footer_border_top' => [
                        'type'    => 'toggle',
                        'label'   => 'Footer Border Top',
                        'desc'    => 'Show a border above the footer.',
                        'default' => '1',
                    ],
                    'theme_footer_border_color' => [
                        'type'    => 'color',
                        'label'   => 'Footer Border Color',
                        'default' => '#3c434a',
                    ],
                    'theme_footer_copyright' => [
                        'type'        => 'textarea',
                        'label'       => 'Copyright Text',
                        'desc'        => 'Text shown in the footer copyright area. HTML is allowed.',
                        'default'     => '© ' . date('Y') . ' Your Site. All rights reserved.',
                        'placeholder' => '© 2025 Your Site. All rights reserved.',
                    ],
                    'theme_footer_padding_top' => [
                        'type'        => 'text',
                        'label'       => 'Footer Padding Top',
                        'default'     => '40px',
                        'placeholder' => '40px',
                    ],
                    'theme_footer_padding_bottom' => [
                        'type'        => 'text',
                        'label'       => 'Footer Padding Bottom',
                        'default'     => '40px',
                        'placeholder' => '40px',
                    ],
                ],
            ],
            'background' => [
                'title' => 'Background',
                'icon'  => 'wallpaper',
                'fields' => [
                    'theme_body_bg_image' => [
                        'type'        => 'image',
                        'label'       => 'Body Background Image',
                        'desc'        => 'Select an image for the body background. Leave empty for no background image.',
                        'default'     => '',
                    ],
                    'theme_body_bg_position' => [
                        'type'    => 'select',
                        'label'   => 'Background Position',
                        'default' => 'center center',
                        'options' => [
                            'top left' => 'Top Left', 'top center' => 'Top Center', 'top right' => 'Top Right',
                            'center left' => 'Center Left', 'center center' => 'Center Center', 'center right' => 'Center Right',
                            'bottom left' => 'Bottom Left', 'bottom center' => 'Bottom Center', 'bottom right' => 'Bottom Right',
                        ],
                    ],
                    'theme_body_bg_size' => [
                        'type'    => 'select',
                        'label'   => 'Background Size',
                        'default' => 'cover',
                        'options' => ['auto' => 'Auto', 'cover' => 'Cover', 'contain' => 'Contain'],
                    ],
                    'theme_body_bg_repeat' => [
                        'type'    => 'select',
                        'label'   => 'Background Repeat',
                        'default' => 'no-repeat',
                        'options' => ['no-repeat' => 'No Repeat', 'repeat' => 'Repeat', 'repeat-x' => 'Repeat X', 'repeat-y' => 'Repeat Y'],
                    ],
                    'theme_body_bg_attachment' => [
                        'type'    => 'button_group',
                        'label'   => 'Background Attachment',
                        'desc'    => 'Fixed creates a parallax-like scrolling effect.',
                        'default' => 'scroll',
                        'options' => ['scroll' => 'Scroll', 'fixed' => 'Fixed'],
                    ],
                ],
            ],
            'performance' => [
                'title' => 'Performance',
                'icon'  => 'speed',
                'fields' => [
                    'performance_allowed_formats' => [
                        'type'    => 'multi_select',
                        'label'   => 'Allowed Upload Formats',
                        'desc'    => 'Select which file types are allowed to be uploaded. This applies globally across the site.',
                        'default' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'zip'],
                        'options' => [
                            'jpg'  => 'JPG',
                            'jpeg' => 'JPEG',
                            'png'  => 'PNG',
                            'gif'  => 'GIF',
                            'webp' => 'WebP',
                            'svg'  => 'SVG',
                            'pdf'  => 'PDF',
                            'zip'  => 'ZIP',
                            'mp4'  => 'MP4',
                            'mp3'  => 'MP3',
                            'csv'  => 'CSV',
                            'docx' => 'DOCX',
                        ],
                    ],
                    'performance_static_caching' => [
                        'type'    => 'toggle',
                        'label'   => 'Static Caching',
                        'desc'    => 'Enable response caching for frontend. Drastically improves speed by caching HTML output.',
                        'default' => '0',
                    ],
                    'performance_webp_conversion' => [
                        'type'    => 'toggle',
                        'label'   => 'WebP Conversion',
                        'desc'    => 'Auto convert uploaded images to WebP. Recommended for better performance and smaller file sizes.',
                        'default' => '1',
                    ],
                    'performance_image_quality' => [
                        'type'        => 'text',
                        'label'       => 'Image Quality',
                        'desc'        => '(0-100) Lower quality means smaller file sizes. 80 is recommended.',
                        'default'     => '80',
                        'placeholder' => '80',
                    ],
                    'performance_max_image_width' => [
                        'type'        => 'text',
                        'label'       => 'Max Image Width',
                        'desc'        => 'Pixels. Images wider than this will be automatically resized. 1920 is default.',
                        'default'     => '1920',
                        'placeholder' => '1920',
                    ],
                    'performance_bulk_optimize' => [
                        'type'  => 'action_button',
                        'label' => 'Bulk Actions',
                        'text'  => 'Optimize Existing Images Now',
                        'desc'  => '<span class="text-red-600 font-semibold">Caution:</span> This will replace all existing original images with optimized versions. This process cannot be undone.',
                        'action'=> 'optimizeImages',
                    ],
                ],
            ],
            'social' => [
                'title' => 'Social Media',
                'icon'  => 'share',
                'fields' => [
                    'theme_social_facebook'  => ['type' => 'url', 'label' => 'Facebook URL',   'default' => '', 'placeholder' => 'https://facebook.com/yourpage'],
                    'theme_social_twitter'   => ['type' => 'url', 'label' => 'X (Twitter) URL','default' => '', 'placeholder' => 'https://x.com/yourhandle'],
                    'theme_social_instagram' => ['type' => 'url', 'label' => 'Instagram URL',  'default' => '', 'placeholder' => 'https://instagram.com/yourhandle'],
                    'theme_social_linkedin'  => ['type' => 'url', 'label' => 'LinkedIn URL',   'default' => '', 'placeholder' => 'https://linkedin.com/in/yourprofile'],
                    'theme_social_youtube'   => ['type' => 'url', 'label' => 'YouTube URL',    'default' => '', 'placeholder' => 'https://youtube.com/@yourchannel'],
                    'theme_social_github'    => ['type' => 'url', 'label' => 'GitHub URL',     'default' => '', 'placeholder' => 'https://github.com/yourprofile'],
                    'theme_social_pinterest' => ['type' => 'url', 'label' => 'Pinterest URL',  'default' => '', 'placeholder' => 'https://pinterest.com/yourprofile'],
                    'theme_social_tiktok'    => ['type' => 'url', 'label' => 'TikTok URL',     'default' => '', 'placeholder' => 'https://tiktok.com/@yourhandle'],
                    'theme_social_whatsapp'  => ['type' => 'url', 'label' => 'WhatsApp Link',  'default' => '', 'placeholder' => 'https://wa.me/yourphone'],
                ],
            ],
            'custom_css' => [
                'title' => 'Custom CSS',
                'icon'  => 'code',
                'fields' => [
                    'theme_custom_css' => [
                        'type'        => 'css',
                        'label'       => 'Custom CSS',
                        'desc'        => 'Add your custom CSS here. It will be injected into the &lt;head&gt; on the frontend. Do not include &lt;style&gt; tags.',
                        'default'     => '',
                        'placeholder' => "/* Add your custom CSS here */\n.my-class {\n    color: red;\n}",
                    ],
                ],
            ],
            'custom_scripts' => [
                'title' => 'Custom Scripts',
                'icon'  => 'javascript',
                'fields' => [
                    'theme_head_script' => [
                        'type'        => 'script',
                        'label'       => 'Head Script',
                        'desc'        => 'Add scripts to be injected into the &lt;head&gt; tag. Useful for Google Analytics, Meta Pixel, etc. Do not include &lt;script&gt; tags.',
                        'default'     => '',
                        'placeholder' => "// Google Analytics or other head scripts\nconsole.log('Head script loaded');",
                    ],
                    'theme_footer_script' => [
                        'type'        => 'script',
                        'label'       => 'Footer Script',
                        'desc'        => 'Add scripts to be injected before the closing &lt;/body&gt; tag. Do not include &lt;script&gt; tags.',
                        'default'     => '',
                        'placeholder' => "// Footer scripts or tracking codes\nconsole.log('Footer script loaded');",
                    ],
                ],
            ],
            'import_export' => [
                'title' => 'Import / Export',
                'icon'  => 'import_export',
                'fields' => [],
            ],
        ];
    }

    public function index(Request $request)
    {
        if (!auth()->user()->hasPermission('manage_settings')) {
            abort(403);
        }

        $section  = $request->get('section', 'layout');
        $sections = $this->sections();
        if (!array_key_exists($section, $sections)) {
            $section = 'layout';
        }

        $rawSettings = DB::table('cms_settings')->pluck('value', 'key')->toArray();

        // Merge defaults so fields always have a value
        $settings = [];
        foreach ($sections as $sec) {
            foreach ($sec['fields'] as $key => $field) {
                $settings[$key] = $rawSettings[$key] ?? ($field['default'] ?? '');
            }
        }

        return view('cms-dashboard::admin.customizer.index', compact('section', 'sections', 'settings'));
    }

    public function save(Request $request)
    {
        if (!auth()->user()->hasPermission('manage_settings')) {
            if ($request->ajax()) return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
            abort(403);
        }

        $activeSection = $request->input('_section', 'layout');

        foreach ($this->sections() as $sec) {
            foreach ($sec['fields'] as $key => $field) {
                $value = $request->input($key, '');
                DB::table('cms_settings')->updateOrInsert(['key' => $key], ['value' => $value]);
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Settings saved successfully.']);
        }

        return redirect()->route('admin.customizer.index', ['section' => $activeSection])
            ->with('success', 'Settings saved successfully.');
    }

    public function resetSection(Request $request)
    {
        if (!auth()->user()->hasPermission('manage_settings')) {
            if ($request->ajax()) return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
            abort(403);
        }

        $section  = $request->input('section', '');
        $sections = $this->sections();

        if ($request->input('all') === '1') {
            foreach ($sections as $sec) {
                foreach ($sec['fields'] as $key => $field) {
                    DB::table('cms_settings')->updateOrInsert(['key' => $key], ['value' => $field['default'] ?? '']);
                }
            }
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'All settings reset to defaults.', 'reload' => true]);
            }
            return redirect()->route('admin.customizer.index')
                ->with('success', 'All settings have been reset to defaults.');
        }

        if (isset($sections[$section])) {
            foreach ($sections[$section]['fields'] as $key => $field) {
                DB::table('cms_settings')->updateOrInsert(['key' => $key], ['value' => $field['default'] ?? '']);
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Section reset to defaults.', 'reload' => true]);
        }

        return redirect()->route('admin.customizer.index', ['section' => $section])
            ->with('success', 'Section reset to defaults.');
    }

    public function export()
    {
        if (!auth()->user()->hasPermission('manage_settings')) {
            abort(403);
        }

        $keys     = [];
        foreach ($this->sections() as $sec) {
            $keys = array_merge($keys, array_keys($sec['fields']));
        }

        $settings = DB::table('cms_settings')
            ->whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();

        return response(json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), 200, [
            'Content-Type'        => 'application/json',
            'Content-Disposition' => 'attachment; filename="theme-options-' . date('Y-m-d') . '.json"',
        ]);
    }

    public function import(Request $request)
    {
        if (!auth()->user()->hasPermission('manage_settings')) {
            abort(403);
        }

        if (!$request->hasFile('import_file')) {
            return redirect()->back()->with('error', 'No file uploaded.');
        }

        try {
            $json = file_get_contents($request->file('import_file')->getRealPath());
            $data = json_decode($json, true);

            if (!$data || !is_array($data)) {
                return redirect()->back()->with('error', 'Invalid JSON file.');
            }

            foreach ($data as $key => $val) {
                DB::table('cms_settings')->updateOrInsert(['key' => $key], ['value' => $val]);
            }

            return redirect()->back()->with('success', 'Settings imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function runAction($action)
    {
        if (!auth()->user()->hasPermission('manage_settings')) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        try {
            if ($action === 'optimizeImages') {
                return $this->optimizeImages();
            }

            return response()->json([
                'success' => false,
                'message' => "Action '{$action}' not found."
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    protected function optimizeImages()
    {
        if (!function_exists('imagecreatefromstring')) {
            throw new \Exception("GD extension with imagecreatefromstring is required.");
        }

        $mediaItems = \Acme\CmsDashboard\Models\Media::where('mime_type', 'like', 'image/%')->get();
        $count = 0;
        $quality = (int)get_cms_option('performance_image_quality', 80);
        $maxWidth = (int)get_cms_option('performance_max_image_width', 1920);
        $autoWebp = get_cms_option('performance_webp_conversion', '1') == '1';

        foreach ($mediaItems as $media) {
            $filePath = storage_path('app/public/' . $media->path);
            if (!file_exists($filePath)) continue;

            // Skip if already webp and we are targeting webp
            if ($autoWebp && $media->mime_type === 'image/webp') continue;

            $img = @imagecreatefromstring(file_get_contents($filePath));
            if (!$img) continue;

            $width = imagesx($img);
            $height = imagesy($img);

            // Resize if needed
            if ($width > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = (int)floor($height * ($maxWidth / $width));
                $tmp = imagecreatetruecolor($newWidth, $newHeight);
                imagealphablending($tmp, false);
                imagesavealpha($tmp, true);
                imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($img);
                $img = $tmp;
                $width = $newWidth;
                $height = $newHeight;
            }

            $filename = pathinfo($media->filename, PATHINFO_FILENAME);
            $extension = $autoWebp ? 'webp' : pathinfo($media->path, PATHINFO_EXTENSION);
            $newFilename = $filename . '-' . time() . '.' . $extension;
            $newPath = 'media/' . $newFilename;

            ob_start();
            $success = false;
            if ($autoWebp && function_exists('imagewebp')) {
                imagepalettetotruecolor($img);
                imagealphablending($img, true);
                imagesavealpha($img, true);
                $success = imagewebp($img, null, $quality);
            } else {
                $ext = strtolower($extension);
                if (($ext === 'jpg' || $ext === 'jpeg') && function_exists('imagejpeg')) {
                    $success = imagejpeg($img, null, $quality);
                } elseif ($ext === 'png' && function_exists('imagepng')) {
                    $success = imagepng($img, null, (int)round(9 * (100 - $quality) / 100));
                }
            }

            $imageData = ob_get_clean();
            if ($success && $imageData) {
                // Delete old file
                \Illuminate\Support\Facades\Storage::disk('public')->delete($media->path);
                
                // Save new file
                \Illuminate\Support\Facades\Storage::disk('public')->put($newPath, $imageData);

                // Update Database
                $media->update([
                    'filename' => $newFilename,
                    'path' => $newPath,
                    'mime_type' => $autoWebp ? 'image/webp' : $media->mime_type,
                    'width' => $width,
                    'height' => $height,
                    'compressed_size' => strlen($imageData)
                ]);
                $count++;
            }
            imagedestroy($img);
        }

        lazy_log_activity('settings_updated', "Bulk optimized {$count} media items via Customizer");

        return response()->json([
            'success' => true,
            'message' => "Successfully optimized {$count} images."
        ]);
    }
}
