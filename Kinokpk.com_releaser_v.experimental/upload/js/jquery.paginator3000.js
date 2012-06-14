/**
 * @version 1.2
 * @copyright Copyright (C) 2010-2011 Cyber Applications. All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://www.cyberapp.ru
 * @author email novichkovsergey@yandex.ru
 * @developer Novichkov Sergey (Radik) - novichkovsergey@yandex.ru
 *
 *
 * Paginator 3000 + is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Paginator 3000 + is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Paginator 3000 +. If not, see <http://www.gnu.org/licenses/>.
 *
 */

(function ($) {
    $.event.special.mousewheel = {setup:function () {
        if (this.addEventListener)this.addEventListener('DOMMouseScroll', $.event.special.mousewheel.handler, false);
        this.onmousewheel = $.event.special.mousewheel.handler
    }, teardown:function () {
        if (this.removeEventListener)this.removeEventListener('DOMMouseScroll', $.event.special.mousewheel.handler, false);
        this.onmousewheel = null
    }, handler:function (event) {
        event = $.event.fix(event || window.event);
        event.type = "mousewheel";
        event.delta = 0;
        if (event.wheelDelta < 0 || event.detail > 0)event.delta = -1; else if (event.wheelDelta > 0 || event.detail < 0)event.delta = 1;
        $.event.handle.apply(this, [event]);
        return
    }};
    $.fn.paginator = function (s) {
        function enableSelection(el) {
            el.onselectstart = function () {
                return true
            };
            el.unselectable = "on";
            el.css('-moz-user-select', 'none')
        }

        ;
        function disableSelection(el) {
            document.onselectstart = function () {
                return false
            };
            el.unselectable = "on";
            el.css('-moz-user-select', 'none');
            html.scrollThumb.focus()
        }

        ;
        function getLink(page) {
            return options.baseUrl[0] + options.buildCounter(page) + options.baseUrl[1]
        }

        function setScrollThumbWidth() {
            minWidth = html.scrollThumb.width();
            html.scrollThumb.width((options.pagesSpan / options.pagesTotal * 100) + "%");
            if (html.scrollThumb.width() < minWidth)html.scrollThumb.width(minWidth + 'px')
        }

        ;
        function moveScrollThumb() {
            html.scrollThumb.css("left", html.scrollThumb.xPos + "px")
        }

        function setPageCurrentPointWidth() {
            minWidth = html.pageCurrentMark.width();
            html.pageCurrentMark.css("width", 100 / options.pagesTotal + '%');
            if (html.pageCurrentMark.width() < minWidth)html.pageCurrentMark.css("width", minWidth + 'px')
        }

        ;
        function movePageCurrentPoint() {
            pos = options.pageCurrent / options.pagesTotal * html.table.workWidth;
            pos = options.returnOrder ? html.table.workWidth - pos : pos - html.pageCurrentMark.width();
            pos = pos > html.table.workWidth - html.pageCurrentMark.width() ? html.table.workWidth - html.pageCurrentMark.width() : pos;
            pos = pos < 0 ? 0 : pos;
            html.pageCurrentMark.css('left', pos + 'px')
        }

        ;
        function draw() {
            html.scrollThumb.xPos = html.scrollThumb.xPos > html.table.maxWidth ? html.table.maxWidth : html.scrollThumb.xPos;
            html.scrollThumb.xPos = html.scrollThumb.xPos < 0 ? 0 : html.scrollThumb.xPos;
            xPos = options.returnOrder ? html.table.maxWidth - html.scrollThumb.xPos : html.scrollThumb.xPos;
            percentFromLeft = xPos / html.table.workWidth;
            cellFirstValue = Math.ceil(percentFromLeft * options.pagesTotal);
            cellFirstValue = cellFirstValue < 1 ? 1 : cellFirstValue;
            moveScrollThumb();
            for (i = 0; i < html.tdsPages.length; i++) {
                cellCurrentValue = cellFirstValue + i;
                data = cellCurrentValue == options.pageCurrent ? '<strong>' + cellCurrentValue + '</strong>' : '<a href="' + getLink(cellCurrentValue) + '">' + cellCurrentValue + '</a>';
                index = options.returnOrder ? options.pagesSpan - i - 1 : i;
                html.tdsPages.eq(index).html(data);
                if ($.isFunction(options.clickHandler)) {
                    $('a', html.tdsPages[index]).bind('click', function () {
                        return executeCallBack($(this).text())
                    })
                }
            }
        }

        ;
        function executeCallBack(nextPage) {
            nextPage = parseInt(nextPage);
            lastPage = options.pageCurrent;
            options.pageCurrent = nextPage;
            $links = $(html.holder).find('a[rel!=""]');
            for (i = 0; i < $links.length; i++) {
                pageNumber = parseInt($links.eq(i).attr('rel'));
                if (pageNumber == 1 || pageNumber == options.pagesTotal)continue;
                pageNumber = pageNumber - lastPage + nextPage;
                pageNumber = pageNumber < 1 ? 1 : pageNumber;
                pageNumber = pageNumber > options.pagesTotal ? options.pagesTotal : pageNumber;
                $links.eq(i).attr('rel', pageNumber);
                $links.eq(i).attr('href', getLink(pageNumber))
            }
            movePageCurrentPoint();
            draw();
            return options.clickHandler(options.buildCounter(options.pageCurrent))
        }

        function buildCounter(page) {
            return page
        }

        var options = {pagesTotal:1, pagesSpan:10, pageCurrent:1, baseUrl:document.location.href + '&page=%number%', buildCounter:buildCounter, pageScroll:3, clickHandler:null, returnOrder:false, lang:{next:"Следующая", last:"Последняя", prior:"Предыдущая", first:"Первая", arrowRight:String.fromCharCode(8594), arrowLeft:String.fromCharCode(8592)}, events:{keyboard:true, scroll:true}};
        options = $.extend(true, options, s);
        options.pagesSpan = options.pagesSpan < options.pagesTotal ? options.pagesSpan : options.pagesTotal;
        options.pageCurrent = options.pagesTotal < options.pageCurrent ? options.pagesTotal : options.pageCurrent;
        if (!options.baseUrl.match(/%number%/i))options.baseUrl += '%number%';
        options.baseUrl = options.baseUrl.split('%number%');
        if (!$.isFunction(options.buildCounter))options.buildCounter = buildCounter;
        next_page = (parseInt(options.pageCurrent) < parseInt(options.pagesTotal)) ? parseInt(options.pageCurrent) + 1 : options.pagesTotal;
        next = '<a href="' + getLink(next_page) + '" rel="' + next_page + '">%next%</a>';
        last = '<a href="' + getLink(options.pagesTotal) + '" rel="' + options.pagesTotal + '">%last%</a>';
        prior_page = (parseInt(options.pageCurrent) > 1) ? parseInt(options.pageCurrent) - 1 : 1;
        prior = '<a href="' + getLink(prior_page) + '" rel="' + prior_page + '">%prior%</a>';
        first = '<a href="' + getLink(1) + '" rel="' + 1 + '">%first%</a>';
        top_left = bottom_left = top_right = bottom_right = '';
        if (options.returnOrder) {
            top_left = next.replace(/%next%/, options.lang.arrowLeft + ' ' + options.lang.next);
            bottom_left = last.replace(/%last%/, options.lang.last);
            top_right = prior.replace(/%prior%/, options.lang.prior + ' ' + options.lang.arrowRight);
            bottom_right = first.replace(/%first%/, options.lang.first)
        } else {
            top_right = next.replace(/%next%/, options.lang.next + ' ' + options.lang.arrowRight);
            bottom_right = last.replace(/%last%/, options.lang.last);
            top_left = prior.replace(/%prior%/, options.lang.arrowLeft + ' ' + options.lang.prior);
            bottom_left = first.replace(/%first%/, options.lang.first)
        }
        ;
        tdWidth = Math.ceil(100 / (options.pagesSpan + 2)) + '%';
        code = '<table width="100%">' + '<tr>' + '<td class="left top">' + top_left + '</td>' + '<td class="spaser" />' + '<td rowspan="2" align="center">' + '<table>' + '<tr>';
        for (i = 1; i <= options.pagesSpan; i++) {
            code += '<td width="' + tdWidth + '"><span></span></td>'
        }
        code += '</tr>' + '<tr>' + '<td colspan="' + options.pagesSpan + '">' + '<div class="scroll_bar">' + '<div class="scroll_trough" />' + '<div class="scroll_thumb">' + '<div class="scroll_knob" />' + '</div>' + '<div class="current_page_mark" />' + '</div>' + '</td>' + '</tr>' + '</table>' + '</td>' + '<td class="spaser" />' + '<td class="right top">' + top_right + '</td>' + '</tr>' + '<tr>' + '<td class="left bottom">' + bottom_left + '</td>' + '<td class="spaser" />' + '<td class="spaser" />' + '<td class="right bottom">' + bottom_right + '</td>' + '</tr>' + '</table>';
        $(this).html(code);
        var html = {holder:$(this), table:$('table:last', this), trPages:$('table:last tr:first', this), tdsPages:$('table:last tr:first td span', this), scrollBar:$('div.scroll_trough', this), scrollThumb:$('div.scroll_thumb', this), pageCurrentMark:$('div.current_page_mark', this)};
        setScrollThumbWidth();
        html.table.workWidth = html.table.width();
        html.table.maxWidth = html.table.width() - html.scrollThumb.width();
        html.scrollThumb.xPos = options.returnOrder ? (options.pagesTotal - options.pageCurrent - options.pagesSpan / 2) / options.pagesTotal * html.table.workWidth : (options.pageCurrent - options.pagesSpan / 2) / options.pagesTotal * html.table.workWidth;
        setPageCurrentPointWidth();
        movePageCurrentPoint();
        draw();
        $(window).resize(function () {
            setPageCurrentPointWidth();
            movePageCurrentPoint();
            setScrollThumbWidth()
        });
        if ($.isFunction(options.clickHandler)) {
            $(html.holder).find('a[rel!=""]').bind('click', function (e) {
                return executeCallBack($(this).attr('rel'))
            })
        }
        $(html.scrollThumb).bind('mousedown', function (e) {
            var dx = e.pageX - html.scrollThumb.xPos;
            $(document).bind('mousemove', function (e) {
                html.scrollThumb.xPos = options.returnOrder ? e.pageX - dx : e.pageX - dx;
                draw()
            });
            $(document).bind('mouseup', function () {
                $(document).unbind('mousemove');
                enableSelection(html.holder)
            });
            disableSelection(html.holder)
        });
        $(html.scrollBar).add(html.pageCurrentMark).bind('click', function (e) {
            html.scrollThumb.xPos = e.pageX - $(html.scrollBar).offset().left - Math.ceil(html.scrollThumb.width() / 2);
            moveScrollThumb();
            draw()
        });
        if (options.events.keyboard) {
            $(document).keydown(function (e) {
                if (e.ctrlKey) {
                    switch (e.keyCode ? e.keyCode : e.which ? e.which : null) {
                        case 0x25:
                            el = $(options.returnOrder ? '.right.top a' : '.left.top a', html.holder);
                            $.isFunction(options.clickHandler) ? el.click() : document.location.href = el.attr('href');
                            break;
                        case 0x27:
                            el = $(options.returnOrder ? '.left.top a' : '.right.top a', html.holder);
                            $.isFunction(options.clickHandler) ? el.click() : document.location.href = el.attr('href');
                            break
                    }
                }
            })
        }
        if (options.events.scroll) {
            $(html.holder).bind('mousewheel', function (e) {
                html.scrollThumb.xPos += (e.delta * (options.returnOrder ? -1 : 1)) * (html.table.workWidth / options.pagesTotal);
                draw();
                return false
            })
        }
    }
})(jQuery);