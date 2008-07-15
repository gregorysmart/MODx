/**
 * Displays a dropdown list of widgets
 * 
 * @class MODx.combo.TVWidget
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-tv-widget
 */
MODx.combo.TVWidget = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'widget'
        ,hiddenName: 'widget'
        ,displayField: 'name'
        ,valueField: 'value'
        ,fields: ['value','name']
        ,editable: false
        ,url: MODx.config.connectors_url+'element/tv/widget.php'
    });
    MODx.combo.TVWidget.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.TVWidget,MODx.combo.ComboBox);
Ext.reg('combo-tv-widget',MODx.combo.TVWidget);

/**
 * Displays a tv input type
 * 
 * @class MODx.combo.TVInputType
 * @extends Ext.form.ComboBox
 * @constructor
 * @xtype combo-tv-input-type
 */
MODx.combo.TVInputType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'type'
        ,hiddenName: 'type'
        ,displayField: 'name'
        ,valueField: 'value'
        ,editable: false
        ,fields: ['value','name']
        ,url: MODx.config.connectors_url+'element/tv/inputtype.php'
    });
    MODx.combo.TVInputType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.TVInputType,MODx.combo.ComboBox);
Ext.reg('combo-tv-input-type',MODx.combo.TVInputType);


function makePublic(b) {
	var notPublic=false;
	var f=$('mutate_tv');
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

// Widget Parameters
var widgetParams = {};          // name = description;datatype;default or list values - datatype: int, string, list : separated by comma (,)
    widgetParams['marquee']     = '&width=Width;string;100% &height=Height;string;100px &speed=Speed (1-20);float;3; &pause=Mouse Pause;list;Yes,No;Yes &tfx=Transition;list;Vertical,Horizontal &class=Class;string; &style=Style;string;';
    widgetParams['ticker']      = '&width=Width;string;100% &height=Height;string;50px &delay=Delay (ms);int;3000 &delim=Message Delimiter;string;|| &class=Class;string; &style=Style;string;';
    widgetParams['date']        = '&format='+_('date_format')+';string;%A %d, %B %Y &default='+_('date_use_current')+';list;'+_('yes')+','+_('no')+';'+_('no');
    widgetParams['string']      = '&format='+_('string_format')+';list;'+_('string_format_list');
    widgetParams['delim']       = '&format='+_('delimiter')+';string;,';
    widgetParams['url']         = '&text='+_('url_display_text')+';string; &title='+_('title')+';string; &class='+_('class')+';string &style='+_('style')+';string &target='+_('target')+';string &attrib='+_('attributes')+';string';
    widgetParams['htmltag']     = '&tagname=Tag Name;string;div &tagid=Tag ID;string &class=Class;string &style=Style;string &attrib=Attributes;string';
    widgetParams['viewport']    = '&vpid=ID/Name;string &width=Width;string;100 &height=Height;string;100 &borsize=Border Size;int;1 &sbar=Scrollbars;list;,Auto,Yes,No &asize=Auto Size;list;,Yes,No &aheight=Auto Height;list;,Yes,No &awidth=Auto Width;list;,Yes,No &stretch=Stretch To Fit;list;,Yes,No &class=Class;string &style=Style;string &attrib=Attributes;string';
    widgetParams['floater']     = '&x=Offset X;int &y=Offset Y;int &width=Width;string;200px &height=Height;string;30px &pos=Position;list;top-right,top-left,bottom-left,bottom-right &gs=Glide Speed;int;6 &class=Class;string &style=Style;string ';
    widgetParams['datagrid']    = '&cols=Column Names;string &flds=Field Names;string &cwidth=Column Widths;string &calign=Column Alignments;string &ccolor=Column Colors;string &ctype=Column Types;string &cpad=Cell Padding;int;1 &cspace=Cell Spacing;int;1 &rowid=Row ID Field;string &rgf=Row Group Field;string &rgstyle = Row Group Style;string &rgclass = Row Group Class;string &rowsel=Row Select;string &rhigh=Row Hightlight;string; &psize=Page Size;int;100 &ploc=Pager Location;list;top-right,top-left,bottom-left,bottom-right,both-right,both-left; &pclass=Pager Class;string &pstyle=Pager Style;string &head=Header Text;string &foot=Footer Text;string &tblc=Grid Class;string &tbls=Grid Style;string &itmc=Item Class;string &itms=Item Style;string &aitmc=Alt Item Class;string &aitms=Alt Item Style;string &chdrc=Column Header Class;string &chdrs=Column Header Style;string;&egmsg=Empty message;string;No records found;';
    widgetParams['richtext']    = '&w='+_('width')+';string;100% &h='+_('height')+';string;300px';
    widgetParams['image']       = '&alttext='+_('image_alt')+';string &hspace='+_('image_hspace')+';int &vspace='+_('image_vspace')+';int &borsize='+_('image_border_size')+';int &align='+_('image_align')+';list;'+_('image_align_list')+'&name='+_('name')+';string &class='+_('class')+';string &id='+_('id')+';string &style='+_('style')+';string &attrib='+_('attributes')+';string';

// Current Params
var currentParams = {};
var lastdf, lastmod = {};

function showParameters(cb,rc,i) {
    var c,p,df,cp;
    var ar,desc,value,key,dt;
    currentParams = {}; // reset;

    
    f= $('mutate_tv');
	if(!f) return;
    var params = Ext.get('params');
    cp = params.dom.value.split('&'); // load current setting once

    // get display format
    df = rc.data.value;

    // load last modified param values
    if (lastmod[df]) cp = lastmod[df].split('&');
    for(p = 0; p < cp.length; p++) {
        cp[p]=(cp[p]+'').replace(/^\s|\s$/,''); // trim
        ar = cp[p].split("=");
        currentParams[ar[0]]=ar[1];
    }
    // setup parameters
    tr = $('displayparamrow');
    dp = (widgetParams[df]) ? widgetParams[df].split('&'):'';
    if(!dp) tr.style.display='none';
    else {
        t='<table width="300" style="margin-bottom:3px;margin-left:14px;background-color:#EEEEEE" cellpadding="2" cellspacing="1"><thead><tr><td width="50%">'+_('parameter')+'</td><td width="50%">'+_('value')+'</td></tr></thead>';
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
                    case 'float':
                        c = '<input type="text" name="prop_'+key+'" value="'+value+'" size="30" onchange="setParameter(\''+key+'\',\''+dt+'\',this)" />';
                        break;
                    case 'list':
                        c = '<select name="prop_'+key+'" height="1" style="width:168px" onchange="setParameter(\''+key+'\',\''+dt+'\',this)">';
                        ls = (ar[2]+'').split(",");
                        if(!currentParams[key]||currentParams[key]=='undefined') {
                            currentParams[key] = ls[0]; // use first list item as default
                        }
                        for(i=0;i<ls.length;i++){
                            c += '<option value="'+ls[i]+'"'+((ls[i]==value)? ' selected="selected"':'')+'>'+ls[i]+'</option>';
                        }
                        c += '</select>';
                        break;
                    default:  // string
                        value = Ext.util.Format.htmlEncode(value);
                        c = '<input type="text" name="prop_'+key+'" value="'+value+'" size="30" onchange="setParameter(\''+key+'\',\''+dt+'\',this)" />';
                        break;

                }
                t +='<tr><td style="background-color:#FFFFFF;" width="50%">'+desc+'</td><td style="background-color:#FFFFFF;" width="50%">'+c+'</td></tr>';
            };
        }
        t+='</table>';
        td = $('displayparams');
        td.innerHTML = t;
        tr.style.display='';
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
        case 'float':
            ctrl.value = parseFloat(ctrl.value);
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
    $('mutate_tv').params.value = '';
    lastmod[lastdf]='';
    showParameters();
}
// implode parameters
function implodeParameters(){
    var v, p, s='';
    for(p in currentParams){
        v = currentParams[p];
        if(v) s += '&'+p+'='+ encode(v);
    }
    $('mutate_tv').params.value = s;
    if (lastdf) lastmod[lastdf] = s;
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
