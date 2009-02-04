{literal}
<script type="text/javascript">
// <![CDATA[
/* needs handlers for all RTEs */
function cleanupRTE(editor) {
    if (typeof(tinyMCE) != 'undefined' && editor == 'TinyMCE') {
        tinyMCE.triggerSave(true,true);
        Ext.get('ta').dom.value = tinyMCE.activeEditor.getContent();
    }
    return false;
}


function makePublic(b){
    var notPublic=false;
    var f=$('mutate_document');
    var chkpub = f['chkalldocs'];
    var chks = f['docgroups[]'];
    if(!chks && chkpub) {
        chkpub.checked=true;
        return false;
    }
    else if (!b && chkpub) {
        if(!chks.length) notPublic=chks.checked;
        else for(i=0;i<chks.length;i++) if(chks[i].checked) notPublic=true;
        chkpub.checked=!notPublic;
    }
    else {
        if(!chks.length) chks.checked = (b)? false:chks.checked;
        else for(i=0;i<chks.length;i++) if (b) chks[i].checked=false;
        chkpub.checked=true;
    }
}


function changestate(element) {
    var currval = eval(element).value;
    if(currval==1) {
        eval(element).value=0;
    } else {
        eval(element).value=1;
    }
    documentDirty=true;
}

function previewdocument() {
    var win = window.frames['preview'];
    url = '../index.php?id={/literal}{$smarty.request.id}{literal}&manprev=z';
    nQ = 'id={/literal}{$smarty.request.id}{literal}&manprev=z'; // new querysting
    oQ = (win.location.href.split('?'))[1]; // old querysting
    if (nQ != oQ) {
        win.location.href = url;
        win.alreadyPreviewed = true;
    }
}

/* Added for RTE selection */
var changeRTE = function() {
    var whichEditor = Ext.get('modx-resource-which-editor').dom;
    if (whichEditor) {
        for (var i=0; i<whichEditor.length; i++) {
            if (whichEditor[i].selected) {
                newEditor = whichEditor[i].value;
                break;
            }
        }
    }
    var dropTemplate = $('template');
    var newTemplate = dropTemplate.value;

    documentDirty=false;
    {/literal}
    $('mutate_document').action = 'index.php?id={$smarty.request.id}';
    location.href= 'index.php?id={$smarty.request.id}&a={$smarty.request.a}&template='+newTemplate+'&which_editor='+newEditor;
    {literal}
}
// ]]>
</script>
{/literal}