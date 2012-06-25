<?php
header("Content-Type: text/html; charset=utf-8");
require_once("include/bittorrent.php");
INIT();
if (get_privilege('is_administrator', false)) {
    $line = "1000";
} else {
    $line = "300";
}

if ($CURUSER) {
//delete

    if (isset($_GET['del']) && get_privilege('is_administrator', false) && is_valid_id($_GET['del']))
        $REL_DB->query("DELETE FROM shoutbox WHERE id=" . (int)($_GET['del']));

//update
    if (isset($_GET['edit']) && get_privilege('is_administrator', false) && is_valid_id($_GET['edit'])) {
        $sql = $REL_DB->query("SELECT id,text FROM shoutbox WHERE id=" . (int)($_GET['edit']));
        $res = @mysql_fetch_array($sql);
        if (!empty($res)) {
            ?>
        <meta http-equiv="expires" content="0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        </head>
        <body bgcolor=#F5F4EA>
        <?php
            echo '<form method=post action=shoutbox.php>';
            echo '<input type=hidden name=id value=' . (int)$res['id'] . '>';
            echo $REL_LANG->say_by_key('edit');
            echo '</br><textarea name=text rows=3 cols=80 id=specialbox>' . htmlspecialchars($res['text']) . '</textarea>';
            echo '<br><input type=submit name=save value="' . $REL_LANG->say_by_key('upd') . '" class=btn>';
            echo '</form></body></html>';
            die;
        }
    }

//view
    if (isset($_GET['orig']) && get_privilege('is_administrator', false) && is_valid_id($_GET['orig'])) {
        $sql = $REL_DB->query("SELECT id, orig_text FROM shoutbox WHERE id=" . (int)($_GET['orig']));
        $res = @mysql_fetch_array($sql);
        if (!empty($res)) {
            ?>
        <meta http-equiv="expires" content="0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        </head>
        <body bgcolor=#F5F4EA>
        <?php
            echo 'оригинальный текст.<hr>';
            echo format_comment($res['orig_text']) . "<br><br><br><a href=\"/shoutbox.php\"><input type=submit value=\"Обратно\" class=btn></a></body></html>";
            die;
        }
    }

//update edit
    if (isset($_POST['text']) && get_privilege('is_administrator', false) && is_valid_id($_POST['id'])) {
        $text = trim($_POST['text']);
        $id = (int)$_POST['id'];
        if (strlen($text) > $line) die("Слишком длинный текст");
        if (isset($text) && isset($id) && is_valid_id($id))
            $REL_DB->query("UPDATE shoutbox SET text = " . sqlesc($text) . " WHERE id=" . (int)($id));
    }

// post
    if ($_GET["sent"] == "yes") {
        $text = trim($_GET["shbox_text"]);
        if (strlen($text) > $line) die("Слишком длинный текст");
        if ($text != "") {

            $REL_DB->query("INSERT INTO shoutbox (id,userid, date, text, orig_text) VALUES ('id'," . $CURUSER["id"] . ", " . TIME . ", " . sqlesc($text) . ", " . sqlesc($text) . ")") or sqlerr(__FILE__, __LINE__);
        }
    }

}
?>
<html>
<head>
    <title>ShoutBox</title>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1250">

    <style type="text/css">
        A {
            color: #000000;
            font-weight: bold;
        }

        A:hover {
            color: #FF0000;
        }

        .small {
            font-size: 8pt;
            font-family: tahoma;
        }

        .date {
            font-size: 7pt;
        }
    </style>
    <STYLE>BODY {
        background-color: # ґ ѻ SCROLLBAR-3DLIGHT-COLOR: #004E98;
        SCROLLBAR-ARROW-COLOR: #004E98;
        SCROLLBAR-DARKSHADOW-COLOR: white;
        SCROLLBAR-BASE-COLOR: white;
    }
    </STYLE>
</head>
<body>


<?

$res = $REL_DB->query("SELECT shoutbox.*, users.username, users.class, users.donor, users.warned FROM shoutbox LEFT JOIN users ON shoutbox.userid=users.id ORDER BY date DESC LIMIT 35") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($res) == 0)
    print("\n");
else {
    print("<table  border=0 align='left' class='small'  >\n");

    while ($arr = mysql_fetch_assoc($res)) {
        $usercolor = get_user_class_color($arr["class"], $arr["username"]);
        if (get_privilege('is_administrator', false)) {
            $orig = "";
            if ($arr['text'] != $arr['orig_text'])
                $orig .= "<span class='date'><a href=shoutbox.php?orig=" . $arr[id] . ">(orig)</a></span>\n";

            $del = "<span class='date'><a href=shoutbox.php?del=" . $arr[id] . "><img src=\"pic/warned2.gif\"  border=0></a></span>\n";
            $edit = "<span class='date'><a href=shoutbox.php?edit=" . $arr[id] . "><img src=\"pic/pen.gif\"  border=0></a></span>\n";
        }
        if ($CURUSER)
            $pm = "<span class='date'><a target=_blank href=message.php?action=sendmessage&receiver=" . $arr['userid'] . " title=\"Отправить ЛС\"><img src=\"pic/pn_inbox.gif\" border=\"0\"></a></span>\n";
        $prof = "<span class='date'><a href='userdetails.php?id=" . $arr["userid"] . "' target='_blank'><img src=\"pic/info/guest.gif\"  border=0  title=\"Посмотреть профиль\"></a></span>\n";
        $pic_base_url = 'pic/';
        print("<tr><td>\n<span class='date'>[" . strftime("%d.%m %H:%M", $arr["date"]) . "]</span>\n$del $edit $prof $pm $orig " . ($CURUSER ? "<a href='javascript:window.top.SmileIT(\"<b>" . $arr["username"] . ":</b>\",\"shbox\",\"shbox_text\")'>$usercolor</a>" : "<b>$usercolor</b>") . "\n" .
            ($arr["donor"] ? "<img src='" . $pic_base_url . "star.gif' alt='donate' title='donate'>\n" : "") .
            ($arr["warned"] ? "<img src='" . $pic_base_url . "warned.gif' alt='warn' title='warn'>\n" : "") .
            " " . format_comment($arr["text"]) . "\n</td></tr>\n");
    }
    print("</table>");


}


?>
</body>
</html>