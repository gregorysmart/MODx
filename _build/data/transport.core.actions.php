<?php
$collection['1']= $xpdo->newObject('modAction');
$collection['1']->fromArray(array (
  'id' => '1',
  'context_key' => 'mgr',
  'parent' => '0',
  'controller' => 'welcome',
  'haslayout' => '1',
  'lang_foci' => 'welcome,configcheck',
  'assets' => '',
), '', true, true);
$collection['2']= $xpdo->newObject('modAction');
$collection['2']->fromArray(array (
  'id' => '2',
  'context_key' => 'mgr',
  'parent' => '3',
  'controller' => 'system/action',
  'haslayout' => '1',
  'lang_foci' => 'action,menu',
  'assets' => '',
), '', true, true);
$collection['3']= $xpdo->newObject('modAction');
$collection['3']->fromArray(array (
  'id' => '3',
  'context_key' => 'mgr',
  'parent' => '0',
  'controller' => 'system',
  'haslayout' => '0',
  'lang_foci' => '',
  'assets' => '',
), '', true, true);
$collection['4']= $xpdo->newObject('modAction');
$collection['4']->fromArray(array (
  'id' => '4',
  'context_key' => 'mgr',
  'parent' => '3',
  'controller' => 'system/info',
  'haslayout' => '1',
  'lang_foci' => 'system_info',
  'assets' => '',
), '', true, true);
$collection['5']= $xpdo->newObject('modAction');
$collection['5']->fromArray(array (
  'id' => '5',
  'context_key' => 'mgr',
  'parent' => '0',
  'controller' => 'browser',
  'haslayout' => '1',
  'lang_foci' => 'file',
  'assets' => '',
), '', true, true);
$collection['6']= $xpdo->newObject('modAction');
$collection['6']->fromArray(array (
  'id' => '6',
  'context_key' => 'mgr',
  'parent' => '0',
  'controller' => 'context',
  'haslayout' => '1',
  'lang_foci' => 'context',
  'assets' => '',
), '', true, true);
$collection['7']= $xpdo->newObject('modAction');
$collection['7']->fromArray(array (
  'id' => '7',
  'context_key' => 'mgr',
  'parent' => '6',
  'controller' => 'context/create',
  'haslayout' => '1',
  'lang_foci' => 'context',
  'assets' => '',
), '', true, true);
$collection['8']= $xpdo->newObject('modAction');
$collection['8']->fromArray(array (
  'id' => '8',
  'context_key' => 'mgr',
  'parent' => '6',
  'controller' => 'context/update',
  'haslayout' => '1',
  'lang_foci' => 'context',
  'assets' => '',
), '', true, true);
$collection['9']= $xpdo->newObject('modAction');
$collection['9']->fromArray(array (
  'id' => '9',
  'context_key' => 'mgr',
  'parent' => '6',
  'controller' => 'context/view',
  'haslayout' => '1',
  'lang_foci' => 'context',
  'assets' => '',
), '', true, true);
$collection['10']= $xpdo->newObject('modAction');
$collection['10']->fromArray(array (
  'id' => '10',
  'context_key' => 'mgr',
  'parent' => '0',
  'controller' => 'element',
  'haslayout' => '1',
  'lang_foci' => '',
  'assets' => '',
), '', true, true);
$collection['11']= $xpdo->newObject('modAction');
$collection['11']->fromArray(array (
  'id' => '11',
  'context_key' => 'mgr',
  'parent' => '10',
  'controller' => 'element/chunk',
  'haslayout' => '1',
  'lang_foci' => 'chunk,category',
  'assets' => '',
), '', true, true);
$collection['12']= $xpdo->newObject('modAction');
$collection['12']->fromArray(array (
  'id' => '12',
  'context_key' => 'mgr',
  'parent' => '11',
  'controller' => 'element/chunk/create',
  'haslayout' => '1',
  'lang_foci' => 'chunk,category',
  'assets' => '',
), '', true, true);
$collection['13']= $xpdo->newObject('modAction');
$collection['13']->fromArray(array (
  'id' => '13',
  'context_key' => 'mgr',
  'parent' => '11',
  'controller' => 'element/chunk/update',
  'haslayout' => '1',
  'lang_foci' => 'chunk,category',
  'assets' => '',
), '', true, true);
$collection['14']= $xpdo->newObject('modAction');
$collection['14']->fromArray(array (
  'id' => '14',
  'context_key' => 'mgr',
  'parent' => '0',
  'controller' => 'system/logs/index',
  'haslayout' => '1',
  'lang_foci' => 'manager_log',
  'assets' => '',
), '', true, true);
$collection['15']= $xpdo->newObject('modAction');
$collection['15']->fromArray(array (
  'id' => '15',
  'context_key' => 'mgr',
  'parent' => '10',
  'controller' => 'element/module',
  'haslayout' => '1',
  'lang_foci' => 'module',
  'assets' => '',
), '', true, true);
$collection['16']= $xpdo->newObject('modAction');
$collection['16']->fromArray(array (
  'id' => '16',
  'context_key' => 'mgr',
  'parent' => '15',
  'controller' => 'element/module/create',
  'haslayout' => '1',
  'lang_foci' => 'module,category,user',
  'assets' => '',
), '', true, true);
$collection['17']= $xpdo->newObject('modAction');
$collection['17']->fromArray(array (
  'id' => '17',
  'context_key' => 'mgr',
  'parent' => '15',
  'controller' => 'element/module/update',
  'haslayout' => '1',
  'lang_foci' => 'module,category,user',
  'assets' => '',
), '', true, true);
$collection['18']= $xpdo->newObject('modAction');
$collection['18']->fromArray(array (
  'id' => '18',
  'context_key' => 'mgr',
  'parent' => '15',
  'controller' => 'element/module/dependencies',
  'haslayout' => '1',
  'lang_foci' => 'module,user',
  'assets' => '',
), '', true, true);
$collection['19']= $xpdo->newObject('modAction');
$collection['19']->fromArray(array (
  'id' => '19',
  'context_key' => 'mgr',
  'parent' => '15',
  'controller' => 'element/module/run',
  'haslayout' => '1',
  'lang_foci' => 'module',
  'assets' => '',
), '', true, true);
$collection['20']= $xpdo->newObject('modAction');
$collection['20']->fromArray(array (
  'id' => '20',
  'context_key' => 'mgr',
  'parent' => '10',
  'controller' => 'element/plugin',
  'haslayout' => '1',
  'lang_foci' => 'plugin,category,system_events',
  'assets' => '',
), '', true, true);
$collection['21']= $xpdo->newObject('modAction');
$collection['21']->fromArray(array (
  'id' => '21',
  'context_key' => 'mgr',
  'parent' => '20',
  'controller' => 'element/plugin/create',
  'haslayout' => '1',
  'lang_foci' => 'plugin,category,system_events',
  'assets' => '',
), '', true, true);
$collection['22']= $xpdo->newObject('modAction');
$collection['22']->fromArray(array (
  'id' => '22',
  'context_key' => 'mgr',
  'parent' => '20',
  'controller' => 'element/plugin/update',
  'haslayout' => '1',
  'lang_foci' => 'plugin,category,system_events',
  'assets' => '',
), '', true, true);
$collection['23']= $xpdo->newObject('modAction');
$collection['23']->fromArray(array (
  'id' => '23',
  'context_key' => 'mgr',
  'parent' => '20',
  'controller' => 'element/plugin/sortpriority',
  'haslayout' => '1',
  'lang_foci' => 'plugin,category,system_events',
  'assets' => '',
), '', true, true);
$collection['25']= $xpdo->newObject('modAction');
$collection['25']->fromArray(array (
  'id' => '25',
  'context_key' => 'mgr',
  'parent' => '10',
  'controller' => 'element/snippet',
  'haslayout' => '1',
  'lang_foci' => 'snippet',
  'assets' => '',
), '', true, true);
$collection['26']= $xpdo->newObject('modAction');
$collection['26']->fromArray(array (
  'id' => '26',
  'context_key' => 'mgr',
  'parent' => '25',
  'controller' => 'element/snippet/create',
  'haslayout' => '1',
  'lang_foci' => 'snippet',
  'assets' => '',
), '', true, true);
$collection['27']= $xpdo->newObject('modAction');
$collection['27']->fromArray(array (
  'id' => '27',
  'context_key' => 'mgr',
  'parent' => '25',
  'controller' => 'element/snippet/update',
  'haslayout' => '1',
  'lang_foci' => 'snippet',
  'assets' => '',
), '', true, true);
$collection['28']= $xpdo->newObject('modAction');
$collection['28']->fromArray(array (
  'id' => '28',
  'context_key' => 'mgr',
  'parent' => '10',
  'controller' => 'element/template',
  'haslayout' => '1',
  'lang_foci' => 'template',
  'assets' => '',
), '', true, true);
$collection['29']= $xpdo->newObject('modAction');
$collection['29']->fromArray(array (
  'id' => '29',
  'context_key' => 'mgr',
  'parent' => '28',
  'controller' => 'element/template/create',
  'haslayout' => '1',
  'lang_foci' => 'template',
  'assets' => '',
), '', true, true);
$collection['30']= $xpdo->newObject('modAction');
$collection['30']->fromArray(array (
  'id' => '30',
  'context_key' => 'mgr',
  'parent' => '28',
  'controller' => 'element/template/update',
  'haslayout' => '1',
  'lang_foci' => 'template',
  'assets' => '',
), '', true, true);
$collection['31']= $xpdo->newObject('modAction');
$collection['31']->fromArray(array (
  'id' => '31',
  'context_key' => 'mgr',
  'parent' => '28',
  'controller' => 'element/template/tvsort',
  'haslayout' => '1',
  'lang_foci' => 'template,tv',
  'assets' => '',
), '', true, true);
$collection['32']= $xpdo->newObject('modAction');
$collection['32']->fromArray(array (
  'id' => '32',
  'context_key' => 'mgr',
  'parent' => '10',
  'controller' => 'element/tv',
  'haslayout' => '1',
  'lang_foci' => 'tv',
  'assets' => '',
), '', true, true);
$collection['33']= $xpdo->newObject('modAction');
$collection['33']->fromArray(array (
  'id' => '33',
  'context_key' => 'mgr',
  'parent' => '32',
  'controller' => 'element/tv/create',
  'haslayout' => '1',
  'lang_foci' => 'tv,tv_widget',
  'assets' => '',
), '', true, true);
$collection['34']= $xpdo->newObject('modAction');
$collection['34']->fromArray(array (
  'id' => '34',
  'context_key' => 'mgr',
  'parent' => '32',
  'controller' => 'element/tv/update',
  'haslayout' => '1',
  'lang_foci' => 'tv,tv_widget',
  'assets' => '',
), '', true, true);
$collection['35']= $xpdo->newObject('modAction');
$collection['35']->fromArray(array (
  'id' => '35',
  'context_key' => 'mgr',
  'parent' => '10',
  'controller' => 'element/view',
  'haslayout' => '1',
  'lang_foci' => '',
  'assets' => '',
), '', true, true);
$collection['36']= $xpdo->newObject('modAction');
$collection['36']->fromArray(array (
  'id' => '36',
  'context_key' => 'mgr',
  'parent' => '0',
  'controller' => 'resource',
  'haslayout' => '1',
  'lang_foci' => '',
  'assets' => '',
), '', true, true);
$collection['40']= $xpdo->newObject('modAction');
$collection['40']->fromArray(array (
  'id' => '40',
  'context_key' => 'mgr',
  'parent' => '36',
  'controller' => 'resource/data',
  'haslayout' => '1',
  'lang_foci' => 'resource',
  'assets' => '',
), '', true, true);
$collection['41']= $xpdo->newObject('modAction');
$collection['41']->fromArray(array (
  'id' => '41',
  'context_key' => 'mgr',
  'parent' => '36',
  'controller' => 'resource/empty_recycle_bin',
  'haslayout' => '1',
  'lang_foci' => 'resource',
  'assets' => '',
), '', true, true);
$collection['42']= $xpdo->newObject('modAction');
$collection['42']->fromArray(array (
  'id' => '42',
  'context_key' => 'mgr',
  'parent' => '36',
  'controller' => 'resource/site_schedule',
  'haslayout' => '1',
  'lang_foci' => 'resource',
  'assets' => '',
), '', true, true);
$collection['43']= $xpdo->newObject('modAction');
$collection['43']->fromArray(array (
  'id' => '43',
  'context_key' => 'mgr',
  'parent' => '36',
  'controller' => 'resource/update',
  'haslayout' => '1',
  'lang_foci' => 'resource',
  'assets' => '',
), '', true, true);
$collection['44']= $xpdo->newObject('modAction');
$collection['44']->fromArray(array (
  'id' => '44',
  'context_key' => 'mgr',
  'parent' => '36',
  'controller' => 'resource/create',
  'haslayout' => '1',
  'lang_foci' => 'resource',
  'assets' => '',
), '', true, true);
$collection['45']= $xpdo->newObject('modAction');
$collection['45']->fromArray(array (
  'id' => '45',
  'context_key' => 'mgr',
  'parent' => '0',
  'controller' => 'search',
  'haslayout' => '1',
  'lang_foci' => '',
  'assets' => '',
), '', true, true);
$collection['46']= $xpdo->newObject('modAction');
$collection['46']->fromArray(array (
  'id' => '46',
  'context_key' => 'mgr',
  'parent' => '0',
  'controller' => 'security',
  'haslayout' => '1',
  'lang_foci' => 'user',
  'assets' => '',
), '', true, true);
$collection['47']= $xpdo->newObject('modAction');
$collection['47']->fromArray(array (
  'id' => '47',
  'context_key' => 'mgr',
  'parent' => '46',
  'controller' => 'security/message',
  'haslayout' => '1',
  'lang_foci' => 'messages',
  'assets' => '',
), '', true, true);
$collection['48']= $xpdo->newObject('modAction');
$collection['48']->fromArray(array (
  'id' => '48',
  'context_key' => 'mgr',
  'parent' => '46',
  'controller' => 'security/access',
  'haslayout' => '1',
  'lang_foci' => 'user,policy,access',
  'assets' => '',
), '', true, true);
$collection['49']= $xpdo->newObject('modAction');
$collection['49']->fromArray(array (
  'id' => '49',
  'context_key' => 'mgr',
  'parent' => '46',
  'controller' => 'security/profile',
  'haslayout' => '1',
  'lang_foci' => 'user',
  'assets' => '',
), '', true, true);
$collection['50']= $xpdo->newObject('modAction');
$collection['50']->fromArray(array (
  'id' => '50',
  'context_key' => 'mgr',
  'parent' => '46',
  'controller' => 'security/role',
  'haslayout' => '1',
  'lang_foci' => 'role',
  'assets' => '',
), '', true, true);
$collection['51']= $xpdo->newObject('modAction');
$collection['51']->fromArray(array (
  'id' => '51',
  'context_key' => 'mgr',
  'parent' => '50',
  'controller' => 'security/role/create',
  'haslayout' => '1',
  'lang_foci' => 'role',
  'assets' => '',
), '', true, true);
$collection['52']= $xpdo->newObject('modAction');
$collection['52']->fromArray(array (
  'id' => '52',
  'context_key' => 'mgr',
  'parent' => '50',
  'controller' => 'security/role/update',
  'haslayout' => '1',
  'lang_foci' => 'role',
  'assets' => '',
), '', true, true);
$collection['53']= $xpdo->newObject('modAction');
$collection['53']->fromArray(array (
  'id' => '53',
  'context_key' => 'mgr',
  'parent' => '46',
  'controller' => 'security/user',
  'haslayout' => '1',
  'lang_foci' => 'user',
  'assets' => '',
), '', true, true);
$collection['54']= $xpdo->newObject('modAction');
$collection['54']->fromArray(array (
  'id' => '54',
  'context_key' => 'mgr',
  'parent' => '53',
  'controller' => 'security/user/create',
  'haslayout' => '1',
  'lang_foci' => 'user,system_setting',
  'assets' => '',
), '', true, true);
$collection['55']= $xpdo->newObject('modAction');
$collection['55']->fromArray(array (
  'id' => '55',
  'context_key' => 'mgr',
  'parent' => '53',
  'controller' => 'security/user/update',
  'haslayout' => '1',
  'lang_foci' => 'user,system_setting',
  'assets' => '',
), '', true, true);
$collection['56']= $xpdo->newObject('modAction');
$collection['56']->fromArray(array (
  'id' => '56',
  'context_key' => 'mgr',
  'parent' => '46',
  'controller' => 'security/login',
  'haslayout' => '1',
  'lang_foci' => 'login',
  'assets' => '',
), '', true, true);
$collection['57']= $xpdo->newObject('modAction');
$collection['57']->fromArray(array (
  'id' => '57',
  'context_key' => 'mgr',
  'parent' => '3',
  'controller' => 'system/event',
  'haslayout' => '1',
  'lang_foci' => 'system_events',
  'assets' => '',
), '', true, true);
$collection['58']= $xpdo->newObject('modAction');
$collection['58']->fromArray(array (
  'id' => '58',
  'context_key' => 'mgr',
  'parent' => '57',
  'controller' => 'system/event/details',
  'haslayout' => '1',
  'lang_foci' => '',
  'assets' => '',
), '', true, true);
$collection['59']= $xpdo->newObject('modAction');
$collection['59']->fromArray(array (
  'id' => '59',
  'context_key' => 'mgr',
  'parent' => '3',
  'controller' => 'system/import',
  'haslayout' => '1',
  'lang_foci' => 'import',
  'assets' => '',
), '', true, true);
$collection['60']= $xpdo->newObject('modAction');
$collection['60']->fromArray(array (
  'id' => '60',
  'context_key' => 'mgr',
  'parent' => '59',
  'controller' => 'system/import/html',
  'haslayout' => '1',
  'lang_foci' => 'import',
  'assets' => '',
), '', true, true);
$collection['61']= $xpdo->newObject('modAction');
$collection['61']->fromArray(array (
  'id' => '61',
  'context_key' => 'mgr',
  'parent' => '3',
  'controller' => 'system/settings',
  'haslayout' => '1',
  'lang_foci' => 'system_setting',
  'assets' => '',
), '', true, true);
$collection['62']= $xpdo->newObject('modAction');
$collection['62']->fromArray(array (
  'id' => '62',
  'context_key' => 'mgr',
  'parent' => '3',
  'controller' => 'system/refresh_site',
  'haslayout' => '1',
  'lang_foci' => '',
  'assets' => '',
), '', true, true);
$collection['63']= $xpdo->newObject('modAction');
$collection['63']->fromArray(array (
  'id' => '63',
  'context_key' => 'mgr',
  'parent' => '0',
  'controller' => 'help',
  'haslayout' => '1',
  'lang_foci' => 'about',
  'assets' => '',
), '', true, true);
$collection['64']= $xpdo->newObject('modAction');
$collection['64']->fromArray(array (
  'id' => '64',
  'context_key' => 'mgr',
  'parent' => '3',
  'controller' => 'system/phpinfo',
  'haslayout' => '1',
  'lang_foci' => '',
  'assets' => '',
), '', true, true);
$collection['65']= $xpdo->newObject('modAction');
$collection['65']->fromArray(array (
  'id' => '65',
  'context_key' => 'mgr',
  'parent' => '48',
  'controller' => 'security/access/policy',
  'haslayout' => '1',
  'lang_foci' => 'user,policy',
  'assets' => '',
), '', true, true);
$collection['66']= $xpdo->newObject('modAction');
$collection['66']->fromArray(array (
  'id' => '66',
  'context_key' => 'mgr',
  'parent' => '46',
  'controller' => 'security/permission',
  'haslayout' => '1',
  'lang_foci' => 'role,user',
  'assets' => '',
), '', true, true);
$collection['67']= $xpdo->newObject('modAction');
$collection['67']->fromArray(array (
  'id' => '67',
  'context_key' => 'mgr',
  'parent' => '36',
  'controller' => 'resource/tvs',
  'haslayout' => '0',
  'lang_foci' => '',
  'assets' => '',
), '', true, true);
$collection['68']= $xpdo->newObject('modAction');
$collection['68']->fromArray(array (
  'id' => '68',
  'context_key' => 'mgr',
  'parent' => '3',
  'controller' => 'workspaces',
  'haslayout' => '1',
  'lang_foci' => 'workspace',
  'assets' => '',
), '', true, true);
$collection['69']= $xpdo->newObject('modAction');
$collection['69']->fromArray(array (
  'id' => '69',
  'context_key' => 'mgr',
  'parent' => '3',
  'controller' => 'system/contenttype',
  'haslayout' => '1',
  'lang_foci' => 'content_type',
  'assets' => '',
), '', true, true);
$collection['70']= $xpdo->newObject('modAction');
$collection['70']->fromArray(array (
  'id' => '70',
  'context_key' => 'mgr',
  'parent' => '3',
  'controller' => 'system/file',
  'haslayout' => '1',
  'lang_foci' => 'file',
  'assets' => '',
), '', true, true);
$collection['71']= $xpdo->newObject('modAction');
$collection['71']->fromArray(array (
  'id' => '71',
  'context_key' => 'mgr',
  'parent' => '70',
  'controller' => 'system/file/edit',
  'haslayout' => '1',
  'lang_foci' => 'file',
  'assets' => '',
), '', true, true);
$collection['72']= $xpdo->newObject('modAction');
$collection['72']->fromArray(array (
  'id' => '72',
  'context_key' => 'mgr',
  'parent' => '68',
  'controller' => 'workspaces/builder',
  'haslayout' => '1',
  'lang_foci' => 'workspace,file,package_builder',
  'assets' => '',
), '', true, true);
$collection['73']= $xpdo->newObject('modAction');
$collection['73']->fromArray(array (
  'id' => '73',
  'context_key' => 'mgr',
  'parent' => '68',
  'controller' => 'workspaces/lexicon',
  'haslayout' => '1',
  'lang_foci' => 'package_builder,lexicon,namespace',
  'assets' => '',
), '', true, true);
$collection['74']= $xpdo->newObject('modAction');
$collection['74']->fromArray(array (
  'id' => '74',
  'context_key' => 'mgr',
  'parent' => '68',
  'controller' => 'workspaces/namespace',
  'haslayout' => '1',
  'lang_foci' => 'workspace,package_builder,lexicon,namespace',
  'assets' => '',
), '', true, true);
