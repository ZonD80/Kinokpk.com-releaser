<?php

/*
Project: Kinokpk.com releaser
This file is part of Kinokpk.com releaser.
Kinokpk.com releaser is based on TBDev,
originally by RedBeard of TorrentBits, extensively modified by
Gartenzwerg and Yuna Scatari.
Kinokpk.com releaser is free software;
you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
Kinokpk.com is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with Kinokpk.com releaser; if not, write to the
Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
MA  02111-1307  USA
Do not remove above lines!
*/

require "include/bittorrent.php";

dbconn(false);
stdhead();
?>


<table class=main width=750 border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>
<h2>My ISP uses a transparent proxy. What should I do?<a name="proxy"></a></h2>
<table width=100% border=1 cellspacing=0 cellpadding=10><tr><td class=text> 
<i>Caveat: This section should be considered experimental. It is not meant to be authoritative.</i><br />
<br />
Short reply: change to an ISP that does not force a proxy upon you. If you cannot or do not 
want to then read on.
<br />
<br />
<b>What is a proxy?</b>
<br />
<br />
Basically a middleman. When you're browsing a site through a proxy your requests 
are sent to the proxy and the proxy forwards them to the site instead of 
you connecting directly to the site. There are several classifications (the terminology is far from standard):
<ul>
<li><b>Transparent</b> A transparent proxy is one that needs no configuration on the clients. It 
works by automatically redirecting all port 80 traffic to the proxy. (Sometimes used as synonymous for non-anonymous.)</li>
<li><b>Explicit/Voluntary</b> Clients must configure their browsers to use them.</li>
<li><b>Anonymous</b> The proxy sends no client identification to the server. 
(HTTP_X_FORWARDED_FOR header is not sent; the server doesn't see your IP.)</li>
<li><b>Highly Anonymous</b> The proxy sends no client nor proxy identification to the server. 
(HTTP_X_FORWARDED_FOR, HTTP_VIA and HTTP_PROXY_CONNECTION headers are not sent; 
the server doesn't see your IP and doesn't even know you're using a proxy.)</li>
<li><b>Public</b> (Self explanatory)</li>
</ul>

A transparent proxy may or may not be anonymous, and there are several levels of anonymity.
<br />
<br />
<b>How do I find out if I'm behind a (transparent/anonymous) proxy?</b>
<br />
<br />
Try <a href="http://proxyjudge.org/">ProxyJudge</a>. It lists the HTTP headers that the server 
where it is running received from you. The relevant ones are HTTP_CLIENT_IP, 
HTTP_X_FORWARDED_FOR and REMOTE_ADDR.
<br />
<br />
<b>Why is my port listed as '---' even though I'm not NAT/Firewalled?</b>
<br />
<br />
The TorrentBits tracker is quite smart at finding your real IP, but it does need the 
proxy to send the HTTP header HTTP_X_FORWARDED_FOR. If your ISP's proxy does not 
then what happens is that the tracker will interpret the proxy's IP address as the client's IP address. So when you login and the tracker tries to connect to your 
client to see if you're NAT/firewalled it will actually try to connect to the proxy on 
one of the usual BT ports (6881-6999). Naturally the proxy is not listening on those ports, 
the connection will fail and the tracker will think you're NAT/firewalled.
<br />
<br />
<b>Can I bypass my ISP's proxy?</b>
<br />
<br />
If your ISP only allows HTTP traffic through port 80 or blocks the usual proxy ports then you would 
need to use something like <a href="http://www.socks.permeo.com/">socks</a> and that is outside 
the scope of this FAQ.
<br />
<br />
Otherwise you may try the following:
<ul>
<li> Choose any public <b>non-anonymous</b> proxy that does <b>not</b> use port 80 
(e.g. from <a href="http://tools.rosinstrument.com/proxy/">this</a> or 
<a href="http://www.proxy4free.com/index.html">this</a> list).
</li>
<li> Configure your computer to use that proxy. For Windows XP, do <i>Start</i>, <i>Control Panel</i>, 
<i>Internet Options</i>, <i>Connections</i>, <i>LAN Settings<i>, <i>Use a Proxy server</i>, 
<i>Advanced</i> and type in the IP and port of your chosen Proxy. Or from Internet Explorer 
use <i>Tools</i>, <i>Internet Options</i>, ...</li>
<li> (Facultative) Visit <a href="http://proxyjudge.org/">ProxyJudge</a>. If you see 
an HTTP_X_FORWARDED_FOR in the list 
followed by your IP then everything should be ok, otherwise choose another proxy and try again.</li>
<li>Visit TorrentBits. Hopefully the tracker will now pickup your real IP (check your profile to make sure).</li>
<!--
<li>Run your client with the option to report ip (parameter "--ip" for the original client, Prefs, Advanced, Local IP for Shad0w's). </li> 

For future reference, when we'll have a client that allows the user to specify a proxy we can use a better method.
-->
</ul>
Notice that now you will be doing all your browsing through a public proxy, which are typically quite slow. 
Communications between peers do not use port 80 so their speed will not be affected by this, and
should be better than when you were 'unconnectable'. 
<br />
<br />
<b>How do I make my bittorrent client use a proxy?</b>
<br />
<br />
Just configure Windows XP as above. When you configure a proxy for Internet Explorer you're actually configuring 
a proxy for all HTTP traffic (thank Microsoft and their "IE as part of the OS policy"). 
On the other hand if you use another browser (Opera/Mozilla/Firefox) and configure a proxy there you'll be  
configuring a proxy just for that browser. We don't know of any BT client that allows a proxy to be 
specified explicitly. <br />
<br />
<b>Does this apply to other torrent sites?</b>
<br />
<br />
This section was written for TorrentBits, a closed, port 80 tracker. Other trackers may be open or closed, and 
many listen on the default port 6969. The above does <b>not</b> necessarily apply to other trackers.
<br />
</td></tr></table>
</td></tr></table>
<br />
<br />
<table class=main width=750 border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>
<h2>My ISP is blocking TorrentBits! Can I still reach the site?<a name="block"></a></h2>
<table width=100% border=1 cellspacing=0 cellpadding=10><tr>
<td class=text> 
Yes, but you'll have to use a proxy. Follow the instructions in the section on proxies. 
In this case it doesn't matter if the proxy is anonymous or not, or which port it 
listens to.
<br />
<br />
Notice that you will always be listed as an 'unconnectable' client because 
the tracker will be unable to check that you're capable of accepting 
incoming connections.
<br />
</td></tr></table>
</td></tr></table>

<br />
<br />
<table class=main width=750 border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>
<h2>My IP address is dynamic. How do I stay logged in?<a name="ip"></a></h2>
<table width=100% border=1 cellspacing=0 cellpadding=10><tr><td class=text> 
The IP address associated with your account is the one from the computer where you last logged 
in from (either interactively or via cookies). So if your IP changes the tracker will not recognise 
your IP as valid the next time the client tries to connect to it and will therefore refuse the 
connection.
<br />
<br />
The solution is to login again, by browsing to the site or refreshing a site page. Some browsers 
allow you to periodically reload pages automatically:
<ul>
<li><a href="http://www.opera.com">Opera</a>: right-click on page, 'Reload every' ...</li>
<li><a href="http://www.mozilla.org/products/firefox/">Firefox</a> 
(with <a href="http://texturizer.net/firefox/extensions/#reloadevery">Reload Every</a> extension): 
right-click on page, 'Reload every' ...</li>
<li>Suggestions for other browsers are welcome</li>
</ul>
Notice that even if your client stops being able to connect to the tracker in the middle of a 
session it will keep seeding/leeching. It will just not learn about new peers, and so will 
be working in sub optimal conditions.
<br />
</td></tr></table>
</td></tr></table>
<br />
<br />
<table class=main width=750 border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>
<h2></h2>
<table width=100% border=1 cellspacing=0 cellpadding=10><tr><td class=text> 
<br />
</td></tr></table>
</td></tr></table>
<?
stdfoot();
?>