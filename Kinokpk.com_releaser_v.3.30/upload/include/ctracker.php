<?php
/**
 * CrackerTracker modified by n-ws-bit and zond80 for kinokpk.com releaser
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
$cracktrack = strtolower(urldecode($_SERVER['QUERY_STRING']));
$wormprotector = array('wget ', ' wget', 'wget(',
                 'cmd=', ' cmd', 'cmd ', 'rush=', 'union ','passhash','union+',
                   'select+','union ', ' union', 'union(', 'union=', 'echr(', ' echr', 'echr ', 'echr=', 
                   'esystem(', 'esystem ', 'cp ', ' cp', 'cp(', 'mdir ', ' mdir', 'mdir(', 
                   'mcd ', 'mrd ', ' mcd', ' mrd', ' rm', 
                   'mcd(', 'mrd(', 'rm(', 'mcd=', 'mrd=', 'mv ', 'rmdir ', 'mv(', 'rmdir(', 
                   'chmod(', 'chmod ', ' chmod', 'chmod(', 'chmod=', 'chown ', 'chgrp ', 'chown(', 'chgrp(', 
                   'locate ', 'grep ', 'locate(', 'grep(', 'diff ', 'kill(', 'killall', 
                   'passwd ', ' passwd', 'passwd(', 'telnet ', 'vi(', 'vi ', 
                   'insert into', 'select ', 'nigga(', ' nigga', 'nigga ', 'fopen', 'fwrite',  
                   '$_request', '$_get', '$request', '$get', '.system', 'http_php', '&aim', ' getenv', 'getenv ', 
                   'new_password', '&iicq', '/etc/password','/etc/shadow', '/etc/groups', '/etc/gshadow', 
                   'HTTP_USER_AGENT', 'HTTP_HOST', '/bin/ps', 'wget ', 'uname\x20-a', '/usr/bin/id', 
                   '/bin/echo', '/bin/kill', '/bin/', '/chgrp', '/chown', '/usr/bin', 'g\+\+', 'bin/python', 
                   'bin/tclsh', 'bin/nasm', 'perl ', 'traceroute ', ' ping ', '/usr/X11R6/bin/xterm', 'lsof ', 
                   '/bin/mail', '.conf', 'motd ', 'http/1.', '.inc.php', 'config.php', 'cgi-', '.eml', 
                   'file\://', 'window.open', '<script>', 'javascript\://','img src', '.jsp','ftp.exe', 
                   'xp_enumdsn', 'xp_availablemedia', 'xp_filelist', 'xp_cmdshell', 'nc.exe', '.htpasswd', 
                   'servlet', '/etc/passwd', 'wwwacl', '~root', '~ftp', '.jsp', 'admin_', '.history', 
                   'bash_history', '.bash_history', '~nobody', 'server-info', 'server-status', 'reboot ', 'halt ', 
                   'powerdown ', '/home/ftp', '/home/www', 'secure_site, ok', 'chunked', 'org.apache', '/servlet/con', 
                   '<script', '/robot.txt' ,'/perl' ,'mod_gzip_status', 'db_mysql.inc', '.inc', 
                   'select from', 'drop ', '.system', 'getenv', 'http_', '_php', 'php_', 'phpinfo()', '<?php', 'rootpath', '?>', 'sql=', 'select * from', '/etc/rc.local');

$checkworm = str_replace($wormprotector, '*', $cracktrack);
if ($cracktrack != $checkworm)
{
	$ip = getip();
	$warning_vars = array('agent' => $_SERVER['HTTP_USER_AGENT'], 'script' => $_SERVER['PHP_SELF'], 'requested uri' => $_SERVER['REQUEST_URI'], 'referrer' => $_SERVER['HTTP_REFERER'], 'query string' => $_SERVER['QUERY_STRING'], 'GET' => $_GET,'POST' => $_POST);
	write_log('<b><font color="red">Attack detected</font>, ip = <a href="'.$REL_SEO->make_link('usersearch','ip',$ip).'">'.$ip.'</a></b><hr/>VARIABLES:<br/><pre>'.var_export($warning_vars,true).'</pre>','attack');
	unset($warning_vars);
}
?>