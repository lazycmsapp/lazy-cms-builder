<script>
    const { createApp, ref, reactive, computed, onMounted, watch } = Vue;

    createApp({
        setup() {
            const layout = ref([]);
            const isPreview = ref(false);
            const isSaving = ref(false);
            const isDirty = ref(false);
            let _trackLayoutDirty = false;
            const activeTab = ref('navigator'); // Default to Navigator like in screenshot
            const device = ref('desktop');
            const activeCi = ref(null);
            const editingCi = ref(null);
            const activeColi = ref(null);
            const activeColCi = ref(null);
            const editingContext = ref({ type: null, ci: null, coli: null, eli: null, ncoli: null, neli: null, tab: 'content' });
            const activePanelTab = ref('general');
            const activeColPanelTab = ref('general');
            const siteWidth = ref("{{ get_cms_option('theme_site_width', '1200px') }}");
            if (!isNaN(siteWidth.value)) siteWidth.value += 'px';

            const isDragging = ref(false);
            const dragType = ref(null);
            const dragCi = ref(null);
            const startY = ref(0);
            const startX = ref(0);
            const startVal = ref(0);
            
            const toasts = ref([]);
            const showToast = (message, type = 'success') => {
                const id = Date.now();
                toasts.value.push({ id, message, type });
                setTimeout(() => {
                    toasts.value = toasts.value.filter(t => t.id !== id);
                }, 3000);
            };

            const hoveredType = ref(null); // 'container', 'column', 'element', 'nested-row', 'nested-column'
            const hoveredCi = ref(null);
            const hoveredColi = ref(null);
            const hoveredEli = ref(null);
            const hoveredNcoli = ref(null);

            const setHover = (type, ci = null, coli = null, eli = null, ncoli = null) => {
                hoveredType.value = type;
                hoveredCi.value = ci;
                hoveredColi.value = coli;
                hoveredEli.value = eli;
                hoveredNcoli.value = ncoli;
            };

            const showColumnModal = ref(false);
            const columnModalTarget = ref(null);

            const searchColumnQuery = ref('');
            const searchElementQuery = ref('');

            const columnLayouts = [
                // Row 1
                { id: '1', label: '1/1', config: '1/1' },
                { id: '2', label: '1/2 - 1/2', config: '1/2-1/2' },
                { id: '3', label: '1/3 - 1/3 - 1/3', config: '1/3-1/3-1/3' },
                { id: '4', label: '1/4 - 1/4 - 1/4 - 1/4', config: '1/4-1/4-1/4-1/4' },
                { id: '5', label: '2/3 - 1/3', config: '2/3-1/3' },
                { id: '6', label: '1/3 - 2/3', config: '1/3-2/3' },
                { id: '7', label: '1/4 - 3/4', config: '1/4-3/4' },
                // Row 2
                { id: '8', label: '3/4 - 1/4', config: '3/4-1/4' },
                { id: '9', label: '1/2 - 1/4 - 1/4', config: '1/2-1/4-1/4' },
                { id: '10', label: '1/4 - 1/4 - 1/2', config: '1/4-1/4-1/2' },
                { id: '11', label: '1/4 - 1/2 - 1/4', config: '1/4-1/2-1/4' },
                { id: '12', label: '1/5 - 4/5', config: '1/5-4/5' },
                { id: '13', label: '4/5 - 1/5', config: '4/5-1/5' },
                { id: '14', label: '3/5 - 2/5', config: '3/5-2/5' },
                // Row 3
                { id: '15', label: '2/5 - 3/5', config: '2/5-3/5' },
                { id: '16', label: '1/5 - 1/5 - 3/5', config: '1/5-1/5-3/5' },
                { id: '17', label: '1/5 - 3/5 - 1/5', config: '1/5-3/5-1/5' },
                { id: '18', label: '1/2 - 1/6 - 1/6 - 1/6', config: '1/2-1/6-1/6-1/6' },
                { id: '19', label: '1/6 - 1/6 - 1/6 - 1/2', config: '1/6-1/6-1/6-1/2' },
                { id: '20', label: '1/6 - 2/3 - 1/6', config: '1/6-2/3-1/6' },
                { id: '21', label: '1/5 - 1/5 - 1/5 - 1/5 - 1/5', config: '1/5-1/5-1/5-1/5-1/5' },
                // Row 4
                { id: '22', label: '1/6 - 1/6 - 1/6 - 1/6 - 1/6 - 1/6', config: '1/6-1/6-1/6-1/6-1/6-1/6' },
                { id: '23', label: '5/6', config: '5/6' },
                { id: '24', label: '4/5', config: '4/5' },
                { id: '25', label: '3/4', config: '3/4' },
                { id: '26', label: '2/3', config: '2/3' },
                { id: '27', label: '3/5', config: '3/5' },
                { id: '28', label: '1/2', config: '1/2' },
                // Row 5
                { id: '29', label: '2/5', config: '2/5' },
                { id: '30', label: '1/3', config: '1/3' },
                { id: '31', label: '1/4', config: '1/4' },
                { id: '32', label: '1/5', config: '1/5' },
                { id: '33', label: '1/6', config: '1/6' },
            ];

            // Customizer theme fonts (passed from controller)
            const themeBodyFont    = @json($themeBodyFont ?? null);
            const themeHeadingFont = @json($themeHeadingFont ?? null);

            // Google Fonts database (mirrors customizer list)
            const BUILDER_FONTS = [
                { family: 'Inter', category: 'Sans-serif', variants: ['100','200','300','400','500','600','700','800','900'] },
                { family: 'Roboto', category: 'Sans-serif', variants: ['100','300','400','500','700','900'] },
                { family: 'Open Sans', category: 'Sans-serif', variants: ['300','400','500','600','700','800'] },
                { family: 'Lato', category: 'Sans-serif', variants: ['100','300','400','700','900'] },
                { family: 'Poppins', category: 'Sans-serif', variants: ['100','200','300','400','500','600','700','800','900'] },
                { family: 'Nunito', category: 'Sans-serif', variants: ['200','300','400','500','600','700','800','900'] },
                { family: 'Montserrat', category: 'Sans-serif', variants: ['100','200','300','400','500','600','700','800','900'] },
                { family: 'Raleway', category: 'Sans-serif', variants: ['100','200','300','400','500','600','700','800','900'] },
                { family: 'Ubuntu', category: 'Sans-serif', variants: ['300','400','500','700'] },
                { family: 'Oswald', category: 'Sans-serif', variants: ['200','300','400','500','600','700'] },
                { family: 'Quicksand', category: 'Sans-serif', variants: ['300','400','500','600','700'] },
                { family: 'Work Sans', category: 'Sans-serif', variants: ['100','200','300','400','500','600','700','800','900'] },
                { family: 'Noto Sans', category: 'Sans-serif', variants: ['100','200','300','400','500','600','700','800','900'] },
                { family: 'Rubik', category: 'Sans-serif', variants: ['300','400','500','600','700','800','900'] },
                { family: 'DM Sans', category: 'Sans-serif', variants: ['400','500','700'] },
                { family: 'Cairo', category: 'Sans-serif', variants: ['200','300','400','500','600','700','800','900'] },
                { family: 'Josefin Sans', category: 'Sans-serif', variants: ['100','200','300','400','500','600','700'] },
                { family: 'Public Sans', category: 'Sans-serif', variants: ['100','200','300','400','500','600','700','800','900'] },
                { family: 'Fira Sans', category: 'Sans-serif', variants: ['100','200','300','400','500','600','700','800','900'] },
                { family: 'Playfair Display', category: 'Serif', variants: ['400','500','600','700','800','900'] },
                { family: 'Merriweather', category: 'Serif', variants: ['300','400','700','900'] },
                { family: 'Lora', category: 'Serif', variants: ['400','500','600','700'] },
                { family: 'PT Serif', category: 'Serif', variants: ['400','700'] },
                { family: 'Libre Baskerville', category: 'Serif', variants: ['400','700'] },
                { family: 'Crimson Text', category: 'Serif', variants: ['400','600','700'] },
                { family: 'EB Garamond', category: 'Serif', variants: ['400','500','600','700','800'] },
                { family: 'Fira Code', category: 'Monospace', variants: ['300','400','500','600','700'] },
                { family: 'Source Code Pro', category: 'Monospace', variants: ['200','300','400','500','600','700','800','900'] },
                { family: 'Roboto Mono', category: 'Monospace', variants: ['100','200','300','400','500','600','700'] },
                { family: 'Lobster', category: 'Display', variants: ['400'] },
                { family: 'Pacifico', category: 'Display', variants: ['400'] },
                { family: 'Dancing Script', category: 'Display', variants: ['400','500','600','700'] },
                { family: 'Bebas Neue', category: 'Display', variants: ['400'] },
                { family: 'Comfortaa', category: 'Display', variants: ['300','400','500','600','700'] },
            ];

            const builderFontGroups = BUILDER_FONTS.reduce((acc, font) => {
                if (!acc[font.category]) acc[font.category] = [];
                acc[font.category].push(font);
                return acc;
            }, {});

            const loadBuilderFont = (fontFamily) => {
                if (!fontFamily || fontFamily === 'inherit') return;
                const match = fontFamily.match(/['"]?([^'",$]+)/);
                const name = match ? match[1].trim() : fontFamily.trim();
                if (!name || name === 'inherit') return;
                const linkId = 'bfont-' + name.replace(/\s+/g, '-').toLowerCase();
                if (document.getElementById(linkId)) return;
                const link = document.createElement('link');
                link.id   = linkId;
                link.rel  = 'stylesheet';
                link.href = `https://fonts.googleapis.com/css2?family=${name.replace(/\s+/g, '+')}:wght@100;200;300;400;500;600;700;800;900&display=swap`;
                document.head.appendChild(link);
            };

            const availableElements = [
                { type: 'heading', name: 'Heading', icon: 'fa fa-heading' },
                { type: 'text', name: 'Text Block', icon: 'fa fa-paragraph' },
                { type: 'image', name: 'Image', icon: 'fa fa-image' },
                { type: 'title', name: 'Title', icon: 'fa fa-heading' },
                { type: 'button', name: 'Button', icon: 'fa fa-rectangle-ad' },
                { type: 'video', name: 'Video', icon: 'fa fa-play-circle' },
                { type: 'spacer', name: 'Spacer', icon: 'fa fa-arrows-alt-v' },
            ];

            const filteredColumnLayouts = computed(() => {
                if (!searchColumnQuery.value) return columnLayouts;
                const query = searchColumnQuery.value.toLowerCase();
                return columnLayouts.filter(layout => layout.label.toLowerCase().includes(query) || layout.config.toLowerCase().includes(query));
            });

            const filteredNestedColumnLayouts = computed(() => {
                if (!searchElementQuery.value) return columnLayouts;
                const query = searchElementQuery.value.toLowerCase();
                return columnLayouts.filter(layout => layout.label.toLowerCase().includes(query) || layout.config.toLowerCase().includes(query));
            });

            const filteredAvailableElements = computed(() => {
                if (!searchElementQuery.value) return availableElements;
                const query = searchElementQuery.value.toLowerCase();
                return availableElements.filter(el =>
                    (el.name && el.name.toLowerCase().includes(query)) ||
                    el.type.toLowerCase().includes(query)
                );
            });

            // Dynamic Custom Elements Registration
            const customElements = @json($customElements ?? []);
            if (Object.keys(customElements).length > 0) {
                Object.values(customElements).forEach(el => {
                    availableElements.push(el);
                });
            }

            // Initialize layout
            onMounted(() => {
                const rawContent = @json($post->content);
                try {
                    if (rawContent) {
                        let parsed;
                        if (typeof rawContent === 'string') {
                            const trimmed = rawContent.trim();
                            if (trimmed.startsWith('[') || trimmed.startsWith('{')) {
                                try {
                                    parsed = JSON.parse(trimmed);
                                } catch (parseError) {
                                    console.error('Layout JSON parse error:', parseError);
                                    parsed = [];
                                }
                            } else {
                                // Content is a string but not JSON (likely raw HTML)
                                console.warn('Content is not JSON. It might be legacy HTML.');
                                parsed = [];
                            }
                        } else {
                            parsed = rawContent;
                        }

                        // Recursive migration for basis and visual gap (padding)
                        const migrateBasis = (columns) => {
                            if (!columns || !columns.length) return;
                            const total = columns.length;
                            columns.forEach(col => {
                                // Fix shrinking issue for old columns
                                if (!col.basis) col.basis = (100 / total) + '%';

                                // Fix missing visual gap issue for old columns
                                if (col.settings.paddingLeft === undefined) col.settings.paddingLeft = 10;
                                if (col.settings.paddingRight === undefined) col.settings.paddingRight = 10;

                                // Migrate missing new column settings for old columns
                                Object.entries(makeColumnSettings()).forEach(([k, v]) => {
                                    if (col.settings[k] === undefined) col.settings[k] = v;
                                });

                                if (col.elements) {
                                    col.elements.forEach(el => {
                                        if (el.type === 'row' && el.columns) migrateBasis(el.columns);
                                    });
                                }
                            });
                        };

                        if (Array.isArray(parsed)) {
                            parsed.forEach(row => {
                                if (!row.settings) row.settings = {};
                                if (!row.settings.visibility) row.settings.visibility = { mobile: true, tablet: true, desktop: true };
                                if (row.settings.contentWidth === undefined) row.settings.contentWidth = 'site';
                                if (row.settings.height === undefined) row.settings.height = 'auto';
                                if (row.settings.customHeight === undefined) row.settings.customHeight = '';
                                if (row.settings.alignItems === undefined) row.settings.alignItems = 'stretch';
                                if (row.settings.alignContent === undefined) row.settings.alignContent = 'flex-start';
                                if (row.settings.justifyContent === undefined) row.settings.justifyContent = 'flex-start';
                                if (row.settings.flexWrap === undefined) row.settings.flexWrap = 'wrap';
                                if (row.settings.columnGap === undefined) row.settings.columnGap = '';
                                if (row.settings.htmlTag === undefined) row.settings.htmlTag = 'div';
                                if (row.settings.status === undefined) row.settings.status = 'published';

                                if (row.columns) migrateBasis(row.columns);
                            });
                            layout.value = parsed;
                        } else {
                            layout.value = [];
                        }
                    }
                } catch (e) {
                    console.error('Failed to parse layout', e);
                    layout.value = [];
                }
                // Enable dirty tracking after initial load settles (setTimeout = macro-task, runs after Vue's watcher micro-tasks)
                setTimeout(() => { _trackLayoutDirty = true; }, 0);
            });

            watch(layout, () => {
                if (_trackLayoutDirty) isDirty.value = true;
            }, { deep: true });

            // Watch isPreview to directly control layout via DOM
            watch(isPreview, (val) => {
                const wrapper = document.getElementById('lazy-builder-app');
                const sidebar = document.querySelector('.builder-sidebar');
                if (val) {
                    // Preview ON: collapse sidebar column to 0
                    wrapper.style.gridTemplateColumns = '0px 1fr';
                    if (sidebar) {
                        sidebar.style.display = 'none';
                        sidebar.style.width = '0';
                        sidebar.style.overflow = 'hidden';
                    }
                } else {
                    // Preview OFF: restore sidebar column
                    wrapper.style.gridTemplateColumns = '';
                    if (sidebar) {
                        sidebar.style.display = '';
                        sidebar.style.width = '';
                        sidebar.style.overflow = '';
                    }
                }
            });


            const hexToRgba = (hex, opacity) => {
                if (!hex || hex === 'transparent') return 'transparent';
                if (hex.startsWith('rgba')) return hex;
                let r = 0, g = 0, b = 0;
                if (hex.length == 4) {
                    r = "0x" + hex[1] + hex[1];
                    g = "0x" + hex[2] + hex[2];
                    b = "0x" + hex[3] + hex[3];
                } else if (hex.length == 7) {
                    r = "0x" + hex[1] + hex[2];
                    g = "0x" + hex[3] + hex[4];
                    b = "0x" + hex[5] + hex[6];
                }
                return `rgba(${+r}, ${+g}, ${+b}, ${opacity})`;
            };

            const uid = () => Math.random().toString(36).substr(2, 9);

            const makeColumnSettings = (overrides = {}) => ({
                paddingTop: 10, paddingBottom: 10, paddingLeft: 10, paddingRight: 10,
                marginTop: 0, marginBottom: 0, marginLeft: 0, marginRight: 0,
                alignment: 'default', contentLayout: '', contentAlignH: 'flex-start', contentAlignV: 'flex-start',
                gapWidth: '', gapHeight: '', htmlTag: 'div', linkUrl: '', linkTarget: '_self',
                visibility: { mobile: true, tablet: true, desktop: true },
                cssClass: '', cssId: '', textColor: '', bgColor: 'transparent',
                bgColorOpacity: 1,
                bgType: 'color',
                hoverType: 'none',
                bgGradientStartColor: '', bgGradientEndColor: '',
                bgGradientStartOpacity: 1, bgGradientEndOpacity: 1,
                bgGradientStartPosition: 0, bgGradientEndPosition: 100,
                bgGradientType: 'linear', bgGradientAngle: 180,
                bgImage: '', bgImageSkipLazy: false, bgImagePosition: 'center center',
                bgImageRepeat: 'no-repeat', bgImageSize: 'auto',
                bgImageFading: false, bgImageParallax: 'none', bgImageBlendMode: 'normal',
                fontSize: '', fontWeight: '', lineHeight: '', letterSpacing: '', textAlign: '',
                borderSizeTop: '', borderSizeRight: '', borderSizeBottom: '', borderSizeLeft: '',
                borderColor: '#000000', borderRadiusTopLeft: '', borderRadiusTopRight: '',
                borderRadiusBottomRight: '', borderRadiusBottomLeft: '',
                boxShadow: false, boxShadowPositionVertical: 0, boxShadowPositionHorizontal: 0,
                boxShadowBlurRadius: 0, boxShadowSpreadRadius: 0, boxShadowColor: '#000000', boxShadowStyle: 'outer',
                ...overrides
            });

            const columnModalType = ref('new'); // 'new' or 'edit'

            const clearEditingContext = () => {
                editingContext.value = { type: null, ci: null, coli: null, eli: null, ncoli: null, neli: null, tab: 'content' };
                editingCi.value = null;
                activeColi.value = null;
                activeColCi.value = null;
                activeTab.value = 'navigator';
            };

            const setEditingContext = (type, ci = null, coli = null, eli = null, ncoli = null, neli = null) => {
                editingContext.value = {
                    type, ci, coli, eli, ncoli, neli,
                    tab: editingContext.value.tab || 'content'
                };
                activeTab.value = 'settings';
                
                // Reset highlights
                editingCi.value = null;
                activeColi.value = null;
                activeColCi.value = null;

                // For canvas highlighting
                if (type === 'container') {
                    editingCi.value = ci;
                } else if (type === 'column') {
                    activeColi.value = coli;
                    activeColCi.value = ci;
                } else if (type === 'nested-column') {
                    activeColi.value = ncoli;
                    activeColCi.value = eli;
                }
            };

            // Reset tabs to general only when switching to a different entity
            watch(editingContext, (newCtx, oldCtx) => {
                const sameEntity = newCtx.type === oldCtx?.type && newCtx.ci === oldCtx?.ci && newCtx.coli === oldCtx?.coli && newCtx.ncoli === oldCtx?.ncoli;
                if (!sameEntity) {
                    if (newCtx.type === 'container') activePanelTab.value = 'general';
                    if (newCtx.type === 'column' || newCtx.type === 'nested-column') activeColPanelTab.value = 'general';
                }
            }, { deep: true });

            const selectLayout = (layoutData) => {
                const columns = layoutData.config.split('-').map(part => {
                    const [num, den] = part.split('/');
                    return {
                        id: uid(),
                        basis: ((num / den) * 100).toFixed(2) + '%',
                        settings: makeColumnSettings(),
                        elements: []
                    };
                });

                if (columnModalType.value === 'edit') {
                    // Append mode
                    const ci = columnModalTarget.value;
                    const container = layout.value[ci];

                    // Add the new columns with their correctly calculated basis
                    container.columns.push(...columns);
                    activeCi.value = ci;
                } else {

                    // Add new container
                    const newContainer = {
                        id: uid(),
                        settings: {
                            marginTop: '', marginBottom: '',
                            paddingTop: 0, paddingBottom: 0,
                            paddingLeft: 0, paddingRight: 0,
                            bgColor: 'transparent', bgColorOpacity: 1, bgType: 'color',
                            bgGradientStartColor: '', bgGradientEndColor: '',
                            bgGradientStartPosition: 0, bgGradientEndPosition: 100,
                            bgGradientType: 'linear', bgGradientAngle: 180,
                            bgImage: '', bgImageSkipLazy: false, bgImagePosition: 'center center',
                            bgImageRepeat: 'no-repeat', bgImageSize: 'auto', bgImageFading: false,
                            bgImageParallax: 'none', bgImageBlendMode: 'normal',
                            contentWidth: 'site', height: 'auto', customHeight: '',
                            alignItems: 'stretch', alignContent: '', justifyContent: 'flex-start',
                            flexWrap: 'wrap', columnGap: '', htmlTag: 'div',
                            menuAnchor: '', visibility: { mobile: true, tablet: true, desktop: true },
                            status: 'published', cssClass: '',
                            linkColor: '',
                            borderSizeTop: '', borderSizeRight: '', borderSizeBottom: '', borderSizeLeft: '', borderColor: '#000000',
                            borderRadiusTopLeft: '', borderRadiusTopRight: '', borderRadiusBottomRight: '', borderRadiusBottomLeft: '',
                            boxShadow: false, boxShadowPositionVertical: 0, boxShadowPositionHorizontal: 0,
                            boxShadowBlurRadius: 0, boxShadowSpreadRadius: 0, boxShadowColor: '#000000', boxShadowStyle: 'outer',
                            zIndex: '', overflow: 'default'
                        },
                        columns: columns
                    };

                    if (columnModalTarget.value !== null) {
                        layout.value.splice(columnModalTarget.value, 0, newContainer);
                        activeCi.value = columnModalTarget.value;
                    } else {
                        layout.value.push(newContainer);
                        activeCi.value = layout.value.length - 1;
                    }
                }

                showColumnModal.value = false;
            };

            const addContainer = (index = null) => {
                openColumnModal(index);
            };

            const addColumn = (ci) => {
                layout.value[ci].columns.push({ id: uid(), basis: '100%', settings: makeColumnSettings(), elements: [] });
            };

            const addNestedColumn = (ci, coli, eli) => {
                layout.value[ci].columns[coli].elements[eli].columns.push({ id: uid(), basis: '100%', settings: makeColumnSettings(), elements: [] });
            };

            const shouldShowGuide = (type, ci, coli = null, eli = null, ncoli = null) => {
                if (isPreview.value) return false;
                
                // If dragging, only show what's being dragged
                if (isDragging.value) {
                    if (type === 'container' && dragCi.value === ci && !isColumnDrag.value) return true;
                    if (type === 'column' && dragCi.value === ci && dragColi.value === coli && dragEli.value === null) return true;
                    if (type === 'nested-column' && dragCi.value === ci && dragColi.value === coli && dragEli.value === eli && dragNcoli.value === ncoli) return true;
                    return false;
                }

                // If something is in edit mode (sidebar open), strictly show ONLY that type
                if (editingContext.value.type) {
                    if (type === 'container') return editingContext.value.type === 'container' && editingContext.value.ci === ci;
                    if (type === 'column') return editingContext.value.type === 'column' && editingContext.value.ci === ci && editingContext.value.coli === coli;
                    if (type === 'nested-column') return editingContext.value.type === 'nested-column' && editingContext.value.ci === ci && editingContext.value.coli === coli && editingContext.value.eli === eli && editingContext.value.ncoli === ncoli;
                    return false;
                }

                // Default: Show on hover (handled by CSS classes if this returns true)
                return true;
            };
            const cloneObject = (obj) => {
                if (!obj) return null;
                const clone = JSON.parse(JSON.stringify(obj));
                const resetIds = (item) => {
                    if (!item) return;
                    item.id = uid();
                    if (item.columns && Array.isArray(item.columns)) item.columns.forEach(col => resetIds(col));
                    if (item.elements && Array.isArray(item.elements)) item.elements.forEach(el => resetIds(el));
                };
                resetIds(clone);
                return clone;
            };


            const duplicateContainer = (ci) => {
                layout.value.splice(ci + 1, 0, cloneObject(layout.value[ci]));
            };

            const duplicateColumn = (ci, coli) => {
                layout.value[ci].columns.splice(coli + 1, 0, cloneObject(layout.value[ci].columns[coli]));
            };

            const duplicateNestedColumn = (ci, coli, eli, ncoli) => {
                const row = layout.value[ci]?.columns[coli]?.elements[eli];
                if (!row || !row.columns) return;
                row.columns.splice(ncoli + 1, 0, cloneObject(row.columns[ncoli]));
            };

            const duplicateNestedRow = (ci, coli, eli) => {
                const column = layout.value[ci]?.columns[coli];
                if (!column || !column.elements) return;
                column.elements.splice(eli + 1, 0, cloneObject(column.elements[eli]));
            };

            const duplicateElement = (ci, coli, eli) => {
                const elements = layout.value[ci]?.columns[coli]?.elements;
                if (!elements) return;
                elements.splice(eli + 1, 0, cloneObject(elements[eli]));
            };

            const duplicateNestedElement = (ci, coli, eli, ncoli, neli) => {
                const elements = layout.value[ci]?.columns[coli]?.elements[eli]?.columns[ncoli]?.elements;
                if (!elements) return;
                elements.splice(neli + 1, 0, cloneObject(elements[neli]));
            };

            // Full HTML5 Drag and Drop Logic for Reordering
            const dragSource = ref(null);
            const dragTarget = ref(null); // Used for visual highlighting
            const dragPosition = ref('top'); // top, bottom, left, right

            const onDragStart = (e, type, ci, coli = null, eli = null, ncoli = null, neli = null) => {
                dragSource.value = { type, ci, coli, eli, ncoli, neli };
                if (e.dataTransfer) {
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/plain', type);
                    // Add slight opacity to the dragged element
                    setTimeout(() => {
                        const target = e.target.closest('.group\\/cont, .group\\/col, .group\\/ncol, .group\\/nrow, .group\\/el');
                        if (target) target.style.opacity = '0.4';
                    }, 0);
                }
            };

            const onDragEnd = (e) => {
                const dragged = e.target.closest('.group\\/cont, .group\\/col, .group\\/ncol, .group\\/nrow, .group\\/el');
                if (dragged) dragged.style.opacity = '';
                dragSource.value = null;
                dragTarget.value = null;
            };

            const onDragOver = (e, type, ci, coli = null, eli = null, ncoli = null, neli = null) => {
                if (!dragSource.value) return;
                const src = dragSource.value;

                // Rule 1: Containers only over Containers
                if (src.type === 'container' && type !== 'container') return;
                // Rule 2: Columns can drag over Columns OR Container-inner (to append)
                if (src.type === 'column' && type !== 'column' && type !== 'container') return;
                // Rule 3: Elements can drag over Elements OR Column-inner OR Nested-Column-inner
                if (src.type === 'element' && !['element', 'column', 'nested-column'].includes(type)) return;

                // Restriction: Top-level elements cannot be dropped into nested columns
                if (src.type === 'element' && src.ncoli === null && (type === 'nested-column' || ncoli !== null)) {
                    dragTarget.value = null;
                    return; // Do not preventDefault or stopPropagation, let it bubble to the parent row container
                }

                e.preventDefault();
                e.stopPropagation();

                // Rule 4: Nested Columns (rows) drag over Nested Columns OR Column-inner
                if (src.type === 'nested-column' && !['nested-column', 'column'].includes(type)) return;

                const rect = e.currentTarget.getBoundingClientRect();
                let pos = 'top';
                
                // Axis detection based on SOURCE type (columns are horizontal, others vertical)
                if (src.type === 'column' || src.type === 'nested-column') {
                    pos = e.clientX > rect.left + rect.width / 2 ? 'right' : 'left';
                } else {
                    pos = e.clientY > rect.top + rect.height / 2 ? 'bottom' : 'top';
                }

                dragPosition.value = pos;
                const targetId = `${type}-${ci}-${coli}-${eli}-${ncoli}-${neli}`;
                if (dragTarget.value !== targetId) {
                    dragTarget.value = targetId;
                }
            };

            const moveItem = (srcArr, targetArr, srcIdx, targetIdx, position) => {
                if (!srcArr || !targetArr) return;
                
                // If same list and same index, do nothing
                if (srcArr === targetArr && srcIdx === targetIdx) return;

                const item = srcArr.splice(srcIdx, 1)[0];
                if (!item) return;

                let finalIdx = targetIdx;
                
                // If moving within the same array and source was before target,
                // the target index has shifted back by 1.
                if (srcArr === targetArr && srcIdx < targetIdx) {
                    finalIdx -= 1;
                }

                if (position === 'bottom' || position === 'right') finalIdx += 1;
                
                // Ensure bounds
                if (finalIdx < 0) finalIdx = 0;
                if (!targetArr) return; // Safety check
                if (finalIdx > targetArr.length) finalIdx = targetArr.length;

                targetArr.splice(finalIdx, 0, item);
            };

            const getListAndIndex = (ci, coli, eli, ncoli, neli) => {
                if (neli !== null) return { list: layout.value[ci].columns[coli].elements[eli].columns[ncoli].elements, index: neli };
                if (ncoli !== null) return { list: layout.value[ci].columns[coli].elements[eli].columns, index: ncoli };
                if (eli !== null) return { list: layout.value[ci].columns[coli].elements, index: eli };
                if (coli !== null) return { list: layout.value[ci].columns, index: coli };
                return { list: layout.value, index: ci };
            };

            const onDrop = (e, type, ci, coli = null, eli = null, ncoli = null, neli = null) => {
                e.preventDefault();
                e.stopPropagation();

                if (!dragSource.value) return;
                const src = dragSource.value;
                const pos = dragPosition.value;
                dragTarget.value = null;

                // 1. Get Source List and Index
                const srcData = getListAndIndex(src.ci, src.coli, src.eli, src.ncoli, src.neli);
                
                // 2. Determine Target List and Index
                let targetList;
                let targetIdx;

                if (src.type === 'container') {
                    targetList = layout.value;
                    targetIdx = ci;
                } 
                else if (src.type === 'column') {
                    // Columns can move between containers
                    targetList = layout.value[ci].columns;
                    targetIdx = (coli !== null) ? coli : targetList.length;
                }
                else if (src.type === 'nested-column') {
                    // Nested columns must go into a 'row' element
                    if (ci !== null && coli !== null && eli !== null) {
                        const targetEl = layout.value[ci].columns[coli].elements[eli];
                        if (targetEl && targetEl.type === 'row') {
                            targetList = targetEl.columns;
                            targetIdx = (ncoli !== null) ? ncoli : targetList.length;
                        }
                    }
                }
                else if (src.type === 'element') {
                    if (ncoli !== null) {
                        // Restriction: Top-level elements cannot be dropped into nested columns
                        if (src.ncoli === null) return;

                        // Target is a nested column
                        const targetEl = layout.value[ci].columns[coli].elements[eli];
                        if (targetEl && targetEl.type === 'row' && targetEl.columns[ncoli]) {
                            targetList = targetEl.columns[ncoli].elements;
                            targetIdx = (neli !== null) ? neli : targetList.length;
                        }
                    } else if (coli !== null) {
                        // Target is a main column
                        targetList = layout.value[ci].columns[coli].elements;
                        targetIdx = (eli !== null) ? eli : targetList.length;
                    }
                }

                if (!targetList) return;

                // Move the item
                moveItem(srcData.list, targetList, srcData.index, targetIdx, pos);
            };

            // Existing visual resizing handles drag logic...
            const dragColi = ref(null);
            const dragEli = ref(null);
            const dragNcoli = ref(null);
            const isColumnDrag = ref(false);
            const isNestedDrag = ref(false);
            const dragRefWidth = ref(300);

            const startDrag = (e, type, ci, coli = null, eli = null, ncoli = null) => {
                isDragging.value = true;
                dragType.value = type;
                dragCi.value = ci;
                dragColi.value = coli;
                dragEli.value = eli;
                dragNcoli.value = ncoli;

                isColumnDrag.value = coli !== null && eli === null;
                isNestedDrag.value = ncoli !== null;

                startY.value = e.clientY;
                startX.value = e.clientX;

                let target;
                if (isNestedDrag.value) {
                    target = layout.value[ci].columns[coli].elements[eli].columns[ncoli];
                } else if (isColumnDrag.value) {
                    target = layout.value[ci].columns[coli];
                } else {
                    target = layout.value[ci];
                }

                if (type === 'columnSpacingLeft' || type === 'columnSpacingRight') {
                    const colEl = e.target.closest('.column-outer');
                    const containerEl = colEl ? colEl.parentElement : null;
                    dragRefWidth.value = containerEl ? containerEl.clientWidth : (colEl ? colEl.clientWidth : 300);
                    if (target.settings[type] === undefined || target.settings[type] === null || target.settings[type] === '') {
                        const rawGap = layout.value[ci]?.settings?.columnGap;
                        target.settings[type] = (rawGap !== undefined && rawGap !== '' && rawGap !== null) ? Number(rawGap) : 3;
                    }
                } else if (type === 'paddingLeft' || type === 'paddingRight') {
                    const colEl = e.target.closest('.column-outer');
                    const rowEl = colEl ? null : e.target.closest('.container-row');
                    dragRefWidth.value = colEl ? colEl.clientWidth : (rowEl ? rowEl.clientWidth : 300);
                    if (!target.settings[type]) target.settings[type] = 0;
                } else {
                    if (!target.settings[type]) target.settings[type] = 0;
                }

                startVal.value = target.settings[type] || 0;

                // Open settings panel and switch to design tab for the dragged entity
                if (isNestedDrag.value) {
                    setEditingContext('nested-column', ci, coli, eli, ncoli);
                    activeColPanelTab.value = 'design';
                } else if (isColumnDrag.value) {
                    setEditingContext('column', ci, coli);
                    activeColPanelTab.value = 'design';
                } else {
                    setEditingContext('container', ci);
                    activePanelTab.value = 'design';
                }

                window.addEventListener('mousemove', handleDrag);
                window.addEventListener('mouseup', stopDrag);

                if (type.toLowerCase().includes('left') || type.toLowerCase().includes('right')) {
                    document.body.style.cursor = 'ew-resize';
                } else {
                    document.body.style.cursor = 'ns-resize';
                }

                document.body.classList.add('select-none');
            };

            const handleDrag = (e) => {
                if (!isDragging.value) return;
                const diffY = e.clientY - startY.value;
                const diffX = e.clientX - startX.value;
                let newVal = 0;

                // Column spacing: drag in % relative to column width
                if (dragType.value === 'columnSpacingLeft' || dragType.value === 'columnSpacingRight') {
                    const pctDiff = (diffX / (dragRefWidth.value || 300)) * 100;
                    newVal = dragType.value === 'columnSpacingLeft'
                        ? startVal.value + pctDiff
                        : startVal.value - pctDiff;
                // User logic: Dragging DOWN increases padding for both TOP and BOTTOM handles.
                } else if (dragType.value.toLowerCase().includes('top') || dragType.value.toLowerCase().includes('bottom')) {
                    newVal = startVal.value + diffY;
                } else {
                    newVal = startVal.value + diffX;
                }

                // Invert right-side handles: paddingRight and marginRight both invert
                // marginRight: drag LEFT = increases (right border moves left, left border stays)
                if (dragType.value === 'paddingRight' || dragType.value === 'marginRight') {
                    newVal = startVal.value - diffX;
                }

                let target;
                if (isNestedDrag.value) {
                    target = layout.value[dragCi.value].columns[dragColi.value].elements[dragEli.value].columns[dragNcoli.value];
                } else if (isColumnDrag.value) {
                    target = layout.value[dragCi.value].columns[dragColi.value];
                } else {
                    target = layout.value[dragCi.value];
                }

                if (dragType.value === 'columnSpacingLeft' || dragType.value === 'columnSpacingRight') {
                    const colBasis = parseFloat(target.basis) || 100;
                    const otherKey = dragType.value === 'columnSpacingLeft' ? 'columnSpacingRight' : 'columnSpacingLeft';
                    const otherSpacing = Number(target.settings[otherKey] || 0);
                    target.settings[dragType.value] = Math.max(0, Math.min(newVal, colBasis - otherSpacing));
                } else if (dragType.value === 'paddingLeft' || dragType.value === 'paddingRight') {
                    const otherKey = dragType.value === 'paddingLeft' ? 'paddingRight' : 'paddingLeft';
                    const otherPadding = Number(target.settings[otherKey] || 0);
                    target.settings[dragType.value] = Math.max(0, Math.min(newVal, dragRefWidth.value - otherPadding));
                } else {
                    target.settings[dragType.value] = Math.max(0, newVal);
                }
            };

            const stopDrag = () => {
                isDragging.value = false;
                dragType.value = null;
                dragCi.value = null;
                dragEli.value = null;
                dragNcoli.value = null;
                window.removeEventListener('mousemove', handleDrag);
                window.removeEventListener('mouseup', stopDrag);
                document.body.style.cursor = '';
                document.body.classList.remove('select-none');
            };

            const openMediaModal = (settingKey) => {
                const ctx = editingContext.value;
                let targetSettings = null;

                if (ctx.type === 'container') {
                    if (layout.value[ctx.ci]) targetSettings = layout.value[ctx.ci].settings;
                } else if (ctx.type === 'column' || ctx.type === 'nested-column') {
                    const col = editingColumn.value;
                    if (col) targetSettings = col.settings;
                } else if (ctx.type === 'element' || ctx.type === 'nested-element') {
                    const el = editingElement.value;
                    if (el) targetSettings = el.settings;
                }

                if (!targetSettings) return;

                if (window.openMediaModal) {
                    window.openMediaModal((selectedMedia) => {
                        const url = '/storage/' + selectedMedia.path;
                        targetSettings[settingKey] = url;
                    });
                } else {
                    const currentVal = targetSettings[settingKey] || '';
                    const url = prompt("Enter image URL:", currentVal);
                    if (url !== null) {
                        targetSettings[settingKey] = url;
                    }
                }
            };

            const openColorPicker = (event, obj, colorKey, opacityKey = null) => {
                const target = event.currentTarget;
                const pickr = Pickr.create({
                    el: target,
                    theme: 'classic',
                    default: obj[colorKey] || '#ffffff',
                    defaultRepresentation: 'HEXA',
                    components: {
                        preview: true,
                        opacity: !!opacityKey,
                        hue: true,
                        interaction: {
                            hex: true,
                            rgba: false,
                            input: true,
                            clear: true,
                            save: true
                        }
                    },
                    swatches: [
                        '#000000', '#ffffff', '#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5',
                        '#2196f3', '#03a6f4', '#00bcd4', '#009688', '#4caf50', '#8bc34a', '#cddc39'
                    ]
                });

                pickr.on('save', (color, instance) => {
                    const hexa = color.toHEXA().toString();
                    const rgba = color.toRGBA();
                    
                    obj[colorKey] = '#' + color.toHEXA()[0] + color.toHEXA()[1] + color.toHEXA()[2];
                    if (opacityKey) {
                        obj[opacityKey] = parseFloat((rgba[3]).toFixed(2));
                    }
                    
                    instance.hide();
                    instance.destroyAndRemoveEl();
                }).on('cancel', instance => {
                    instance.hide();
                    instance.destroyAndRemoveEl();
                }).on('change', (color, source, instance) => {
                     // Live update if you want
                     if (source === 'input') return;
                     const rgba = color.toRGBA();
                     obj[colorKey] = '#' + color.toHEXA()[0] + color.toHEXA()[1] + color.toHEXA()[2];
                     if (opacityKey) {
                         obj[opacityKey] = parseFloat((rgba[3]).toFixed(2));
                     }
                });

                pickr.show();
            };

            const saveLayout = async () => {
                isSaving.value = true;
                try {
                    const response = await fetch("{{ route('admin.lazy-builder.save', $post->id) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ layout: layout.value })
                    });
                    const data = await response.json();
                    if (data.success) {
                        showToast('Layout saved successfully!', 'success');
                        isDirty.value = false;
                    } else {
                        showToast('Failed to save layout!', 'error');
                    }
                } catch (e) {
                    console.error('Save failed', e);
                    showToast('Save failed! Please check console.', 'error');
                } finally {
                    isSaving.value = false;
                }
            };

            const editingColumn = computed(() => {
                const ctx = editingContext.value;
                if (ctx.ci === null || ctx.ci === undefined || !layout.value[ctx.ci]) return null;
                const container = layout.value[ctx.ci];

                if (ctx.type === 'column') {
                    if (ctx.coli === null || ctx.coli === undefined) return null;
                    return container.columns[ctx.coli] || null;
                } else if (ctx.type === 'nested-column') {
                    if (ctx.coli === null || ctx.coli === undefined || ctx.eli === null || ctx.eli === undefined || ctx.ncoli === null || ctx.ncoli === undefined) return null;
                    const row = container.columns[ctx.coli].elements[ctx.eli];
                    return (row && row.columns) ? row.columns[ctx.ncoli] : null;
                }
                return null;
            });

            const editingElement = computed(() => {
                const ctx = editingContext.value;
                if (ctx.ci === null || ctx.coli === null || ctx.eli === null) return null;
                const container = layout.value[ctx.ci];
                if (!container) return null;
                const column = container.columns[ctx.coli];
                if (!column) return null;
                const el = column.elements[ctx.eli];
                if (!el) return null;

                if (ctx.ncoli !== null && ctx.neli !== null) {
                    const ncol = el.columns ? el.columns[ctx.ncoli] : null;
                    return ncol ? ncol.elements[ctx.neli] : null;
                }
                return el;
            });

            // Dynamic font variants for the selected title element's font
            const titleFontVariants = computed(() => {
                const rawFamily = editingElement.value?.settings?.fontFamily || '';
                if (!rawFamily || rawFamily === 'inherit') {
                    return ['100','200','300','400','500','600','700','800','900'];
                }
                const match = rawFamily.match(/['"]?([^'",$]+)/);
                const familyName = match ? match[1].trim() : rawFamily.trim();
                const found = BUILDER_FONTS.find(f => f.family === familyName);
                return found ? found.variants : ['100','200','300','400','500','600','700','800','900'];
            });

            // Auto-load Google Font when fontFamily setting changes
            watch(() => editingElement.value?.settings?.fontFamily, (newFamily) => {
                loadBuilderFont(newFamily);
            });

            // Dynamic Styles
            const canvasStyle = computed(() => {
                if (device.value === 'mobile') return { width: '375px' };
                if (device.value === 'tablet') return { width: '768px' };
                return { width: '100%' };
            });

            const formatBasisToFraction = (basis) => {
                if (!basis || basis === 'auto') return 'Auto';
                if (basis === '100%') return '1/1';
                if (basis === '50%') return '1/2';
                if (basis === '33.33%') return '1/3';
                if (basis === '66.66%') return '2/3';
                if (basis === '25%') return '1/4';
                if (basis === '75%') return '3/4';
                if (basis === '20%') return '1/5';
                if (basis === '40%') return '2/5';
                if (basis === '60%') return '3/5';
                if (basis === '80%') return '4/5';
                if (basis === '16.66%') return '1/6';
                if (basis === '83.33%') return '5/6';
                return basis;
            };

            const updateBasis = (val) => {
                if (editingColumn.value) {
                    editingColumn.value.basis = val;
                }
            };

            const getUnitVal = (val, unit = 'px') => {
                if (val !== undefined && val !== null && val !== '') {
                    return String(val) + unit;
                }
                return undefined;
            };

            const containerStyle = (container, ci) => {
                const s = container.settings;
                let mTop = Number(s.marginTop) || 0;
                if (ci === 0) mTop += 60;

                let boxShadowStr = 'none';
                if (s.boxShadow) {
                    const inset = s.boxShadowStyle === 'inner' ? 'inset ' : '';
                    boxShadowStr = `${inset}${s.boxShadowPositionHorizontal || 0}px ${s.boxShadowPositionVertical || 0}px ${s.boxShadowBlurRadius || 0}px ${s.boxShadowSpreadRadius || 0}px ${s.boxShadowColor || '#000000'}`;
                }

                let bgStyle = hexToRgba(s.bgColor, s.bgColorOpacity !== undefined ? s.bgColorOpacity : 1);
                let bgImages = [];

                if (s.bgGradientStartColor && s.bgGradientEndColor) {
                    const start = hexToRgba(s.bgGradientStartColor, s.bgGradientStartOpacity !== undefined ? s.bgGradientStartOpacity : 1);
                    const end = hexToRgba(s.bgGradientEndColor, s.bgGradientEndOpacity !== undefined ? s.bgGradientEndOpacity : 1);

                    if (s.bgGradientType === 'radial') {
                        bgImages.push(`radial-gradient(circle at center, ${start} ${s.bgGradientStartPosition || 0}%, ${end} ${s.bgGradientEndPosition || 100}%)`);
                    } else {
                        bgImages.push(`linear-gradient(${s.bgGradientAngle || 180}deg, ${start} ${s.bgGradientStartPosition || 0}%, ${end} ${s.bgGradientEndPosition || 100}%)`);
                    }
                }

                if (s.bgImage) {
                    bgImages.push(`url('${s.bgImage}')`);
                }

                let bgImageStr = bgImages.length > 0 ? bgImages.join(', ') : 'none';

                return {
                    paddingTop: getUnitVal(s.paddingTop, s.paddingTopUnit) || '0px',
                    paddingBottom: getUnitVal(s.paddingBottom, s.paddingBottomUnit) || '0px',
                    paddingLeft: getUnitVal(s.paddingLeft, s.paddingLeftUnit) || '0px',
                    paddingRight: getUnitVal(s.paddingRight, s.paddingRightUnit) || '0px',
                    marginTop: (ci === 0 && mTop !== 0 && !s.marginTopUnit) ? mTop + 'px' : getUnitVal(mTop, s.marginTopUnit) || '0px',
                    marginBottom: getUnitVal(s.marginBottom, s.marginBottomUnit) || '0px',
                    borderTopWidth: (s.borderSizeTop || 0) + 'px',
                    borderRightWidth: (s.borderSizeRight || 0) + 'px',
                    borderBottomWidth: (s.borderSizeBottom || 0) + 'px',
                    borderLeftWidth: (s.borderSizeLeft || 0) + 'px',
                    borderStyle: 'solid',
                    borderColor: s.borderColor || '#000000',
                    borderTopLeftRadius: getUnitVal(s.borderRadiusTopLeft, s.borderRadiusTopLeftUnit) || '0px',
                    borderTopRightRadius: getUnitVal(s.borderRadiusTopRight, s.borderRadiusTopRightUnit) || '0px',
                    borderBottomRightRadius: getUnitVal(s.borderRadiusBottomRight, s.borderRadiusBottomRightUnit) || '0px',
                    borderBottomLeftRadius: getUnitVal(s.borderRadiusBottomLeft, s.borderRadiusBottomLeftUnit) || '0px',
                    zIndex: s.zIndex || 'auto',
                    overflow: s.overflow && s.overflow !== 'default' ? s.overflow : 'visible',
                    backgroundColor: bgStyle,
                    backgroundImage: bgImageStr ? bgImageStr : 'none',
                    backgroundPosition: s.bgImage ? (s.bgImagePosition || 'center center') : undefined,
                    backgroundRepeat: s.bgImage ? (s.bgImageRepeat || 'no-repeat') : undefined,
                    backgroundSize: s.bgImage ? (s.bgImageSize || 'auto') : undefined,
                    backgroundAttachment: s.bgImage && s.bgImageParallax === 'fixed' ? 'fixed' : undefined,
                    backgroundBlendMode: s.bgImage && s.bgImageBlendMode !== 'normal' ? s.bgImageBlendMode : undefined,
                    minHeight: s.height === 'full' ? '100vh' : (s.height === 'custom' ? (s.customHeight || 'auto') : (s.minHeight || ((container.columns || []).some(col => (col.elements || []).length > 0) ? '0px' : '100px'))),
                    height: 'auto',
                    display: 'flex',
                    flexDirection: 'column'
                };
            };

            const containerInnerStyle = (container) => {
                const s = container.settings;
                const isSpaceDistribution = ['space-between', 'space-around', 'space-evenly'].includes(s.justifyContent);
                const alignItems = s.alignItems || 'stretch';
                const hasContent = (container.columns || []).some(col => (col.elements || []).length > 0);
                const innerMinHeight = s.minHeight ? s.minHeight : (hasContent ? '0px' : '100px');
                // Use height:100% so flex-grow fills the outer container for custom/full height
                const innerHeight = (s.height === 'custom' || s.height === 'full') ? '100%' : 'auto';
                
                // Align-content logic:
                // - Auto height: flex-start
                // - Fixed height: Default to 'stretch' so rows fill the container,
                //   allowing internal align-items (Top/Center/Bottom/Stretch) to work.
                let alignContent = 'stretch';
                if (container.type === 'row') {
                    alignContent = s.rowAlignContent || 'flex-start';
                } else if (!s.height || s.height === 'auto') {
                    alignContent = 'flex-start';
                } else {
                    alignContent = s.rowAlignContent || 'stretch';
                }

                return {
                    display: 'flex',
                    ...(container.type !== 'row' ? { maxWidth: s.contentWidth === '100%' ? '100%' : siteWidth.value } : {}),
                    width: '100%',
                    flexGrow: 1,
                    flexShrink: 0,
                    overflow: s.overflow && s.overflow !== 'default' ? s.overflow : undefined,
                    minHeight: innerHeight === '100%' ? '100%' : innerMinHeight,
                    maxHeight: s.maxHeight || undefined,
                    height: 'auto',
                    flexDirection: 'row',
                    flexWrap: !s.flexWrap || s.flexWrap === 'default' ? 'wrap' : s.flexWrap,
                    alignItems: alignItems,
                    alignContent: alignContent,
                    justifyContent: s.justifyContent || 'flex-start',
                    // columnGap removed in favor of percentage-based column padding
                };
            };

            const columnOuterStyle = (container, column, count) => {
                const s = column.settings;
                const containerAlign = container?.settings?.alignItems || 'stretch';
                const basis = column.basis || (100 / count) + '%';
                
                let flexBasis;
                const gap = parseInt(container.settings.columnGap || '20px');
                if (basis === 'auto') {
                    flexBasis = 'auto';
                } else {
                    flexBasis = basis;
                }

                const pTop = Number(s.paddingTop) || 0;
                const pBottom = Number(s.paddingBottom) || 0;
                
                const elements = column.elements || [];
                const isEmpty = elements.length === 0;
                const colAlign = (s.alignment && s.alignment !== 'default') ? s.alignment : containerAlign;
                const isStretch = colAlign === 'stretch';
                const containerHeight = container?.settings?.height || 'auto';

                const hasDefinedHeight = containerHeight === 'full' || containerHeight === 'custom';
                
                const rawGap = container.settings.columnGap;
                const globalGap = (rawGap !== undefined && rawGap !== '' && rawGap !== null) ? rawGap : 3;

                const pLeft = (s.columnSpacingLeft !== undefined && s.columnSpacingLeft !== '' && s.columnSpacingLeft !== null) 
                              ? s.columnSpacingLeft + '%' 
                              : globalGap + '%';
                
                const pRight = (s.columnSpacingRight !== undefined && s.columnSpacingRight !== '' && s.columnSpacingRight !== null) 
                               ? s.columnSpacingRight + '%' 
                               : globalGap + '%';

                const style = {
                    flexBasis: flexBasis,
                    maxWidth: flexBasis === 'auto' ? 'none' : flexBasis,
                    flexGrow: s.flexGrow !== undefined && s.flexGrow !== '' ? s.flexGrow : (isStretch ? 1 : 0),
                    flexShrink: s.flexShrink !== undefined && s.flexShrink !== '' ? s.flexShrink : 0,
                    maxHeight: getUnitVal(s.maxHeight, s.maxHeightUnit) || undefined,
                    paddingLeft: pLeft,
                    paddingRight: pRight,
                    marginTop: getUnitVal(s.marginTop, s.marginTopUnit),
                    marginBottom: getUnitVal(s.marginBottom, s.marginBottomUnit),
                    zIndex: s.zIndex || 'auto',
                    display: 'flex',
                    flexDirection: 'column',
                    alignItems: 'stretch',
                    alignSelf: isStretch ? 'stretch' : colAlign
                };
                
                // Final refined height/align logic:
                // Final refined height/align logic:
                if (isStretch) {
                    style.flexGrow = 1;
                    style.alignSelf = 'stretch';
                    style.minHeight = isEmpty ? '100px' : 'auto';
                    style.height = 'auto';
                } else {
                    style.height = 'auto';
                    style.flexGrow = 0;
                    style.alignSelf = colAlign;
                    style.minHeight = getUnitVal(s.minHeight, s.minHeightUnit) || (isEmpty ? '100px' : 'auto');
                }

                // Visibility
                if (s.visibility) {
                    if (s.visibility.desktop === false) style.opacity = isPreview.value ? 0 : 0.5;
                    if (s.visibility.tablet === false) style.opacity = isPreview.value ? 0 : 0.5;
                    if (s.visibility.mobile === false) style.opacity = isPreview.value ? 0 : 0.5;
                    // In real frontend, we'd use classes like 'hidden md:block'. 
                    // In builder, we'll just dim them if they are hidden on the current "hypothetical" device, 
                    // or just pass the settings for the frontend to handle.
                }

                return style;
            };

            const columnInnerStyle = (column, container) => {
                const s = column.settings;
                const containerAlign = container?.settings?.alignItems || 'stretch';
                const pTop = Number(s.paddingTop) || 0;
                const pBottom = Number(s.paddingBottom) || 0;
                
                let shadowStr = 'none';
                if (s.boxShadow) {
                    const x = s.boxShadowPositionHorizontal || 0;
                    const y = s.boxShadowPositionVertical || 0;
                    const b = s.boxShadowBlurRadius || 10;
                    const sp = s.boxShadowSpreadRadius || 0;
                    const c = s.boxShadowColor || 'rgba(0,0,0,0.1)';
                    const inst = s.boxShadowStyle === 'inner' ? 'inset ' : '';
                    shadowStr = `${inst}${x}px ${y}px ${b}px ${sp}px ${c}`;
                }

                const elements = column.elements || [];
                const isEmpty = elements.length === 0;
                const colAlign = (s.alignment && s.alignment !== 'default') ? s.alignment : containerAlign;
                const isStretch = colAlign === 'stretch';
                const containerHeight = container?.settings?.height || 'auto';
                const hasDefinedHeight = containerHeight === 'full' || containerHeight === 'custom';

                const style = {
                    backgroundColor: s.bgColor || 'transparent',
                    color: s.textColor || 'inherit',
                    paddingTop: getUnitVal(s.paddingTop, s.paddingTopUnit),
                    paddingLeft: getUnitVal(s.paddingLeft, s.paddingLeftUnit),
                    paddingRight: getUnitVal(s.paddingRight, s.paddingRightUnit),
                    marginLeft: getUnitVal(s.marginLeft, s.marginLeftUnit),
                    marginRight: getUnitVal(s.marginRight, s.marginRightUnit),
                    minHeight: isEmpty ? `calc(100px + ${getUnitVal(s.paddingTop, s.paddingTopUnit) || '0px'} + ${getUnitVal(s.paddingBottom, s.paddingBottomUnit) || '0px'})` : 'auto',
                    height: 'auto',
                    paddingBottom: getUnitVal(s.paddingBottom, s.paddingBottomUnit),
                    flex: isStretch ? '1 1 auto' : '0 1 auto',
                    display: 'flex',
                    flexDirection: 'column',
                    borderTopWidth: getUnitVal(s.borderSizeTop) || '0px',
                    borderBottomWidth: getUnitVal(s.borderSizeBottom) || '0px',
                    borderLeftWidth: getUnitVal(s.borderSizeLeft) || '0px',
                    borderRightWidth: getUnitVal(s.borderSizeRight) || '0px',
                    borderStyle: 'solid',
                    borderColor: s.borderColor || '#eee',
                    boxSizing: 'border-box',
                    borderTopLeftRadius: getUnitVal(s.borderRadiusTopLeft, s.borderRadiusTopLeftUnit),
                    borderTopRightRadius: getUnitVal(s.borderRadiusTopRight, s.borderRadiusTopRightUnit),
                    borderBottomRightRadius: getUnitVal(s.borderRadiusBottomRight, s.borderRadiusBottomRightUnit),
                    borderBottomLeftRadius: getUnitVal(s.borderRadiusBottomLeft, s.borderRadiusBottomLeftUnit),
                    boxShadow: shadowStr,
                    fontSize: getUnitVal(s.fontSize, s.fontSizeUnit),
                    fontWeight: s.fontWeight || undefined,
                    lineHeight: s.lineHeight || undefined,
                    letterSpacing: getUnitVal(s.letterSpacing, s.letterSpacingUnit),
                    textAlign: s.textAlign || undefined,
                    cursor: s.linkUrl ? 'pointer' : 'default'
                };
                // content layout — default is 'column' if not explicitly set
                const contentLayout = s.contentLayout || 'column';
                if (contentLayout === 'block') {
                    style.display = 'block';
                } else {
                    style.display = 'flex';
                    style.flexDirection = contentLayout === 'row' ? 'row' : 'column';
                    style.flexWrap = 'wrap';
                    const gW = s.gapWidth ? s.gapWidth + 'px' : '0px';
                    const gH = s.gapHeight ? s.gapHeight + 'px' : '0px';
                    if (s.gapWidth || s.gapHeight) style.gap = gH + ' ' + gW;
                    if (contentLayout === 'row') {
                        if (s.contentAlignH) style.justifyContent = s.contentAlignH;
                        if (s.contentAlignV) style.alignItems = s.contentAlignV;
                    } else {
                        if (s.contentAlignV) style.justifyContent = s.contentAlignV;
                        if (s.contentAlignH) style.alignItems = s.contentAlignH;
                    }
                }
                
                if (s.overflow) style.overflow = s.overflow;

                // Layered Background Logic
                let bgImages = [];
                if (s.bgGradientStartColor && s.bgGradientEndColor) {
                    const gType = s.bgGradientType || 'linear';
                    const angle = s.bgGradientAngle !== undefined ? s.bgGradientAngle + 'deg' : '180deg';
                    const start = hexToRgba(s.bgGradientStartColor, s.bgGradientStartOpacity !== undefined ? s.bgGradientStartOpacity : 1);
                    const end = hexToRgba(s.bgGradientEndColor, s.bgGradientEndOpacity !== undefined ? s.bgGradientEndOpacity : 1);
                    const startPos = s.bgGradientStartPosition !== undefined ? s.bgGradientStartPosition + '%' : '0%';
                    const endPos = s.bgGradientEndPosition !== undefined ? s.bgGradientEndPosition + '%' : '100%';

                    if (gType === 'linear') {
                        bgImages.push(`linear-gradient(${angle}, ${start} ${startPos}, ${end} ${endPos})`);
                    } else {
                        bgImages.push(`radial-gradient(circle, ${start} ${startPos}, ${end} ${endPos})`);
                    }
                }

                if (s.bgImage) {
                    bgImages.push(`url('${s.bgImage}')`);
                    style.backgroundPosition = s.bgImagePosition || 'center center';
                    style.backgroundRepeat = s.bgImageRepeat || 'no-repeat';
                    style.backgroundSize = s.bgImageSize || 'cover';
                    style.backgroundAttachment = s.bgImageParallax === 'fixed' ? 'fixed' : 'scroll';
                    style.backgroundBlendMode = s.bgImageBlendMode || 'normal';
                }

                if (bgImages.length > 0) {
                    style.backgroundImage = bgImages.join(', ');
                }
                
                if (s.bgColor) {
                    style.backgroundColor = hexToRgba(s.bgColor, s.bgColorOpacity !== undefined ? s.bgColorOpacity : 1);
                }

                return style;
            };

            const showElementModal = ref(false);
            const elementModalTab = ref('design'); // design, library, nested, studio
            const elementModalRestricted = ref(false);
            const elementModalAllowedTabs = ref(['design', 'nested']);
            const currentTargetCi = ref(null);
            const currentTargetColi = ref(null);
            const currentTargetEli = ref(null);
            const currentTargetNcoli = ref(null);
            const currentTargetNeli = ref(null);

            const openColumnModal = (index = null, type = 'new') => {
                columnModalTarget.value = index;
                columnModalType.value = type;
                showColumnModal.value = true;
            };

            const openElementModal = (ci, coli = null, defaultTab = 'design', restricted = false, eli = null, ncoli = null, neli = null, allowedTabs = ['design', 'nested']) => {
                currentTargetCi.value = ci;
                currentTargetColi.value = coli;
                currentTargetEli.value = eli;
                currentTargetNcoli.value = ncoli;
                currentTargetNeli.value = neli;
                elementModalTab.value = defaultTab;
                elementModalRestricted.value = restricted;
                elementModalAllowedTabs.value = allowedTabs;
                showElementModal.value = true;
            };

            const selectNestedLayout = (layoutConfig) => {
                if (currentTargetCi.value === null) return;

                const layoutData = {
                    id: Date.now(),
                    type: 'row',
                    settings: {
                        paddingTop: 0, paddingBottom: 0, paddingLeft: 0, paddingRight: 0,
                        marginTop: 0, marginBottom: 0, marginLeft: 0, marginRight: 0
                    },
                    columns: layoutConfig.split('-').map((part, idx) => {
                        const [num, den] = part.split('/');
                        return { id: uid(), basis: ((num / den) * 100).toFixed(2) + '%', settings: makeColumnSettings(), elements: [] };
                    })
                };

                if (currentTargetNeli.value !== null) {
                    const nestedColumn = layout.value[currentTargetCi.value].columns[currentTargetColi.value].elements[currentTargetEli.value].columns[currentTargetNcoli.value];
                    nestedColumn.elements.splice(currentTargetNeli.value, 0, layoutData);
                } else if (currentTargetNcoli.value !== null) {
                    // Horizontal Expand: Add columns to the parent row
                    const nestedRow = layout.value[currentTargetCi.value].columns[currentTargetColi.value].elements[currentTargetEli.value];
                    nestedRow.columns.splice(currentTargetNcoli.value + 1, 0, ...layoutData.columns);
                } else if (currentTargetColi.value === null) {
                    // Top level container add
                    layout.value[currentTargetCi.value].columns.push(...layoutData.columns);
                } else if (currentTargetEli.value === null) {
                    // Main column end
                    const column = layout.value[currentTargetCi.value].columns[currentTargetColi.value];
                    column.elements.push(layoutData);
                } else if (elementModalRestricted.value) {
                    // Horizontal Expand: Add columns to existing row
                    const nestedRow = layout.value[currentTargetCi.value].columns[currentTargetColi.value].elements[currentTargetEli.value];
                    nestedRow.columns.push(...layoutData.columns);
                } else {
                    // Main column insert
                    const column = layout.value[currentTargetCi.value].columns[currentTargetColi.value];
                    column.elements.splice(currentTargetEli.value, 0, layoutData);
                }

                showElementModal.value = false;
                currentTargetCi.value = null;
                currentTargetColi.value = null;
                currentTargetEli.value = null;
                currentTargetNcoli.value = null;
                currentTargetNeli.value = null;
            };

            const getVisibilityClasses = (settings) => {
                if (!settings || !settings.visibility) return '';
                let classes = '';
                if (settings.visibility.mobile === false) classes += ' lazy-hide-mobile';
                if (settings.visibility.tablet === false) classes += ' lazy-hide-tablet';
                if (settings.visibility.desktop === false) classes += ' lazy-hide-desktop';
                if (settings.visibility.mobile === false && settings.visibility.tablet === false && settings.visibility.desktop === false) {
                    classes = ' lazy-hide-all';
                }
                return classes;
            };

            const getCanvasVisibilityStyle = (settings) => {
                if (!settings || !settings.visibility) return {};
                const v = settings.visibility;
                const anyHidden = v.mobile === false || v.tablet === false || v.desktop === false;
                return anyHidden ? { opacity: '0.4', outline: '2px dashed #fbbf24', outlineOffset: '-2px' } : {};
            };

            const addElement = (type) => {
                if (currentTargetCi.value === null || currentTargetColi.value === null) return;

                const newEl = {
                    id: Date.now(),
                    type: type,
                    settings: {
                        visibility: { mobile: true, tablet: true, desktop: true },
                        ...(type === 'heading' ? { title: 'New Heading', textAlign: 'left' } : {}),
                        ...(type === 'title' ? {
                            title: 'Title', titleColor: '#222', fontSize: 36, fontSizeUnit: 'px', fontWeight: '800', textAlign: 'center',
                            useLink: false, linkUrl: '', linkColor: '#0091ea', linkHoverColor: '#007cc0',
                            separator: 'default', separatorColor: '#0091ea', dividerWidth: 60, dividerHeight: 3,
                            paddingTop: 20, paddingBottom: 20, marginTop: 0, marginBottom: 0, marginLeft: 0, marginRight: 0,
                            visibility: { mobile: true, tablet: true, desktop: true }
                        } : {}),
                        ...(type === 'text' ? { content: '<p>New text here...</p>' } : {}),
                        ...(type === 'button' ? { text: 'Click Me', url: '#', style: 'primary' } : {}),
                        ...(type === 'image' ? { url: '', alt: '' } : {}),
                    }
                };

                // Apply custom element defaults
                const customDef = Object.values(customElements).find(e => e.type === type);
                if (customDef && customDef.fields) {
                    Object.entries(customDef.fields).forEach(([key, field]) => {
                        if (field.default !== undefined) newEl.settings[key] = field.default;
                    });
                }

                if (currentTargetNeli.value !== null) {
                    // Nested insertion inside a nested column at specific index
                    const nestedCol = layout.value[currentTargetCi.value].columns[currentTargetColi.value].elements[currentTargetEli.value].columns[currentTargetNcoli.value];
                    nestedCol.elements.splice(currentTargetNeli.value, 0, newEl);
                } else if (currentTargetEli.value !== null && currentTargetNcoli.value !== null) {
                    // Nested insertion inside a nested column (append)
                    const nestedCol = layout.value[currentTargetCi.value].columns[currentTargetColi.value].elements[currentTargetEli.value].columns[currentTargetNcoli.value];
                    nestedCol.elements.push(newEl);
                } else if (currentTargetEli.value !== null) {
                    // Insertion at specific index in main column
                    const column = layout.value[currentTargetCi.value].columns[currentTargetColi.value];
                    column.elements.splice(currentTargetEli.value, 0, newEl);
                } else {
                    // Regular insertion at end of column
                    const column = layout.value[currentTargetCi.value].columns[currentTargetColi.value];
                    column.elements.push(newEl);
                }
                showElementModal.value = false;
            };



            return {
                layout, isPreview, isSaving, isDirty, activeTab, activePanelTab, activeColPanelTab, device, availableElements,
                activeCi, editingCi, activeColi, activeColCi, editingContext,
                clearEditingContext, setEditingContext,
                showColumnModal, columnModalType, columnLayouts, openColumnModal, selectLayout,
                showElementModal, elementModalTab, elementModalRestricted, elementModalAllowedTabs, openElementModal, selectNestedLayout,
                editingColumn, editingElement,
                addContainer, addColumn, addNestedColumn, addElement, duplicateContainer, duplicateColumn, duplicateElement, duplicateNestedColumn, duplicateNestedRow, duplicateNestedElement, saveLayout, openMediaModal, openColorPicker,
                isDragging, dragType, dragSource, dragCi, dragColi, dragEli, dragNcoli, startDrag,
                onDragStart, onDragEnd, onDragOver, onDrop, dragTarget, dragPosition,
                canvasStyle, containerStyle, containerInnerStyle, columnOuterStyle, columnInnerStyle, formatBasisToFraction, updateBasis, hexToRgba, getUnitVal,
                getVisibilityClasses, getCanvasVisibilityStyle,
                searchColumnQuery, searchElementQuery, filteredColumnLayouts, filteredNestedColumnLayouts, filteredAvailableElements,
                shouldShowGuide,
                toasts, showToast,
                hoveredType, hoveredCi, hoveredColi, hoveredEli, hoveredNcoli, setHover,
                themeBodyFont, themeHeadingFont, builderFontGroups, builderFonts: BUILDER_FONTS,
                titleFontVariants, loadBuilderFont
            };
        }
    }).mount('#lazy-builder-app');
</script>
