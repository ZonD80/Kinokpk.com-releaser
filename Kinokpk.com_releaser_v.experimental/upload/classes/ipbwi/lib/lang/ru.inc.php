<?php
/**
 * @desc            This language-file provides systemMessages from IPBWI in your foreign language.
 * @copyright        2007-2010 IPBWI development team
 * @package            Languages
 * @author            Frutti ($LastChangedBy: matthias $)
 * @license            http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @version            $LastChangedDate: 2009-02-12 03:30:00 +0000 (Do, 12 Feb 2009) $
 * @since            2.07
 * @web                http://ipbwi.com
 */

// Define Encoding and localisation
$liblang['encoding'] = 'WINDOWS-1251';
$liblang['local'] = 'ru_RU';

// attachment
$libLang['attachMimeNotFound'] = 'Запрошенный mimetype не.';
$libLang['attachNotFoundFS'] = 'Прикреплённые файлы не найдены в системе.';
$libLang['attachNotFoundDB'] = 'Прикреплённые файлы не найдены в БД.';
$libLang['attachCreated'] = 'Файл удачно прикреплён.';
$libLang['attachCreationFailed'] = 'Файл не прикреплён.';
$libLang['attachFileNotInUploadDir'] = 'Выбранный файл не найден в директории.';
$libLang['attachFileExtNotExists'] = 'Расширение файла не существует в БД.';
$libLang['attachFileExtNotAllowed'] = 'Расширение файла не разрешено.';
$libLang['attachFileTooBig'] = 'Файл слишком большой.';
$libLang['attachFileExceedsUserSpace'] = 'У вас недостаточно места.';

// captcha
$libLang['badKey'] = 'Ключа не существует.';
$libLang['captchaWrongCode'] = 'Неправильный код.';

// forum
$libLang['catNotExist'] = 'Категория не существует';
$libLang['forumNotExist'] = 'Форум не существует.';

// member
$libLang['badMemID'] = 'Неправильный номер пользователя';
$libLang['badMemPW'] = 'Неправильный Пароль';
$libLang['cfMissing'] = 'Одно или более из необходимых дополнительных полей не обозначено.';
$libLang['cfLength'] = 'Одно или более из необходимых дополнительных полей слишком длинное.';
$libLang['cfInvalidValue'] = 'Неправильное Значение';
$libLang['cfMustFillIn'] = 'Дополнительное поле профиля "%s" должно быть заполнено.';
$libLang['cfCantEdit'] = 'Не могу изменить дополнительно поле "%s".';
$libLang['cfNotExist'] = 'Дополнительное поле профиля "%s" не существует.';
$libLang['accBanned'] = 'Этот пользователь забанен';
$libLang['accUser'] = 'Неверный Логин.';
$libLang['accPass'] = 'Неверный Пароль.';
$libLang['accEmail'] = 'Е-майл адрес неверен.';
$libLang['accTaken'] = 'Логин или е-майл уже используется.';
$libLang['loginNoFields'] = 'Введите логин и пароль.';
$libLang['loginLength'] = 'Логин или пароль слишком длинные.';
$libLang['loginMemberID'] = 'Нет номера пользователя.';
$libLang['loginWrongPass'] = 'Неправильный Пароль.';
$libLang['loginNoMember'] = 'Пользователя не существует.';
$libLang['noAdmin'] = 'Необходимы права администратора.';
$libLang['membersOnly'] = 'Эта функция доступна только зарегистрированным пользователям.';
$libLang['sigTooLong'] = 'Подпись слишком длинная.';
$libLang['groupIcon'] = 'Иконка группы';
$libLang['avatarSuccess'] = 'Аватар удачно обновлён.';
$libLang['avatarError'] = 'Аватар не обновлён.';
$libLang['reg_username'] = 'Username: ';
$libLang['reg_dname'] = 'Display Name: ';

// permissions
$libLang['badPermID'] = 'Неверный номер разрешения';
$libLang['noPerms'] = 'У вас недостаточно прав для выполнения этой операции.';

// pm
$libLang['pmFolderNotExist'] = 'Папка не существует.';
$libLang['pmMsgNoMove'] = 'Не смог перенести сообщение.';
$libLang['pmFolderNoRem'] = 'Папка не может быть удалена.';
$libLang['pmNoRecipient'] = 'Не обозначем адресат.';
$libLang['pmTitle'] = 'Неправильный заголовок.';
$libLang['pmMessage'] = 'Неправильное сообщение.';
$libLang['pmMemNotExist'] = 'Пользователь не существует.';
$libLang['pmMemDisAllowed'] = 'Этому пользователю запрещён доступ к ЛС.';
$libLang['pmMemFull'] = 'Почтовый ящик адресата полон.';
$libLang['pmMemBlocked'] = 'Этот пользователь вас заблокировал.';
$libLang['pmCClimit'] = 'Вы не можете отправить сообщение стольким пользователям.';
$libLang['pmRecDisallowed'] = 'Данный адресат не может использовать систему личных сообщений.';
$libLang['pmRecFull'] = 'Почтовый ящик адресата полон.';
$libLang['pmRecBlocked'] = 'Этот пользователь вас заблокировал.';
$libLang['pmCantSendToSelf'] = 'You cannot send a conversation to yourself';

// poll
$libLang['pollAlreadyVoted'] = 'Вы уже голосовали';
$libLang['pollInvalidVote'] = 'Неверный голос.';
$libLang['pollNotExist'] = 'Голосование не существует.';
$libLang['pollInvalidOpts'] = 'Вы должны обозначить 2 или  %s  вариантов.';
$libLang['pollInvalidQuestions'] = 'Вы должны обозначить 1 и %s  вопросы.';

// topic
$libLang['topicNotExist'] = 'Тема не существует.';
$libLang['topicNoTitle'] = 'Вы не ввели название темы.';

// post
$libLang['floodControl'] = 'Антифлуд - подождите "%s" секунд перед попыткой отправить снова.';
$libLang['postNotExist'] = 'Сообщение не существует.';

// search
$libLang['searchIDnotExist'] = 'Поисковый запрос не найден.';
$libLang['searchNoResults'] = 'Нет результатов.';

// skin
$libLang['skinNotExist'] = 'Тема не существует.';

// tag cloud
$libLang['badTag'] = 'Вы должны написать имя тэга';
$libLang['badDestination'] = 'Вы должны написать Назначение';
$libLang['badTagID'] = 'Вы должны написать номер тэга';

// wordpress
$libLang['wpRegisterNameExists'] = 'Такой логин уже используется на форуме, выберите другой.';
$libLang['wpRegisterEmailExists'] = 'Такой мейл уже используется на форуме, выберите другой.';

// months
$libLang['month_1'] = 'Январь';
$libLang['month_2'] = 'Февраль';
$libLang['month_3'] = 'Март';
$libLang['month_4'] = 'Апрель';
$libLang['month_5'] = 'Май';
$libLang['month_6'] = 'Июнь';
$libLang['month_7'] = 'Июль';
$libLang['month_8'] = 'Август';
$libLang['month_9'] = 'Сентябрь';
$libLang['month_10'] = 'Октябрь';
$libLang['month_11'] = 'Ноябрь';
$libLang['month_12'] = 'Декабрь';


// system messages
$libLang['sysMsg_Success'] = 'Успешно: ';
$libLang['sysMsg_Error'] = 'Ошибка: ';
$libLang['sysMsg_Hidden'] = 'Скрытая заметка: ';
?>