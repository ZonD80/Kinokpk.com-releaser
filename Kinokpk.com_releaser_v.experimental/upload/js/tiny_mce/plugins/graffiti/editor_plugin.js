/**
 * $Id: editor_plugin_src.js 520 2008-01-07 16:30:32Z spocke $
 *
 * @author ZonD80
 * @copyright Copyright © 2009, ZonD80, Kinokpk.com releaser, All rights reserved.
 */

(function() {
	tinymce.create('tinymce.plugins.GraffitiPlugin', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mceGraffiti', function() {
				ed.windowManager.open({
					file : url + '/graffiti.php',
					width : 610,
					height : 400,
					inline : 1
				}, {
					plugin_url : url
				});
			});
   			// Register buttons
			ed.addButton('graffiti', {title : 'graffiti.graffiti_desc', cmd : 'mceGraffiti', image : url + '/img/graffiti.gif'});
		},

		getInfo : function() {
			return {
				longname : 'Graffiti',
				author : 'ZonD80',
				authorurl : 'http://www.kinokpk.com',
				infourl : 'http://dev.kinokpk.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('graffiti', tinymce.plugins.GraffitiPlugin);
})();