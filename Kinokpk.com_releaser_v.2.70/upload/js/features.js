$(document).ready(
function(){
  $('div.news-head')
  .click(function() {
    $(this).toggleClass('unfolded');
    $(this).next('div.news-body').slideToggle('slow');
  });
/* $(function() {
  $('a[@href!="http://"]').each(
    function(){
            if(this.href.indexOf(location.hostname) == -1) {
        $(this).addClass('external').attr('target', '_blank');
      }
    }
  )
  });*/
 $('img').error(function() {
        $(this).attr({
        src: 'pic/imgmiss.gif'
        });
    });
        $(window).scroll(function()

        {

        $('#user_bar').css({bottom:-$(window).scrollTop()+"px" });

        });
});

function rateit(rid,rtype,rate) {
no_ajax=true;
      (function($){
      if ($) no_ajax = false;
      field = "#ratearea-"+rid+"-"+rtype;

   $(field).empty();
   $(field).append('<div align="center"><img src="pic/loading.gif" border="0"/></div>');
    $.get("rate.php", { ajax: 1, id: rid, type: rtype, act: rate}, function(data){
   $(field).empty();
   $(field).append(data);
});
})(jQuery);

return no_ajax;

}