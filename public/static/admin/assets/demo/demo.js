var spath = document.getElementById("demo").getAttribute("data-bind");
(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        define([], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory();
    } else {
        root.pxDemo = factory();
    }
}(this, function () {
    'use strict';
    function attachOnLoadHandler(cb) {
        if (window.attachEvent) {
            window.attachEvent('onload', cb);
        } else if (window.onload) {
            var curronload = window.onload;

            window.onload = function (evt) {
                curronload(evt);
                cb(evt);
            };
        } else {
            window.onload = cb;
        }
    }

    var pxDemo = (function () {

        // Constants

        var COLORS = [
            '#0288D1',
            '#FF4081',
            '#4CAF50',
            '#D32F2F',
            '#FFC107',
            '#673AB7',
            '#FF5722',
            '#CDDC39',
            '#795548',
            '#607D8B',
            '#009688',
            '#E91E63',
            '#9E9E9E',
            '#E040FB',
            '#00BCD4',
        ];

        var BACKGROUNDS = [
            spath + '/admin/assets/demo/bgs/1.jpg',
            spath + '/admin/assets/demo/bgs/2.jpg',
            spath + '/admin/assets/demo/bgs/3.jpg'
        ];

        var THEMES = [
            'default',
            'asphalt',
            'purple-hills',
            'dust',
            'frost',
            'silver',
            'clean',
            'white',
            'candy-black',
            'candy-blue',
            'candy-red',
            'candy-orange',
            'candy-green',
            'candy-purple',
            'candy-cyan',
            'mint-dark',
            'dark-blue',
            'dark-red',
            'dark-orange',
            'dark-green',
            'dark-purple',
            'dark-cyan',
            'darklight-blue',
            'darklight-red',
            'darklight-orange',
            'darklight-green',
            'darklight-purple',
            'darklight-cyan',
        ];

        var demoSettings = (function loadDemoSettings() {
            var result = {
                fixed_navbar: '0',
                fixed_nav: '0',
                right_nav: '0',
                offcanvas_nav: '0',
                rtl: '0',
                footer: 'bottom',
                theme: THEMES[0],
            };

            var cookie = ';' + document.cookie + ';';

            var re;
            var found;

            for (var key in result) {
                if (Object.prototype.hasOwnProperty.call(result, key)) {
                    re = new RegExp(';\\s*' + encodeURIComponent('px-demo-' + key) + '\\s*=\\s*([^;]+)\\s*;');
                    found = cookie.match(re);

                    if (found) {
                        result[key] = decodeURIComponent(found[1]);
                    }
                }
            }

            // Guards
            result.fixed_navbar = ['0', '1'].indexOf(result.fixed_navbar) !== -1 ? result.fixed_navbar : '0';
            result.fixed_nav = ['0', '1'].indexOf(result.fixed_nav) !== -1 ? result.fixed_nav : '0';
            result.right_nav = ['0', '1'].indexOf(result.right_nav) !== -1 ? result.right_nav : '0';
            result.offcanvas_nav = ['0', '1'].indexOf(result.offcanvas_nav) !== -1 ? result.offcanvas_nav : '0';
            result.rtl = ['0', '1'].indexOf(result.rtl) !== -1 ? result.rtl : '0';
            result.footer = ['static', 'bottom', 'fixed'].indexOf(result.footer) !== -1 ? result.footer : 'bottom';
            result.theme = THEMES.indexOf(result.theme) !== -1 ? result.theme : THEMES[0];

            return result;
        })();

        var CURRENT_THEME = demoSettings.theme;

        function setSidebarState(state) {
            $('#px-demo-sidebar input').prop('disabled', state === 'disabled');
            $('#px-demo-sidebar-loader')[CURRENT_THEME.indexOf('dark') === -1 ? 'removeClass' : 'addClass']('form-loading-inverted');
            $('#px-demo-sidebar-loader')[state === 'disabled' ? 'show' : 'hide']();
        }

        // Private

        function updateDemoSettings(settings) {
            $.extend(demoSettings, settings);

            for (var key in demoSettings) {
                if (Object.prototype.hasOwnProperty.call(demoSettings, key)) {
                    document.cookie =
                            encodeURIComponent('px-demo-' + key) + '=' +
                            encodeURIComponent(demoSettings[key])+';path=/';
                }
            }
        }

        function _createStylesheetLink(href, className, cb) {
            var head = document.getElementsByTagName('head')[0];
            var link = document.createElement('link');

            link.className = className;
            link.type = 'text/css';
            link.rel = 'stylesheet';
            link.href = href;

            var r = false;

            link.onload = link.onreadystatechange = function () {
                if (!r && (!this.readyState || this.readyState === 'complete')) {
                    r = true;

                    var links = document.getElementsByClassName(className);

                    if (links.length > 1) {
                        for (var i = 1, l = links.length; i < l; i++) {
                            head.removeChild(links[i]);
                        }
                    }

                    document.documentElement.className =
                            document.documentElement.className.replace(/\s*px-demo-no-transition/, '');
                }

                if (cb) {
                    cb();
                }
            };

            document.documentElement.className += ' px-demo-no-transition';

            return link;
        }

        function setTheme(themeName) {
            if (themeName === CURRENT_THEME) {
                return;
            }

            CURRENT_THEME = themeName;

            var _isDark = themeName.indexOf('dark') !== -1;
            var _isRtl = document.getElementsByTagName('html')[0].getAttribute('dir') === 'rtl';
            var themePath = spath + '/admin/assets/css/themes/' + themeName + (_isRtl ? '.rtl' : '') + '.min.css';

            var linksToLoad = [];

            // Switch between light and dark assets

            var _assetCls = ['px-demo-stylesheet-bs', 'px-demo-stylesheet-core', 'px-demo-stylesheet-widgets'];
            var _assetLink;

            function _assetReplacer(match, path, name, suffix) {
                return path + name.replace('-dark', '') + (_isDark ? '-dark' : '') + suffix;
            }

            for (var _i = 0, _l = _assetCls.length; _i < _l; _i++) {
                _assetLink = (document.getElementsByClassName(_assetCls[_i]) || [])[0] || null;

                if (_assetLink) {
                    linksToLoad.push(
                            [_assetLink.getAttribute('href').replace(/^(.*?)([^\/\.]+)((?:\.rtl)?(?:\.min)?\.css)$/, _assetReplacer), _assetCls[_i]]
                            );
                }
            }

            linksToLoad.push([themePath, 'px-demo-stylesheet-theme']);

            var linksContainer = document.createDocumentFragment();
            var loadedLinks = 0;

            function _cb() {
                loadedLinks++;

                if (loadedLinks < linksToLoad.length) {
                    return;
                }

                setSidebarState('enabled');
            }

            for (var i = 0, l = linksToLoad.length; i < l; i++) {
                linksContainer.appendChild(_createStylesheetLink(linksToLoad[i][0], linksToLoad[i][1], _cb));
            }

            document.getElementsByTagName('head')[0].insertBefore(
                    linksContainer,
                    document.getElementsByClassName('px-demo-stylesheet-bs')[0]
                    );
        }

        function loadTheme() {
            setTheme(demoSettings.theme);
        }

        function loadRtl() {
            if (demoSettings.rtl !== '1') {
                return;
            }

            document.getElementsByTagName("html")[0].setAttribute('dir', 'rtl');
        }

        function placeNav(side) {
            var navEl = document.querySelector('body > .px-nav') || document.querySelector('body > ui-view > .px-nav');

            navEl.className =
                    navEl.className
                    .replace(new RegExp("^\\s*px-nav-(?:left|right)\\s*", 'i'), '')
                    .replace(new RegExp("\\s*px-nav-(?:left|right)\\s*$", 'i'), '')
                    .replace(new RegExp("\\s+px-nav-(?:left|right)\\s+", 'ig'), ' ') +
                    ' px-nav-' + side;
        }

        function setFooterPosition(pos) {
            var footer = document.querySelector('body > .px-footer') || document.querySelector('body > ui-view > .px-footer');

            if (!footer) {
                return;
            }

            footer.className = footer.className
                    .replace(/^\s*px-footer-(?:bottom|fixed)\s*/i, '')
                    .replace(/\s*px-footer-(?:bottom|fixed)\s*$/i, '')
                    .replace(/\s+px-footer-(?:bottom|fixed)\s+/gi, ' ') +
                    ((pos === 'bottom' || pos === 'fixed') ? (' px-footer-' + pos) : '');
        }

        function capitalizeAllLetters(str, splitter) {
            var parts = str.split(splitter || ' ');

            for (var i = 0, l = parts.length; i < l; i++) {
                parts[i] = parts[i].charAt(0).toUpperCase() + parts[i].slice(1);
            }

            return parts.join(' ');
        }

        // Public

        function shuffle(a) {
            var j;
            var x;
            var i;

            for (i = a.length; i; i -= 1) {
                j = Math.floor(Math.random() * i);
                x = a[i - 1];
                a[i - 1] = a[j];
                a[j] = x;
            }
        }

        function getRandomData(max, min) {
            return Math.floor(Math.random() * ((max || 100) - (min || 0))) + (min || 0);
        }

        function getRandomColors(count) {
            if (count && count > COLORS.length) {
                throw new Error('Have not enough colors');
            }

            var clrLeft = count || COLORS.length;
            var source = [].concat(COLORS);
            var result = [];

            while (clrLeft-- > 0) {
                result.unshift(source[source.length > 1 ? getRandomData(source.length - 1) : 0]);
                source.splice(source.indexOf(result[0]), 1);
            }

            shuffle(result);

            return result;
        }

        function initializeDemo() {
            $('input#px-demo-fixed-navbar-toggler').on('change', function () {
                updateDemoSettings({
                    fixed_navbar: $(this).is(':checked') ? '1' : '0',
                });

                $(document.querySelector('body > ui-view') || document.body)[
                        $(this).is(':checked') ? 'addClass' : 'removeClass'
                ]('px-navbar-fixed');

                var $fixedNavToggler = $('input#px-demo-fixed-nav-toggler');

                if (!$(this).is(':checked') && $fixedNavToggler.is(':checked')) {
                    $fixedNavToggler.click();
                }
            });

            $('input#px-demo-fixed-nav-toggler').on('change', function () {
                updateDemoSettings({
                    fixed_nav: $(this).is(':checked') ? '1' : '0',
                });

                $('body > .px-nav, body > ui-view > .px-nav')[
                        $(this).is(':checked') ? 'addClass' : 'removeClass'
                ]('px-nav-fixed');

                var $fixedNavbarToggler = $('input#px-demo-fixed-navbar-toggler');

                if ($(this).is(':checked') && !$fixedNavbarToggler.is(':checked')) {
                    $fixedNavbarToggler.click();
                }

                $(window).trigger('scroll');
            });

            $('input#px-demo-nav-right-toggler').on('change', function () {
                updateDemoSettings({
                    right_nav: $(this).is(':checked') ? '1' : '0',
                });

                placeNav($(this).is(':checked') ? 'right' : 'left');
            });

            $('input#px-demo-nav-off-canvas-toggler').on('change', function () {
                updateDemoSettings({
                    offcanvas_nav: $(this).is(':checked') ? '1' : '0',
                });

                $('body > .px-nav, body > ui-view > .px-nav')[
                        $(this).is(':checked') ? 'addClass' : 'removeClass'
                ]('px-nav-off-canvas');

                $(window).trigger('resize');
            });

            $('input#px-demo-nav-rtl-toggler').on('change', function () {
                setSidebarState('disabled');

                updateDemoSettings({
                    rtl: $(this).is(':checked') ? '1' : '0',
                });

                document.location.reload();
            });

            $('select#px-demo-footer-position-select').on('change', function () {
                updateDemoSettings({
                    footer: $(this).val(),
                });

                setFooterPosition($(this).val());

                $(window).trigger('resize');
            });

            $('input[name="px-demo-current-theme"]').on('change', function () {
                setSidebarState('disabled');

                var themeName = THEMES.indexOf(this.value) !== -1 ? this.value : THEMES[0];

                updateDemoSettings({theme: themeName});
                setTheme(themeName);
            });


            // Initialize "close" button
            //

            $('.px-nav')
                    .off('click.demo-px-nav-box')
                    .on('click.demo-px-nav-box', '#demo-px-nav-box .close', function (e) {
                        e.preventDefault();

                        var $box = $(this).parents('.px-nav-box').addClass('no-animation');
                        var $wrapper = $('<div></div>').css({overflow: 'hidden'});

                        // Remove close button
                        $(this).remove();

                        $wrapper
                                .insertBefore($box)
                                .append($box)
                                .animate({
                                    opacity: 0,
                                    height: 'toggle',
                                }, 400, function () {
                                    $wrapper.remove();
                                });
                    });
        }

        function initializeBgsDemo(selector, defaultBgIndex, overlay, afterCall) {
            var isBgSet = false;

            if (defaultBgIndex) {
                $(selector).pxResponsiveBg({
                    backgroundImage: BACKGROUNDS[defaultBgIndex - 1],
                    overlay: overlay,
                });

                isBgSet = true;

                if (afterCall) {
                    afterCall(isBgSet);
                }
            }

            var elementsHtml = '<a href="#" class="px-demo-bgs-container px-demo-bgs-clear">&times;</a>';

            for (var i = 0, l = BACKGROUNDS.length; i < l; i++) {
                elementsHtml += '<a href="#" class="px-demo-bgs-container"><img src="' + BACKGROUNDS[i] + '" alt=""></a>';
            }

            var $block = $('<div class="px-demo-bgs">' + elementsHtml + '</div>');

            $block.on('click', '.px-demo-bgs-container', function (e) {
                e.preventDefault();

                var $container = $(this);

                if ($container.hasClass('px-demo-bgs-clear')) {
                    if (!isBgSet) {
                        return;
                    }

                    $(selector).pxResponsiveBg('destroy', true);

                    isBgSet = false;

                    if (afterCall) {
                        afterCall(isBgSet);
                    }
                } else {
                    if (isBgSet || $(selector).data('px.responsiveBg')) {
                        $(selector).pxResponsiveBg('destroy');
                    }

                    $(selector).pxResponsiveBg({
                        backgroundImage: $container.find('> img').attr('src'),
                        overlay: overlay,
                    });

                    isBgSet = true;

                    if (afterCall) {
                        afterCall(isBgSet);
                    }
                }
            });

            $('body').append($block);
        }

        function destroyBgsDemo(selector) {
            if (!$.fn.pxResponsiveBg) {
                return;
            }
            $(selector).pxResponsiveBg('destroy', true);
            $('.px-demo-bgs').off().remove();
        }

        function initializeDemoSidebar(container, skipFooter) {
            var sidebarEl = document.createElement('DIV');

            sidebarEl.id = 'px-demo-sidebar';
            sidebarEl.className = 'px-sidebar-right bg-primary';
            sidebarEl.style.width = '242px';
            sidebarEl.innerHTML = '<a href="javascript:void(0)" id="px-demo-sidebar-toggle" class="bg-primary b-y-1 b-l-1 text-default" data-toggle="sidebar" data-target="#px-demo-sidebar"><i class="ion-ios-gear"></i><i class="ion-android-close"></i></a><div id="px-demo-sidebar-loader" class="form-loading form-loading-inverted"></div>';

            var contentEl = document.createElement('DIV');

            contentEl.className = 'px-sidebar-content';
            sidebarEl.appendChild(contentEl);

            var content = '';
            var navEl = document.querySelector('body > .px-nav') || document.querySelector('body > ui-view > .px-nav');

            content += '<div id="px-demo-togglers">';

            content += '<h6 class="px-demo-sidebar-header b-y-1 bg-primary darker">后台显示设置</h6>';

            // Togglers

            content += '<div><div class="box m-a-0 border-radius-0 bg-transparent">';

            // Fixed navbar

            content +=
                    '<div class="box-row"' + (navEl ? '' : 'style="display: none;"') + '>' +
                    '<div class="box-cell p-l-3 bg-primary"><label for="px-demo-fixed-navbar-toggler">固定头部</label></div>' +
                    '<div class="box-cell p-r-3 bg-primary" style="width: 70px;">' +
                    '<label for="px-demo-fixed-navbar-toggler" class="switcher switcher-blank switcher-sm"><input type="checkbox" id="px-demo-fixed-navbar-toggler"' + (demoSettings.fixed_navbar === '1' ? ' checked' : '') + '><div class="switcher-indicator"><div class="switcher-yes bg-primary darker"><i class="fa fa-check"></i></div><div class="switcher-no"><i class="fa fa-close"></i></div></div></label>' +
                    '</div>' +
                    '</div>';

            if (navEl && demoSettings.fixed_navbar === '1') {
                (document.querySelector('body > ui-view') || document.body).className += ' px-navbar-fixed';
            }

            // Fixed nav

            content +=
                    '<div class="box-row"' + (navEl ? '' : 'style="display: none;"') + '>' +
                    '<div class="box-cell bg-primary p-l-3"><label for="px-demo-fixed-nav-toggler">固定头部和左侧</label></div>' +
                    '<div class="box-cell bg-primary p-r-3" style="width: 70px;">' +
                    '<label for="px-demo-fixed-nav-toggler" class="switcher switcher-blank switcher-sm"><input type="checkbox" id="px-demo-fixed-nav-toggler"' + (demoSettings.fixed_nav === '1' ? ' checked' : '') + '><div class="switcher-indicator"><div class="switcher-yes bg-primary darker"><i class="fa fa-check"></i></div><div class="switcher-no"><i class="fa fa-close"></i></div></div></label>' +
                    '</div>' +
                    '</div>';

            if (navEl && demoSettings.fixed_nav === '1') {
                navEl.className += ' px-nav-fixed';
            }

            // Right nav

            content +=
                    '<div class="box-row"' + (navEl ? '' : 'style="display: none;"') + '>' +
                    '<div class="box-cell bg-primary p-l-3"><label for="px-demo-nav-right-toggler">右置菜单</label></div>' +
                    '<div class="box-cell bg-primary p-r-3" style="width: 70px;">' +
                    '<label for="px-demo-nav-right-toggler" class="switcher switcher-blank switcher-sm"><input type="checkbox" id="px-demo-nav-right-toggler"' + (demoSettings.right_nav === '1' ? ' checked' : '') + '><div class="switcher-indicator"><div class="switcher-yes bg-primary darker"><i class="fa fa-check"></i></div><div class="switcher-no"><i class="fa fa-close"></i></div></div></label>' +
                    '</div>' +
                    '</div>';

            if (navEl) {
                placeNav(demoSettings.right_nav === '1' ? 'right' : 'left');
            }

            // Off canvas nav

            content +=
                    '<div class="box-row"' + (navEl ? '' : 'style="display: none;"') + '>' +
                    '<div class="box-cell bg-primary p-l-3"><label for="px-demo-nav-off-canvas-toggler">Off canvas nav</label></div>' +
                    '<div class="box-cell bg-primary p-r-3" style="width: 70px;">' +
                    '<label for="px-demo-nav-off-canvas-toggler" class="switcher switcher-blank switcher-sm"><input type="checkbox" id="px-demo-nav-off-canvas-toggler"' + (demoSettings.offcanvas_nav === '1' ? ' checked' : '') + '><div class="switcher-indicator"><div class="switcher-yes bg-primary darker"><i class="fa fa-check"></i></div><div class="switcher-no"><i class="fa fa-close"></i></div></div></label>' +
                    '</div>' +
                    '</div>';

            if (navEl && demoSettings.offcanvas_nav === '1') {
                navEl.className += ' px-nav-off-canvas';
            }

            // RTL

            content +=
                    '<div class="box-row box-row-rtl-toggler">' +
                    '<div class="box-cell bg-primary p-l-3"><label for="px-demo-nav-rtl-toggler">从左到右书写</label></div>' +
                    '<div class="box-cell bg-primary p-r-3" style="width: 70px;">' +
                    '<label for="px-demo-nav-rtl-toggler" class="switcher switcher-blank switcher-sm"><input type="checkbox" id="px-demo-nav-rtl-toggler"' + (demoSettings.rtl === '1' ? ' checked' : '') + '><div class="switcher-indicator"><div class="switcher-yes bg-primary darker"><i class="fa fa-check"></i></div><div class="switcher-no"><i class="fa fa-close"></i></div></div></label>' +
                    '</div>' +
                    '</div>';

            content += '</div></div>';

            // Footer
            var hasFooter = document.querySelector('body > .px-footer') || document.querySelector('body > ui-view > .px-footer');

            if (!skipFooter) {
                content +=
                        '<div id="px-demo-footer-position"' + (hasFooter ? '' : 'style="display: none;"') + '><div class="box m-a-0 border-radius-0 bg-transparent">' +
                        '<div class="box-row">' +
                        '<div class="box-cell bg-primary p-l-3"><label for="px-demo-footer-position-select">页脚</label></div>' +
                        '<div class="box-cell bg-primary p-r-3">' +
                        '<select class="custom-select form-control input-sm bg-primary darker" id="px-demo-footer-position-select"><option value="static"' + (demoSettings.footer === 'static' ? ' selected' : '') + '>Static</option><option value="bottom"' + (demoSettings.footer === 'bottom' ? ' selected' : '') + '>Bottom</option><option value="fixed"' + (demoSettings.footer === 'fixed' ? ' selected' : '') + '>Fixed</option></select>' +
                        '</div>' +
                        '</div>' +
                        '</div></div>';

                if (hasFooter) {
                    setFooterPosition(demoSettings.footer);
                }
            }

            content += '</div>';

            // Themes

            content += '<h6 class="px-demo-sidebar-header bg-primary darker b-y-1">颜色风格</h6>';
            content += '<div class="px-demo-themes-list clearfix bg-primary">';

            for (var i = 0, l = THEMES.length; i < l; i++) {
                content += '<label class="px-demo-themes-item">';

                content += '<input type="radio" class="px-demo-themes-toggler" name="px-demo-current-theme" value="' + THEMES[i] + '"' + (demoSettings.theme === THEMES[i] ? ' checked' : '') + '>';
                content += '<img src="' + spath + '/admin/assets/demo/themes/' + THEMES[i] + '.png" class="px-demo-themes-thumbnail">';
                content += '<div class="px-demo-themes-title font-weight-semibold"><span class="text-white">' + capitalizeAllLetters(THEMES[i], '-') + '</span><div class="bg-primary"></div></div>';

                content += '</label>';
            }

            content += '</div>';

            contentEl.innerHTML = content;
            (container ? document.querySelector(container) : document.body).appendChild(sidebarEl);
        }

        // Return

        return {
            COLORS: COLORS,
            shuffle: shuffle,
            getRandomData: getRandomData,
            getRandomColors: getRandomColors,
            initializeDemo: initializeDemo,
            initializeBgsDemo: initializeBgsDemo,
            initializeDemoSidebar: initializeDemoSidebar,
            destroyBgsDemo: destroyBgsDemo,
            loadTheme: loadTheme,
            loadRtl: loadRtl,
        };
    })();

    return pxDemo;
}));