{if $descr.slogan}<span style="color: #00ff00; font-size: medium;">«{$descr.slogan}»</span><br/>{/if}
<u>Информация о фильме:</u><br/>
<b>Название:</b> {$descr.name}<br/>
{if $descr.original}<b>Оригинальное название:</b> {$descr.original}<br/>{/if}
<b>Год выхода:</b> {$descr.year}<br/>
<b>Жанр:</b> {$descr.genres}<br/>
<b>Режиссер:</b> {$descr.director}<br/>
<b>В ролях:</b> {$descr.roles}<br/>
{if $descr.roles_dubl}<b>Роли дублировали:</b> {$descr.roles_dubl}<br/>{/if}
<br/>
<b>Описание:</b><br/>{$descr.about|nl2br}<br/><br/>

{if $descr.serial}[spoiler=<span
        style="color: #0000ff;">Внимание! Раздача ведётся путём добавления новых серий!</span>]{$descr.serial|nl2br}[/spoiler]
<br/><br/>{/if}



{if $descr.kinopoisk}<a href="http://www.kinopoisk.ru/level/1/film/{$descr.kinopoisk}/"><img
        src="http://www.kinopoisk.ru/rating/{$descr.kinopoisk}.gif"/></a>{/if}
{if $descr.imdb}&nbsp;<a href="http://www.imdb.com/title/tt{$descr.imdb}/"><img
        src="http://imdb.snick.ru/ratefor/03/tt{$descr.imdb}.png"/></a>{/if} <br/><br/>
<b>Выпущено:</b> {$descr.author}<br/>
<b>Продолжительность:</b> {$descr.time}<br/>
<b>Перевод:</b> {$descr.translation}<br/>
<b>Вид субтитров:</b> {$descr.typesub}<br/><br/>
<u>Файл:</u><br/>
<b>Формат:</b> {$descr.video_codec}<br/>
<b>Качество:</b> {$descr.quality}<br/>
<b>Видео:</b> {$descr.video_cadr}, {$descr.video_bitr} kbps avg<br/>
<b>Аудио:</b>
{foreach from=$descr.audio.lang key="k" item="lang"}
Дорожка {$k}: {$descr.audio.lang[$k]}, {$descr.audio.codec[$k]}, {$descr.audio.bitr[$k]} kbps<br/>
{/foreach}<br/>
<br/>
{if $descr.sample}<b>Сэмпл:</b> <a href="{$descr.sample}">Скачать</a>{/if}
{if $descr.group}<br/><b>Релиз група:</b> <img src="{$descr.group}" alt="">{/if}




