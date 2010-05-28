/**
 * $Id: editor_plugin_src.js 520 2008-01-07 16:30:32Z spocke $
 *
 * @author ZonD80
 * @copyright Copyright © 2009, ZonD80, Kinokpk.com releaser, All rights reserved.
 */

(function() {
	tinymce.create('tinymce.plugins.KinopoiskPlugin', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mceKinopoisk', function() {
				ed.windowManager.open({
					file : url + '/kinopoisk.php',
					width : 400 + parseInt(ed.getLang('kinopoisk.delta_width', 0)),
					height : 600 + parseInt(ed.getLang('kinopoisk.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});
   			// Register buttons
			ed.addButton('kinopoisk', {title : 'kinopoisk.kinopoisk_desc', cmd : 'mceKinopoisk', image : url + '/img/kinopoisk.gif'});
		},

		getInfo : function() {
			return {
				longname : 'Kinopoisk parser',
				author : 'ZonD80',
				authorurl : 'http://www.kinokpk.com',
				infourl : 'http://dev.kinokpk.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('kinopoisk', tinymce.plugins.KinopoiskPlugin);
})();