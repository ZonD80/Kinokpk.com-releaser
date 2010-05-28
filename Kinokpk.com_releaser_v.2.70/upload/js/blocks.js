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