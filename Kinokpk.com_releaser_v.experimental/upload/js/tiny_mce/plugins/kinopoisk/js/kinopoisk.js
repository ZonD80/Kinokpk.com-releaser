tinyMCEPopup.requireLangPack();

var KinopoiskDialog = {
	init : function(ed) {
		tinyMCEPopup.resizeToInnerSize();
	},

	insert : function(html) {
		var ed = tinyMCEPopup.editor, dom = ed.dom;

					ed.execCommand("mceInsertContent", false, html);
					ed.execCommand('mceRepaint');

		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(KinopoiskDialog.init, KinopoiskDialog);
