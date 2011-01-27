/**
 * jQuery NiceIt plugin
 *
 * jNiceIt jQuery light-weight plugin which converts boring generic user controls (i.e. text input boxes, textareas, selectboxes, buttons, etc) to a fully customizable controls. 
 * You may use this plugin to emulate any operating system (for example, MacOS, Windows 7) or create your own fantastic UI. 
 * jNiceIt was created with cross-browser functionality in mind and was tested on large forms.
 * The plugin does not use absolute positioning for skinned controls and is fully compatible with fluid layouts.
 *
 * Current version of jNiceIt is a stable release but it does not support theming yet. 
 * You can customize any control by changing one general CSS file only, NO JavaScript modifications are required.
 *
 * @name jquery.nice-it.1.0.js
 * @author ajaxBlender.com - http://www.ajaxBlender.com
 * @version 1.0
 * @date January 13, 2010
 * @category jQuery plugin
 * @copyright (c) 2010 ajaxBlender.com
 * @example Visit http://www.ajaxBlender.com/ for more informations about this jQuery plugin
 */

(function($) {

	/**
	 * $ is an alias to jQuery object
	 *
	 */

	$.fn.NiceIt = function(settings) {
		settings = jQuery.extend({ // Settings
			Version: 		'1.0'
		}, settings);
		
		var jQueryMatchedObj = this; // This, in this context, refer to jQuery object
		
		/**
		 * Initializing the plugin
		 */
		Run(this, jQueryMatchedObj);
		
		/**
		 * Start the jQuery NiceIt plugin
		 *
		 * @param object objForm The object (form) which the user wants to stylish
		 * @param object jQueryMatchedObj The jQuery object with all elements matched
		 */
		 
		function Run(objForm, jQueryMatchedObj) {
			for ( var idx = 0; idx < jQueryMatchedObj.length; idx++ ) {
			
				var form = $(jQueryMatchedObj[idx]);
				
				if(!$(form).attr('id')) { $(form).attr('id', 'fm-' + idx); }
				
				$(form).setTabIndexes();
				$(form).fnReplaceCheckBoxes();
				$(form).fnReplaceRadioButtons();
				$(form).fnReplaceInputBoxes();
				$(form).fnReplaceSelectBoxes();
				$(form).fnReplaceSelectboxesM();
				$(form).fnReplaceTextareas();
				$(form).fnReplaceButtons();
				$(form).fnReplaceFiles();
			}
		}
		
		return;
	};
	
	/*
	 * Set up tab indexes to all form elements for correct tab navigation
	 */
	$.fn.setTabIndexes = function() {
		$(this).find('select, input:not(:hidden), textarea, button').each(function(i, ctrl) {
			$(ctrl).attr('tabindex', (i + 1));
		});
	}
	
	/*
	 * Run replacement Checkboxes
	 */
	$.fn.fnReplaceCheckBoxes = function() {
		var objForm = this;
		$(objForm).find('input[type="checkbox"]').each(function(i, ctrl) {
			$(ctrl).hide();
	        
			var id = 'fmCbx-' + $(objForm).attr('id') + '-' + (i + 1); 

			if($(ctrl).attr('id')) { id = 'fmChbx-' + $(ctrl).attr('id'); }
				        
	        $(ctrl).after('<b class="fmCheckbox' + ($(ctrl).attr('checked') ? ' checked' : '') + '" id="' + id + '">&nbsp;</b>');
	        
	        var nCtrl = $('#' + id);
	        
			if($(ctrl).attr('disabled')) { 
				$(nCtrl).addClass('chbx-disabled');
				return;
			}
			
			$(ctrl).bind('click', function () {
	        	if(!$(ctrl).attr('checked')) { $(nCtrl).removeClass('checked'); } else { $(nCtrl).addClass('checked'); }
	        });
	        
	        $(nCtrl).bind('click', function () {
				if($(ctrl).attr('checked')) {
					$(ctrl).attr('checked', false);
					$(nCtrl).removeClass('checked');
				} else {
					$(ctrl).attr('checked', true);
					$(nCtrl).addClass('checked');
				}
	        });
			/**/	        
	        $('label[for="' + $(ctrl).attr('id') + '"]').bind('click', function () {
				if($(ctrl).attr('checked')) {
					$(ctrl).attr('checked', false);
					$(nCtrl).removeClass('checked');
				} else {
					$(ctrl).attr('checked', true);
					$(nCtrl).addClass('checked');
				}
	        });
	    });
	}
	
	/*
	 * Run replacement of Radio Buttons
	 */
	$.fn.fnReplaceRadioButtons = function() {
		var objForm = this;
		$(objForm).find('input[type="radio"]').each(function(i, ctrl) {
			$(ctrl).hide();

			var id = 'fmRbtn-' + $(objForm).attr('id') + '-' + (i + 1);
			
			if($(ctrl).attr('id')) { id = 'fmRbtn-' + $(ctrl).attr('id'); }

	        $(ctrl).after('<a rel="' + $(ctrl).attr('name') + '" class="fmRadio' + ($(ctrl).attr('checked') ? ' checked' : '') + '" id="' + id + '">&nbsp;</a>');
	        
			var nCtrl = $('#' + id); 
			
			if($(ctrl).attr('disabled')) { 
				$(nCtrl).addClass('rbtn-disabled');
				return;
			}
			
			$(ctrl).bind('click', function () {
				$('.fmRadio[rel="' + $(ctrl).attr('name') + '"]').removeClass('checked');
				$(nCtrl).addClass('checked');
	        });
	        
	        $(nCtrl).bind('click', function () {
				$('.fmRadio[rel="' + $(nCtrl).attr('rel') + '"]').removeClass('checked');
				$('input[name="' + $(nCtrl).attr('rel') + '"]').attr('checked', false);
				
				$(this).addClass('checked');
				$(ctrl).attr('checked', true);
				
				return false;
	        });
	    });			
	}
	
	/*
	 * Run replacement of Inputs
	 */
	$.fn.fnReplaceInputBoxes = function() {
		var objForm = this;
		
		$(objForm).find('input[type="text"],input[type="password"]').each(function (i, ctrl) {
		
			var width = '50%';
	    	
	    	if($(ctrl).css('width') != 'auto' && $(ctrl).parent().css('width') != 'auto') {
		    	width = Math.ceil( 100 * parseFloat($(ctrl).css('width')) / parseFloat($(ctrl).parent().css('width')) ) + '%';
	    	}  
	    	
	    	var id = 'fmInp-' + $(objForm).attr('id') + '-' + (i + 1);
	    	if($(ctrl).attr('id')) { 
	    		id = 'fmInp-' + $(ctrl).attr('id'); 
	    	}
	    	
	    	$(ctrl).width('100%');
	    	$(ctrl).wrap('<span class="fmInput" id="' + id + '"><span></span></span>');
	    	
	    	var nCtrl = $('#' + id);
	    	
	    	$(nCtrl).width(width);
	    	
	    	if($(ctrl).attr('disabled')) { $(nCtrl).addClass('disabled'); }
	    	
	    	$(nCtrl).swapStyles($(ctrl));
	    	
	    	$(ctrl).bind('focus', function () { 
	    		if($(ctrl).val() == $(ctrl).attr('title')) { $(ctrl).val(''); } 
	    		$(nCtrl).CtrlInFocus();
	    	});
			$(ctrl).bind('blur', function () { 
				if($(ctrl).val() == '') { $(ctrl).val($(ctrl).attr('title')); }
				$(nCtrl).CtrlOutFocus();
			});
	    });		
	}
	
	/*
	 * Run replacement of Select Boxes
	 */
	$.fn.fnReplaceSelectBoxes = function() {
		var objForm = this;
		var cite = 'strong span cite';
		
	    $(objForm).find('select').each(function (i, ctrl) {
			
			if($(ctrl).attr('multiple')) { return; }
			
			var width = '50%';
	    	
	    	if($(ctrl).css('width') != 'auto' && $(ctrl).parent().css('width') != 'auto') {
		    	width = Math.ceil( 100 * parseFloat($(ctrl).css('width')) / parseFloat($(ctrl).parent().css('width')) ) + '%';
	    	}
	    	
	    	var id = 'fmCbox-' + $(objForm).attr('id') + '-' + (i + 1);
	    	
	    	if($(ctrl).attr('id')) { 
	    		id = $(ctrl).attr('id');
	    		$(ctrl).attr('id', '');
	    	}
	    	
	    	$(ctrl).width('100%');
	    	
	    	var selectedTxt = ($(ctrl).find('option:selected').text() != '' ? $(ctrl).find('option:selected').text() : '&nbsp;');
	    	
	    	$(ctrl).before('<div class="fmSelect" tabindex="' + $(ctrl).attr('tabindex') + '" id="' + id + '"><strong><span><cite>' + selectedTxt + '</cite></span></strong><ul></ul></div>');
	    	$(ctrl).attr('tabindex', '');
	    	$(ctrl).hide();
	    		
	    	var nCtrl = $('#' + id);
	    	
	    	$(nCtrl).width(width);
	    	$(nCtrl).swapStyles($(ctrl));
	    	
	    	/* Define Events  */
	    	
	    	$(nCtrl).bind('click', function () { $(this).find('ul').show(); });
	    	$(nCtrl).bind('mouseleave', function () { $(this).find('ul').hide(); });
	    	
	    	$(nCtrl).bind('focus', function () { 
	    		$('body').focus();
	    		$('.fmSelect').css('z-index', '100');
	    		$(nCtrl).css('z-index', '1500'); 
	
	    		$(this).CtrlInFocus();
	    	});
	    	
			$(nCtrl).bind('blur', function () { $(this).CtrlOutFocus(); });
	    	
	    	$(ctrl).find('option').each(function (idx, item) {
	    		nCtrl.find('ul').append('<li option="' + $(item).attr('value') + '">' + $(item).text() + '</li>');
	    	});
	    	
	    	nCtrl.find('li').each(function(num, elem) {
	    		$(elem).bind('mouseenter', function() { $(this).addClass('active'); });
	    		$(elem).bind('mouseout', function() { $(this).removeClass('active'); });
	    		
	    		$(elem).bind('click', function() { 
	    			nCtrl.find(cite).text($(this).text());
	    			$(ctrl).val($(this).attr('option'));
	    			nCtrl.find('ul').fadeOut();
	    		});
	    	});
	    	
	    	nCtrl.bind('keydown', function(event) {
	    		var selected = $(ctrl).find('option:selected');
	    		
	    		$(nCtrl).find('ul').hide();
	    		
	    		if(event.keyCode == 38) { // Up Key
	    			if(selected.val() != $(ctrl).find('option:first').val()) {
	    				selected.prev().attr('selected', true);
	    				$(nCtrl).find(cite).text(selected.prev().text());
	    			}   
	    			return false;
	    		} 
	    		
	    		if(event.keyCode == 40) { // Down Key
	    			if(selected.val() != $(ctrl).find('option:last').val()) {
	    				selected.next().attr('selected', true);
	    				$(nCtrl).find(cite).text(selected.next().text());
	    			}   
	    			return false;
	    		}	
	    		
	    		if(event.keyCode == 33) { // PageUp Key
	    			$(ctrl).find('option:first').attr('selected', true);
					$(nCtrl).find(cite).text($(ctrl).find('option:first').text());
					
	    			return false;
	    		}	
	    		
	    		if(event.keyCode == 34) { // PageDown Key
	    			$(ctrl).find('option:last').attr('selected', true);
					$(nCtrl).find(cite).text($(ctrl).find('option:last').text());
	
	    			return false;
	    		}	
	    	});
	    	
	    	$(ctrl).bind('change', function() {
	    		nCtrl.find(cite).text($(this).find('option:selected').text());	
	    	});
	    });
	}
	
	/*
	 * Run replacement of List Boxes
	 */
	$.fn.fnReplaceSelectboxesM = function() {
		var objForm = this;
		
		$(objForm).find('select[multiple]').each(function (i, ctrl) {
	    	
	    	var width = '100%';
			var height = $(ctrl).height();
	    	
	    	if($(ctrl).css('width') != 'auto' && $(ctrl).parent().css('width') != 'auto') {
		    	width = Math.ceil( 100 * parseFloat($(ctrl).css('width')) / parseFloat($(ctrl).parent().css('width')) ) + '%';
	    	} 
	    	
			var id = 'fmMsel-' + $(objForm).attr('id') + '-' + (i + 1);
	    	
	    	if($(ctrl).attr('id')) { 
	    		id = $(ctrl).attr('id');
	    		$(ctrl).attr('id', '');
	    	}
	    	
	    	$(ctrl).wrap('<span class="fmMultipleSelect" id="' + id + '"><span><span><span></span></span></span></span>');
	    	
	    	var nCtrl = $('#' + id);
	    	
	    	$(nCtrl).width(width);
	    	$(ctrl).width('96%');
	    	$(ctrl).height(height);
	    	
	    	/* Define Events  */
	    	
	    	$(ctrl).bind('focus', function () { $(nCtrl).CtrlInFocus(); });
			$(ctrl).bind('blur', function () { $(nCtrl).CtrlOutFocus(); });
	    });
	}
	
	/*
	 * Run replacement of Textareas
	 */
	$.fn.fnReplaceTextareas = function() {
		var objForm = this;
		
		$(objForm).find('textarea').each(function (i, ctrl) {
			
			var width = '50%';
			var height = $(ctrl).height();
	    	
	    	if($(ctrl).css('width') != 'auto' && $(ctrl).parent().css('width') != 'auto') {
		    	width = Math.ceil( 100 * parseFloat($(ctrl).css('width')) / parseFloat($(ctrl).parent().css('width')) ) + '%';
	    	} 
	    	
	    	var id = 'fmTarea-' + $(objForm).attr('id') + '-' + (i + 1);
	    	if($(ctrl).attr('id')) { id = 'fmTarea-' + $(ctrl).attr('id'); }
	    	
	    	$(ctrl).wrap('<span class="fmTextarea" id="' + id + '"><span><span><span></span></span></span></span>');
	    	
	    	var nCtrl = $('#' + id);
	    	$(nCtrl).width(width);
	    	$(ctrl).height(height);
	    	
	    	if($(ctrl).attr('disabled')) { $(nCtrl).addClass('tx-disabled'); }
	    	
	    	$(ctrl).bind('focus', function () { $(nCtrl).CtrlInFocus(); });
			$(ctrl).bind('blur', function () { $(nCtrl).CtrlOutFocus(); });
	    });
	}
	
	/*
	 * Run replacement of Buttons
	 */
	$.fn.fnReplaceButtons = function() {
		var objForm = this;
		
		$(objForm).find('button:not(.fmButton)').each(function(i, ctrl) {
			$(ctrl).addClass('fmButton');
			
			if($(ctrl).attr('type').toLowerCase() == 'submit') { 
				$(ctrl).wrapInner('<strong><span></span></strong>');
			} else { 
				$(ctrl).wrapInner('<span><span></span></span>');
			}
			
			$(ctrl).bind('focus', function () { $(ctrl).CtrlInFocus(); });
			$(ctrl).bind('blur', function () { $(ctrl).CtrlOutFocus(); });
		});
	}
	
	/*
	 * Run replacement of File Inputs
	 */
	$.fn.fnReplaceFiles = function() {
		var objForm = this;
		
		$(objForm).find('input[type="file"]').each(function (i, ctrl) {
			
			var width = '50%';
	    	
	    	if($(ctrl).css('width') != 'auto' && $(ctrl).parent().css('width') != 'auto') {
		    	width = Math.ceil( 100 * parseFloat($(ctrl).css('width')) / parseFloat($(ctrl).parent().css('width')) ) + '%';
	    	} 
	    	
	    	var id = 'fmFinp-' + $(objForm).attr('id') + '-' + (i + 1);
	    	if($(ctrl).attr('id')) { 
	    		id = 'fmFinp-' + $(ctrl).attr('id'); 
	    	}
	    	
	    	$(ctrl).before('<a tabindex="' + $(ctrl).attr('tabindex') + '" class="fnFileInput" id="' + id + '"><span><cite>Not Selected ...</cite><strong>' + ($(ctrl).attr('title') != '' ? $(ctrl).attr('title') : 'Browse ...') + '</strong></span></a>');
	    	$(ctrl).attr('tabindex', 0);
	   		$(ctrl).addClass('fnFileHidden');
	    	
	    	var nCtrl = $('#' + id);
	    	
	   		$(ctrl).appendTo('#' + id + ' span strong');
	    	
	    	$(nCtrl).width(width);
	    	
	    	if($(ctrl).attr('disabled')) { $(nCtrl).addClass('disabled'); }
	    	    	
	    	/* Define Events  */
	    	$(ctrl).bind('change', function () { $(nCtrl).find('cite').text($(this).attr('value')); });
	    	
	    	$(nCtrl).bind('focus', function () { $(nCtrl).CtrlInFocus(); });
			$(nCtrl).bind('blur', function () { $(nCtrl).CtrlOutFocus(); });
	    });
	}
		
	/* 
	 * Service functions 
	 *
	 */

	$.fn.swapStyles = function (objSrc) { // Apply to newely created control old styles (such as margins)
		var styles = new Array('margin-left', 'margin-right', 'margin-top', 'margin-bottom');
		
		$(styles).each(function(idx, property) {
	    	$(this).css(property, $(objSrc).css( property ));
		});
		
		$(objSrc).addClass('fmZero');
	}
	
	$.fn.CtrlInFocus = function() { $(this).addClass('fmInFocus'); }		// Apply fmInFocus class when control get focus 
	$.fn.CtrlOutFocus = function() { $(this).removeClass('fmInFocus'); }	// Apply fmInFocus class when control lose focus
	
})(jQuery); // Call and execute the function immediately passing the jQuery object