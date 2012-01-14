<u>Информация о фильме:</u><br/>
{if $descr.original}<b>Оригинальное название:</b> {$descr.original}<br/>{/if}
<b>Год выхода:</b> {$descr.year}<br/>
<b>Жанр:</b> {$descr.genres}<br/>
<b>Режиссер:</b> {$descr.director}<br/>
<b>В ролях:</b> {$descr.roles}<br/>
{if $descr.roles_dubl}<b>Роли дублировали:</b> {$descr.roles_dubl}<br/>{/if}
<br/>
<b>Описание:</b><br/>{$descr.about|nl2br}<br/><br/>
<b>Рейтинги:</b> {if $descr.kinopoisk}<a href="http://www.kinopoisk.ru/level/1/film/{$descr.kinopoisk}/"><img src="http://www.kinopoisk.ru/rating/{$descr.kinopoisk}.gif"/></a>{/if}
{if $descr.imdb}&nbsp;<a href="http://www.imdb.com/title/tt{$descr.imdb}/"><img src="http://imdb.snick.ru/ratefor/03/tt{$descr.imdb}.png"/></a>{/if} <br/>
<b>Выпущено:</b> {$descr.author}<br/>
<b>Продолжительность:</b> {$descr.time}<br/>
<b>Перевод:</b> {$descr.translation}<br/><br/>

<u>Файл:</u><br/>
<b>Формат:</b> {$descr.video_codec}<br/>
<b>Качество:</b> {$descr.quality}<br/>
<b>Видео:</b> {$descr.video_cadr}, {$descr.video_bitr} kbps avg<br/>
<b>Аудио:</b><br/>
{foreach from=$descr.audio.lang key="k" item="lang"}
Дорожка {$k}: язык: {$lang}, перевод: {$descr.audio.trans[$k]}, кодек: {$descr.audio.codec[$k]}, битрейт: {$descr.audio.bitr[$k]}<br/>
{/foreach}<br/>
<br/>
{if $descr.sample}<b>Сэмпл:</b> <a href="{$descr.sample}">Скачать</a>{/if}





