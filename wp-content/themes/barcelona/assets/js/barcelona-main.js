/*!
 * jQuery lockfixed plugin
 * http://www.directlyrics.com/code/lockfixed/
 *
 * Copyright 2012-2015 Yvo Schaap
 * Released under the MIT license
 * http://www.directlyrics.com/code/lockfixed/license.txt
 *
 * Date: Sun March 30 2015 12:00:01 GMT
 */
!function(t){t.extend({lockfixed:function(e,o){if(o&&o.offset?("function"!=typeof o.offset.bottom&&(o.offset.bottom=parseInt(o.offset.bottom,10)),"function"!=typeof o.offset.top&&(o.offset.top=parseInt(o.offset.top,10))):o.offset={bottom:100,top:0},o&&o.stuck_class||(o.stuck_class="is_stuck"),o&&"undefined"!=typeof o.screen_limit||(o.screen_limit=767),e&&e.offset()){var s=e.css("position"),i=parseInt(e.css("marginTop"),10),n=e.css("top"),a=e.offset().top,f=!1;(o.forcemargin===!0||navigator.userAgent.match(/\bMSIE (4|5|6)\./)||navigator.userAgent.match(/\bOS ([0-9])_/)||navigator.userAgent.match(/\bAndroid ([0-9])\./i))&&(a-=100),e.wrap("<div class='navbar-wrapper' style='height:"+e.outerHeight()+"px;display:"+e.css("display")+"'></div>"),t(window).bind("DOMContentLoaded load scroll resize orientationchange lockfixed:pageupdate",e,function(){if(t(window).width()<o.screen_limit)return e.removeAttr("style"),e.parents(".navbar-wrapper").removeAttr("style"),void e.removeClass(o.stuck_class);if(!f||!document.activeElement||"INPUT"!==document.activeElement.nodeName){var r="function"==typeof o.offset.top?o.offset.top():o.offset.top,p="function"==typeof o.offset.bottom?o.offset.bottom():o.offset.bottom,c=0,d=e.outerHeight(),m=e.parent().innerWidth()-parseInt(e.css("marginLeft"),10)-parseInt(e.css("marginRight"),10),l=t(document).height()-p,u=t(window).scrollTop();"fixed"===e.css("position")||f||(a=e.offset().top,n=e.css("top")),u>=a-(i?i:0)-r?(c=u+d+i+r>l?u+d+i+r-l:0,f?e.css({marginTop:parseInt(u-a-c,10)+2*r+"px"}):e.css({position:"fixed",top:r-c+"px",width:m+"px"}).addClass(o.stuck_class)):e.css({position:s,top:n,width:m+"px",marginTop:(i&&!f?i:0)+"px"}).removeClass(o.stuck_class),e.parents(".navbar-wrapper").css("height",e.outerHeight())}})}}})}(jQuery);

/*!
 * Theia Sticky Sidebar v1.2.2
 * https://github.com/WeCodePixels/theia-sticky-sidebar
 *
 * Glues your website's sidebars, making them permanently visible while scrolling.
 *
 * Copyright 2013-2014 WeCodePixels and other contributors
 * Released under the MIT license
 */
