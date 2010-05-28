function show_hide_no_img(id)
{
        var klappText = document.getElementById('s' + id);
        var klappBild = document.getElementById('pic' + id);

        if (klappText.style.display == 'none') {
                  klappText.style.display = 'block';
                  // klappBild.src = 'images/blank.gif';
        }
        else {
                  klappText.style.display = 'none';
                  // klappBild.src = 'images/blank.gif';
        }
}

function show_hide(id)
{
        var klappText = document.getElementById('s' + id);
        var klappBild = document.getElementById('pic' + id);

        if (klappText.style.display == 'none') {
                  klappText.style.display = 'block';
                  klappBild.src = 'pic/minus.gif';
                  klappBild.title = 'Скрыть';
        } else {
                  klappText.style.display = 'none';
                  klappBild.src = 'pic/plus.gif';
                  klappBild.title = 'Показать';
        }
}