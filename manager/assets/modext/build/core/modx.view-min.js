MODx.DataView=function(A){A=A||{};this._loadStore(A);Ext.applyIf(A.listeners||{},{loadexception:{fn:this.onLoadException,scope:this},beforeselect:{fn:function(B){return B.store.getRange().length>0}},contextmenu:{fn:this._showContextMenu,scope:this}});Ext.applyIf(A,{store:this.store,singleSelect:true,overClass:"x-view-over",itemSelector:"div.thumb-wrap",emptyText:'<div style="padding:10px;">'+_("file_err_filter")+"</div>"});MODx.DataView.superclass.constructor.call(this,A);this.config=A;this.cm=new Ext.menu.Menu(Ext.id())};Ext.extend(MODx.DataView,Ext.DataView,{lookup:{},onLoadException:function(){this.getEl().update('<div style="padding:10px;">'+_("data_err_load")+"</div>")},_addContextMenuItem:function(items){var a=items,l=a.length;for(var i=0;i<l;i++){var options=a[i];if(options=="-"){this.cm.add("-");continue}if(options.handler){var h=eval(options.handler)}else{var h=function(itm,e){var o=itm.options;var id=this.cm.activeNode.id.split("_");id=id[1];var w=Ext.get("modx_content");if(o.confirm){Ext.Msg.confirm("",o.confirm,function(e){if(e=="yes"){var a=Ext.urlEncode(o.params||{action:o.action});var s="index.php?id="+id+"&"+a;if(w==null){location.href=s}else{w.dom.src=s}}},this)}else{var a=Ext.urlEncode(o.params);var s="index.php?id="+id+"&"+a;if(w==null){location.href=s}else{w.dom.src=s}}}}this.cm.add({id:options.id,text:options.text,scope:this,options:options,handler:h})}},_loadStore:function(A){this.store=new Ext.data.JsonStore({url:A.url,baseParams:A.baseParams||{action:"getList"},root:A.root||"results",fields:A.fields,listeners:{load:{fn:function(){this.select(0)},scope:this,single:true}}});this.store.load()},_showContextMenu:function(B,C,F,E){E.preventDefault();var D=this.lookup[F.id];var A=this.cm;A.removeAll();if(D.menu){this._addContextMenuItem(D.menu);A.show(F,"t?")}A.activeNode=F}});Ext.reg("modx-dataview",MODx.DataView);