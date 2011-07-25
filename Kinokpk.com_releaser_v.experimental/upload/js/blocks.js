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


$("li a#myAccount").click(function() {
				// ul#myOptions is the hidden list
				$("ul#myOptions").toggle();
				$(this).toggleClass("active");
				return false;
			});

});

