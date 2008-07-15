<?php
/**
 * @package modx
 * @subpackage processors.security.message
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('messages','user');

if (!$modx->hasPermission('messages')) $error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'date_sent';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'DESC';

$c = $modx->newQuery('modUserMessage');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$c->where(array('recipient' => $modx->user->id));
$messages = $modx->getCollection('modUserMessage', $c);

$cc = $modx->newQuery('modUserMessage');
$count = $modx->getCount('modUserMessage',$cc);

$ms = array();
foreach ($messages as $message) {
	$ma = $message->toArray();
    $sender = $modx->getObject('modUser',$message->sender);
    $ma['sender_name'] = $sender->username;
    $ma['read'] = $message->read ? true : false;
    $ma['menu'] = array(
        array(
            'text' => $modx->lexicon('reply'),
            'handler' => array(
                'xtype' => 'window-message-reply'
                ,'id' => $message->id, 
            ),
        ),
        array(
            'text' => $modx->lexicon('forward'),
            'handler' => array(
                'xtype' => 'window-message-forward'
                ,'id' => $message->id, 
            ),
        ),
        array(
            'text' => $modx->lexicon('mark_unread'),
            'handler' => 'this.markUnread',
        ),
        '-',
        array(
            'text' => $modx->lexicon('delete'),
            'handler' => 'this.remove.createDelegate(this,["message_remove_confirm"])'
        ),
    );
	$ms[] = $ma;
}
$this->outputArray($ms,$count);