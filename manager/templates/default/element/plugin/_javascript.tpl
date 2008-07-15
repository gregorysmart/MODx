{literal}
<script type="text/javascript">
// <[CDATA[
function duplicaterecord(){
	if(confirm("{/literal}{$_lang.confirm_duplicate_record}{literal}")) {
		documentDirty=false;
		document.location.href="index.php?id={/literal}{$smarty.request.id}{literal}&a=105";
	}
}
// ]]>
</script>
{/literal}