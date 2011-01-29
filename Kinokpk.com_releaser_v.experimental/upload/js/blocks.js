function showshides(id)
{
        var klappText = document.getElementById('b' + id);
        var klappBild = document.getElementById('pic' + id);
        if (klappText.style.display == 'block') {
                  klappBild.src = 'pic/plus.gif';
              type = "hide";
        } else {
                  klappBild.src = 'pic/minus.gif';
              type = "show";
        }
    $.get("blocks.php", {"type": type, "bid": id}, function(data){}, 'html');
    $(document).ready(function(){
		$('#b' + id).slideToggle("medium");
	});
}



				jQuery(function () {
				 jQuery(window).scroll(function () {
					if (jQuery(this).scrollTop() != 0) {
						 jQuery('#toTop').fadeIn();
					} else {
						 jQuery('#toTop').fadeOut();
					}
				 });
					 jQuery('#toTop').click(function () {
						 jQuery('body,html').animate({
							 scrollTop: 0
						 },
						 800);
						 });

					});

$(document).ready(function(){
	$('select[name!="type"]').evSboxDecorate({style:true,scroll:true});
	$('.pagetop select.linkselect,.pagebottom select.linkselect').evSboxDecorate({style:true,scroll:true,submitForm:true});
	$('#forum table, #torrenttable').evParseTable();

$(window).scroll(function(){
	$('table#torrenttable, table#forumtable').each(function(){
var $elTop = $('#torrenttable,#forumtable').evElementCoords(); //0>>>361
var $elTopFix = $.evScrollTop("table#torrenttable, table#forumtable");//<>361
	if($elTopFix>0){
			if($elTopFix>=$elTop.top){
				$("#releases-table").find("table#torrenttable").find("tr.top").addClass("fixed");
				$("#forum").find("table#forumtable").find("tr.top").addClass("fixed");
				
			}else{
				$("#releases-table").find("table#torrenttable").find("tr.top").removeClass("fixed");
				$("#forum").find("table#forumtable").find("tr.top").removeClass("fixed");
			
			}
		}
	});
});
/*
if ($("#entries").length>0){
	var $StWidth=$.evScrollHeight("body");//СЂР°Р·РјРµСЂ С‚РµР»Р°
	var $ElHeight=$("#scrollbox").evElementCoords();
		function screenSize() {
			 var h; // h - РІС‹СЃРѕС‚Р° РѕРєРЅР°
			  h = (window.innerHeight ? window.innerHeight : (document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.offsetHeight));
			  return {h:h};
		}	



			if ($StWidth>=screenSize().h){
				var $size = $StWidth-screenSize().h;
				$("#scrollbox").css("height", $ElHeight.height-$size+"px" );
			}else{
				var $size = screenSize().h-$StWidth;$("#scrollbox").css("height", $ElHeight.height+$size+"px" );} 
	$(window).each(function(){
		var $hTop = 20+ $("td.blocks_c").height();
		$("div#main").css("top",$hTop);


	
	});
	$(window).bind('resize', function(){
			if ($StWidth>=screenSize().h){var $size = $StWidth-screenSize().h;$("#entries").css("height", $ElHeight.height-$size+"px" );
			}else{var $size = screenSize().h-$StWidth;$("#entries").css("height", $ElHeight.height+$size+"px" );}

		});

	
	}
	*/
$('#torrenttable').evParseTable();



$("li a#myAccount").click(function() {
				// ul#myOptions is the hidden list
				$("ul#myOptions").toggle();
				$(this).toggleClass("active");
				return false;
			});

});

