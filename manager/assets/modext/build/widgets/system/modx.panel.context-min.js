MODx.panel.Context=function(A){A=A||{};Ext.applyIf(A,{url:MODx.config.connectors_url+"context/index.php",baseParams:{},id:"panel-context",class_key:"modContext",plugin:"",bodyStyle:"",defaults:{collapsible:false,autoHeight:true},items:{xtype:"tabpanel",activeTab:0,deferredRender:false,border:false,defaults:{autoHeight:true,layout:"form",bodyStyle:"padding: 1.5em;",labelWidth:150},items:[{title:_("general_information"),defaults:{border:false,msgTarget:"side"},items:[{html:"<h2>"+_("context")+": "+A.context+"</h2>",id:"context-name"},{xtype:"hidden",name:"key",value:A.context},{xtype:"textfield",fieldLabel:_("key"),name:"key",width:300,maxLength:255,enableKeyEvents:true,allowBlank:false,listeners:{keyup:{scope:this,fn:function(B,C){Ext.getCmp("context-name").getEl().update("<h2>"+_("context")+": "+B.getValue()+"</h2>")}}},value:A.context},{xtype:"textarea",fieldLabel:_("description"),name:"description",width:300,grow:true}]},{title:_("context_settings"),items:[{html:"<h2>"+_("context_settings")+"</h2>",border:false},{xtype:"grid-context-settings",id:"grid-context-setting",preventRender:true,context_key:A.context}]}]},listeners:{setup:{fn:this.setup,scope:this}}});MODx.panel.Context.superclass.constructor.call(this,A)};Ext.extend(MODx.panel.Context,MODx.FormPanel,{setup:function(){if(this.config.context==""||this.config.context==0){return }Ext.Ajax.request({url:this.config.url,params:{action:"get",key:this.config.context},scope:this,success:function(B){B=Ext.decode(B.responseText);if(B.success){this.getForm().setValues(B.object);var A=Ext.getCmp("context-name");if(A){A.getEl().update("<h2>"+_("context")+": "+B.object.key+"</h2>")}}else{MODx.form.Handler.errorJSON(B)}}})}});Ext.reg("panel-context",MODx.panel.Context);