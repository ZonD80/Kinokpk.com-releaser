<input type="hidden" name="descrtpl[type]" value="soft"/>
<tr>
    <td
            width="" class="heading" valign="top" align="right">Название программы
    </td>
    <td valign="top" align="left"><input type=text name=descrtpl[name] size=80>
    </td>
</tr>

<tr>
    <td width=""
        class="heading" valign="top" align="right">Версия программы
    </td>
    <td
            valign="top" align="left"><input type=text name=descrtpl[v]
                                             size=14><br/>Например: <b>3.0.2.1</b>
    </td>
</tr>

<tr>
    <td width=""
        class="heading" valign="top" align="right">Архитектура
    </td>
    <td
            valign="top" align="left"><input type=text name=descrtpl[arh]
                                             size=14><br/>Например: <b>x86</b>
    </td>
</tr>

<tr>
    <td width=""
        class="heading" valign="top" align="right">Формат пакета
    </td>
    <td
            valign="top" align="left"><input type=text name=descrtpl[format]
                                             size=80><br/>Например: <b>rpm,deb</b>
    </td>
</tr>

<tr>
    <td width=""
        class="heading" valign="top" align="right">Язык интерфейса
    </td>
    <td
            valign="top" align="left"><input type=text name=descrtpl[language]
                                             size=14><br/>Например: <b>Русский</b>
    </td>
</tr>

<tr>
    <td width="" class="heading" valign="top" align="right">Лечение</td>
    <td valign="top" align="left"><select name="descrtpl[tablet]">
        <option value="">--- Выберите ---</option>
        <option value="В комплекте">В комплекте</option>
        <option value="Не требуется">Не требуется</option>
        <option value="Требуется, но отсутствует">Требуется, но отсутствует</option>
        <option value="Не требуется (инсталятор уже пролечен)">Не требуется (инсталятор уже пролечен)</option>
        <option value="Другое">Другое</option>
    </select>
    </td>
</tr>

<tr>
    <td width="" class="heading" valign="top" align="right">Тип лекарства</td>
    <td valign="top" align="left"><select name="descrtpl[typetablet]">
        <option value="">--- Выберите ---</option>
        <option value="Кейген">Кейген</option>
        <option value="Патч">Патч</option>
        <option value="Патч-кейген">Патч-кейген</option>
        <option value="Готовый серийник">Готовый серийник</option>
        <option value="Файл лицензии">Файл лицензии</option>
        <option value="Замена файлов">Замена файлов</option>
        <option value="Не требуется">Не требуется</option>
        <option value="Другое">Другое</option>
    </select><br/><b>Обязательно</b> если "лечение" требуется и присутствует в раздаче в любом виде
    </td>
</tr>


<tr>
    <td width="" class="heading" valign="top"
        align="right">Системные требования
    </td>
    <td valign="top" align="left">
        <textarea
                rows="20" cols="80" name="descrtpl[system]"
                id="about"></textarea></td>
</tr>


<tr>
    <td width="" class="heading" valign="top"
        align="right">Описание
    </td>
    <td valign="top" align="left">
        <textarea
                rows="20" cols="80" name="descrtpl[about]"
                id="about"></textarea></td>
</tr>


<tr>
    <td width="" class="heading" valign="top"
        align="right">Дополнительная информация
    </td>
    <td valign="top" align="left">
        <textarea
                rows="20" cols="80" name="descrtpl[aboutt]"
                id="about"></textarea><br/>Дополнительная информация о материале будет свёрнута в спойлере
    </td>
</tr>

<tr>
    <td width="" class="heading" valign="top"
        align="right">Процедура лечения
    </td>
    <td valign="top" align="left">
        <textarea
                rows="20" cols="80" name="descrtpl[abouttablet]"
                id="about"></textarea></td>
</tr>


