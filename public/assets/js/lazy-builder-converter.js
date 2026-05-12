/**
 * Lazy Builder ↔ Shortcode Converter
 *
 * Converts builder JSON ↔ [lazy_section] shortcodes inside the admin rich editor.
 * - On page load : JSON → shortcodes (display-side conversion)
 * - On form submit: shortcodes → JSON (editor_type stays 'rich' to preserve the active tab)
 *
 * All settings are plain human-readable snake_case attributes — no base64.
 * Mirrors BuilderShortcodeConverter.php exactly.
 */
(function () {
    'use strict';

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    function num(v) {
        if (v === null || v === undefined || v === '') return null;
        if (typeof v === 'number') return v;
        var n = parseFloat(v);
        if (isNaN(n)) return v;
        return n === Math.floor(n) ? Math.floor(n) : n;
    }

    /** Push key="value" onto array; skip null/undefined/empty */
    function attr(a, key, value) {
        if (value === null || value === undefined || value === '') return;
        a.push(key + '="' + value + '"');
    }

    /** Push key="value" onto array; skip null/undefined/empty AND skip === default */
    function attrIf(a, key, value, skip) {
        if (value === null || value === undefined || value === '' || value === skip) return;
        a.push(key + '="' + value + '"');
    }

    /** Append key="value" to a string; skip null/undefined/empty AND skip === default */
    function attrI(str, key, value, skip) {
        if (value === null || value === undefined || value === '') return str;
        if (skip !== undefined && value === skip) return str;
        return str + ' ' + key + '="' + value + '"';
    }

    function parseAttrs(str) {
        var out = {};
        var rx = /(\w+)\s*=\s*"([^"]*)"/g;
        var m;
        while ((m = rx.exec(str)) !== null) {
            out[m[1]] = m[2];
        }
        return out;
    }

    function visibilityFromAttrs(a) {
        return {
            mobile:  (a.hide_mobile  || '') !== 'yes',
            tablet:  (a.hide_tablet  || '') !== 'yes',
            desktop: (a.hide_desktop || '') !== 'yes'
        };
    }

    function generateId() {
        return Math.random().toString(36).substring(2, 11);
    }

    // -------------------------------------------------------------------------
    // JSON → Shortcodes
    // -------------------------------------------------------------------------

    function jsonToShortcodes(jsonStr) {
        var layout;
        try { layout = JSON.parse(jsonStr); } catch (e) { return jsonStr; }
        if (!Array.isArray(layout) || !layout.length) return jsonStr;
        return layout.map(containerToShortcode).join('\n\n');
    }

    function containerToShortcode(c) {
        var s = c.settings || {};
        var a = [];

        a.push('id="'   + (c.id   || '') + '"');
        a.push('type="' + (c.type || 'container') + '"');

        attr(a, 'status',        s.status);
        attr(a, 'content_width', s.contentWidth);
        attr(a, 'height',        s.height);
        attr(a, 'custom_height', s.customHeight);

        attr(a, 'bg_type',    s.bgType);
        attr(a, 'bg_color',   s.bgColor);
        attrIf(a, 'bg_opacity', s.bgColorOpacity, 1);

        attr(a, 'gradient_start',        s.bgGradientStartColor);
        attr(a, 'gradient_end',          s.bgGradientEndColor);
        attrIf(a, 'gradient_type',       s.bgGradientType, 'linear');
        attrIf(a, 'gradient_angle',      s.bgGradientAngle, 180);
        attrIf(a, 'gradient_start_pos',  s.bgGradientStartPosition, 0);
        attrIf(a, 'gradient_end_pos',    s.bgGradientEndPosition, 100);

        attr(a, 'bg_image',       s.bgImage);
        attr(a, 'bg_position',    s.bgImagePosition);
        attrIf(a, 'bg_size',      s.bgImageSize, 'auto');
        attrIf(a, 'bg_repeat',    s.bgImageRepeat, 'no-repeat');
        attrIf(a, 'bg_parallax',  s.bgImageParallax, 'none');
        attrIf(a, 'bg_blend',     s.bgImageBlendMode, 'normal');

        ['top', 'bottom', 'left', 'right'].forEach(function (side) {
            var cap = side.charAt(0).toUpperCase() + side.slice(1);
            var pk = 'padding' + cap, mk = 'margin' + cap;
            if (pk in s && s[pk] !== null && s[pk] !== undefined) a.push('padding_' + side + '="' + s[pk] + '"');
            if (mk in s && s[mk] !== null && s[mk] !== undefined) a.push('margin_'  + side + '="' + s[mk] + '"');
        });

        attrIf(a, 'align_items',     s.alignItems,     'stretch');
        attrIf(a, 'justify_content', s.justifyContent, 'flex-start');
        attrIf(a, 'flex_wrap',       s.flexWrap,       'wrap');
        attr(a, 'column_gap', s.columnGap);

        attrIf(a, 'html_tag',    s.htmlTag, 'div');
        attr(a, 'menu_anchor',   s.menuAnchor);
        attr(a, 'css_class',     s.cssClass);
        attr(a, 'z_index',       s.zIndex);
        attrIf(a, 'overflow',    s.overflow, 'default');

        var v = s.visibility || {};
        if (v.mobile  === false) a.push('hide_mobile="yes"');
        if (v.tablet  === false) a.push('hide_tablet="yes"');
        if (v.desktop === false) a.push('hide_desktop="yes"');

        attr(a, 'link',         s.linkUrl);
        attrIf(a, 'link_target', s.linkTarget, '_self');
        attr(a, 'link_color',   s.linkColor);

        ['Top', 'Right', 'Bottom', 'Left'].forEach(function (side) {
            attr(a, 'border_' + side.toLowerCase(), s['borderSize' + side]);
        });
        attrIf(a, 'border_color', s.borderColor, '#000000');
        [['TopLeft','tl'],['TopRight','tr'],['BottomRight','br'],['BottomLeft','bl']].forEach(function (pair) {
            attr(a, 'radius_' + pair[1], s['borderRadius' + pair[0]]);
        });

        if (s.boxShadow) {
            a.push('box_shadow="yes"');
            attr(a, 'shadow_color',  s.boxShadowColor);
            attr(a, 'shadow_h',      s.boxShadowPositionHorizontal);
            attr(a, 'shadow_v',      s.boxShadowPositionVertical);
            attr(a, 'shadow_blur',   s.boxShadowBlurRadius);
            attr(a, 'shadow_spread', s.boxShadowSpreadRadius);
            attrIf(a, 'shadow_style', s.boxShadowStyle, 'outer');
        }

        var colLines = (c.columns || []).map(function (col) {
            return '  ' + columnToShortcode(col);
        });
        var inner = colLines.length ? '\n' + colLines.join('\n') + '\n' : '';

        return '[lazy_section ' + a.join(' ') + ']' + inner + '[/lazy_section]';
    }

    function columnToShortcode(col) {
        var s = col.settings || {};
        var a = [];

        a.push('id="'    + (col.id    || '') + '"');
        a.push('width="' + (col.basis || '100%') + '"');

        ['top', 'bottom', 'left', 'right'].forEach(function (side) {
            var cap = side.charAt(0).toUpperCase() + side.slice(1);
            var pk = 'padding' + cap, mk = 'margin' + cap;
            if (pk in s && s[pk] !== null && s[pk] !== undefined) a.push('padding_' + side + '="' + s[pk] + '"');
            if (mk in s && s[mk] !== null && s[mk] !== undefined) a.push('margin_'  + side + '="' + s[mk] + '"');
        });

        attrIf(a, 'alignment',     s.alignment,    'default');
        attr(a, 'content_layout',  s.contentLayout);
        attr(a, 'align_h',         s.contentAlignH);
        attr(a, 'align_v',         s.contentAlignV);
        attr(a, 'gap_width',       s.gapWidth);
        attr(a, 'gap_height',      s.gapHeight);
        attrIf(a, 'html_tag',      s.htmlTag, 'div');
        attr(a, 'css_class',       s.cssClass);
        attr(a, 'css_id',          s.cssId);

        attrIf(a, 'bg_color',      s.bgColor, 'transparent');
        attr(a, 'text_color',      s.textColor);
        attrIf(a, 'bg_type',       s.bgType, 'color');
        attrIf(a, 'hover_type',    s.hoverType, 'none');

        attr(a, 'gradient_start',      s.bgGradientStartColor);
        attr(a, 'gradient_end',        s.bgGradientEndColor);
        attrIf(a, 'gradient_angle',    s.bgGradientAngle, 180);

        attr(a, 'bg_image',     s.bgImage);
        attr(a, 'bg_position',  s.bgImagePosition);

        attr(a, 'link',           s.linkUrl);
        attrIf(a, 'link_target',  s.linkTarget, '_self');

        var v = s.visibility || {};
        if (v.mobile  === false) a.push('hide_mobile="yes"');
        if (v.tablet  === false) a.push('hide_tablet="yes"');
        if (v.desktop === false) a.push('hide_desktop="yes"');

        ['Top', 'Right', 'Bottom', 'Left'].forEach(function (side) {
            attr(a, 'border_' + side.toLowerCase(), s['borderSize' + side]);
        });
        attrIf(a, 'border_color', s.borderColor, '#000000');
        [['TopLeft','tl'],['TopRight','tr'],['BottomRight','br'],['BottomLeft','bl']].forEach(function (pair) {
            attr(a, 'radius_' + pair[1], s['borderRadius' + pair[0]]);
        });

        var elems = (col.elements || []).map(elementToShortcode);
        var inner = elems.length ? ' ' + elems.join(' ') + ' ' : '';

        return '[lazy_col ' + a.join(' ') + ']' + inner + '[/lazy_col]';
    }

    function elementToShortcode(el) {
        var type = el.type     || 'text';
        var id   = el.id       || '';
        var s    = el.settings || {};
        var base = id ? 'id="' + id + '"' : '';

        var visAttrs = [];
        var v = s.visibility || {};
        if (v.mobile  === false) visAttrs.push('hide_mobile="yes"');
        if (v.tablet  === false) visAttrs.push('hide_tablet="yes"');
        if (v.desktop === false) visAttrs.push('hide_desktop="yes"');
        var vis = visAttrs.length ? ' ' + visAttrs.join(' ') : '';

        switch (type) {
            case 'heading': {
                var a = base;
                a = attrI(a, 'tag',        s.tag,        'h2');
                a = attrI(a, 'font_size',   s.fontSize);
                a = attrI(a, 'font_weight', s.fontWeight);
                a = attrI(a, 'align',       s.textAlign);
                a = attrI(a, 'color',       s.color);
                a = attrI(a, 'css_class',   s.cssClass);
                return '[lazy_heading ' + a.trim() + vis + ']' + (s.title || '').replace(/[\r\n]+/g, '') + '[/lazy_heading]';
            }
            case 'title': {
                var a = base;
                a = attrI(a, 'font_size',      s.fontSize);
                a = attrI(a, 'font_size_unit',  s.fontSizeUnit, 'px');
                a = attrI(a, 'font_weight',     s.fontWeight);
                a = attrI(a, 'align',           s.textAlign);
                a = attrI(a, 'color',           s.titleColor);
                a = attrI(a, 'separator',       s.separator, 'default');
                a = attrI(a, 'separator_color', s.separatorColor);
                a = attrI(a, 'use_link',        s.useLink ? 'yes' : null);
                a = attrI(a, 'link_url',        s.linkUrl);
                a = attrI(a, 'link_color',      s.linkColor);
                a = attrI(a, 'css_class',       s.cssClass);
                return '[lazy_title ' + a.trim() + vis + ']' + (s.title || '').replace(/[\r\n]+/g, '') + '[/lazy_title]';
            }
            case 'text': {
                var a = base;
                a = attrI(a, 'font_size',   s.fontSize);
                a = attrI(a, 'font_weight', s.fontWeight);
                a = attrI(a, 'color',       s.color);
                a = attrI(a, 'align',       s.textAlign);
                a = attrI(a, 'css_class',   s.cssClass);
                return '[lazy_text ' + a.trim() + vis + ']' + (s.content || '').replace(/[\r\n]+/g, '') + '[/lazy_text]';
            }
            case 'button': {
                var a = base;
                a = attrI(a, 'text',       s.text      || 'Button');
                a = attrI(a, 'url',        s.url       || '#');
                a = attrI(a, 'target',     s.target,   '_self');
                a = attrI(a, 'bg_color',   s.bgColor);
                a = attrI(a, 'text_color', s.textColor);
                a = attrI(a, 'align',      s.alignment);
                a = attrI(a, 'size',       s.size);
                a = attrI(a, 'css_class',  s.cssClass);
                return '[lazy_button ' + a.trim() + vis + ' /]';
            }
            case 'image': {
                var a = base;
                a = attrI(a, 'src',       s.src);
                a = attrI(a, 'alt',       s.alt);
                a = attrI(a, 'width',     s.width);
                a = attrI(a, 'align',     s.alignment);
                a = attrI(a, 'css_class', s.cssClass);
                return '[lazy_image ' + a.trim() + vis + ' /]';
            }
            case 'spacer': {
                var a = base;
                a = attrI(a, 'height', s.height !== undefined ? s.height : 20);
                return '[lazy_spacer ' + a.trim() + vis + ' /]';
            }
            case 'video': {
                var a = base;
                a = attrI(a, 'src',    s.src);
                a = attrI(a, 'type',   s.type);
                a = attrI(a, 'width',  s.width);
                a = attrI(a, 'height', s.height);
                return '[lazy_video ' + a.trim() + vis + ' /]';
            }
            case 'row': {
                if (el.columns && el.columns.length) {
                    var rowCols = el.columns.map(columnToShortcode);
                    var rowInner = ' ' + rowCols.join(' ') + ' ';
                    return '[lazy_row' + (base ? ' ' + base.trim() : '') + vis + ']' + rowInner + '[/lazy_row]';
                }
                return '[lazy_row ' + base.trim() + vis + ' /]';
            }
            default:
                return '[lazy_element type="' + type + '" ' + base.trim() + vis + ' /]';
        }
    }

    // -------------------------------------------------------------------------
    // Shortcodes → JSON
    // -------------------------------------------------------------------------

    function shortcodesToJson(content) {
        var layout = [];
        var containerRx = /\[lazy_section([^\]]*)\]([\s\S]*?)\[\/lazy_section\]/g;
        var m;
        while ((m = containerRx.exec(content)) !== null) {
            var container = parseContainer(m[1], m[2]);
            if (container) layout.push(container);
        }
        return JSON.stringify(layout);
    }

    function parseContainer(attrStr, inner) {
        var a = parseAttrs(attrStr);
        return {
            id:       a.id   || generateId(),
            type:     a.type || 'container',
            settings: containerSettings(a),
            columns:  parseColumns(inner)
        };
    }

    function parseColumns(inner) {
        var cols = [];
        var pos  = 0;
        var len  = inner.length;

        while (pos < len) {
            var tagStart = inner.indexOf('[lazy_col', pos);
            if (tagStart === -1) break;

            var c = inner[tagStart + 9];
            if (c !== ' ' && c !== ']') { pos = tagStart + 9; continue; }

            var openEnd = inner.indexOf(']', tagStart);
            if (openEnd === -1) break;

            var attrStr = inner.substring(tagStart + 9, openEnd);
            var depth   = 1;
            var search  = openEnd + 1;
            var done    = false;

            while (depth > 0 && search < len) {
                var nextOpen  = inner.indexOf('[lazy_col', search);
                var nextClose = inner.indexOf('[/lazy_col]', search);

                if (nextClose === -1) break;

                if (nextOpen !== -1 && nextOpen < nextClose) {
                    var nc = inner[nextOpen + 9];
                    if (nc === ' ' || nc === ']') depth++;
                    search = nextOpen + 9;
                } else {
                    depth--;
                    if (depth === 0) {
                        var colInner = inner.substring(openEnd + 1, nextClose);
                        var col = parseColumn(attrStr, colInner);
                        if (col) cols.push(col);
                        pos  = nextClose + 11; // '[/lazy_col]'.length
                        done = true;
                        break;
                    }
                    search = nextClose + 11;
                }
            }

            if (!done) break;
        }

        return cols;
    }

    function parseColumn(attrStr, inner) {
        var a = parseAttrs(attrStr);
        return {
            id:       a.id    || generateId(),
            basis:    a.width || '100%',
            settings: columnSettings(a),
            elements: parseElements(inner)
        };
    }

    function parseElements(inner) {
        var elems = [];
        var elemRx = /\[lazy_(?!section\b|col\b)(\w+)([^\]]*?)(?:\/\]|\]([\s\S]*?)\[\/lazy_\1\])/g;
        var m;
        while ((m = elemRx.exec(inner)) !== null) {
            var elem = parseElement(m[1], m[2], m[3] || '');
            if (elem) elems.push(elem);
        }
        return elems;
    }

    function parseElement(type, attrStr, inner) {
        var a   = parseAttrs(attrStr);
        var vis = visibilityFromAttrs(a);

        switch (type) {
            case 'heading':
                return { id: a.id || generateId(), type: 'heading', settings: {
                    title:      inner.trim(),
                    tag:        a.tag        || 'h2',
                    fontSize:   a.font_size   || null,
                    fontWeight: a.font_weight || null,
                    textAlign:  a.align       || null,
                    color:      a.color       || null,
                    cssClass:   a.css_class   || null,
                    visibility: vis
                }};

            case 'title':
                return { id: a.id || generateId(), type: 'title', settings: {
                    title:          inner.trim(),
                    fontSize:       a.font_size ? parseInt(a.font_size) : null,
                    fontSizeUnit:   a.font_size_unit  || 'px',
                    fontWeight:     a.font_weight     || null,
                    textAlign:      a.align           || null,
                    titleColor:     a.color           || null,
                    separator:      a.separator       || 'default',
                    separatorColor: a.separator_color || null,
                    useLink:        (a.use_link || '') === 'yes',
                    linkUrl:        a.link_url   || null,
                    linkColor:      a.link_color || null,
                    cssClass:       a.css_class  || null,
                    visibility: vis
                }};

            case 'text':
                return { id: a.id || generateId(), type: 'text', settings: {
                    content:    inner.trim(),
                    fontSize:   a.font_size   || null,
                    fontWeight: a.font_weight || null,
                    color:      a.color       || null,
                    textAlign:  a.align       || null,
                    cssClass:   a.css_class   || null,
                    visibility: vis
                }};

            case 'button':
                return { id: a.id || generateId(), type: 'button', settings: {
                    text:      a.text       || 'Button',
                    url:       a.url        || '#',
                    target:    a.target     || '_self',
                    bgColor:   a.bg_color   || null,
                    textColor: a.text_color || null,
                    alignment: a.align      || null,
                    size:      a.size       || null,
                    cssClass:  a.css_class  || null,
                    visibility: vis
                }};

            case 'image':
                return { id: a.id || generateId(), type: 'image', settings: {
                    src:       a.src       || '',
                    alt:       a.alt       || '',
                    width:     a.width     || null,
                    alignment: a.align     || null,
                    cssClass:  a.css_class || null,
                    visibility: vis
                }};

            case 'spacer':
                return { id: a.id || generateId(), type: 'spacer', settings: {
                    height:    a.height ? parseInt(a.height) : 20,
                    visibility: vis
                }};

            case 'video':
                return { id: a.id || generateId(), type: 'video', settings: {
                    src:    a.src    || '',
                    type:   a.type   || null,
                    width:  a.width  || null,
                    height: a.height || null,
                    visibility: vis
                }};

            case 'row': {
                var rowObj = { id: a.id || generateId(), type: 'row', settings: { visibility: vis } };
                if (inner.trim()) {
                    var nestedCols = parseColumns(inner);
                    if (nestedCols.length) rowObj.columns = nestedCols;
                }
                return rowObj;
            }

            default:
                return { id: a.id || generateId(), type: type === 'element' ? (a.type || 'text') : type, settings: {} };
        }
    }

    // -------------------------------------------------------------------------
    // Settings builders — mirror PHP containerSettings() / columnSettings()
    // -------------------------------------------------------------------------

    function containerSettings(a) {
        return {
            marginTop:    num(a.margin_top    !== undefined ? a.margin_top    : null),
            marginBottom: num(a.margin_bottom !== undefined ? a.margin_bottom : null),
            paddingTop:   num(a.padding_top   !== undefined ? a.padding_top   : 0),
            paddingBottom:num(a.padding_bottom !== undefined ? a.padding_bottom : 0),
            paddingLeft:  num(a.padding_left  !== undefined ? a.padding_left  : 0),
            paddingRight: num(a.padding_right !== undefined ? a.padding_right : 0),
            bgColor:              a.bg_color       || null,
            bgColorOpacity:       a.bg_opacity ? parseFloat(a.bg_opacity) : 1,
            bgType:               a.bg_type        || 'color',
            bgGradientStartColor: a.gradient_start || null,
            bgGradientEndColor:   a.gradient_end   || null,
            bgGradientStartPosition: a.gradient_start_pos ? parseInt(a.gradient_start_pos) : 0,
            bgGradientEndPosition:   a.gradient_end_pos   ? parseInt(a.gradient_end_pos)   : 100,
            bgGradientType:       a.gradient_type  || 'linear',
            bgGradientAngle:      a.gradient_angle ? parseInt(a.gradient_angle) : 180,
            bgImage:              a.bg_image        || null,
            bgImageSkipLazy:      false,
            bgImagePosition:      a.bg_position     || 'center center',
            bgImageRepeat:        a.bg_repeat       || 'no-repeat',
            bgImageSize:          a.bg_size         || 'auto',
            bgImageFading:        false,
            bgImageParallax:      a.bg_parallax     || 'none',
            bgImageBlendMode:     a.bg_blend        || 'normal',
            contentWidth:         a.content_width   || 'site',
            height:               a.height          || 'auto',
            customHeight:         a.custom_height   || null,
            alignItems:           a.align_items     || 'stretch',
            alignContent:         null,
            justifyContent:       a.justify_content || 'flex-start',
            flexWrap:             a.flex_wrap       || 'wrap',
            columnGap:            num(a.column_gap  || null),
            htmlTag:              a.html_tag        || 'div',
            menuAnchor:           a.menu_anchor     || null,
            visibility:           visibilityFromAttrs(a),
            status:               a.status          || 'published',
            cssClass:             a.css_class       || null,
            linkColor:            a.link_color      || null,
            linkUrl:              a.link            || null,
            linkTarget:           a.link_target     || '_self',
            borderSizeTop:        num(a.border_top    || null),
            borderSizeRight:      num(a.border_right  || null),
            borderSizeBottom:     num(a.border_bottom || null),
            borderSizeLeft:       num(a.border_left   || null),
            borderColor:          a.border_color     || '#000000',
            borderRadiusTopLeft:     num(a.radius_tl  || null),
            borderRadiusTopRight:    num(a.radius_tr  || null),
            borderRadiusBottomRight: num(a.radius_br  || null),
            borderRadiusBottomLeft:  num(a.radius_bl  || null),
            boxShadow:               (a.box_shadow || '') === 'yes',
            boxShadowPositionVertical:   num(a.shadow_v      || 0),
            boxShadowPositionHorizontal: num(a.shadow_h      || 0),
            boxShadowBlurRadius:         num(a.shadow_blur   || 0),
            boxShadowSpreadRadius:       num(a.shadow_spread || 0),
            boxShadowColor:              a.shadow_color || '#000000',
            boxShadowStyle:              a.shadow_style || 'outer',
            zIndex:                      num(a.z_index  || null),
            overflow:                    a.overflow    || 'default'
        };
    }

    function columnSettings(a) {
        return {
            paddingTop:    num(a.padding_top    !== undefined ? a.padding_top    : 10),
            paddingBottom: num(a.padding_bottom !== undefined ? a.padding_bottom : 10),
            paddingLeft:   num(a.padding_left   !== undefined ? a.padding_left   : 10),
            paddingRight:  num(a.padding_right  !== undefined ? a.padding_right  : 10),
            marginTop:     num(a.margin_top     !== undefined ? a.margin_top     : 0),
            marginBottom:  num(a.margin_bottom  !== undefined ? a.margin_bottom  : 0),
            marginLeft:    num(a.margin_left    !== undefined ? a.margin_left    : 0),
            marginRight:   num(a.margin_right   !== undefined ? a.margin_right   : 0),
            alignment:     a.alignment      || 'default',
            contentLayout: a.content_layout || null,
            contentAlignH: a.align_h        || 'flex-start',
            contentAlignV: a.align_v        || 'flex-start',
            gapWidth:      num(a.gap_width   || null),
            gapHeight:     num(a.gap_height  || null),
            htmlTag:       a.html_tag        || 'div',
            linkUrl:       a.link            || null,
            linkTarget:    a.link_target     || '_self',
            visibility:    visibilityFromAttrs(a),
            cssClass:      a.css_class       || null,
            cssId:         a.css_id          || null,
            textColor:     a.text_color      || null,
            bgColor:       a.bg_color        || 'transparent',
            bgColorOpacity:1,
            bgType:        a.bg_type         || 'color',
            hoverType:     a.hover_type      || 'none',
            bgGradientStartColor: a.gradient_start || null,
            bgGradientEndColor:   a.gradient_end   || null,
            bgGradientStartOpacity:  1,
            bgGradientEndOpacity:    1,
            bgGradientStartPosition: 0,
            bgGradientEndPosition:   100,
            bgGradientType:   'linear',
            bgGradientAngle:  a.gradient_angle ? parseInt(a.gradient_angle) : 180,
            bgImage:          a.bg_image       || null,
            bgImageSkipLazy:  false,
            bgImagePosition:  a.bg_position    || 'center center',
            bgImageRepeat:    'no-repeat',
            bgImageSize:      'auto',
            bgImageFading:    false,
            bgImageParallax:  'none',
            bgImageBlendMode: 'normal',
            fontSize:         null,
            fontWeight:       null,
            lineHeight:       null,
            letterSpacing:    null,
            textAlign:        null,
            borderSizeTop:    num(a.border_top    || null),
            borderSizeRight:  num(a.border_right  || null),
            borderSizeBottom: num(a.border_bottom || null),
            borderSizeLeft:   num(a.border_left   || null),
            borderColor:      a.border_color      || '#000000',
            borderRadiusTopLeft:     num(a.radius_tl || null),
            borderRadiusTopRight:    num(a.radius_tr || null),
            borderRadiusBottomRight: num(a.radius_br || null),
            borderRadiusBottomLeft:  num(a.radius_bl || null),
            boxShadow:               false,
            boxShadowPositionVertical:   0,
            boxShadowPositionHorizontal: 0,
            boxShadowBlurRadius:         0,
            boxShadowSpreadRadius:       0,
            boxShadowColor:              '#000000',
            boxShadowStyle:              'outer'
        };
    }

    // -------------------------------------------------------------------------
    // Detection helpers
    // -------------------------------------------------------------------------

    function isBuilderJson(str) {
        var s = (str || '').trim();
        if (!s || (s[0] !== '[' && s[0] !== '{')) return false;
        try {
            var parsed = JSON.parse(s);
            return Array.isArray(parsed) && parsed.length > 0 && parsed[0].id !== undefined;
        } catch (e) {
            return false;
        }
    }

    function isBuilderShortcode(str) {
        return (str || '').indexOf('[lazy_section') !== -1;
    }

    // -------------------------------------------------------------------------
    // Main initialisation
    // -------------------------------------------------------------------------

    function setEditorContent(shortcodes) {
        var textarea = document.getElementById('wp-editor');
        if (textarea) textarea.value = shortcodes;

        if (typeof tinymce === 'undefined') return;
        var ed = tinymce.get('wp-editor');
        if (ed && ed.initialized) {
            ed.setContent(shortcodes);
        } else {
            tinymce.on('AddEditor', function onAdd(ev) {
                if (ev.editor.id === 'wp-editor') {
                    ev.editor.on('init', function () { this.setContent(shortcodes); });
                    tinymce.off('AddEditor', onAdd);
                }
            });
        }
    }

    function init() {
        var textarea = document.getElementById('wp-editor');
        if (!textarea) return;

        // When returning via browser Back from the page builder (bfcache restore),
        // the textarea still holds the old shortcodes — force a fresh load so the
        // latest saved JSON is converted again.
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) location.reload();
        });

        // Convert JSON → shortcodes on load (for display)
        var content = textarea.value;
        if (isBuilderJson(content)) {
            try {
                var shortcodes = jsonToShortcodes(content);
                if (shortcodes && shortcodes !== content) {
                    setEditorContent(shortcodes);
                }
            } catch (e) {
                console.warn('[LazyBuilder] Could not convert JSON to shortcodes:', e);
            }
        }

        // Intercept form submit: shortcodes → JSON before sending to server
        var form = document.getElementById('post-form');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            var richContainer = document.getElementById('rich-editor-container');

            // Only intercept when the rich editor is the active tab
            if (richContainer && richContainer.classList.contains('hidden')) return;

            // Sync TinyMCE → textarea so we read the latest typed content
            if (typeof tinymce !== 'undefined') {
                var ed = tinymce.get('wp-editor');
                if (ed) ed.save();
            }

            var currentContent = textarea.value;
            if (!isBuilderShortcode(currentContent)) return;

            e.preventDefault();

            try {
                var json = shortcodesToJson(currentContent);
                textarea.value = json;
                // Keep editor_type as 'rich' — user saved from rich editor, not page builder
            } catch (err) {
                console.error('[LazyBuilder] Could not convert shortcodes to JSON:', err);
            }

            form.submit();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
