<table id="torrenttable" class="center torrenttable_browse" cellspacing="0" width="100%">
<tbody id="highlighted">

<tr>
{foreach item=row from=$res}
<div class="entry entry-{$row.id} read read-state-locked" id="current-entry">
		<div class="collapsed">
			<div class="entry-icons">
				{if $CURUSER.id}
				<div class="star link"></div>
				[/if]
			</div>
			<div class="entry-date">{mkprettymonth($row.added)}</div>
			<div class="entry-main">
				<a href="{$REL_SEO->make_link("details","id",$row.id,"name",translit($row.name))}" target="_blank" class="entry-original"></a>
				<span class="entry-source-title">{$row.username}</span>
				<div class="entry-secondary">
					<h2 class="entry-title">{$row.name}</h2>
						<span class="entry-secondary-snippet"> - 
							<span class="snippet"> {$row.cat_names}
							</span>
						</span>
				</div>
			</div>
		</div>
	</div>
{/foreach}
</tr>
</tbody>
</table>

<script language="javascript">
<!--
$(document).ready(function(){
	
	$('#torrenttable').evParseTable();
});
	
//-->
</script>
<style type="text/css">
#entries.list .entry {
border-bottom:1px solid #CCCCCC;
margin:0;
overflow:hidden;
padding:0;
}
.samedir #entries.list .collapsed .entry-icons {
left:0.2em;
}
.samedir #entries.list .collapsed .entry-date {
margin-right:2.3em;
margin-top:-19px;
}
#entries.list .collapsed .entry-date {
margin:0;
padding:0;
width:auto;
}
.samedir #entries .collapsed .entry-date {
float:right;
margin-right:0.2em;
padding-right:15px;
text-align:right;
}
#entries .collapsed .entry-date {
direction:ltr;
width:6.5em;
}
.entry .entry-author, .entry-comments .comment-time, .entry .entry-date {
color:#666666;
text-decoration:none;
}
#entries.list .collapsed .entry-main {
float:none;
margin:0 5em 0 0;
padding:0;
}
#entries .collapsed .entry-main {
overflow:hidden;
padding-left:4px;
white-space:nowrap;
}
.entry .entry-main {
margin-left:20px;
margin-right:10px;
overflow:auto;
}
.samedir #entries.single-source .collapsed .entry-secondary {
margin-left:2em !important;
}
.samedir #entries.list .collapsed .entry-secondary {
left:0;
margin:0 9em 0 15em;
padding:0 0 0 1px;
}
#entries.list .collapsed .entry-secondary {
color:#777777;
overflow:hidden;
position:absolute;
top:3px;
white-space:normal;
width:auto;
}
#entries.list .collapsed .entry-secondary .entry-title {
display:inline;
margin:0;
padding:0;
position:static;
width:auto;
}
#entries .read .collapsed .entry-title {
font-weight:normal;
}
#entries .collapsed .entry-title {
color:#000000;
display:inline;
font-size:100%;
font-weight:bold;
margin-right:0.5em;
}
.entry .entry-body, .entry .entry-title, .entry .entry-likers {
max-width:650px;
}
#entries.list .collapsed .entry-secondary {
color:#777777;
white-space:normal;
}
#entries.list .collapsed .entry-secondary .snippet {
float:none;
margin:0;
padding:0;
position:static;
width:auto;
}
.collapsed {
-moz-user-select:none;
background:none repeat scroll 0 0 #FFFFFF;
border:2px solid #FFFFFF;
cursor:pointer;
height:2.2ex;
line-height:2.3ex;
margin:0;
overflow:hidden;
padding:3px 0;
position:relative;
width:auto;
}
#entries.list .collapsed .entry-main .entry-source-title {
color:#555555;
display:block;
font-size:100%;
left:2.1em;
overflow:hidden;
padding:0 1em 0 0;
position:absolute;
top:3px;
white-space:nowrap;
width:11em;
}
.entry .entry-icons .star {
height:17px;
padding:0;
width:15px;
}

.entry .entry-icons .link {
margin-left:0.3em;
margin-top:-0.2em;
}
#entries.list .collapsed .entry-main .entry-original {
float:none;
margin:0;
padding:0;
position:absolute;
top:3px;
white-space:normal;
width:1.3em;
z-index:2;
}
.samedir .search-result .entry-original, .samedir #entries .collapsed .entry-original {
background:url("/pic/action-icons.png") no-repeat scroll left -418px transparent;
right:0.2em;
}
.search-result .entry-original, #entries .collapsed .entry-original {
background-repeat:no-repeat;
height:14px;
margin-top:-7px;
position:absolute;
top:50%;
width:14px;
}
.entry .entry-actions .tag, .entry .entry-icons .star {
background:url("/pic/action-icons.png") no-repeat scroll 0 0 transparent;
white-space:nowrap;
}
.entry .entry-icons .item-star-active {
background-position:-16px -33px;
}
</style>
