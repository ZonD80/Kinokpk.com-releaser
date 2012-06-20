<input type="hidden" name="descrtpl[type]" value="movie"/>
<tr>
    <td width="" class="heading" valign="top" align="right">Название</td>
    <td valign="top" align="left"><input type=text name=descrtpl[name] size=80>
    </td>
</tr>
<tr>
    <td width="" class="heading" valign="top" align="right">Оригинальное название</td>
    <td valign="top" align="left"><input type=text name=descrtpl[original] size=80>
    </td>
</tr>
<tr>
    <td width="" class="heading" valign="top" align="right">Слоган</td>
    <td valign="top" align="left"><input type=text name=descrtpl[slogan] size=80>
    </td>
</tr>
<tr>
    <td width="" class="heading" valign="top" align="right">Год выхода</td>
    <td valign="top" align="left"><input type=text name=descrtpl[year] size=4><br/>Например: <b>2001</b>
    </td>
</tr>
<tr>
    <td width="" class="heading" valign="top" align="right">Жанр</td>
    <td valign="top" align="left"><input type=text name=descrtpl[genres] size=80><br/>Например: <b>Комедия,Семейный</b>
    </td>
</tr>
<tr>
    <td width="" class="heading" valign="top" align="right">Рейтинги<br/>Картинки сформируются автоматически, вам
        необходимо вставить лишь идентификаторы фильмов
    </td>
    <td valign="top" align="left">ID КиноПоиска&nbsp;<input type=text name=descrtpl[kinopoisk] value="" size=10>&nbsp;ID
        IMDB&nbsp;<input type=text name=descrtpl[imdb] value="" size=10><br/>ID кинопоиска можно достать из ссылки
        фильма на кинопоиске: http://www.kinopoisk.ru/level/1/film/<b>[ID]</b>/<br/>ID IMDB можно достать из ссылки
        фильма на IMDB: http://www.imdb.com/title/tt<b>[ID]</b>/
    </td>
</tr>
<tr>
    <td width="" class="heading" valign="top" align="right">Выпущено</td>
    <td valign="top" align="left"><input type=text name=descrtpl[author] size=80><br/>Например: <b>США, Paramout
        Pictures</b>
    </td>
</tr>
<tr>
    <td width="" class="heading" valign="top" align="right">Режиссер</td>
    <td valign="top" align="left"><input type=text name=descrtpl[director] size=80>
    </td>
</tr>
<tr>
    <td width="" class="heading" valign="top" align="right">В ролях</td>
    <td valign="top" align="left"><input type=text name=descrtpl[roles] size=80>
    </td>
</tr>
<tr>
    <td width="" class="heading" valign="top" align="right">Роли дублировали</td>
    <td valign="top" align="left"><input type=text name=descrtpl[roles_dubl] size=80>
    </td>
</tr>
<tr>
    <td width="" class="heading" valign="top" align="right">Описание</td>
    <td valign="top" align="left">
        <textarea rows="20" cols="120" name="descrtpl[about]" id="about"></textarea></td>
</tr>


<tr>
    <td width="" class="heading" valign="top" align="right">Продолжительность</td>
    <td valign="top" align="left"><input type=text name=descrtpl[time] value="" size=10><br/>Например: <b>01:35:40</b>
    </td>
</tr>

<tr>
    <td width="" class="heading" valign="top" align="right">Раздача</td>
    <td valign="top" align="left"><select name="descrtpl[serial]">
        <option value="">--- Выберите ---</option>
        <option value="">Без добавления новых серий</option>
        <option value="Внимание! Раздача ведётся путём добавления новых серий; при каждом добавлении создётся новый торрент.<br />Чтобы начать докачку новой серии, пользователям необходимо сделать следующее:<br />1) остановить скачивание<br />2) удалить старый торрент у себя из клиента (старые серии удалять не надо)<br />3) скачать новый торрент и запустить его у себя в клиенте вместо старого, при этом указать клиенту путь в старую папку куда и должно происходить скачивание новых серий.<br />Ваш клиент при этом должен произвести хеширование (проверку) старой папки (если не производит сам - помогите ему сделать это), и будет докачивать только те серии которых у вас ещё нет. Старые серии при этом не удаляются, а продолжают раздаваться!<br />Eсли вы уже удалили старые серии, то предотвратить повторную закачку старых серий можно, отжав галочку в соответствовавших местах при запуске нового торрента.">
            Путем добавлением новых серий
        </option>
    </select>
    </td>
</tr>

<tr>
    <td width="" class="heading" valign="top" align="right">Перевод</td>
    <td valign="top" align="left"><select name="descrtpl[translation]">
        <option value="">--- Выберите ---</option>
        <option value="Оригинал">Оригинал</option>
        <option value="Одноголосый закадровый, профессиональный">Одноголосый закадровый, профессиональный</option>
        <option value="Одноголосый закадровый, любительский">Одноголосый закадровый, любительский</option>
        <option value="Двуголосый закадровый, профессиональный">Двуголосый закадровый, профессиональный</option>
        <option value="Двуголосый закадровый, любительский">Двуголосый закадровый, любительский</option>
        <option value="Многоголосый закадровый, профессиональный">Многоголосый закадровый, профессиональный</option>
        <option value="Многоголосый закадровый, любительский">Многоголосый закадровый, любительский</option>
        <option value="Дублированный">Дублированный</option>
        <option value="Отсутствуют">Отсутствуют</option>
        <option value="Немой">Немой</option>
    </select>
    </td>
