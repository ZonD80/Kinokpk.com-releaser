google.load("search", "1", {"nocss":true, "nooldnames":true, "language":"ru", "style":1});

$().ready(function(){
	C_search.init();
})

var C_search={
	init: function(){
		C_search.createSearchEngine('www.torrentsbook.com');
	//	C.search.createSearchEngine('008925083164290612781:v-qk13aiplq'); 
		C_search.drawTagsAttachEvents('#searchBox');
	
	},
	createSearchEngine: function(site){
		C_search.google_web_search = new google.search.WebSearch();
		C_search.google_web_search.setSiteRestriction(site);
		C_search.google_web_search.setResultSetSize(google.search.Search.LARGE_RESULTSET);
		C_search.google_web_search.setSearchCompleteCallback(C_search, C_search.executeQuery);

	
	},


	drawTagsAttachEvents: function(selector){
		C_search.$search_box=$(selector).empty();
		C_search.$form=$(document.createElement('form')).attr({action:'search.php', method:'get', id:'searchForm'}).
			append($(document.createElement('p')).
				append($(document.createElement('input')).attr({className:'txt', name:'q'})).
				append($(document.createElement('input')).attr({className:'sbmt', type:'submit', value:'РќР°Р№С‚Рё'}))).
			appendTo(C_search.$search_box);
		
		if(C_search.initial_query){
			C_search.$form.find('input.txt')[0].value=C_search.initial_query;
			C_search.google_web_search.execute(C_search.initial_query);

			
		}
		C_search.$search_box.
			append($(document.createElement('div')).addClass('results')).
			append($(document.createElement('div')).addClass('pages')).
			append($(document.createElement('div')).addClass('branding'));
	},
	executeQuery: function(){
		
		var $results=C_search.$search_box.children('div.results').empty();
		var $pages=C_search.$search_box.children('div.pages').empty();
		var $branding=C_search.$search_box.children('div.branding');
		if(C_search.google_web_search.results && C_search.google_web_search.results.length){
			if( C_search.initial_page == 1+C_search.google_web_search.cursor.currentPageIndex ){
				var start = C_search.google_web_search.cursor.currentPageIndex*8+1
				var $ol=$(document.createElement('ol')).attr({start:start}).appendTo($results);

			  
				for(var i=0; i<C_search.google_web_search.results.length; i++){
					var result=C_search.google_web_search.results[i];
		
					var $li=$(document.createElement('li')).
						append($(document.createElement('strong')).
							append($(document.createElement('a')).attr({href: result.url }).text( result.titleNoFormatting ))).appendTo($ol);
					if(result.content){
						$li.append($(document.createElement('p')).html( result.content ));
					}
				}
				if(C_search.google_web_search.cursor.pages && C_search.google_web_search.cursor.pages.length > 1){
					$pages.text('РЎС‚СЂР°РЅРёС†С‹:');
					
					for(var i=0; i<C_search.google_web_search.cursor.pages.length; i++){
						var page=C_search.google_web_search.cursor.pages[i];
						if(i==C_search.google_web_search.cursor.currentPageIndex){
							$pages.append($(document.createElement('b')).text( page.label ));
						}else{
							var url = 'search.php?q='+ C_search.initial_query+ '&p='+ page.label;
										
							$pages.append($(document.createElement('a')).attr({href:url}).text( page.label ));
						}
					}
				}
			}else{
				if( 8*(C_search.initial_page-1) < C_search.google_web_search.cursor.estimatedResultCount ){
					C_search.google_web_search.gotoPage(C_search.initial_page - 1);
				}else{
					var url = 'search.php?q='+ C_search.initial_query+ '&p=1';
								
					location.replace(url);
				}
			}
			// РїРѕРєР°Р·С‹РІР°РµРј Р»РѕРіРѕ google
			if($branding.children().length==0){
				google.search.Search.getBranding($branding[0]);
			}
			if($branding[0].offsetHeight==0){
				$branding.show();
			}
		}else{
			// РЅРёС‡РµРіРѕ РЅРµ РЅР°Р№РґРµРЅРѕ
			if(C_search.initial_page==1){
				$results.append($(document.createElement('p')).html('РџРѕ Р·Р°РїСЂРѕСЃСѓ В«<b>'+ C_search.initial_query+ '</b>В» РЅРёС‡РµРіРѕ РЅРµ РЅР°Р№РґРµРЅРѕ'))
				$branding.hide();
			}else{
				// РµСЃР»Рё РёСЃРєР°Р»Рё РЅРµ РЅР° РїРµСЂРІРѕР№ СЃС‚СЂР°РЅРёС†Рµ, РїРѕРїС‹С‚Р°РµРјСЃСЏ РЅР°Р№С‚Рё РЅР° РїРµСЂРІРѕР№
				var url = 'search.php?q='+ C_search.initial_query+ '&p=1';
				location.replace(url);
			}
		}
	}
}

