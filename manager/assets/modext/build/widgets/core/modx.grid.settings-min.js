MODx.grid.SettingsGrid=function(A){A=A||{};var B=new Ext.grid.RowExpander({tpl:new Ext.Template('<p style="padding: .7em 1em .3em;"><i>{description}</i></p>')});if(!A.tbar){A.tbar=[{text:_("setting_create"),scope:this,handler:{xtype:"window-setting-create",url:A.url||MODx.config.connectors_url+"system/settings.php"}}]}A.tbar.push("->",{xtype:"textfield",name:"filter_key",id:"filter_key",emptyText:_("search")+"...",listeners:{change:{fn:this.filterByKey,scope:this},render:{fn:this._addEnterKeyHandler}}},{xtype:"button",id:"filter_clear",text:_("filter_clear"),listeners:{click:{fn:this.clearFilter,scope:this}}});Ext.applyIf(A,{title:_("settings"),url:MODx.config.connectors_url+"system/settings.php",baseParams:{action:"getList"},fields:["key","name","value","description","xtype","namespace","editedon","oldkey","menu"],paging:true,autosave:true,remoteSort:true,primaryKey:"key",viewConfig:{forceFit:true,autoFill:true,showPreview:true,enableRowBody:true},plugins:B,columns:[B,{header:_("name"),dataIndex:"name",width:250,editor:{xtype:"textfield"}},{header:_("value"),id:"value",dataIndex:"value",width:150,renderer:this.renderDynField.createDelegate(this,[this],true)},{header:_("key"),dataIndex:"key",width:100,sortable:true},{header:_("last_modified"),dataIndex:"editedon",width:100,sortable:true}]});MODx.grid.SettingsGrid.superclass.constructor.call(this,A);this.removeListener("celldblclick",this.onCellDblClick,this);this.on("celldblclick",this.changeEditor,this)};Ext.extend(MODx.grid.SettingsGrid,MODx.grid.Grid,{_addEnterKeyHandler:function(){this.getEl().addKeyListener(Ext.EventObject.ENTER,function(){this.fireEvent("change")},this)},clearFilter:function(){this.getStore().baseParams={action:"getList"};this.refresh();this.getBottomToolbar().changePage(1)},filterByKey:function(C,B,A){this.getStore().baseParams={action:"getList",key:B};this.refresh();this.getBottomToolbar().changePage(1)},changeEditor:function(E,B,C,F){var A=this.getColumnModel();if(A.getColumnId(C)!="value"){this.onCellDblClick(E,B,C,F)}else{F.preventDefault();var D=this.getStore().getAt(B).data;this.initEditor(A,C,B,D);this.startEditing(B,C)}},initEditor:function(A,D,B,E){A.setEditable(D,true);var F=Ext.ComponentMgr.create({xtype:E.xtype||"textfield"});var C=new Ext.grid.GridEditor(F);A.setEditor(D,C)},startEditing:function(F,B){this.stopEditing();if(this.colModel.isCellEditable(B,F)){this.view.ensureVisible(F,B,true);var C=this.store.getAt(F);var E=this.colModel.getDataIndex(B);var D={grid:this,record:C,field:E,value:C.data[E],row:F,column:B,cancel:false};if(this.fireEvent("beforeedit",D)!==false&&!D.cancel){this.editing=true;var A=this.colModel.getCellEditor(B,F);if(!A.rendered){A.render(this.view.getEditorParent(A))}(function(){A.row=F;A.col=B;A.record=C;A.on("complete",this.onEditComplete,this);A.on("specialkey",this.selModel.onEditorKey,this.selModel);this.activeEditor=A;var G=this.preEditValue(C,E);A.startEdit(this.view.getCell(F,B).firstChild,G)}).defer(50,this)}}},renderDynField:function(J,G,C,H,L,K,E){var A=K.getAt(H).data;if(A.xtype=="combo-boolean"){var F=MODx.grid.Grid.prototype.rendYesNo;return F(J==1?true:false,G)}else{if(A.xtype==="datefield"){var F=Ext.util.Format.dateRenderer("Y-m-d");return F(J)}else{if(A.xtype.substr(0,5)=="combo"){var I=E.getColumnModel();var D=I.getCellEditor(L,H);if(!D){var B=Ext.ComponentMgr.create({xtype:A.xtype||"textfield"});D=new Ext.grid.GridEditor(B);I.setEditor(L,D)}var F=MODx.combo.Renderer(D.field);return F(J)}}}return J}});Ext.reg("modx-grid-settings",MODx.grid.SettingsGrid);MODx.window.CreateSetting=function(A){A=A||{};Ext.applyIf(A,{title:_("setting_create"),width:400,url:A.url,action:"create",fields:[{xtype:"hidden",name:"fk",value:A.fk||0},{xtype:"textfield",fieldLabel:_("key"),name:"key",maxLength:100},{xtype:"textfield",fieldLabel:_("name"),name:"name",allowBlank:false},{xtype:"combo-xtype",fieldLabel:_("xtype"),description:_("xtype_desc")},{xtype:"combo-namespace",fieldLabel:_("namespace"),name:"namespace",value:"core"},{xtype:"textfield",fieldLabel:_("value"),name:"value"},{xtype:"textarea",fieldLabel:_("description"),name:"description",allowBlank:true,width:225}]});MODx.window.CreateSetting.superclass.constructor.call(this,A)};Ext.extend(MODx.window.CreateSetting,MODx.Window);Ext.reg("window-setting-create",MODx.window.CreateSetting);Ext.override(Ext.PagingToolbar,{doLoad:function(C){var B={},A=this.paramNames;B[A.start]=C;B[A.limit]=this.pageSize;this.store.load({params:B,scope:this,callback:function(){this.store.reload()}})}});MODx.combo.xType=function(A){A=A||{};Ext.applyIf(A,{store:new Ext.data.SimpleStore({fields:["d","v"],data:[[_("textfield"),"textfield"],[_("textarea"),"textarea"],[_("yesno"),"combo-boolean"]]}),displayField:"d",valueField:"v",mode:"local",name:"xtype",hiddenName:"xtype",triggerAction:"all",editable:false,selectOnFocus:false,value:"textfield"});MODx.combo.xType.superclass.constructor.call(this,A)};Ext.extend(MODx.combo.xType,Ext.form.ComboBox);Ext.reg("combo-xtype",MODx.combo.xType);MODx.window.UpdateSetting=function(A){A=A||{};Ext.applyIf(A,{title:_("setting_update"),width:400,url:A.grid.config.url,action:"update",fields:[{xtype:"hidden",name:"fk",value:A.fk||0},{xtype:"statictextfield",fieldLabel:_("key"),name:"key",submitValue:true},{xtype:"textfield",fieldLabel:_("name"),name:"name",allowBlank:false},{xtype:"combo-xtype",name:"xtype",hiddenName:"xtype",fieldLabel:_("xtype"),description:_("xtype_desc")},{xtype:"combo-namespace",fieldLabel:_("namespace"),name:"namespace",value:"core"},{xtype:"textfield",fieldLabel:_("value"),name:"value"},{xtype:"textarea",fieldLabel:_("description"),name:"description",allowBlank:true,width:225}]});MODx.window.UpdateSetting.superclass.constructor.call(this,A)};Ext.extend(MODx.window.UpdateSetting,MODx.Window);Ext.reg("window-setting-update",MODx.window.UpdateSetting);