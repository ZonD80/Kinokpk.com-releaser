tinyMCEPopup.requireLangPack();

var ReltemplatesDialog = {
	init : function(ed) {
		tinyMCEPopup.resizeToInnerSize();
	},

	insert : function(file) {
		var ed = tinyMCEPopup.editor, dom = ed.dom;

		tinyMCEPopup.execCommand('mceInsertContent', false, file);
		//ed.execCommand('mceRepaint');

		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(ReltemplatesDialog.init, ReltemplatesDialog);
