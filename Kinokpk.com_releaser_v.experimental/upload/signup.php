<?php
/**
 * Signup script
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
INIT();

if ($REL_CONFIG['deny_signup'] && !$REL_CONFIG['allow_invite_signup'])
    $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_("Registration disabled by administration."));

if ($CURUSER)
    $REL_TPL->stderr($REL_LANG->say_by_key('error'), sprintf($REL_LANG->say_by_key('signup_already_registered'), $REL_CONFIG['sitename']));

if ($REL_CONFIG['maxusers']) {
    $users = get_row_count("users");
    if ($users >= $REL_CONFIG['maxusers'])
        $REL_TPL->stderr($REL_LANG->say_by_key('error'), sprintf($REL_LANG->say_by_key('signup_users_limit'), number_format($REL_CONFIG['maxusers'])));
}
if (!$_POST["agree"]) {
    $REL_TPL->stdhead("Правила трекера");
    ?>
<form method="post" action="<?php print $REL_SEO->make_link('signup'); ?>">
    <div align="center">
        <fieldset class="fieldset">
            <legend><?php print $REL_LANG->_("Site rules"); ?></legend>
            <table cellpadding="4" cellspacing="0" border="0" style="width: 100%"
                   class="tableinborder">
                <tr>
                    <td class="tablea"><?php print $REL_LANG->_("To proceed registration you must agreee the following terms"); ?>
                        :
                    </td>
                </tr>
                <tr>
                    <td class="tablea"
                        style="font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif">
                        <div class="page"
                             style="border-right: thin inset; padding-right: 6px; border-top: thin inset; padding-left: 6px; padding-bottom: 6px; overflow: auto; border-left: thin inset; padding-top: 1px; border-bottom: thin inset; height: 275px">
                            <p><strong>Правила <?php print $REL_CONFIG['sitename']; ?></strong></p>

                            <p><?php print nl2br('
Настоящие Правила созданы с целью упорядочивания общения на трекере, и являются обязательными для исполнения всеми участниками. 
Администрация оставляет за собой право удалять и редактировать сообщения пользователей  даного трекера.  
Администрация оставляет за собой право отключать от трекера лиц, нарушающих порядок и Правила. 
Кроме того, Администрация оставляет за собой право изменять данные Правила. 

	1. Регистрация. 

Регистрация на нашем трекере является добровольной, она нужна только в том случае, если Вы решили принять более активное участие нашего ресурса (то есть писать сообщения, коментарии, делать свои раздачи, принимать участье в конкурсах и викторинах, а не только скачивать, для этого регистрация не нужна).   

1.1. Администрация трекера оставляет за собой право удалять линки и комментарии, а также ограничивать доступ к трекеру отдельных пользователей. Если Вы считаете, что Администрация поступила несправедливо по отношению к Вам или Вашему релизу - просьба решать подобные вопросы в частном порядке непосредственно с Администрацией трекера - все мы люди и тоже имеем право на ошибку.
1.2. Всё общение должно происходить на дружественной основе. Не допускаются оскорбления пользователей в любой форме. За подобные действия Вы получите предупреждение и ограничение доступа, а это последнее, что мы хотим делать. 
1.3. Языки сайта – русский. Если у Вас на клавиатуре нет русской раскладки пользуйтесь транслитом.

1.4. Общие вопросы по работе трекера обсуждаются на форуме в разделе Тех.Поддержка

1.5. Уведомляйте Администрацию о любых ошибках, проблемах в работе трекера и неточностях. Приветствуются любые предложения, способные улучшить функциональность сайта.

1.6. Категорически запрещено давать пользоваться своим аккаунтом другим людям. Каждый пользователь имеет право зарегистрировать только один аккаунт. Помните, что Вы несете персональную ответственность за свой аккаунт, а также за все действия, произведенные с Вашего аккаунта.

1.7. На трекере, а также в подписи аккаунта категорически запрещается размещать ссылки или текст, похожий на ссылку, на ресурсы схожей тематики. Также запрещается размещать любые сообщения рекламного характера.

1.8. В случае постоянного нарушения данных Правил рецидивист будет забанен - доступ к трекеру с его IP-адреса будет закрыт.

1.9.  Политика сайта обсуждению не подлежит.

1.10. Администрация оставляет за собой право изменять данные Правила. 

	2. Правила комментирования и общения в чате

2.1. Общение на трекере должно происходить только с использованием литературного русского языка. Использование других языков не приветствуется.
 
2.2. Соблюдайте общепринятые правила поведения. Категорически запрещена нецензурная брань во всех её проявлениях и на всех известных языках. Матом являются также слова ВСЕ производные от них. Также к мату приравниваются слова и выражения, сходные по звучанию либо написанию с матерными словами. (Примеры: "заипался", "афуеть", "мляццкий" и т.п.) Является какое-либо слово матом или нет - в конечном итоге определяется Модератором или Администрацией. Если Вы считаете, что Вас оскорбили, обратитесь к Модераторам с жалобой. Будьте взаимно вежливыми и корректными в своих высказываниях.


2.3. Запрещены бессмысленные, бессодержательные реплики, поток повторяющейся или ненужной информации, а также сообщения, не относящиеся к теме общения. Сюда же относятся сообщения, состоящие из одних смайлов. Запрещено использовать больше 3 смайлов подряд в комментарии или сообщении. Использование только заглавных букв в сообщениях также не приветствуется, т.к. они воспринимаются другими пользователями трекера как крик (например, ЧТО МНЕ ДЕЛАТЬ?) и очень бросаются в глаза.

2.4. Запрещено использовать красный цвет при общении на трекере, а также в подписях. Этот цвет является привилегией Администрации.

2.5. Запрещено оскорбление лиц нетрадиционной ориентации, и расовые дискриминации .

2.6. Запрещены ссылки  на варез-порталы.
		'); ?></p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="tablea">
                        <div><label> <input class="tablea" type="checkbox" name="agree"
                                            value="1"> <input type="hidden" name="do" value="register">
                            <strong><?php print $REL_LANG->_("I agree with this rules"); ?></strong>
                        </label></div>
                    </td>
                </tr>
            </table>
        </fieldset>
        <p><input class="tableinborder" type="submit" value="Регистрация"></p>
    </div>
</form>
<?php    $REL_TPL->stdfoot();
    die;
}

$REL_TPL->stdhead($REL_LANG->say_by_key('signup_signup'));

?>
<span style="color: red; font-weight: bold;"><?php print $REL_LANG->say_by_key('signup_use_cookies'); ?></span>

<?php
if ($REL_CONFIG['deny_signup'] && $REL_CONFIG['allow_invite_signup'])
    $REL_TPL->stdmsg($REL_LANG->_("Attention"), $REL_LANG->_("Only invite registrations are allowed!"));
?>
<form method="post" action="<?php print $REL_SEO->make_link('takesignup'); ?>">
    <table border="1" cellspacing=0 cellpadding="10">
        <tr valign=top>
            <td align="right" class="heading"><?php print $REL_LANG->say_by_key('signup_email'); ?></td>
            <td align=left><input type="text" size="40" name="email"/>
                <table width=250 border=0 cellspacing=0 cellpadding=0>
                    <tr>
                        <td class=embedded><font
                            class=small><?php print $REL_LANG->_("This email must be used to login this site.") . ($REL_CONFIG['use_email_act'] ? $REL_LANG->_("<br/>Confirmation letter will be sent to this address") : ''); ?></font>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="right" class="heading"><?php print $REL_LANG->_('Nickname'); ?></td>
            <td align=left><input type="text" size="40" name="wantusername"/></td>
        </tr>
        <tr>
            <td align="right" class="heading"><?php print $REL_LANG->say_by_key('signup_password'); ?></td>
            <td align=left><input type="password" size="40" name="wantpassword"/></td>
        </tr>
        <tr>
            <td align="right" class="heading"><?php print $REL_LANG->say_by_key('signup_password_again'); ?></td>
            <td align=left><input type="password" size="40" name="passagain"/></td>
        </tr>
        <?php
        if ($REL_CONFIG['use_captcha']) {

            require_once('include/recaptchalib.php');
            tr($REL_LANG->_("Are you a human?"), recaptcha_get_html($REL_CONFIG['re_publickey']), 1, 1);

        }

        if ($REL_CONFIG['allow_invite_signup']) {
            tr($REL_LANG->_("Invite code"), "<p>{$REL_LANG->_("If you have an invite code, past it into field below")}</p><input type=\"text\" name=\"invite\" maxlength=\"32\" value=\"" . htmlspecialchars((string)$_GET['h']) . "\" size=\"32\" />", 1);
        }

        $returnto = trim((string)$_GET['returnto']);
        if (isset($returnto))
            print("<input type=\"hidden\" name=\"returnto\" value=\"" . urlencode(strip_tags($returnto)) . "\" />\n");

        ?>
        <tr>
            <td colspan="2" align="center"><input type="submit"
                                                  value="<?php print $REL_LANG->_("Registrer now!"); ?>"
                                                  style='height: 25px'/></td>
        </tr>
    </table>
</form>

<?php
$REL_TPL->stdfoot();

?>