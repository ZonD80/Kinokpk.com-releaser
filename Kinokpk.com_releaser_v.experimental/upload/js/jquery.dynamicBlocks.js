/* 
 * jQuery dynamicBlocks v1.0.0
 * Copyright (c) 2008 Taranets Aleksey
 * www: markup-javascript.com
 * Licensed under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 */

jQuery.fn.dynamicBlocks = function (_opt) {
    // defaults options
    var _opt = jQuery.extend({
        opener:'a',
        changeStyle:'left',
        setParam:'0',
        event:'click',
        duration:500
    }, _opt);
    return this.each(function () {
        var _obj = jQuery(this);
        var _defParam = parseInt(_obj.css(_opt.changeStyle));
        var _setParam = parseInt(_opt.setParam);
        var _opener = jQuery(_opt.opener, _obj);
        var _duration = _opt.duration;
        var _style = _opt.changeStyle.toString();

        _obj.addClass("blockDefault")
        // clic event
        if (_opt.opener && _opt.event != 'move') {
            _opener.bind(_opt.event, function () {
                if (!_obj.hasClass('blockChange')) {
                    _obj.addClass('blockChange');
                    _obj.removeClass("blockDefault");
                    eval('_obj.animate({' + _style + ' : ' + _setParam + '}, ' + _duration + ', false)');
                } else {
                    eval('_obj.animate({' + _style + ' : ' + _defParam + '}, ' + _duration + ', false, function(){_obj.removeClass("blockChange"), _obj.addClass("blockDefault")})');
                }
                return false;
            });
            // move event
        } else if (_opt.event == 'move') {
            var _changeX = false, _changeY = false, _revers = 1;
            // set change parametr
            if (_style == 'left' || _style == 'right' || _style == 'marginLeft' || _style == 'marginRight') _changeX = true;
            if (_style == 'top' || _style == 'bottom' || _style == 'marginTop' || _style == 'marginBottom') _changeY = true;
            if (_style == 'bottom' || _style == 'right' || _style == 'marginRight' || _style == 'marginBottom') _revers = -1;

            _opener.click(function () {
                return false
            });
            var _firstDown = true, _xDef, _yDef;
            _opener.bind('mousedown', function (e) {
                if (!e) e = window.event;
                if (_firstDown) {
                    _xDef = e.clientX;
                    _yDef = e.clientY;
                    _firstDown = false;
                }
                $(document).bind('mousemove', function (ev) {
                    if (!ev) ev = window.event;
                    var _x = ev.clientX;
                    var _y = ev.clientY;
                    var _def, _change, _dif;
                    if (_changeX) {
                        _def = _xDef;
                        _change = _x;
                    }
                    if (_changeY) {
                        _def = _yDef;
                        _change = _y;
                    }

                    _dif = _defParam + (_change - _def) * _revers;

                    if (_setParam < _defParam) {
                        if (_dif < _setParam) {
                            _dif = _setParam;
                        }
                        if (_dif >= _defParam) {
                            _dif = _defParam;
                            _obj.removeClass('blockChange');
                            _obj.addClass("blockDefault");
                        }
                        else {
                            _obj.addClass('blockChange');
                            _obj.removeClass("blockDefault");
                        }
                    } else {
                        if (_dif > _setParam) {
                            _dif = _setParam;
                        }
                        if (_dif <= _defParam) {
                            _dif = _defParam;
                            _obj.removeClass('blockChange');
                            _obj.addClass("blockDefault");
                        }
                        else {
                            _obj.addClass('blockChange');
                            _obj.removeClass("blockDefault");
                        }
                    }

                    _obj.css(_style, _dif);

                    return false;
                });
                $(document).bind('mouseup', function (e) {
                    $(document).unbind('mousemove');
                    $(document).unbind('mouseup');
                    return false;
                });
                return false;
            });
        }
    });
}

