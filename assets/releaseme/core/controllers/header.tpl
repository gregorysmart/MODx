<script type="text/javascript" src="<?php echo $rm->config['js_url']; ?>rm.js"></script>
<script type="text/javascript">
Ext.onReady(function() {
    RM.config = <?php echo $modx->toJSON($rm->config); ?>;
    RM.request = <?php echo $modx->toJSON($_GET); ?>;
});
</script>
<div class="padding">