</tr>


<tr>
    <td width="" class="heading" valign="top" align="right">Вид субтитров</td>
    <td valign="top" align="left"><select name="descrtpl[typesub]">
        <option value="">--- Выберите ---</option>
        <option value="Вшитые отключаемые">Вшитые отключаемые</option>
        <option value="Вшитые неотключаемые">Вшитые неотключаемые</option>
        <option value="Отдельным файлом">Отдельным файлом</option>
        <option value="Отсутствуют">Отсутствуют</option>
    </select>
    </td>
</tr>


<tr>
    <td width="" class="heading" valign="top" align="right">Качество видео</td>
    <td valign="top" align="left"><select name="descrtpl[quality]">
        <option value="">--- Выберите ---</option>
        <option value="HDTV">HDTV</option>
        <option value="HDTVRip">HDTVRip</option>
        <option value="HDRip">HDRip</option>
        <option value="HD DVDRip">HD DVDRip</option>
        <option value="BDRip">BDRip</option>
        <option value="DVD 5">DVD 5</option>
        <option value="DVD 9">DVD 9</option>
        <option value="DVDRip">DVDRip</option>
        <option value="DVDScr">DVDScr</option>
        <option value="Scr">Scr</option>
        <option value="SatRip">SatRip</option>
        <option value="TVRip">TVRip</option>
        <option value="TC">TC</option>
        <option value="TS">TS</option>
        <option value="WP">WP</option>
        <option value="VHSRip">VHSRip</option>
        <option value="CAMRip">CAMRip</option>
    </select>
    </td>
</tr>
<tr>
    <td width="" class="heading" valign="top" align="right">Видео</td>
    <td valign="top" align="left">
        <table cellpadding="0" cellspacing="3" border="0" class="borderless">
            <tr>
                <td>
                    <small>кодек</small>
                </td>
                <td>
                    <small>размер кадра</small>
                </td>
                <td>
                    <small>битрейт</small>
                </td>
            </tr>
            <tr>
                <td><select name="descrtpl[video_codec]">
                    <option value="">--- Выберите ---</option>
                    <option value="DivX 3">DivX 3</option>
                    <option value="DivX 4/5/6">DivX 4/5/6</option>
                    <option value="Xvid">Xvid</option>
                    <option value="MPEG-1">MPEG-1</option>
                    <option value="MPEG-2">MPEG-2</option>
                    <option value="MPEG-4 AVC/H.264">MPEG-4 AVC/H.264</option>
                    <option value="WMV 1/2">WMV 1/2</option>
                    <option value="VC-1 (WMV3/WMVA/WVC1)">VC-1 (WMV3/WMVA/WVC1)</option>
                    <option value="On2 VP6/7/8">On2 VP6/7/8</option>
                    <option value="Другой">Другой</option>
                </select></td>
                <td><input type="text" name="descrtpl[video_cadr]" size="20" value=""></td>
                <td><input type="text" name="descrtpl[video_bitr]" size="20" value=""> кб/с</td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td width="" class="heading" valign="top" align="right">Аудио<br/><a href="javascript://"
                                                                         onclick="javascript:$('.audiodata').last().after($('.audiodata').first().clone());">Добавить
        еще 1 дорожку</a></td>
    <td valign="top" align="left">
        <table cellpadding="0" cellspacing="3" border="0" class="audiodata">
            <tr>
                <td>
                    <small>язык</small>
                </td>

                <td>
                    <small>аудиокодек</small>
                </td>
                <td>
                    <small>битрейт аудио</small>
                </td>
            </tr>
            <tr>
                <td><select name="descrtpl[audio][lang][]">
                    <option value="">--- Выберите ---</option>
                    <option value="русский">русский</option>
                    <option value="украинский">украинский</option>
                    <option value="английский">английский</option>
                    <option value="немецкий">немецкий</option>
                    <option value="французский">французский</option>
                    <option value="французский">итальянский</option>
                </select></td>

                <td><select name="descrtpl[audio][codec][]">
                    <option value="">--- Выберите ---</option>

                    <option value="0">--- Выберите ---</option>
                    <option value="MP2">MP2</option>
                    <option value="MP3">MP3</option>
                    <option value="AC3">AC3</option>
                    <option value="AC3 2.0">AC3 2.0</option>
                    <option value="AC3 5.1">AC3 5.1</option>
                    <option value="AAC">AAC</option>
                    <option value="PCM">PCM</option>
                    <option value="LPCM">LPCM</option>
                    <option value="DTS">DTS</option>
                    <option value="WMA">WMA</option>
                    <option value="OGG">OGG</option>
                </select></td>
                <td><input type="text" name="descrtpl[audio][bitr][]" size="20" value=""> кб/с</td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td width="" class="heading" valign="top" align="right">Сэмпл<br/>(ссылка)</td>
    <td valign="top" align="left"><input type=text name=descrtpl[sample] size=80>
    </td>
</tr>
<tr>
    <td width="" class="heading" valign="top" align="right">Релиз группа(внешняя)</td>
    <td valign="top" align="left"><input type=text name=descrtpl[group] value="" size=80><br/>Ссылка на картинку.
        Например: <b>http://f-torrent.com/pic/reliz-grup/F-Torrent.gif</b>
    </td>
</tr>