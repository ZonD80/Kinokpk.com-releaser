(function () {
    tinymce.create('tinymce.plugins.StampsPlugin', {init:function (a, b) {
        a.addCommand('mceStamp', function () {
            a.windowManager.open({file:b + '/stamps.php', width:300 + parseInt(a.getLang('stamps.delta_width', 0)), height:600 + parseInt(a.getLang('stamps.delta_height', 0)), inline:1}, {plugin_url:b})
        });
        a.addButton('stamps', {title:'stamps.stamps_desc', cmd:'mceStamp', image:b + '/img/stamp.gif'})
    }, getInfo:function () {
        return{longname:'Stamps', author:'ZonD80', authorurl:'http://www.kinokpk.com', infourl:'http://dev.kinokpk.com', version:tinymce.majorVersion + "." + tinymce.minorVersion}
    }});
    tinymce.PluginManager.add('stamps', tinymce.plugins.StampsPlugin)
})();