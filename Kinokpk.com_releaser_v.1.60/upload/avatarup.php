<?
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
stdhead();
echo '<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td class="block" width="100%" align="center" valign="middle" ><strong>Загрузка аватара</strong></td></tr></table>';

    $path="./avatars";
    $max_image_width = 120;
    $max_image_height = 120;
    $maxfilesize = 60 * 1024;
    $size = GetImageSize($_FILES['avatar']['tmp_name']);

    if(!file_exists($path)) die("<div class=\"validation-advice\">Пожалуйста, создайте папку <font color=black>".$path."</font> и <a href=?>повторите попытку загрузить файл</a>.</div>");

if(empty($_FILES['avatar']['tmp_name']))
echo "<br><form id=test method=post enctype=multipart/form-data><div class=\"form-row\"><div class=\"field-widget\"><label for=\"avatar\">Выберите аватару</label> : <input type=file name=avatar id=avatar class=\"emtyavatar validate-img validate-img-size\" title=\"Выберите аватарку\"></div></div>
<input type=submit value=Загрузить ></form><br><br><center><font color=green>Подсказка: Аватара должна быть размером не больше ".round($maxfilesize/1024,2)." килобайт<br>и pазмером не больше ".$max_image_width."х".$max_image_height." пикселей</font></center> ";
elseif (($size[0] > $max_image_width ) || ($size[1] > $max_image_height))
echo "<br><div class=\"validation-advice\">Размер вашего аватара ".$size[0]."х".$size[1]." Требуется размер не более ".$max_image_width."х".$max_image_height."  пикселей</div> <a href=?> Повторить попытку?</a></font></b>";
elseif ($_FILES['avatar']['size'] > $maxfilesize) {
echo $_FILES['avatar']['size'];
echo "<br><div class=\"validation-advice\">Размер вашей аватары превышает ".round($maxfilesize/1024,2)." килобайт!</div> <a href=?> Повторить попытку?</a></font></b>";
}else{
if(!copy($_FILES['avatar']['tmp_name'],$path.chr(47).$CURUSER["id"].substr($_FILES['avatar']['name'], strripos($_FILES['avatar']['name'], '.'))))
die("<b><font color=red>Файл не был загружен! Попробуйте <a href=?>повторить попытку</a>!</font></b>");
else
$pathav = "$DEFAULTBASEURL/avatars/".$CURUSER["id"].substr($_FILES['avatar']['name'], strripos($_FILES['avatar']['name'], '.'));
sql_query("UPDATE users SET avatar = '".$pathav."' WHERE id = " . $CURUSER["id"])or sqlerr(__FILE__,__LINE__);
/*sql_query("UPDATE ipb_member_extra SET avatar_type = 'url' WHERE id = " . $CURUSER["id"])or sqlerr(__FILE__,__LINE__);
sql_query("UPDATE ipb_member_extra SET avatar_size = '".$size[0]."x".$size[1]."' WHERE id = " . $CURUSER["id"])or sqlerr(__FILE__,__LINE__);
sql_query("UPDATE ipb_member_extra SET avatar_location = '".$pathav."' WHERE id = " . $CURUSER["id"])or sqlerr(__FILE__,__LINE__);
*/
echo "<center><b><br>Ваша аватара была успешно загружёна на сервер!</font></b></center><hr>Название файла: <b>".$CURUSER["id"].substr($_FILES['avatar']['name'], strripos($_FILES['avatar']['name'], '.'))."</b><br>Размер файла: <b>".round($_FILES['avatar']['size']/1024,2)." кб.</b><hr><center>Аватар автоматически добавлен в профиль пользователя</b></center>";// как на релизере, так и на <a href=\"".$DEFAULTBASEURL."/forums/\">Форуме</a></b></center> ";

}
stdfoot();
?>
<script type="text/javascript">
                        function formCallback(result, form) {
                            window.status = "Проверка заполнения формы '" + form.id + "': результат = " + result;
                        }

                        var valid = new Validation('test', {immediate : true, onFormValidate : formCallback});
                Validation.addAllThese([
                            ['emtyavatar', 'Для продолжения вы должны выбрать аватар!', function(v) {
                return !Validation.get('IsEmpty').test(v);
            }],
                            ['validate-img', 'Загружаемый файл не является рисунком', function (v) {
     return Validation.get('IsEmpty').test(v) ||  /^(.+)\.(jpg|jpeg|png|gif)$/.test(v);
                            }]



                        ]);
                    </script>