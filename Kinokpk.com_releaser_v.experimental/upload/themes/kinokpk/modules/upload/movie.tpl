<input type="hidden" name="descrtpl[type]" value="movie"/>
<tr><td width="" class="heading" valign="top" align="right">Оригинальное название</td><td valign="top" align="left"><input type=text name=descrtpl[original] size=80>
</td></tr>
<tr><td width="" class="heading" valign="top" align="right">Год выхода</td><td valign="top" align="left"><input type=text name=descrtpl[year] size=4><br />Например: <b>2001</b>
</td></tr>
<tr><td width="" class="heading" valign="top" align="right">Жанр</td><td valign="top" align="left"><input type=text name=descrtpl[genres] size=80><br />Например: <b>Комедия,Семейный</b>
</td></tr>
<tr><td width="" class="heading" valign="top" align="right">Рейтинги<br/>Картинки сформируются автоматически, вам необходимо вставить лишь идентификаторы фильмов</td><td valign="top" align="left">ID КиноПоиска&nbsp;<input type=text name=descrtpl[kinopoisk]  value="" size=10>&nbsp;ID IMDB&nbsp;<input type=text name=descrtpl[imdb]  value="" size=10><br />ID кинопоиска можно достать из ссылки фильма на кинопоиске: http://www.kinopoisk.ru/level/1/film/<b>[ID]</b>/<br/>ID IMDB можно достать из ссылки фильма на IMDB: http://www.imdb.com/title/tt<b>[ID]</b>/
</td></tr>
<tr><td width="" class="heading" valign="top" align="right">Выпущено</td><td valign="top" align="left"><input type=text name=descrtpl[author] size=80><br />Например: <b>США, Paramout Pictures</b>
</td></tr>
<tr><td width="" class="heading" valign="top" align="right">Режиссер</td><td valign="top" align="left"><input type=text name=descrtpl[director] size=80>
</td></tr>
<tr><td width="" class="heading" valign="top" align="right">В ролях</td><td valign="top" align="left"><input type=text name=descrtpl[roles] size=80>
</td></tr>
<tr><td width="" class="heading" valign="top" align="right">Роли дублировали</td><td valign="top" align="left"><input type=text name=descrtpl[roles_dubl] size=80>
</td></tr>
<tr><td width="" class="heading" valign="top" align="right">Описание</td><td valign="top" align="left">
<textarea rows="20" cols="120" name="descrtpl[about]" id="about"></textarea></td></tr>
<tr><td width="" class="heading" valign="top" align="right">Продолжительность</td><td valign="top" align="left"><input type=text name=descrtpl[time]  value="" size=10><br />Например: <b>01:35:40</b>
</td></tr>
<tr><td width="" class="heading" valign="top" align="right">Перевод</td><td valign="top" align="left"><input type=text name=descrtpl[translation]  value="" size=80><br />Например: <b>Русский, Украинский (субтитры)</b> или <b>Не требуется</b>
</td></tr>
<tr><td width="" class="heading" valign="top" align="right">Качество видео</td><td valign="top" align="left"><select name="descrtpl[quality]"><option value="">--- Выберите ---</option>
<option value="HDTV">HDTV</option><option value="HDTVRip">HDTVRip</option>
<option value="HD DVDRip">HD DVDRip</option>
<option value="BDRip">BDRip</option>
<option value="DVD 5">DVD 5</option>
<option value="DVD 9">DVD 9</option>
<option value="DVDRip">DVDRip</option><option value="DVDScr">DVDScr</option>
<option value="Scr">Scr</option><option value="SatRip">SatRip</option>
<option value="TVRip">TVRip</option><option value="TC">TC</option>
<option value="TS">TS</option><option value="WP">WP</option>
<option value="VHSRip">VHSRip</option><option value="CAMRip">CAMRip</option></select>
</td></tr>
<tr><td width="" class="heading" valign="top" align="right">Видео</td><td valign="top" align="left">
<table cellpadding="0" cellspacing="3" border="0" class="borderless">
<tr>
    <td><small>кодек</small></td>
    <td><small>размер кадра</small></td>
    <td><small>битрейт</small></td>
</tr>
<tr>
    <td><select name="descrtpl[video_codec]"><option value="">--- Выберите ---</option><option value="DivX 3.x">DivX 3.x</option><option value="DivX 4.x">DivX 4.x</option><option value="DivX 5.x">DivX 5.x</option><option value="DivX 6.x">DivX 6.x</option><option value="XviD">XviD</option><option value="VPx">VPx</option><option value="MPEG 1">MPEG 1</option><option value="MPEG 2 SVCD">MPEG 2 SVCD</option><option value="MPEG 2 DVD">MPEG 2 DVD</option><option value="ASF">ASF</option><option value="WMV">WMV</option><option value="H.26x">H.26x</option></select></td>
    <td><input type="text" name="descrtpl[video_cadr]" size="20" value=""></td>
    <td><input type="text" name="descrtpl[video_bitr]" size="20" value=""> кб/с</td>
</tr>
</table>
</td></tr>
<tr><td width="" class="heading" valign="top" align="right">Аудио<br/><a href="javascript://" onclick="javascript:$('.audiodata').last().after($('.audiodata').first().clone());">Добавить еще 1 дорожку</a></td><td valign="top" align="left">
<table cellpadding="0" cellspacing="3" border="0" class="audiodata">
<tr>
	<td><small>язык</small></td>
    <td><small>перевод</small></td>
    <td><small>аудиокодек</small></td>
    <td><small>битрейт аудио</small></td>
</tr>
<tr>
	<td><select name="descrtpl[audio][lang][]"><option value="">--- Выберите ---</option><option value="русский">русский</option><option value="украинский">украинский</option><option value="английский">английский</option><option value="немецкий">немецкий</option><option value="французский">французский</option></select></td>
    <td><select name="descrtpl[audio][trans][]"><option value="">--- Выберите ---</option><option value="оригинал">(оригинал)</option><option value="дублированный (дубляж)">дублированный (дубляж)</option><option value="многоголосый закадровый">многоголосый закадровый</option><option value="двухголосый закадровый">двухголосый закадровый</option><option value="одноголосый закадровый">одноголосый закадровый</option><option value="Профессиональный (одноголосый)">Профессиональный (одноголосый)</option><option value="(гоблин) Полный ПЭ">(гоблин) Полный ПЭ</option><option value="(гоблин) Божья искра">(гоблин) Божья искра</option></select></td>
    <td><select name="descrtpl[audio][codec][]"><option value="">--- Выберите ---</option><option value="MP3">MP3</option><option value="MP3 Pro">MP3 Pro</option><option value="AC3">AC3</option><option value="AC3 2.0">AC3 2.0</option><option value="AC3 5.1">AC3 5.1</option><option value="WMA">WMA</option><option value="AAC">AAC</option><option value="AAC 5.1">AAC 5.1</option><option value="OGG">OGG</option><option value="MP2">MP2</option><option value="PCM">PCM</option></select></td>
    <td><input type="text" name="descrtpl[audio][bitr][]" size="20" value=""> кб/с</td>
</tr></table>
</td></tr>
<tr><td width="" class="heading" valign="top" align="right">Сэмпл<br/>(ссылка)</td><td valign="top" align="left"><input type=text name=descrtpl[sample] size=80>
</td></tr>
