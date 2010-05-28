/**
 * $Id: editor_plugin_src.js 520 2008-01-07 16:30:32Z spocke $
 *
 * @author ZonD80
 * @copyright Copyright © 2009, ZonD80, Kinokpk.com releaser, All rights reserved.
 */

(function() {
	tinymce.create('tinymce.plugins.ReltemplatesPlugin', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mceReltemplate', function() {
				ed.windowManager.open({
					file : url + '/reltemplates.php',
					width : 300 + parseInt(ed.getLang('reltemplates.delta_width', 0)),
					height : 600 + parseInt(ed.getLang('reltemplates.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});
   			// Register buttons
			ed.addButton('reltemplates', {title : 'reltemplates.reltemplates_desc', cmd : 'mceReltemplate', image : url + '/img/reltemplate.gif'});
		},

		getInfo : function() {
			return {
				longname : 'Reltemplates',
				author : 'ZonD80',
				authorurl : 'http://www.kinokpk.com',
				infourl : 'http://dev.kinokpk.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('reltemplates', tinymce.plugins.ReltemplatesPlugin);
})();