tinyMCEPopup.requireLangPack();

var GraffitiDialog = {
    init:function (ed) {
        tinyMCEPopup.resizeToInnerSize();
    },

    insert:function (file) {
        var ed = tinyMCEPopup.editor, dom = ed.dom;

        tinyMCEPopup.execCommand('mceInsertContent', false, dom.createHTML('img', {
            src:'/graffities/' + file,
            alt:'Graffiti',
            title:'Graffiti',
            border:0,
            style:'display: block; margin-left: auto; margin-right: auto; border: 0pt none;'
        }));

        tinyMCEPopup.close();
    }
};

tinyMCEPopup.onInit.add(GraffitiDialog.init, GraffitiDialog);