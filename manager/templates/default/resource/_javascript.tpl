{literal}
<script type="text/javascript">
// <![CDATA[

// needs handlers for all RTEs
function cleanupRTE(editor) {
    if (typeof(tinyMCE) != 'undefined' && editor == 'TinyMCE') {
        tinyMCE.triggerSave(true,true);
        Ext.get('ta').dom.value = tinyMCE.getContent();
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

function clearKeywordSelection() {
    var opt = Ext.get('mutate_document').dom.elements['keywords[]'].options;
    for(i = 0; i < opt.length; i++) {
        opt[i].selected = false;
    }
}

function clearMetatagSelection() {
    var opt = Ext.get('mutate_document').dom.elements['metatags[]'].options;
    for(i = 0; i < opt.length; i++) {
        opt[i].selected = false;
    }
}

// Added for RTE selection
var changeRTE = function() {
    var whichEditor = Ext.get('which_editor').dom;
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

/** 
 * Snippet properties 
 */
var snippetParams = {};     // Snippet Params
var currentParams = {};     // Current Params
var lastsp, lastmod = {};

function showParameters(ctrl) {
    var c,p,df,cp;
    var ar,desc,value,key,dt;

    cp = {};
    currentParams = {}; // reset;

    if (ctrl) {
        f = ctrl.form;
    } else {
        f= $('mutate_document');
        ctrl = f.snippetlist;
    }

    // get display format
    df = '';//lastsp = ctrl.options[ctrl.selectedIndex].value;

    // load last modified param values
    if (lastmod[df]) cp = lastmod[df].split('&');
    for(p = 0; p < cp.length; p++) {
        cp[p]=(cp[p]+'').replace(/^\s|\s$/,''); // trim
        ar = cp[p].split('=');
        currentParams[ar[0]]=ar[1];
    }

    // setup parameters
    dp = (snippetParams[df]) ? snippetParams[df].split('&'):[''];
    if(dp) {
        t='<table width="100%" style="margin-bottom:3px;margin-left:14px;background-color:#EEEEEE" cellpadding="2" cellspacing="1"><thead><tr><td width="50%">{/literal}{$_lang.parameter}{literal}<\/td><td width="50%">{/literal}{$_lang.value}{literal}<\/td><\/tr><\/thead>';
        for(p = 0; p < dp.length; p++) {
            dp[p]=(dp[p]+'').replace(/^\s|\s$/,''); // trim
            ar = dp[p].split('=');
            key = ar[0]     // param
            ar = (ar[1]+'').split(';');
            desc = ar[0];   // description
            dt = ar[1];     // data type
            value = decode((currentParams[key]) ? currentParams[key]:(dt=='list') ? ar[3] : (ar[2])? ar[2]:'');
            if (value!=currentParams[key]) currentParams[key] = value;
            value = (value+'').replace(/^\s|\s$/,""); // trim
            if (dt) {
                switch(dt) {
                case 'int':
                    c = '<input type="text" name="prop_'+key+'" value="'+value+'" size="30" onchange="setParameter(\''+key+'\',\''+dt+'\',this)" \/>';
                    break;
                case 'list':
                    c = '<select name="prop_'+key+'" height="1" style="width:168px" onchange="setParameter(\''+key+'\',\''+dt+'\',this)">';
                    ls = (ar[2]+'').split(",");
                    if(currentParams[key]==ar[2]) currentParams[key] = ls[0]; // use first list item as default
                    for(i=0;i<ls.length;i++){
                        c += '<option value="'+ls[i]+'"'+((ls[i]==value)? ' selected="selected"':'')+'>'+ls[i]+'<\/option>';
                    }
                    c += '<\/select>';
                    break;
                default:  // string
                    c = '<input type="text" name="prop_'+key+'" value="'+value+'" size="30" onchange="setParameter(\''+key+'\',\''+dt+'\',this)" \/>';
                    break;

                }
                t +='<tr><td bgcolor="#FFFFFF" width="50%">'+desc+'<\/td><td bgcolor="#FFFFFF" width="50%">'+c+'<\/td><\/tr>';
            };
        }
        t+='<\/table>';
        td = $('snippetparams');
        td.innerHTML = t;
    }
    implodeParameters();
}

function setParameter(key,dt,ctrl) {
    var v;
    if(!ctrl) return null;
    switch (dt) {
        case 'int':
            ctrl.value = parseInt(ctrl.value);
            if(isNaN(ctrl.value)) ctrl.value = 0;
            v = ctrl.value;
            break;
        case 'list':
            v = ctrl.options[ctrl.selectedIndex].value;
            break;
        default:
            v = ctrl.value+'';
            break;
    }
    currentParams[key] = v;
    implodeParameters();
}

function resetParameters() {
    $('mutate_document').params.value = '';
    lastmod[lastsp]='';
    showParameters();
}
// implode parameters
function implodeParameters(){
    var v, p, s='';
    for(p in currentParams){
        v = currentParams[p];
        if(v) s += '&'+p+'='+ encode(v);
    }
    if (lastsp) lastmod[lastsp] = s;
}

function encode(s){
    s=s+'';
    s = s.replace(/\=/g,'%3D'); // =
    s = s.replace(/\&/g,'%26'); // &
    return s;
}

function decode(s){
    s=s+'';
    s = s.replace(/\%3D/g,'='); // =
    s = s.replace(/\%26/g,'&'); // &
    return s;
}
// ]]>
</script>
{/literal}