!function(i){i.fn.theiaStickySidebar=function(t){var o={containerSelector:"",additionalMarginTop:0,additionalMarginBottom:0,updateSidebarHeight:!0,minWidth:0};t=i.extend(o,t),t.additionalMarginTop=parseInt(t.additionalMarginTop)||0,t.additionalMarginBottom=parseInt(t.additionalMarginBottom)||0,i("head").append(i('<style>.theiaStickySidebar:after {content: ""; display: table; clear: both;}</style>')),this.each(function(){function o(){e.fixedScrollTop=0,e.sidebar.css({"min-height":"1px"}),e.stickySidebar.css({position:"static",width:""})}function a(t){var o=t.height();return t.children().each(function(){o=Math.max(o,i(this).height())}),o}var e={};e.sidebar=i(this),e.options=t||{},e.container=i(e.options.containerSelector),0==e.container.size()&&(e.container=e.sidebar.parent()),e.sidebar.parents().css("-webkit-transform","none"),e.sidebar.css({position:"relative",overflow:"visible","-webkit-box-sizing":"border-box","-moz-box-sizing":"border-box","box-sizing":"border-box"}),e.stickySidebar=e.sidebar.find(".theiaStickySidebar"),0==e.stickySidebar.length&&(e.sidebar.find("script").remove(),e.stickySidebar=i("<div>").addClass("theiaStickySidebar").append(e.sidebar.children()),e.sidebar.append(e.stickySidebar)),e.marginTop=parseInt(e.sidebar.css("margin-top")),e.marginBottom=parseInt(e.sidebar.css("margin-bottom")),e.paddingTop=parseInt(e.sidebar.css("padding-top")),e.paddingBottom=parseInt(e.sidebar.css("padding-bottom"));var d=e.stickySidebar.offset().top,r=e.stickySidebar.outerHeight();e.stickySidebar.css("padding-top",1),e.stickySidebar.css("padding-bottom",1),d-=e.stickySidebar.offset().top,r=e.stickySidebar.outerHeight()-r-d,0==d?(e.stickySidebar.css("padding-top",0),e.stickySidebarPaddingTop=0):e.stickySidebarPaddingTop=1,0==r?(e.stickySidebar.css("padding-bottom",0),e.stickySidebarPaddingBottom=0):e.stickySidebarPaddingBottom=1,e.previousScrollTop=null,e.fixedScrollTop=0,o(),e.onScroll=function(e){if(e.stickySidebar.is(":visible")){if(i("body").width()<e.options.minWidth)return void o();if(e.sidebar.outerWidth(!0)+50>e.container.width())return void o();var d=i(document).scrollTop(),r="static";if(d>=e.container.offset().top+(e.paddingTop+e.marginTop-e.options.additionalMarginTop)){var s,n=e.paddingTop+e.marginTop+t.additionalMarginTop,c=e.paddingBottom+e.marginBottom+t.additionalMarginBottom,p=e.container.offset().top,b=e.container.offset().top+a(e.container),g=0+t.additionalMarginTop,l=e.stickySidebar.outerHeight()+n+c<i(window).height();s=l?g+e.stickySidebar.outerHeight():i(window).height()-e.marginBottom-e.paddingBottom-t.additionalMarginBottom;var f=p-d+e.paddingTop+e.marginTop,S=b-d-e.paddingBottom-e.marginBottom,h=e.stickySidebar.offset().top-d,m=e.previousScrollTop-d;"fixed"==e.stickySidebar.css("position")&&(h+=m),h=m>0?Math.min(h,g):Math.max(h,s-e.stickySidebar.outerHeight()),h=Math.max(h,f),h=Math.min(h,S-e.stickySidebar.outerHeight());var y=e.container.height()==e.stickySidebar.outerHeight();r=(y||h!=g)&&(y||h!=s-e.stickySidebar.outerHeight())?d+h-e.sidebar.offset().top-e.paddingTop<=t.additionalMarginTop?"static":"absolute":"fixed"}if("fixed"==r)e.stickySidebar.css({position:"fixed",width:e.sidebar.width(),top:h,left:e.sidebar.offset().left+parseInt(e.sidebar.css("padding-left"))+parseInt(e.sidebar.css("border-left"))});else if("absolute"==r){var k={};"absolute"!=e.stickySidebar.css("position")&&(k.position="absolute",k.top=d+h-e.sidebar.offset().top-e.stickySidebarPaddingTop-e.stickySidebarPaddingBottom),k.width=e.sidebar.width(),k.left="",e.stickySidebar.css(k)}else"static"==r&&o();"static"!=r&&1==e.options.updateSidebarHeight&&e.sidebar.css({"min-height":e.stickySidebar.outerHeight()+e.stickySidebar.offset().top-e.sidebar.offset().top+e.paddingBottom}),e.previousScrollTop=d}},e.onScroll(e),i(document).scroll(function(i){return function(){i.onScroll(i)}}(e)),i(window).resize(function(i){return function(){i.stickySidebar.css({position:"static"}),i.onScroll(i)}}(e))})}}(jQuery);

/*! Backstretch - v2.0.4 - 2013-06-19
 * http://srobbin.com/jquery-plugins/backstretch/
 * Copyright (c) 2013 Scott Robbin; Licensed MIT */
