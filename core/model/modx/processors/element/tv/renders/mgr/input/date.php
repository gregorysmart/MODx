<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');

$v = $this->get('value');
$v = strftime('%Y-%m-%d',strtotime($v));
$this->set('value',$v);

return $this->xpdo->smarty->fetch('element/tv/renders/input/date.tpl');