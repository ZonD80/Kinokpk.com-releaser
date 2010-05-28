<?php
/**
 * Language file for relgroups administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

$tracker_lang['rg_title'] = 'Management release groups';
$tracker_lang['to_rgadmin'] = ' | <a href="rgadmin.php">By the management of release groups</a>';
$tracker_lang['relgroupsadd'] = ' | <a href="rgadmin.php?a=add">Add</a>';
$tracker_lang['spec'] = 'Specialization';
$tracker_lang['no_relgroups'] = 'No release groups <a href="rgadmin.php?a=add">Add</a>';
$tracker_lang['owners'] = 'Owners';
$tracker_lang['amount'] = 'The quantity of a farmed necessary for a subscription.';
$tracker_lang['only_invites'] = 'May subscribe by invitation only';
$tracker_lang['members'] = 'Member';
$tracker_lang['private'] = 'Private (Group Closed)';
$tracker_lang['nonfree'] = 'Pay (For entry into the group have something to pay)';
$tracker_lang['page_pay'] = 'Pages payment<br/><small>(If empty, the "currency" - Farmed)<br/>If the value is blank, the group will automatically become Pay.</small>';
$tracker_lang['subscribe_length'] = 'Subscription Period (0 - indefinitely)';
$tracker_lang['users'] = 'Quantity of subscribers';
$tracker_lang['actions'] = 'Actions';
$tracker_lang['descr'] = 'Description';
$tracker_lang['delete_all_users'] = 'Remove all subscribers';
$tracker_lang['are_you_sure'] = 'Are you sure?';
$tracker_lang['view_users'] = 'View subscribers';
$tracker_lang['add_group'] = 'Add release groups';
$tracker_lang['edit_group'] = 'Edit release groups';
$tracker_lang['continue'] = 'Continue';
$tracker_lang['rg_faq'] = 'Tip: in the field of image indicates a full or relative URL of the image, in the fields of the owners and members of the specified user ID, <b>after a comma, no spaces</b>. In the "payment page" indicates a full or relative path to the payment page/<br/>
To make the user paid subscription to the mailing list you want to run SQL-query:<br/>
<pre>
INSERT INTO rg_subscribes (userid,rgid,valid_until) VALUES (ID_user,ID_release_group,UNIX_time+time_subscription*86400);
</pre>';
$tracker_lang['group_added'] = 'Group successfully added. Now you can go to her page';
$tracker_lang['group_error'] = 'An error occurred in the operations of the group';
$tracker_lang['no_value'] = 'Not specified one of the mandatory values of the form';
$tracker_lang['group_edited'] = 'Group successfully edited. Now you can go to her page';
$tracker_lang['unknown_action'] = 'Unknown action';
$tracker_lang['users_deleted'] = 'All subscribers to the group successfully removed';
$tracker_lang['subscribe_until'] = 'Subscribe to';
$tracker_lang['in_time'] = ', expires ';
$tracker_lang['no_users'] = 'In this release group no subscribers';
$tracker_lang['delete_user_ok'] = 'The user is removed from the group of subscribers';
$tracker_lang['notify_send'] = 'Sent a notify';
$tracker_lang['notify_subject'] = 'Unsubscribe release group';
$tracker_lang['delete_with_notify'] = 'Remove from notifying user';
$tracker_lang['delete_notify'] = 'Dear user!<br/>Administrator group (site) has been discontinued your subscription to releases of "%s"';
$tracker_lang['comma_separated'] = 'User ID, after a comma, <b>no spaces</b>';
$tracker_lang['relgroup_deleted'] = 'Release group is removed, now you can go to the management panel release groups';
?>