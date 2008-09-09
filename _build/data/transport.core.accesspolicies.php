<?php
$collection['1']= $xpdo->newObject('modAccessPolicy');
$collection['1']->fromArray(array (
  'id' => '1',
  'name' => 'Basic',
  'description' => 'A basic policy with create, remove, save, and load attributes.',
  'parent' => '0',
  'class' => '',
  'data' => '{"create":true,"remove":true,"save":true,"load":true}',
), '', true, true);
$collection['2']= $xpdo->newObject('modAccessPolicy');
$collection['2']->fromArray(array (
  'id' => '2',
  'name' => 'Admin',
  'description' => 'A basic admin policy.',
  'parent' => '0',
  'class' => '',
  'data' => '{"create":true,"remove":true,"save":true,"load":true,"frames":true,"home":true,"view_document":true,"new_document":true,"save_document":true,"publish_document":true,"delete_document":true,"action_ok":true,"logout":true,"help":true,"messages":true,"new_user":true,"edit_user":true,"logs":true,"edit_parser":true,"save_parser":true,"edit_template":true,"settings":true,"credits":true,"new_template":true,"save_template":true,"delete_template":true,"edit_snippet":true,"new_snippet":true,"save_snippet":true,"delete_snippet":true,"edit_chunk":true,"new_chunk":true,"save_chunk":true,"delete_chunk":true,"empty_cache":true,"edit_document":true,"change_password":true,"error_dialog":true,"about":true,"file_manager":true,"save_user":true,"delete_user":true,"save_password":true,"edit_role":true,"save_role":true,"delete_role":true,"new_role":true,"access_permissions":true,"bk_manager":true,"new_plugin":true,"edit_plugin":true,"save_plugin":true,"delete_plugin":true,"new_module":true,"edit_module":true,"save_module":true,"delete_module":true,"exec_module":true,"view_eventlog":true,"delete_eventlog":true,"manage_metatags":true,"edit_doc_metatags":true,"new_web_user":true,"edit_web_user":true,"save_web_user":true,"delete_web_user":true,"web_access_permissions":true,"view_unpublished":true,"import_static":true,"export_static":true,"edit_locked":true}',
), '', true, true);
