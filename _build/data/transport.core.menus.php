<?php
$collection['1']= $xpdo->newObject('modMenu');
$collection['1']->fromArray(array (
  'id' => '1',
  'parent' => '0',
  'action' => '0',
  'text' => 'site',
  'icon' => 'images/misc/logo_tbar.gif',
  'menuindex' => '0',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['2']= $xpdo->newObject('modMenu');
$collection['2']->fromArray(array (
  'id' => '2',
  'parent' => '0',
  'action' => '0',
  'text' => 'components',
  'icon' => 'images/icons/plugin.gif',
  'menuindex' => '1',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['3']= $xpdo->newObject('modMenu');
$collection['3']->fromArray(array (
  'id' => '3',
  'parent' => '1',
  'action' => '1',
  'text' => 'home',
  'icon' => 'images/icons/home.gif',
  'menuindex' => '0',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['4']= $xpdo->newObject('modMenu');
$collection['4']->fromArray(array (
  'id' => '4',
  'parent' => '1',
  'action' => '0',
  'text' => 'preview',
  'icon' => 'images/icons/show.gif',
  'menuindex' => '1',
  'params' => '',
  'handler' => 'window.open("../");',
), '', true, true);
$collection['5']= $xpdo->newObject('modMenu');
$collection['5']->fromArray(array (
  'id' => '5',
  'parent' => '1',
  'action' => '62',
  'text' => 'refresh_site',
  'icon' => 'images/icons/refresh.png',
  'menuindex' => '2',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['6']= $xpdo->newObject('modMenu');
$collection['6']->fromArray(array (
  'id' => '6',
  'parent' => '1',
  'action' => '45',
  'text' => 'search',
  'icon' => 'images/icons/context_view.gif',
  'menuindex' => '3',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['7']= $xpdo->newObject('modMenu');
$collection['7']->fromArray(array (
  'id' => '7',
  'parent' => '1',
  'action' => '0',
  'text' => '-',
  'icon' => '',
  'menuindex' => '4',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['8']= $xpdo->newObject('modMenu');
$collection['8']->fromArray(array (
  'id' => '8',
  'parent' => '0',
  'action' => '0',
  'text' => 'security',
  'icon' => 'images/icons/lock.gif',
  'menuindex' => '2',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['9']= $xpdo->newObject('modMenu');
$collection['9']->fromArray(array (
  'id' => '9',
  'parent' => '0',
  'action' => '0',
  'text' => 'tools',
  'icon' => 'images/icons/menu_settings.gif',
  'menuindex' => '3',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['10']= $xpdo->newObject('modMenu');
$collection['10']->fromArray(array (
  'id' => '10',
  'parent' => '0',
  'action' => '0',
  'text' => 'reports',
  'icon' => 'images/icons/menu_settings16.gif',
  'menuindex' => '4',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['11']= $xpdo->newObject('modMenu');
$collection['11']->fromArray(array (
  'id' => '11',
  'parent' => '0',
  'action' => '0',
  'text' => 'profile',
  'icon' => 'images/icons/user_go.png',
  'menuindex' => '5',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['12']= $xpdo->newObject('modMenu');
$collection['12']->fromArray(array (
  'id' => '12',
  'parent' => '1',
  'action' => '44',
  'text' => 'add_document',
  'icon' => 'images/icons/folder_page_add.png',
  'menuindex' => '5',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['13']= $xpdo->newObject('modMenu');
$collection['13']->fromArray(array (
  'id' => '13',
  'parent' => '1',
  'action' => '44',
  'text' => 'add_weblink',
  'icon' => 'images/icons/link_add.png',
  'menuindex' => '6',
  'params' => '&class_key=modWebLink',
  'handler' => '',
), '', true, true);
$collection['14']= $xpdo->newObject('modMenu');
$collection['14']->fromArray(array (
  'id' => '14',
  'parent' => '1',
  'action' => '0',
  'text' => '-',
  'icon' => '',
  'menuindex' => '7',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['15']= $xpdo->newObject('modMenu');
$collection['15']->fromArray(array (
  'id' => '15',
  'parent' => '1',
  'action' => '0',
  'text' => 'logout',
  'icon' => 'images/icons/unzip.gif',
  'menuindex' => '8',
  'params' => '',
  'handler' => 'MODx.msg.confirm({
title: _(\'logout\')
,text: _(\'logout_confirm\')
,connector: MODx.config.connectors_url+\'security/logout.php\'
,params: {
action: \'logout\'
}
,scope: this
,success: function(r) {
    location.href = \'./\';
}
});',
), '', true, true);
$collection['17']= $xpdo->newObject('modMenu');
$collection['17']->fromArray(array (
  'id' => '17',
  'parent' => '2',
  'action' => '0',
  'text' => '-',
  'icon' => '',
  'menuindex' => '1',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['18']= $xpdo->newObject('modMenu');
$collection['18']->fromArray(array (
  'id' => '18',
  'parent' => '8',
  'action' => '53',
  'text' => 'user_management_title',
  'icon' => 'images/icons/user.gif',
  'menuindex' => '0',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['19']= $xpdo->newObject('modMenu');
$collection['19']->fromArray(array (
  'id' => '19',
  'parent' => '8',
  'action' => '66',
  'text' => 'user_group_management_title',
  'icon' => 'images/icons/mnu_users.gif',
  'menuindex' => '1',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['20']= $xpdo->newObject('modMenu');
$collection['20']->fromArray(array (
  'id' => '20',
  'parent' => '8',
  'action' => '48',
  'text' => 'access_permissions',
  'icon' => 'images/icons/password.gif',
  'menuindex' => '2',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['22']= $xpdo->newObject('modMenu');
$collection['22']->fromArray(array (
  'id' => '22',
  'parent' => '9',
  'action' => '0',
  'text' => 'remove_locks',
  'icon' => 'images/ext/default/grid/hmenu-unlock.png',
  'menuindex' => '0',
  'params' => '',
  'handler' => 'e.preventDefault();
			MODx.msg.confirm({
				title: _(\'remove_locks\')
				,text: _(\'confirm_remove_locks\')
				,connector: MODx.config.connectors_url+\'system/remove_locks.php\'
				,params: {
					action: \'remove\'
				}
				,scope: this
				,success: function(r) {
					navtree.refresh();
				}
			});',
), '', true, true);
$collection['23']= $xpdo->newObject('modMenu');
$collection['23']->fromArray(array (
  'id' => '23',
  'parent' => '9',
  'action' => '0',
  'text' => '-',
  'icon' => '',
  'menuindex' => '5',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['24']= $xpdo->newObject('modMenu');
$collection['24']->fromArray(array (
  'id' => '24',
  'parent' => '9',
  'action' => '59',
  'text' => 'import_resources',
  'icon' => 'images/icons/application_side_contract.png',
  'menuindex' => '2',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['25']= $xpdo->newObject('modMenu');
$collection['25']->fromArray(array (
  'id' => '25',
  'parent' => '9',
  'action' => '60',
  'text' => 'import_site',
  'icon' => 'images/icons/application_side_contract.png',
  'menuindex' => '3',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['26']= $xpdo->newObject('modMenu');
$collection['26']->fromArray(array (
  'id' => '26',
  'parent' => '9',
  'action' => '1',
  'text' => 'export_site',
  'icon' => 'images/icons/application_side_expand.png',
  'menuindex' => '4',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['27']= $xpdo->newObject('modMenu');
$collection['27']->fromArray(array (
  'id' => '27',
  'parent' => '9',
  'action' => '0',
  'text' => '-',
  'icon' => '',
  'menuindex' => '1',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['28']= $xpdo->newObject('modMenu');
$collection['28']->fromArray(array (
  'id' => '28',
  'parent' => '9',
  'action' => '6',
  'text' => 'contexts',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => '6',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['29']= $xpdo->newObject('modMenu');
$collection['29']->fromArray(array (
  'id' => '29',
  'parent' => '9',
  'action' => '68',
  'text' => 'manage_workspaces',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => '7',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['30']= $xpdo->newObject('modMenu');
$collection['30']->fromArray(array (
  'id' => '30',
  'parent' => '9',
  'action' => '61',
  'text' => 'edit_settings',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => '8',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['31']= $xpdo->newObject('modMenu');
$collection['31']->fromArray(array (
  'id' => '31',
  'parent' => '10',
  'action' => '42',
  'text' => 'site_schedule',
  'icon' => 'images/icons/cal.gif',
  'menuindex' => '0',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['32']= $xpdo->newObject('modMenu');
$collection['32']->fromArray(array (
  'id' => '32',
  'parent' => '10',
  'action' => '14',
  'text' => 'view_logging',
  'icon' => '',
  'menuindex' => '1',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['33']= $xpdo->newObject('modMenu');
$collection['33']->fromArray(array (
  'id' => '33',
  'parent' => '10',
  'action' => '57',
  'text' => 'eventlog_viewer',
  'icon' => 'images/icons/comment.gif',
  'menuindex' => '2',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['34']= $xpdo->newObject('modMenu');
$collection['34']->fromArray(array (
  'id' => '34',
  'parent' => '10',
  'action' => '4',
  'text' => 'view_sysinfo',
  'icon' => 'images/icons/logging.gif',
  'menuindex' => '2',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['35']= $xpdo->newObject('modMenu');
$collection['35']->fromArray(array (
  'id' => '35',
  'parent' => '10',
  'action' => '63',
  'text' => 'help',
  'icon' => 'images/icons/information.png',
  'menuindex' => '2',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['36']= $xpdo->newObject('modMenu');
$collection['36']->fromArray(array (
  'id' => '36',
  'parent' => '11',
  'action' => '49',
  'text' => 'profile',
  'icon' => '',
  'menuindex' => '2',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['37']= $xpdo->newObject('modMenu');
$collection['37']->fromArray(array (
  'id' => '37',
  'parent' => '11',
  'action' => '47',
  'text' => 'messages',
  'icon' => 'images/icons/messages.gif',
  'menuindex' => '2',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['38']= $xpdo->newObject('modMenu');
$collection['38']->fromArray(array (
  'id' => '38',
  'parent' => '9',
  'action' => '2',
  'text' => 'edit_menu',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => '10',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['39']= $xpdo->newObject('modMenu');
$collection['39']->fromArray(array (
  'id' => '39',
  'parent' => '0',
  'action' => '0',
  'text' => 'support',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => '6',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['40']= $xpdo->newObject('modMenu');
$collection['40']->fromArray(array (
  'id' => '40',
  'parent' => '39',
  'action' => '0',
  'text' => 'forums',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => '0',
  'params' => '',
  'handler' => 'window.open("http://www.modxcms.com/forums");',
), '', true, true);
$collection['41']= $xpdo->newObject('modMenu');
$collection['41']->fromArray(array (
  'id' => '41',
  'parent' => '39',
  'action' => '0',
  'text' => 'wiki',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => '1',
  'params' => '',
  'handler' => 'window.open("http://svn.modxcms.com/docs/");',
), '', true, true);
$collection['42']= $xpdo->newObject('modMenu');
$collection['42']->fromArray(array (
  'id' => '42',
  'parent' => '39',
  'action' => '0',
  'text' => 'jira',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => '2',
  'params' => '',
  'handler' => 'window.open("http://svn.modxcms.com/jira/browse/MODX");',
), '', true, true);
$collection['43']= $xpdo->newObject('modMenu');
$collection['43']->fromArray(array (
  'id' => '43',
  'parent' => '8',
  'action' => '65',
  'text' => 'policy_management_title',
  'icon' => 'images/icons/logging.gif',
  'menuindex' => '3',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['44']= $xpdo->newObject('modMenu');
$collection['44']->fromArray(array (
  'id' => '44',
  'parent' => '9',
  'action' => '69',
  'text' => 'content_types',
  'icon' => 'images/icons/logging.gif',
  'menuindex' => '10',
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['45']= $xpdo->newObject('modMenu');
$collection['45']->fromArray(array (
  'id' => '45',
  'parent' => '9',
  'action' => '72',
  'text' => 'package_builder',
  'icon' => 'images/icons/logging.gif',
  'menuindex' => '11',
  'params' => '',
  'handler' => '',
), '', true, true);