!function(t,i,e){t.fn.backstretch=function(n,s){return(n===e||0===n.length)&&t.error("No images were supplied for Backstretch"),0===t(i).scrollTop()&&i.scrollTo(0,0),this.each(function(){var i=t(this),e=i.data("backstretch");if(e){if("string"==typeof n&&"function"==typeof e[n])return void e[n](s);s=t.extend(e.options,s),e.destroy(!0)}e=new r(this,n,s),i.data("backstretch",e)})},t.backstretch=function(i,e){return t("body").backstretch(i,e).data("backstretch")},t.expr[":"].backstretch=function(i){return t(i).data("backstretch")!==e},t.fn.backstretch.defaults={centeredX:!0,centeredY:!0,duration:5e3,fade:0};var n={left:0,top:0,overflow:"hidden",margin:0,padding:0,height:"100%",width:"100%",zIndex:-999999},s={position:"absolute",display:"none",margin:0,padding:0,border:"none",width:"auto",height:"auto",maxHeight:"none",maxWidth:"none",zIndex:-999999},r=function(e,s,r){this.options=t.extend({},t.fn.backstretch.defaults,r||{}),this.images=t.isArray(s)?s:[s],t.each(this.images,function(){t("<img />")[0].src=this}),this.isBody=e===document.body,this.$container=t(e),this.$root=this.isBody?t(a?i:document):this.$container,e=this.$container.children(".backstretch").first(),this.$wrap=e.length?e:t('<div class="backstretch"></div>').css(n).appendTo(this.$container),this.isBody||(e=this.$container.css("position"),s=this.$container.css("zIndex"),this.$container.css({position:"static"===e?"relative":e,zIndex:"auto"===s?0:s,background:"none"}),this.$wrap.css({zIndex:-999998})),this.$wrap.css({position:this.isBody&&a?"fixed":"absolute"}),this.index=0,this.show(this.index),t(i).on("resize.backstretch",t.proxy(this.resize,this)).on("orientationchange.backstretch",t.proxy(function(){this.isBody&&0===i.pageYOffset&&(i.scrollTo(0,1),this.resize())},this))};r.prototype={resize:function(){try{var t,e={left:0,top:0},n=this.isBody?this.$root.width():this.$root.innerWidth(),s=n,r=this.isBody?i.innerHeight?i.innerHeight:this.$root.height():this.$root.innerHeight(),a=s/this.$img.data("ratio");a>=r?(t=(a-r)/2,this.options.centeredY&&(e.top="-"+t+"px")):(a=r,s=a*this.$img.data("ratio"),t=(s-n)/2,this.options.centeredX&&(e.left="-"+t+"px")),this.$wrap.css({width:n,height:r}).find("img:not(.deleteable)").css({width:s,height:a}).css(e)}catch(o){}return this},show:function(i){if(!(Math.abs(i)>this.images.length-1)){var e=this,n=e.$wrap.find("img").addClass("deleteable"),r={relatedTarget:e.$container[0]};return e.$container.trigger(t.Event("backstretch.before",r),[e,i]),this.index=i,clearInterval(e.interval),e.$img=t("<img />").css(s).bind("load",function(s){var a=this.width||t(s.target).width();s=this.height||t(s.target).height(),t(this).data("ratio",a/s),t(this).fadeIn(e.options.speed||e.options.fade,function(){n.remove(),e.paused||e.cycle(),t(["after","show"]).each(function(){e.$container.trigger(t.Event("backstretch."+this,r),[e,i])})}),e.resize()}).appendTo(e.$wrap),e.$img.attr("src",e.images[i]),e}},next:function(){return this.show(this.index<this.images.length-1?this.index+1:0)},prev:function(){return this.show(0===this.index?this.images.length-1:this.index-1)},pause:function(){return this.paused=!0,this},resume:function(){return this.paused=!1,this.next(),this},cycle:function(){return 1<this.images.length&&(clearInterval(this.interval),this.interval=setInterval(t.proxy(function(){this.paused||this.next()},this),this.options.duration)),this},destroy:function(e){t(i).off("resize.backstretch orientationchange.backstretch"),clearInterval(this.interval),e||this.$wrap.remove(),this.$container.removeData("backstretch")}};var a,o=navigator.userAgent,h=navigator.platform,c=o.match(/AppleWebKit\/([0-9]+)/),c=!!c&&c[1],d=o.match(/Fennec\/([0-9]+)/),d=!!d&&d[1],p=o.match(/Opera Mobi\/([0-9]+)/),f=!!p&&p[1],u=o.match(/MSIE ([0-9]+)/),u=!!u&&u[1];a=!((-1<h.indexOf("iPhone")||-1<h.indexOf("iPad")||-1<h.indexOf("iPod"))&&c&&534>c||i.operamini&&"[object OperaMini]"==={}.toString.call(i.operamini)||p&&7458>f||-1<o.indexOf("Android")&&c&&533>c||d&&6>d||"palmGetResource"in i&&c&&534>c||-1<o.indexOf("MeeGo")&&-1<o.indexOf("NokiaBrowser/8.5.0")||u&&6>=u)}(jQuery,window);

