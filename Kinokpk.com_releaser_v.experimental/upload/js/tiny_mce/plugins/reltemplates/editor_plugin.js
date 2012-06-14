(function () {
    tinymce.create('tinymce.plugins.ReltemplatesPlugin', {init:function (a, b) {
        a.addCommand('mceReltemplate', function () {
            a.windowManager.open({file:b + '/reltemplates.php', width:300 + parseInt(a.getLang('reltemplates.delta_width', 0)), height:600 + parseInt(a.getLang('reltemplates.delta_height', 0)), inline:1}, {plugin_url:b})
        });
        a.addButton('reltemplates', {title:'reltemplates.reltemplates_desc', cmd:'mceReltemplate', image:b + '/img/reltemplate.gif'})
    }, getInfo:function () {
        return{longname:'Reltemplates', author:'ZonD80', authorurl:'http://www.kinokpk.com', infourl:'http://dev.kinokpk.com', version:tinymce.majorVersion + "." + tinymce.minorVersion}
    }});
    tinymce.PluginManager.add('reltemplates', tinymce.plugins.ReltemplatesPlugin)
})();