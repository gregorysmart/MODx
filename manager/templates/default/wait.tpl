<br />
<div class="sectionHeader">{$_lang.cleaningup}</div>
<div class="sectionBody">
<p>{$_lang.actioncomplete}</p>

<script type="text/javascript">
// <![CDATA[

{if $smarty.request.r EQ 10 AND $smarty.session.mgrRefreshTheme EQ 1}{literal}

function goHome() { top.location.reload(); }

{/literal}{elseif $smarty.request.dv EQ 1 AND $smarty.request.id NEQ ''}{literal}

function goHome() { document.location.href="index.php?a=resource/data&id={/literal}{$smarty.request.id}{literal}"; }

{/literal}{else}{literal}

function goHome() { document.location.href="index.php?a=welcome"; }

{/literal}{/if}

x=window.setTimeout('goHome()',2000);
// ]]>
</script>
</div>