// Copyright (c) 2012 Florian H., https://github.com/js-coder https://github.com/js-coder/cookie.js cookie.js is released under the MIT/X11 license.
!function(e,t){var n=function(){return n.get.apply(n,arguments)},r=n.utils={isArray:Array.isArray||function(e){return Object.prototype.toString.call(e)==="[object Array]"},isPlainObject:function(e){return!!e&&Object.prototype.toString.call(e)==="[object Object]"},toArray:function(e){return Array.prototype.slice.call(e)},getKeys:Object.keys||function(e){var t=[],n="";for(n in e)e.hasOwnProperty(n)&&t.push(n);return t},escape:function(e){return String(e).replace(/[,;"\\=\s%]/g,function(e){return encodeURIComponent(e)})},retrieve:function(e,t){return e==null?t:e}};n.defaults={},n.expiresMultiplier=86400,n.set=function(n,i,s){if(r.isPlainObject(n))for(var o in n)n.hasOwnProperty(o)&&this.set(o,n[o],i);else{s=r.isPlainObject(s)?s:{expires:s};var u=s.expires!==t?s.expires:this.defaults.expires||"",a=typeof u;a==="string"&&u!==""?u=new Date(u):a==="number"&&(u=new Date(+(new Date)+1e3*this.expiresMultiplier*u)),u!==""&&"toGMTString"in u&&(u=";expires="+u.toGMTString());var f=s.path||this.defaults.path;f=f?";path="+f:"";var l=s.domain||this.defaults.domain;l=l?";domain="+l:"";var c=s.secure||this.defaults.secure?";secure":"";e.cookie=r.escape(n)+"="+r.escape(i)+u+f+l+c}return this},n.remove=function(e){e=r.isArray(e)?e:r.toArray(arguments);for(var t=0,n=e.length;t<n;t++)this.set(e[t],"",-1);return this},n.empty=function(){return this.remove(r.getKeys(this.all()))},n.get=function(e,n){n=n||t;var i=this.all();if(r.isArray(e)){var s={};for(var o=0,u=e.length;o<u;o++){var a=e[o];s[a]=r.retrieve(i[a],n)}return s}return r.retrieve(i[e],n)},n.all=function(){if(e.cookie==="")return{};var t=e.cookie.split("; "),n={};for(var r=0,i=t.length;r<i;r++){var s=t[r].split("=");n[decodeURIComponent(s[0])]=decodeURIComponent(s[1])}return n},n.enabled=function(){if(navigator.cookieEnabled)return!0;var e=n.set("_","_").get("_")==="_";return n.remove("_"),e},typeof define=="function"&&define.amd?define(function(){return n}):typeof exports!="undefined"?exports.cookie=n:window.cookie=n}(document);

/*!
 * FitVids 1.1
 *
 * Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
 * Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
 * Released under the WTFPL license - http://sam.zoy.org/wtfpl/
 */
!function(t){"use strict";t.fn.fitVids=function(e){var i={customSelector:null,ignore:null};if(!document.getElementById("fit-vids-style")){var r=document.head||document.getElementsByTagName("head")[0],a=".fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}",d=document.createElement("div");d.innerHTML='<p>x</p><style id="fit-vids-style">'+a+"</style>",r.appendChild(d.childNodes[1])}return e&&t.extend(i,e),this.each(function(){var e=['iframe[src*="player.vimeo.com"]','iframe[src*="youtube.com"]','iframe[src*="youtube-nocookie.com"]','iframe[src*="kickstarter.com"][src*="video.html"]',"object","embed"];i.customSelector&&e.push(i.customSelector);var r=".fitvidsignore";i.ignore&&(r=r+", "+i.ignore);var a=t(this).find(e.join(","));a=a.not("object object"),a=a.not(r),a.each(function(e){var i=t(this);if(!(i.parents(r).length>0||"embed"===this.tagName.toLowerCase()&&i.parent("object").length||i.parent(".fluid-width-video-wrapper").length)){i.css("height")||i.css("width")||!isNaN(i.attr("height"))&&!isNaN(i.attr("width"))||(i.attr("height",9),i.attr("width",16));var a="object"===this.tagName.toLowerCase()||i.attr("height")&&!isNaN(parseInt(i.attr("height"),10))?parseInt(i.attr("height"),10):i.height(),d=isNaN(parseInt(i.attr("width"),10))?i.width():parseInt(i.attr("width"),10),o=a/d;if(!i.attr("id")){var h="fitvid"+e;i.attr("id",h)}i.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top",100*o+"%"),i.removeAttr("height").removeAttr("width")}})})}}(window.jQuery||window.Zepto);

/**
 * Javascript-Equal-Height-Responsive-Rows
 * https://github.com/Sam152/Javascript-Equal-Height-Responsive-Rows
 */
!function(i){"use strict";i.fn.equalHeight=function(){var t=[];return i.each(this,function(e,n){var r,s=i(n),a="border-box"===s.css("box-sizing")||"border-box"===s.css("-moz-box-sizing");r=a?s.innerHeight():s.height(),t.push(r)}),this.css("height",Math.max.apply(window,t)+"px"),this},i.fn.equalHeightGrid=function(t){var e=this.filter(":visible");e.css("height","auto");for(var n=0;n<e.length;n++)if(n%t===0){for(var r=i(e[n]),s=1;t>s;s++)r=r.add(e[n+s]);r.equalHeight()}return this},i.fn.detectGridColumns=function(){var t=0,e=0,n=this.filter(":visible");return n.each(function(n,r){var s=i(r).offset().top;return 0!==t&&s!==t?!1:(e++,void(t=s))}),e};var t=0;i.fn.responsiveEqualHeightGrid=function(){function e(){var i=n.detectGridColumns();n.equalHeightGrid(i)}var n=this,r=".grids_"+t;return n.data("grids-event-namespace",r),i(window).bind("resize"+r+" load"+r,e),e(),t++,this},i.fn.responsiveEqualHeightGridDestroy=function(){var t=this;return t.css("height","auto"),i(window).unbind(t.data("grids-event-namespace")),this}}(window.jQuery);

jQuery(document).ready(function($) {
    "use strict";

    var _conf = typeof barcelonaParams == 'object' ? barcelonaParams : {},
        _b = $('body'),
        _col_resp = [
            '.sidebar-widget .posts-box-sidebar .col',
            '.footer-widget .posts-box-sidebar .col',
            '.posts-box-6 .col',
            '.posts-box-8 .col',
            '.posts-box-9 .col'
        ],
        _el = {
            root: $('html, body'),
            navbar: $('.navbar'),
            navbar_sticky: $('.navbar-sticky'),
            navbar_nav: $('.navbar-nav'),
            wp_bar: $('#wpadminbar'),
            main: $('#main'),
            footer: $('.footer'),
            par_wrapper: $('.barcelona-parallax-wrapper'),
            par_inner: $('.barcelona-parallax-inner'),
            fimg_sp: $('.fimg-sp'),
            fimg_fp: $('.fimg-fp'),
            fimg_fs: $('.fimg-fs')
        };

    window.requestAnimFrame = (function() {
        return window.requestAnimationFrame    ||
            window.webkitRequestAnimationFrame ||
            window.mozRequestAnimationFrame    ||
            window.oRequestAnimationFrame      ||
            window.msRequestAnimationFrame     ||
            function( callback ){
                window.setTimeout(callback, 1000 / 60);
            };
    })();

    /*
     * Main menu add caret icons
     */
    _el.navbar_nav.find('li').each(function() {
        var t = $(this);
        if(t.hasClass('menu-item-has-children') && !t.hasClass('menu-item-mega-menu')) {
            t.children('a').append($('<span>').addClass('fa fa-caret-right')).append($('<span>').addClass('fa fa-caret-down'));
        }
    });

    /*
     * Mobile nav menu
     */
    _b.on('click', '.navbar-nav li a', function(e) {

        var t = $(this),
            li = t.parent();

        if(!_el.navbar.find('.navbar-toggle').is(':hidden')
            && li.hasClass('menu-item-has-children')
            && !li.hasClass('menu-item-mega-menu')) {

            e.preventDefault();

            if(!li.hasClass('barcelona-tap')) {
                li.addClass('barcelona-tap').children('.sub-menu').show();
            } else {
                li.removeClass('barcelona-tap').find('.sub-menu').hide();
            }

        }

    });

    /*
     * Sticky Navbar
     */
    if(_el.navbar.length && _el.navbar.hasClass('navbar-sticky')) {

        $.lockfixed(_el.navbar, {
            offset: {
                top: function () {
                    var el_wpbar_offset = ( _el.wp_bar.length && _el.wp_bar.css('position') == 'fixed' ) ? _el.wp_bar.height() : 0,
                        el_navbar_offset = _el.navbar.find('.navbar-toggle').is(':hidden') ? ( _el.navbar.children('.container').innerHeight() - _el.navbar.find('.navbar-collapse').outerHeight() ) : 0;
                    return -1 * ( el_navbar_offset - el_wpbar_offset );
                },
                bottom: function () {
                    return _el.navbar.find('.navbar-toggle').is(':hidden') ? _el.footer.height() : 0;
                }
            },
            stuck_class: 'navbar-stuck',
            screen_limit: 0
        });

    }

    /*
     * Sticky Sidebar
     */
    $('.sidebar-sticky').theiaStickySidebar({
        containerSelector: '.row-primary',
        additionalMarginTop: ( _el.wp_bar.length && _el.navbar.length ) ? 100 : 68,
        additionalMarginBottom: 0,
        minWidth: 977
    });

    $(window).load(function() {



        /*
         * Responsive Equal Height
         */
        for( var i in _col_resp ) {
            $(_col_resp[i]).responsiveEqualHeightGrid();
        }

    });

    /*
     * Featured Video fix
     */
    $('.fimg-media-video').fitVids().find('.post-meta').css('visibility','visible');

    /*
     * Post sharing buttons
     */
    _b.on('click', '.post-sharing ul li a', function(e) {

        e.preventDefault();

        var href = $(this).attr('href'),
            title = $(this).attr('title');

        window.open(href, title, 'width=600,height=300');

    });

    /*
     * Activate Boxer Lightbox
     */
    $('.boxer').boxer({
        fixed: true
    });

    $('.gal-img').each(function() {

        var s = $(this),
            t = s.attr('title'),
            p = s.parents('a'),
            pc = p.parents('.gallery-icon'),
            g = s.data('gallery');

        s.removeAttr('data-gallery');

        pc.click(function(){
            $(this).find('a').trigger('click');
        });

        p.attr({'title':t,'data-gallery':g}).boxer({
            fixed: true
        });

    });

    $('.post-content a').each(function() {

        var childImg = $(this).children('img');

        if ( childImg.length > 0 ) {

            var hrefExt = $(this).attr('href').split('.').pop(),
                captionText = $(this).siblings('.wp-caption-text');

            if ( ['jpg','jpeg','png','bmp','gif'].indexOf(hrefExt) != -1 ) {

                var elGal = $(this).parents('.gallery'),
                    imgTitle = childImg.attr('title');

                if (elGal.length > 0) {
                    $(this).attr('data-gallery', elGal.attr('id'));
                }

                if ( captionText.length > 0 ) {
                    $(this).attr('title', captionText.text());
                } else if ( typeof imgTitle != 'undefined' ) {
                    $(this).attr('title', imgTitle);
                }

                $(this).boxer({
                    fixed: true
                });

            }

        }

    });

    /*
     * Post Vote
     */
    $('.btn-vote').on('click', function(e) {

        e.preventDefault();

        var btn = $(this),
            type = btn.data('type'),
            vote_type = btn.data('vote-type'),
            vote_id = vote_type == 'post' ? _conf.post_id : btn.data('vote-id'),
            unique_id = vote_type +'_'+ vote_id,
            cookie_key = 'barcelona_voted_'+ ( vote_type == 'post' ? 'posts' : 'comments' ),
            voted_data = {};

        try {
            voted_data = $.parseJSON(cookie.get( cookie_key )) || {};
        } catch (e) {}

        if(unique_id == null || typeof voted_data[unique_id] != 'undefined' || typeof type == 'undefined' || ['up','down'].indexOf(type) == -1) {
            return voted_data;
        }

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: _conf.ajaxurl,
            data: {
                action: 'barcelona_vote',
                barcelona_nonce: btn.data('nonce'),
                barcelona_post_id: vote_id,
                barcelona_type: type,
                barcelona_vote_type: vote_type
            },
            success: function(r) {

                if(typeof r != 'object') {

                    var vp = btn.parents('.'+ vote_type +'-vote'),
                        el = vp.find('.vote-login'),
                        cl = vote_type == 'post' ? ' col col-sm-12' : '';

                    if ( vote_type == 'comment' && el.length == 0 ) {
                        vp = vp.siblings('.comment-vote');
                        el = vp.find('.vote-login');
                    }

                    if (el.length == 0) {
                        el = $('<div>').addClass('vote-login'+ cl);
                        vp.prepend(el);
                    }

                    el.text(_conf.i18n.login_to_vote);

                    return;

                }

                if(r.status) {

                    var elVal = vote_type == 'post' ? $('.post_vote_' + type + '_val') : btn.find('.vote-num'),
                        voteVal = r['vote_' + type];

                    if (elVal.length > 0 && typeof voteVal != 'undefined') {
                        elVal.text(voteVal);
                    }

                    btn.addClass('btn-voted');

                    var k = '.'+ vote_type +'-vote',
                        p = btn.parents(k);

                    p.add(p.siblings(k)).addClass(vote_type +'-vote-disabled').find('.btn-vote').off('click');

                    if (cookie.enabled()) {
                        voted_data[unique_id] = type;
                        var cookie_params = {};
                        cookie_params[cookie_key] = JSON.stringify(voted_data);
                        cookie.set(cookie_params, {expires: 30});
                    }

                }

            }
        });

    });

    /*
     * Owl Carousel
     */
    var owlEvent = function() {

        var t = $(this),
            c = t.data('controls'),
            owlParams = {
                items: 1,
                slideBy: 1,
                loop: true,
                center: true,
                mouseDrag: false,
                dots: true,
                nav: false,
                navText: ['<span class="fa fa-angle-left"></span>','<span class="fa fa-angle-right"></span>'],
                autoplay: false,
                autoplayHoverPause: true,
                autoplayTimeout: 8000,
                dotsSpeed: 400,
                smartSpeed: 300,
                rtl: false
            }, i;

        for(i in owlParams){
            var z = i;
            if ( i == 'slideBy' ) {
                z = 'slideby';
            }
            var d = t.data(z);
            if(typeof d != 'undefined'){
                owlParams[i] = d;
            }
        }

        owlParams.responsive = {0:{items:1}};

        var bp = t.data('breakpoint');
        if ( typeof bp != 'undefined' ) {
            var bpx = bp.split(','),
                j;
            for(j in bpx){
                var kx = bpx[j].split(':');
                if(kx.length == 2) {
                    owlParams.responsive[kx[0]] = {items: parseInt(kx[1])};
                }
            }
        } else {
            owlParams.responsive[560] = {items:owlParams.items};
        }

        if(typeof c != 'undefined') {
            var cp = t.find(c+' li').length == 0 ? _b : t;
            cp.on('click', c+ ' li', function (e) {
                e.preventDefault();
                var j = $(this).index(),
                    d = j == 0 ? 'next' : 'prev';
                t.trigger(d + '.owl.carousel');
            });
        }

        t.owlCarousel(owlParams);

        t.on('resized.owl.carousel', function(e) {
            $(e.target).find('.fp-box').each(function() {
                var bg = $(this).data('bg');
                if(typeof bg != 'undefined') {
                    $(this).backstretch(bg);
                }
            });
        });

    };

    $('.owl-carousel').each(owlEvent);

    /*
     * Minimize button group on small screens
     */
    var toggle_btn_group = function(e) {

        var t = $(this),
            p = t.parents('.btn-group'),
            b = t.siblings('.btn:not(.active)'),
            ch = typeof p.data('closed-height') == 'undefined' ? p.outerHeight() : p.data('closed-height'), // closed height
            oh = typeof p.data('opened-height') == 'undefined' ? ch + b.outerHeight() * b.length - 1 : p.data('opened-height');

        p.data('closed-height', ch);
        p.data('opened-height', oh);

        var current_status = p.data('current-status');
        if(typeof current_status == 'undefined') {
            current_status = 'closed';
        }

        var new_status = current_status == 'closed' ? 'opened' : 'closed';
        p.data('current-status', new_status);

        p.css({
            '-webkit-transition': 'height 0.2s',
            '-moz-transition': 'height 0.2s',
            '-ms-transition': 'height 0.2s',
            '-o-transition': 'height 0.2s',
            'transition': 'height 0.2s'
        });

        p.css('height', p.data(new_status+'-height'));

    };

    /*
     * Organize Tab Shortcut
     */
    $('.barcelona-sc-tab').each(function() {

        var t = $(this),
            el_bg = t.find('.box-header'),
            el_con = t.find('.tab-content');

        if(el_bg.length == 0) {
            el_bg = $('<div>').addClass('box-header').html($('<div>').addClass('btn-group btn-group-items-'+ el_con.length).attr('role','group'));
            t.prepend(el_bg);
        }

        el_con.each(function(){
            var el_btn = $(this).find('.btn:first-child');
            el_bg.find('.btn-group').append(el_btn);
        });

        el_bg = el_bg.find('.btn-group');
        el_bg.find('.btn:first-child').addClass('active');
        el_bg.append($('<button>').addClass('btn-toggle').html($('<span>').addClass('fa fa-navicon')));
        t.show();

    });

    _b.on('click', '.barcelona-sc-tab .btn-group .btn', function(e) {

        e.preventDefault();

        var self = this,
            t = $(self),
            c = t.data('controls');

        if(typeof c != 'undefined') {
            $('.tab-content'+c).show().siblings('.tab-content').hide();
            t.addClass('active').siblings('.btn').removeClass('active');
        }

        if( ! t.siblings('.btn-toggle').is(':hidden') ) {
            toggle_btn_group.call(self, e);
        }

    });

    /*
     * Module Tabs
     */
    _b.on('click', '.btn-group .btn-toggle', toggle_btn_group);

    _b.on('click', '.posts-box .btn-group .btn', function(e) { // controlled 072015

        e.preventDefault();

        var self = this,
            btn = $(self),
            parent = btn.parents('.posts-box'),
            type = parent.data('type');

        if(btn.hasClass('active')) {

            if( ! btn.siblings('.btn-toggle').is(':hidden') ) {
                toggle_btn_group.call(self, e);
            }

            return;
        }

        if(typeof type != 'undefined' && /^t[0-9]_[0-9]+$/.test(type)) {

            var t = type.split('_'),
                ajaxData = {
                    action: 'barcelona_pb',
                    barcelona_tab: t[0],
                    barcelona_module: t[1],
                    barcelona_page_id: _conf.post_id,
                    barcelona_item_id: btn.data('catid') || btn.index()
                },
                pn = parent.data('post-not');

            if ( typeof pn != 'undefined' ) {
                ajaxData.barcelona_post_not = pn;
            }

            btn.addClass('active').siblings('.btn').removeClass('active');

            if( ! btn.siblings('.btn-toggle').is(':hidden') ) {
                toggle_btn_group.call(self, e);
            }

            var elLoaderName = 'loader-overlay',
                wrapper = parent.find('.posts-wrapper'),
                elLoader = wrapper.find('.'+ elLoaderName);

            if(elLoader.length == 0) {

                var conWrap = $('<div>').addClass('preload-wrap pos-cc'),
                    con = $('<div>').addClass(elLoaderName).append(conWrap).hide(),
                    rots = [null, 90, 180, 270],
                    i, rot;

                for( i in rots ) {
                    rot = rots[i] != null ? ' rot-'+ rots[i] : '';
                    conWrap.append($('<div>').addClass('preload'+ rot)
                        .append($('<div>').addClass('ln-anim-8')));
                }

                wrapper.prepend(con.fadeIn(200));

            } else if (elLoader.is(':hidden')) {

                elLoader.fadeIn(200);

            }

            $.ajax({
                type: 'post',
                dataType: 'html',
                url: _conf.ajaxurl,
                data: ajaxData,
                success: function(r) {

                    if ( [0, '0', ''].indexOf(r) != -1 ) {
                        return;
                    }

                    wrapper.children().not('.'+ elLoaderName).fadeOut(200);
                    wrapper.html(r).children().hide();
                    wrapper.children().fadeIn(200, function() {
                        wrapper.children('.owl-carousel').each(owlEvent);
                    });

                    setTimeout(function(){
                        var i;
                        for(i in _col_resp) {
                            wrapper.children('.col').responsiveEqualHeightGrid();
                        }
                    }, 200);

                }
            });

        }

    });

    /*
     * Search modal
     */
    var searchForm = $('.search-form-full'),
        searchFormClose = searchForm.find('.barcelona-sc-close');

    $('.btn-search').click(function(e) {
        e.preventDefault();
        searchForm.fadeIn(50);
    });

    var closeSearchForm = function() {
        searchForm.fadeOut(50);
    };

    searchForm.add(searchFormClose).click(function() {
        closeSearchForm();
    });

    searchForm.find('.search-form-inner').click(function(e) {
        e.stopPropagation();
    });

    /*
     * Window key & resize events
     */
    $(window).keyup(function(e) {

        if(e.which == 27) {
            closeSearchForm();
        }

    });

    var aggTicking = false,
        aggScrollTop = 0;

    function aggOnResize() {
        aggUpdateElements('resize');
    }

    function aggOnScroll() {

        if(!aggTicking) {
            aggTicking = true;
            requestAnimFrame(aggUpdateElements);
            aggScrollTop = window.pageYOffset;
        }

    }

    function aggUpdateElements(ev) {

        var aggWinHg = window.innerHeight,
            aggWinWd = window.innerWidth,
            aggWpBarHg = _el.wp_bar.length ? (aggWinWd <= 782 ? 46 : 32) : 0;

        if(ev == 'resize' && aggWinHg >= 560) {
            _el.par_wrapper.css('height', aggWinHg);
        }

        if(aggWinWd > 782 && aggScrollTop < aggWinHg && (_el.fimg_sp.length || _el.fimg_fp.length)) {

            var yPos = Math.round( aggScrollTop / 2 * 100 ) / 100;

            aggPrefix(_el.par_inner.find('img'), 'transform', 'translate3d(0, '+ yPos +'px, 0)');

        }

        if(_el.fimg_fp.length || _el.fimg_fs.length) {

            var aggFimgHg = _el.par_wrapper.outerHeight() - _el.navbar.height() - aggWpBarHg;

            if(_el.fimg_fp.length) {
                _el.fimg_fp.find('.fimg-inner').css('height', aggFimgHg);
            }

            if(_el.fimg_fs.length) {
                _el.fimg_fs.find('.fimg-inner').css('height', aggFimgHg);
            }

        }

        aggTicking = false;

    }

    function aggPrefix(obj, prop, value) {
        var prefs = ['webkit', 'moz', 'o', 'ms', ''];
        for (var pref in prefs) {
            obj.css('-'+ prefs[pref] +'-'+ prop, value);
        }
    }

    (function() {

        aggUpdateElements('resize');
        _el.fimg_fp.find('.fimg-inner').show();
        _el.fimg_fs.find('.fimg-inner').show();

    })();

    window.addEventListener('resize', aggOnResize, false);
    window.addEventListener('scroll', aggOnScroll, false);

    _el.root.on('mousewheel DOMMouseScroll', function() {
        _el.root.stop();
    });

});