<?php

namespace Acme\CmsDashboard\Services;

/**
 * Converts Lazy Builder JSON ↔ human-readable shortcodes.
 *
 * Format mirrors Fusion Builder style — every setting is a plain attribute.
 * No base64 encoding. Null / default values are omitted to keep shortcodes short.
 *
 * Roundtrip: shortcode attributes are mapped back to the exact camelCase keys
 * the builder expects, with null defaults for any omitted setting.
 */
class BuilderShortcodeConverter
{
    // =========================================================================
    // Public API
    // =========================================================================

    public static function isBuilderJson(string $content): bool
    {
        $t = trim($content);
        if (empty($t) || ($t[0] !== '[' && $t[0] !== '{')) return false;
        $d = json_decode($t, true);
        return is_array($d) && !empty($d) && isset($d[0]['id']);
    }

    public static function isBuilderShortcode(string $content): bool
    {
        return str_contains($content, '[lazy_section');
    }

    public static function jsonToShortcodes(string $json): string
    {
        $layout = json_decode($json, true);
        if (!is_array($layout) || empty($layout)) return $json;
        return implode("\n\n", array_map([self::class, 'containerToShortcode'], $layout));
    }

    public static function shortcodesToJson(string $content): string
    {
        $layout  = [];
        $pattern = '/\[lazy_section([^\]]*)\](.*?)\[\/lazy_section\]/s';
        if (!preg_match_all($pattern, $content, $m, PREG_SET_ORDER)) return $content;
        foreach ($m as $match) {
            $c = self::parseContainer($match[1], $match[2]);
            if ($c) $layout[] = $c;
        }
        return json_encode($layout, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    // =========================================================================
    // JSON → Shortcode
    // =========================================================================

    private static function containerToShortcode(array $container): string
    {
        $s  = $container['settings'] ?? [];
        $a  = [];

        $a[] = 'id="'   . ($container['id']   ?? '') . '"';
        $a[] = 'type="' . ($container['type'] ?? 'container') . '"';

        // Status & layout
        self::attr($a, 'status',        $s['status']       ?? null);
        self::attr($a, 'content_width', $s['contentWidth'] ?? null);
        self::attr($a, 'height',        $s['height']       ?? null);
        self::attr($a, 'custom_height', $s['customHeight'] ?? null);

        // Background
        self::attr($a, 'bg_type',    $s['bgType']        ?? null);
        self::attr($a, 'bg_color',   $s['bgColor']       ?? null);
        self::attrIf($a, 'bg_opacity', $s['bgColorOpacity'] ?? null, 1); // skip if == 1

        // Gradient
        self::attr($a, 'gradient_start',    $s['bgGradientStartColor']    ?? null);
        self::attr($a, 'gradient_end',      $s['bgGradientEndColor']      ?? null);
        self::attrIf($a, 'gradient_type',   $s['bgGradientType']          ?? null, 'linear');
        self::attrIf($a, 'gradient_angle',  $s['bgGradientAngle']         ?? null, 180);
        self::attrIf($a, 'gradient_start_pos', $s['bgGradientStartPosition'] ?? null, 0);
        self::attrIf($a, 'gradient_end_pos',   $s['bgGradientEndPosition']   ?? null, 100);

        // Background image
        self::attr($a, 'bg_image',    $s['bgImage']         ?? null);
        self::attr($a, 'bg_position', $s['bgImagePosition'] ?? null);
        self::attrIf($a, 'bg_size',      $s['bgImageSize']     ?? null, 'auto');
        self::attrIf($a, 'bg_repeat',    $s['bgImageRepeat']   ?? null, 'no-repeat');
        self::attrIf($a, 'bg_parallax',  $s['bgImageParallax'] ?? null, 'none');
        self::attrIf($a, 'bg_blend',     $s['bgImageBlendMode'] ?? null, 'normal');

        // Spacing (include 0 so they round-trip)
        foreach (['top', 'bottom', 'left', 'right'] as $side) {
            $key = 'padding' . ucfirst($side);
            if (array_key_exists($key, $s) && $s[$key] !== null) $a[] = 'padding_' . $side . '="' . $s[$key] . '"';
            $key = 'margin' . ucfirst($side);
            if (array_key_exists($key, $s) && $s[$key] !== null) $a[] = 'margin_' . $side . '="' . $s[$key] . '"';
        }

        // Flex/alignment
        self::attrIf($a, 'align_items',     $s['alignItems']     ?? null, 'stretch');
        self::attrIf($a, 'justify_content', $s['justifyContent'] ?? null, 'flex-start');
        self::attrIf($a, 'flex_wrap',       $s['flexWrap']       ?? null, 'wrap');
        self::attr($a, 'column_gap', $s['columnGap'] ?? null);

        // HTML / CSS
        self::attrIf($a, 'html_tag',    $s['htmlTag']    ?? null, 'div');
        self::attr($a, 'menu_anchor',   $s['menuAnchor'] ?? null);
        self::attr($a, 'css_class',     $s['cssClass']   ?? null);
        self::attr($a, 'z_index',       $s['zIndex']     ?? null);
        self::attrIf($a, 'overflow',    $s['overflow']   ?? null, 'default');

        // Visibility (only emit if hidden)
        $v = $s['visibility'] ?? [];
        if (!($v['mobile']  ?? true)) $a[] = 'hide_mobile="yes"';
        if (!($v['tablet']  ?? true)) $a[] = 'hide_tablet="yes"';
        if (!($v['desktop'] ?? true)) $a[] = 'hide_desktop="yes"';

        // Link
        self::attr($a, 'link',        $s['linkUrl']    ?? null);
        self::attrIf($a, 'link_target', $s['linkTarget'] ?? null, '_self');
        self::attr($a, 'link_color',  $s['linkColor']  ?? null);

        // Border
        foreach (['Top', 'Right', 'Bottom', 'Left'] as $side) {
            self::attr($a, 'border_' . strtolower($side), $s['borderSize' . $side] ?? null);
        }
        self::attrIf($a, 'border_color', $s['borderColor'] ?? null, '#000000');
        foreach (['TopLeft' => 'tl', 'TopRight' => 'tr', 'BottomRight' => 'br', 'BottomLeft' => 'bl'] as $k => $short) {
            self::attr($a, 'radius_' . $short, $s['borderRadius' . $k] ?? null);
        }

        // Box shadow
        if (!empty($s['boxShadow'])) {
            $a[] = 'box_shadow="yes"';
            self::attr($a, 'shadow_color',  $s['boxShadowColor']              ?? null);
            self::attr($a, 'shadow_h',      $s['boxShadowPositionHorizontal'] ?? null);
            self::attr($a, 'shadow_v',      $s['boxShadowPositionVertical']   ?? null);
            self::attr($a, 'shadow_blur',   $s['boxShadowBlurRadius']         ?? null);
            self::attr($a, 'shadow_spread', $s['boxShadowSpreadRadius']       ?? null);
            self::attrIf($a, 'shadow_style', $s['boxShadowStyle'] ?? null, 'outer');
        }

        $colLines = [];
        foreach ($container['columns'] ?? [] as $col) {
            $colLines[] = '  ' . self::columnToShortcode($col);
        }
        $inner = $colLines ? "\n" . implode("\n", $colLines) . "\n" : '';

        return '[lazy_section ' . implode(' ', $a) . ']' . $inner . '[/lazy_section]';
    }

    private static function columnToShortcode(array $column): string
    {
        $s = $column['settings'] ?? [];
        $a = [];

        $a[] = 'id="'    . ($column['id']    ?? '') . '"';
        $a[] = 'width="' . ($column['basis'] ?? '100%') . '"';

        // Spacing
        foreach (['top', 'bottom', 'left', 'right'] as $side) {
            $key = 'padding' . ucfirst($side);
            if (array_key_exists($key, $s) && $s[$key] !== null) $a[] = 'padding_' . $side . '="' . $s[$key] . '"';
            $key = 'margin' . ucfirst($side);
            if (array_key_exists($key, $s) && $s[$key] !== null) $a[] = 'margin_' . $side . '="' . $s[$key] . '"';
        }

        // Layout
        self::attrIf($a, 'alignment',      $s['alignment']      ?? null, 'default');
        self::attr($a, 'content_layout',   $s['contentLayout']  ?? null);
        self::attr($a, 'align_h',          $s['contentAlignH']  ?? null);
        self::attr($a, 'align_v',          $s['contentAlignV']  ?? null);
        self::attr($a, 'gap_width',        $s['gapWidth']       ?? null);
        self::attr($a, 'gap_height',       $s['gapHeight']      ?? null);
        self::attrIf($a, 'html_tag',       $s['htmlTag']        ?? null, 'div');
        self::attr($a, 'css_class',        $s['cssClass']       ?? null);
        self::attr($a, 'css_id',           $s['cssId']          ?? null);

        // Colors
        self::attrIf($a, 'bg_color',       $s['bgColor']        ?? null, 'transparent');
        self::attr($a, 'text_color',        $s['textColor']      ?? null);
        self::attrIf($a, 'bg_type',        $s['bgType']         ?? null, 'color');
        self::attrIf($a, 'hover_type',     $s['hoverType']      ?? null, 'none');

        // Gradient (column)
        self::attr($a, 'gradient_start',   $s['bgGradientStartColor'] ?? null);
        self::attr($a, 'gradient_end',     $s['bgGradientEndColor']   ?? null);
        self::attrIf($a, 'gradient_angle', $s['bgGradientAngle']      ?? null, 180);

        // Background image (column)
        self::attr($a, 'bg_image',         $s['bgImage']         ?? null);
        self::attr($a, 'bg_position',      $s['bgImagePosition'] ?? null);

        // Link
        self::attr($a, 'link',             $s['linkUrl']    ?? null);
        self::attrIf($a, 'link_target',    $s['linkTarget'] ?? null, '_self');

        // Visibility
        $v = $s['visibility'] ?? [];
        if (!($v['mobile']  ?? true)) $a[] = 'hide_mobile="yes"';
        if (!($v['tablet']  ?? true)) $a[] = 'hide_tablet="yes"';
        if (!($v['desktop'] ?? true)) $a[] = 'hide_desktop="yes"';

        // Border
        foreach (['Top', 'Right', 'Bottom', 'Left'] as $side) {
            self::attr($a, 'border_' . strtolower($side), $s['borderSize' . $side] ?? null);
        }
        self::attrIf($a, 'border_color', $s['borderColor'] ?? null, '#000000');
        foreach (['TopLeft' => 'tl', 'TopRight' => 'tr', 'BottomRight' => 'br', 'BottomLeft' => 'bl'] as $k => $short) {
            self::attr($a, 'radius_' . $short, $s['borderRadius' . $k] ?? null);
        }

        $elems = [];
        foreach ($column['elements'] ?? [] as $el) {
            $elems[] = self::elementToShortcode($el);
        }
        $inner = $elems ? ' ' . implode(' ', $elems) . ' ' : '';

        return '[lazy_col ' . implode(' ', $a) . ']' . $inner . '[/lazy_col]';
    }

    private static function elementToShortcode(array $el): string
    {
        $type = $el['type']     ?? 'text';
        $id   = $el['id']       ?? '';
        $s    = $el['settings'] ?? [];
        $base = $id ? 'id="' . $id . '"' : '';

        // Visibility (common to all elements)
        $visAttrs = [];
        $v = $s['visibility'] ?? [];
        if (!($v['mobile']  ?? true)) $visAttrs[] = 'hide_mobile="yes"';
        if (!($v['tablet']  ?? true)) $visAttrs[] = 'hide_tablet="yes"';
        if (!($v['desktop'] ?? true)) $visAttrs[] = 'hide_desktop="yes"';
        $vis = $visAttrs ? ' ' . implode(' ', $visAttrs) : '';

        switch ($type) {
            case 'heading': {
                $a = $base;
                self::attrI($a, 'tag',        $s['tag']        ?? null, 'h2');
                self::attrI($a, 'font_size',   $s['fontSize']   ?? null);
                self::attrI($a, 'font_weight', $s['fontWeight'] ?? null);
                self::attrI($a, 'align',       $s['textAlign']  ?? null);
                self::attrI($a, 'color',       $s['color']      ?? null);
                self::attrI($a, 'css_class',   $s['cssClass']   ?? null);
                $body = str_replace(["\r\n", "\r", "\n"], '', $s['title'] ?? '');
                return '[lazy_heading ' . trim($a) . $vis . ']' . $body . '[/lazy_heading]';
            }

            case 'title': {
                $a = $base;
                self::attrI($a, 'font_size',      $s['fontSize']    ?? null);
                self::attrI($a, 'font_size_unit',  $s['fontSizeUnit'] ?? null, 'px');
                self::attrI($a, 'font_weight',     $s['fontWeight']  ?? null);
                self::attrI($a, 'align',           $s['textAlign']   ?? null);
                self::attrI($a, 'color',           $s['titleColor']  ?? null);
                self::attrI($a, 'separator',       $s['separator']   ?? null, 'default');
                self::attrI($a, 'separator_color', $s['separatorColor'] ?? null);
                self::attrI($a, 'use_link',        (!empty($s['useLink']) ? 'yes' : null));
                self::attrI($a, 'link_url',        $s['linkUrl']     ?? null);
                self::attrI($a, 'link_color',      $s['linkColor']   ?? null);
                self::attrI($a, 'css_class',       $s['cssClass']    ?? null);
                $body = str_replace(["\r\n", "\r", "\n"], '', $s['title'] ?? '');
                return '[lazy_title ' . trim($a) . $vis . ']' . $body . '[/lazy_title]';
            }

            case 'text': {
                $a = $base;
                self::attrI($a, 'font_size',   $s['fontSize']   ?? null);
                self::attrI($a, 'font_weight', $s['fontWeight'] ?? null);
                self::attrI($a, 'color',       $s['color']      ?? null);
                self::attrI($a, 'align',       $s['textAlign']  ?? null);
                self::attrI($a, 'css_class',   $s['cssClass']   ?? null);
                $body = str_replace(["\r\n", "\r", "\n"], '', $s['content'] ?? '');
                return '[lazy_text ' . trim($a) . $vis . ']' . $body . '[/lazy_text]';
            }

            case 'button': {
                $a = $base;
                self::attrI($a, 'text',       $s['text']            ?? 'Button');
                self::attrI($a, 'url',        $s['url']             ?? '#');
                self::attrI($a, 'target',     $s['target']          ?? null, '_self');
                self::attrI($a, 'bg_color',   $s['bgColor']         ?? null);
                self::attrI($a, 'text_color', $s['textColor']       ?? null);
                self::attrI($a, 'align',      $s['alignment']       ?? null);
                self::attrI($a, 'size',       $s['size']            ?? null);
                self::attrI($a, 'css_class',  $s['cssClass']        ?? null);
                return '[lazy_button ' . trim($a) . $vis . ' /]';
            }

            case 'image': {
                $a = $base;
                self::attrI($a, 'src',       $s['src']       ?? '');
                self::attrI($a, 'alt',       $s['alt']       ?? '');
                self::attrI($a, 'width',     $s['width']     ?? null);
                self::attrI($a, 'align',     $s['alignment'] ?? null);
                self::attrI($a, 'css_class', $s['cssClass']  ?? null);
                return '[lazy_image ' . trim($a) . $vis . ' /]';
            }

            case 'spacer': {
                $a = $base;
                self::attrI($a, 'height', $s['height'] ?? 20);
                return '[lazy_spacer ' . trim($a) . $vis . ' /]';
            }

            case 'video': {
                $a = $base;
                self::attrI($a, 'src',    $s['src']    ?? '');
                self::attrI($a, 'type',   $s['type']   ?? null);
                self::attrI($a, 'width',  $s['width']  ?? null);
                self::attrI($a, 'height', $s['height'] ?? null);
                return '[lazy_video ' . trim($a) . $vis . ' /]';
            }

            case 'row': {
                if (!empty($el['columns'])) {
                    $rowCols = [];
                    foreach ($el['columns'] as $nestedCol) {
                        $rowCols[] = self::columnToShortcode($nestedCol);
                    }
                    $rowInner = ' ' . implode(' ', $rowCols) . ' ';
                    return '[lazy_row' . ($base ? ' ' . trim($base) : '') . $vis . ']' . $rowInner . '[/lazy_row]';
                }
                return '[lazy_row ' . trim($base) . $vis . ' /]';
            }

            default:
                return '[lazy_element type="' . $type . '" ' . trim($base) . $vis . ' /]';
        }
    }

    // =========================================================================
    // Shortcode → JSON
    // =========================================================================

    private static function parseContainer(string $attrStr, string $inner): ?array
    {
        $a = self::attrs($attrStr);

        $container = [
            'id'       => $a['id']   ?? self::uid(),
            'type'     => $a['type'] ?? 'container',
            'settings' => self::containerSettings($a),
            'columns'  => self::parseColumnsFromContent($inner),
        ];

        return $container;
    }

    /**
     * Depth-counting column extractor — handles nested [lazy_col] inside [lazy_row] correctly.
     * A simple .*? regex stops at the first [/lazy_col] it finds (the inner nested one),
     * which breaks nested-row structures.
     */
    private static function parseColumnsFromContent(string $content): array
    {
        $cols = [];
        $pos  = 0;
        $len  = strlen($content);

        while ($pos < $len) {
            $tagStart = strpos($content, '[lazy_col', $pos);
            if ($tagStart === false) break;

            // Must be [lazy_col] or [lazy_col ...], not [lazy_columns or similar
            $c = $content[$tagStart + 9] ?? '';
            if ($c !== ' ' && $c !== ']') { $pos = $tagStart + 9; continue; }

            $openEnd = strpos($content, ']', $tagStart);
            if ($openEnd === false) break;

            $attrStr = substr($content, $tagStart + 9, $openEnd - $tagStart - 9);
            $depth   = 1;
            $search  = $openEnd + 1;
            $done    = false;

            while ($depth > 0 && $search < $len) {
                $nextOpen  = strpos($content, '[lazy_col', $search);
                $nextClose = strpos($content, '[/lazy_col]', $search);

                if ($nextClose === false) break;

                if ($nextOpen !== false && $nextOpen < $nextClose) {
                    $nc = $content[$nextOpen + 9] ?? '';
                    if ($nc === ' ' || $nc === ']') $depth++;
                    $search = $nextOpen + 9;
                } else {
                    $depth--;
                    if ($depth === 0) {
                        $colInner = substr($content, $openEnd + 1, $nextClose - $openEnd - 1);
                        $col = self::parseColumn($attrStr, $colInner);
                        if ($col) $cols[] = $col;
                        $pos  = $nextClose + 11; // strlen('[/lazy_col]')
                        $done = true;
                        break;
                    }
                    $search = $nextClose + 11;
                }
            }

            if (!$done) break;
        }

        return $cols;
    }

    private static function parseColumn(string $attrStr, string $inner): ?array
    {
        $a = self::attrs($attrStr);

        $column = [
            'id'       => $a['id']    ?? self::uid(),
            'basis'    => $a['width'] ?? '100%',
            'settings' => self::columnSettings($a),
            'elements' => [],
        ];

        $elemRx = '/\[lazy_(?!section\b|col\b)(\w+)([^\]]*?)(?:\/\]|\]([\s\S]*?)\[\/lazy_\1\])/';
        if (preg_match_all($elemRx, $inner, $m, PREG_SET_ORDER)) {
            foreach ($m as $em) {
                $elem = self::parseElement($em[1], $em[2], $em[3] ?? '');
                if ($elem) $column['elements'][] = $elem;
            }
        }

        return $column;
    }

    private static function parseElement(string $type, string $attrStr, string $inner): ?array
    {
        $a   = self::attrs($attrStr);
        $vis = self::visibilityFromAttrs($a);

        switch ($type) {
            case 'heading':
                return ['id' => $a['id'] ?? self::uid(), 'type' => 'heading', 'settings' => array_merge([
                    'title'      => trim($inner),
                    'tag'        => $a['tag']        ?? 'h2',
                    'fontSize'   => $a['font_size']   ?? null,
                    'fontWeight' => $a['font_weight'] ?? null,
                    'textAlign'  => $a['align']       ?? null,
                    'color'      => $a['color']       ?? null,
                    'cssClass'   => $a['css_class']   ?? null,
                    'visibility' => $vis,
                ], [])];

            case 'title':
                return ['id' => $a['id'] ?? self::uid(), 'type' => 'title', 'settings' => [
                    'title'          => trim($inner),
                    'fontSize'       => isset($a['font_size']) ? (int)$a['font_size'] : null,
                    'fontSizeUnit'   => $a['font_size_unit']  ?? 'px',
                    'fontWeight'     => $a['font_weight']     ?? null,
                    'textAlign'      => $a['align']           ?? null,
                    'titleColor'     => $a['color']           ?? null,
                    'separator'      => $a['separator']       ?? 'default',
                    'separatorColor' => $a['separator_color'] ?? null,
                    'useLink'        => ($a['use_link'] ?? '') === 'yes',
                    'linkUrl'        => $a['link_url']   ?? null,
                    'linkColor'      => $a['link_color'] ?? null,
                    'cssClass'       => $a['css_class']  ?? null,
                    'visibility'     => $vis,
                ]];

            case 'text':
                return ['id' => $a['id'] ?? self::uid(), 'type' => 'text', 'settings' => [
                    'content'    => trim($inner),
                    'fontSize'   => $a['font_size']   ?? null,
                    'fontWeight' => $a['font_weight'] ?? null,
                    'color'      => $a['color']       ?? null,
                    'textAlign'  => $a['align']       ?? null,
                    'cssClass'   => $a['css_class']   ?? null,
                    'visibility' => $vis,
                ]];

            case 'button':
                return ['id' => $a['id'] ?? self::uid(), 'type' => 'button', 'settings' => [
                    'text'       => $a['text']       ?? 'Button',
                    'url'        => $a['url']        ?? '#',
                    'target'     => $a['target']     ?? '_self',
                    'bgColor'    => $a['bg_color']   ?? null,
                    'textColor'  => $a['text_color'] ?? null,
                    'alignment'  => $a['align']      ?? null,
                    'size'       => $a['size']       ?? null,
                    'cssClass'   => $a['css_class']  ?? null,
                    'visibility' => $vis,
                ]];

            case 'image':
                return ['id' => $a['id'] ?? self::uid(), 'type' => 'image', 'settings' => [
                    'src'        => $a['src']       ?? '',
                    'alt'        => $a['alt']       ?? '',
                    'width'      => $a['width']     ?? null,
                    'alignment'  => $a['align']     ?? null,
                    'cssClass'   => $a['css_class'] ?? null,
                    'visibility' => $vis,
                ]];

            case 'spacer':
                return ['id' => $a['id'] ?? self::uid(), 'type' => 'spacer', 'settings' => [
                    'height'     => isset($a['height']) ? (int)$a['height'] : 20,
                    'visibility' => $vis,
                ]];

            case 'video':
                return ['id' => $a['id'] ?? self::uid(), 'type' => 'video', 'settings' => [
                    'src'        => $a['src']    ?? '',
                    'type'       => $a['type']   ?? null,
                    'width'      => $a['width']  ?? null,
                    'height'     => $a['height'] ?? null,
                    'visibility' => $vis,
                ]];

            case 'row': {
                $rowObj = ['id' => $a['id'] ?? self::uid(), 'type' => 'row', 'settings' => ['visibility' => $vis]];
                if (!empty(trim($inner))) {
                    $nestedCols = self::parseColumnsFromContent($inner);
                    if (!empty($nestedCols)) $rowObj['columns'] = $nestedCols;
                }
                return $rowObj;
            }

            default:
                return ['id' => $a['id'] ?? self::uid(), 'type' => $type === 'element' ? ($a['type'] ?? 'text') : $type, 'settings' => []];
        }
    }

    // =========================================================================
    // Settings builders (shortcode → JSON)
    // =========================================================================

    private static function containerSettings(array $a): array
    {
        return [
            'marginTop'    => self::num($a['margin_top']    ?? null),
            'marginBottom' => self::num($a['margin_bottom'] ?? null),
            'paddingTop'   => self::num($a['padding_top']   ?? 0),
            'paddingBottom'=> self::num($a['padding_bottom']?? 0),
            'paddingLeft'  => self::num($a['padding_left']  ?? 0),
            'paddingRight' => self::num($a['padding_right'] ?? 0),
            'bgColor'             => $a['bg_color']       ?? null,
            'bgColorOpacity'      => isset($a['bg_opacity']) ? (float)$a['bg_opacity'] : 1,
            'bgType'              => $a['bg_type']        ?? 'color',
            'bgGradientStartColor'=> $a['gradient_start'] ?? null,
            'bgGradientEndColor'  => $a['gradient_end']   ?? null,
            'bgGradientStartPosition' => isset($a['gradient_start_pos']) ? (int)$a['gradient_start_pos'] : 0,
            'bgGradientEndPosition'   => isset($a['gradient_end_pos'])   ? (int)$a['gradient_end_pos']   : 100,
            'bgGradientType'      => $a['gradient_type']  ?? 'linear',
            'bgGradientAngle'     => isset($a['gradient_angle']) ? (int)$a['gradient_angle'] : 180,
            'bgImage'             => $a['bg_image']       ?? null,
            'bgImageSkipLazy'     => false,
            'bgImagePosition'     => $a['bg_position']    ?? 'center center',
            'bgImageRepeat'       => $a['bg_repeat']      ?? 'no-repeat',
            'bgImageSize'         => $a['bg_size']        ?? 'auto',
            'bgImageFading'       => false,
            'bgImageParallax'     => $a['bg_parallax']    ?? 'none',
            'bgImageBlendMode'    => $a['bg_blend']       ?? 'normal',
            'contentWidth'        => $a['content_width']  ?? 'site',
            'height'              => $a['height']         ?? 'auto',
            'customHeight'        => $a['custom_height']  ?? null,
            'alignItems'          => $a['align_items']    ?? 'stretch',
            'alignContent'        => null,
            'justifyContent'      => $a['justify_content'] ?? 'flex-start',
            'flexWrap'            => $a['flex_wrap']      ?? 'wrap',
            'columnGap'           => self::num($a['column_gap'] ?? null),
            'htmlTag'             => $a['html_tag']       ?? 'div',
            'menuAnchor'          => $a['menu_anchor']    ?? null,
            'visibility'          => self::visibilityFromAttrs($a),
            'status'              => $a['status']         ?? 'published',
            'cssClass'            => $a['css_class']      ?? null,
            'linkColor'           => $a['link_color']     ?? null,
            'linkUrl'             => $a['link']           ?? null,
            'linkTarget'          => $a['link_target']    ?? '_self',
            'borderSizeTop'       => self::num($a['border_top']    ?? null),
            'borderSizeRight'     => self::num($a['border_right']  ?? null),
            'borderSizeBottom'    => self::num($a['border_bottom'] ?? null),
            'borderSizeLeft'      => self::num($a['border_left']   ?? null),
            'borderColor'         => $a['border_color']   ?? '#000000',
            'borderRadiusTopLeft'     => self::num($a['radius_tl'] ?? null),
            'borderRadiusTopRight'    => self::num($a['radius_tr'] ?? null),
            'borderRadiusBottomRight' => self::num($a['radius_br'] ?? null),
            'borderRadiusBottomLeft'  => self::num($a['radius_bl'] ?? null),
            'boxShadow'               => ($a['box_shadow'] ?? '') === 'yes',
            'boxShadowPositionVertical'   => self::num($a['shadow_v']      ?? 0),
            'boxShadowPositionHorizontal' => self::num($a['shadow_h']      ?? 0),
            'boxShadowBlurRadius'         => self::num($a['shadow_blur']   ?? 0),
            'boxShadowSpreadRadius'       => self::num($a['shadow_spread'] ?? 0),
            'boxShadowColor'              => $a['shadow_color'] ?? '#000000',
            'boxShadowStyle'              => $a['shadow_style'] ?? 'outer',
            'zIndex'                      => self::num($a['z_index']  ?? null),
            'overflow'                    => $a['overflow'] ?? 'default',
        ];
    }

    private static function columnSettings(array $a): array
    {
        return [
            'paddingTop'    => self::num($a['padding_top']    ?? 10),
            'paddingBottom' => self::num($a['padding_bottom'] ?? 10),
            'paddingLeft'   => self::num($a['padding_left']   ?? 10),
            'paddingRight'  => self::num($a['padding_right']  ?? 10),
            'marginTop'     => self::num($a['margin_top']     ?? 0),
            'marginBottom'  => self::num($a['margin_bottom']  ?? 0),
            'marginLeft'    => self::num($a['margin_left']    ?? 0),
            'marginRight'   => self::num($a['margin_right']   ?? 0),
            'alignment'     => $a['alignment']      ?? 'default',
            'contentLayout' => $a['content_layout'] ?? null,
            'contentAlignH' => $a['align_h']        ?? 'flex-start',
            'contentAlignV' => $a['align_v']        ?? 'flex-start',
            'gapWidth'      => self::num($a['gap_width']  ?? null),
            'gapHeight'     => self::num($a['gap_height'] ?? null),
            'htmlTag'       => $a['html_tag']  ?? 'div',
            'linkUrl'       => $a['link']      ?? null,
            'linkTarget'    => $a['link_target'] ?? '_self',
            'visibility'    => self::visibilityFromAttrs($a),
            'cssClass'      => $a['css_class'] ?? null,
            'cssId'         => $a['css_id']    ?? null,
            'textColor'     => $a['text_color'] ?? null,
            'bgColor'       => $a['bg_color']   ?? 'transparent',
            'bgColorOpacity'=> 1,
            'bgType'        => $a['bg_type']    ?? 'color',
            'hoverType'     => $a['hover_type'] ?? 'none',
            'bgGradientStartColor' => $a['gradient_start'] ?? null,
            'bgGradientEndColor'   => $a['gradient_end']   ?? null,
            'bgGradientStartOpacity' => 1,
            'bgGradientEndOpacity'   => 1,
            'bgGradientStartPosition' => 0,
            'bgGradientEndPosition'   => 100,
            'bgGradientType'  => 'linear',
            'bgGradientAngle' => isset($a['gradient_angle']) ? (int)$a['gradient_angle'] : 180,
            'bgImage'         => $a['bg_image']    ?? null,
            'bgImageSkipLazy' => false,
            'bgImagePosition' => $a['bg_position'] ?? 'center center',
            'bgImageRepeat'   => 'no-repeat',
            'bgImageSize'     => 'auto',
            'bgImageFading'   => false,
            'bgImageParallax' => 'none',
            'bgImageBlendMode'=> 'normal',
            'fontSize'        => null,
            'fontWeight'      => null,
            'lineHeight'      => null,
            'letterSpacing'   => null,
            'textAlign'       => null,
            'borderSizeTop'   => self::num($a['border_top']    ?? null),
            'borderSizeRight' => self::num($a['border_right']  ?? null),
            'borderSizeBottom'=> self::num($a['border_bottom'] ?? null),
            'borderSizeLeft'  => self::num($a['border_left']   ?? null),
            'borderColor'     => $a['border_color'] ?? '#000000',
            'borderRadiusTopLeft'     => self::num($a['radius_tl'] ?? null),
            'borderRadiusTopRight'    => self::num($a['radius_tr'] ?? null),
            'borderRadiusBottomRight' => self::num($a['radius_br'] ?? null),
            'borderRadiusBottomLeft'  => self::num($a['radius_bl'] ?? null),
            'boxShadow'               => false,
            'boxShadowPositionVertical'   => 0,
            'boxShadowPositionHorizontal' => 0,
            'boxShadowBlurRadius'         => 0,
            'boxShadowSpreadRadius'       => 0,
            'boxShadowColor'              => '#000000',
            'boxShadowStyle'              => 'outer',
        ];
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    /** Append ' key="value"' to a string (used for element inline attrs) */
    private static function attrI(string &$str, string $key, $value, $skip = null): void
    {
        if ($value === null || $value === '' || $value === $skip) return;
        $str .= ' ' . $key . '="' . $value . '"';
    }

    /** Append to attr array (used for section/col attrs) */
    private static function attr(array &$a, string $key, $value): void
    {
        if ($value === null || $value === '') return;
        $a[] = $key . '="' . $value . '"';
    }

    /** Append to attr array, skip if value equals $skip (default value) */
    private static function attrIf(array &$a, string $key, $value, $skip): void
    {
        if ($value === null || $value === '' || $value === $skip) return;
        $a[] = $key . '="' . $value . '"';
    }

    private static function attrs(string $str): array
    {
        $out = [];
        preg_match_all('/(\w+)\s*=\s*"([^"]*)"/', $str, $m, PREG_SET_ORDER);
        foreach ($m as $pair) $out[$pair[1]] = $pair[2];
        return $out;
    }

    private static function visibilityFromAttrs(array $a): array
    {
        return [
            'mobile'  => ($a['hide_mobile']  ?? '') !== 'yes',
            'tablet'  => ($a['hide_tablet']  ?? '') !== 'yes',
            'desktop' => ($a['hide_desktop'] ?? '') !== 'yes',
        ];
    }

    private static function num($v)
    {
        if ($v === null || $v === '') return null;
        return is_numeric($v) ? ($v == (int)$v ? (int)$v : (float)$v) : $v;
    }

    private static function uid(): string
    {
        return substr(md5(uniqid('', true)), 0, 9);
    }
}
