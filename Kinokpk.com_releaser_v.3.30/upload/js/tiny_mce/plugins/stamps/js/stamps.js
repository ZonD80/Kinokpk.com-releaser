tinyMCEPopup.requireLangPack();

var StampsDialog = {
	init : function(ed) {
		tinyMCEPopup.resizeToInnerSize();
	},

	insert : function(file) {
		var ed = tinyMCEPopup.editor, dom = ed.dom;

		tinyMCEPopup.execCommand('mceInsertContent', false, dom.createHTML('img', {
			src : 'pic/stamp/' + file,
			alt : 'Stamp',
			title : 'Stamp',
			border : 0
		}));

		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(StampsDialog.init, StampsDialog);
