/**
 * @class MODx.panel.PackageDownload
 * @extends MODx.Panel
 * @param {Object} config An object of config properties
 * @xtype panel-package-download
 */
MODx.panel.PackageDownload = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        border: false
        ,layout: 'column'
        ,height: 400
        ,items: [{
            xtype: 'tree-package-download'
            ,id: 'tree-package-download'
            ,columnWidth: 0.7
            ,width: '60%'
            ,height: 400
            ,autoHeight: false
        },{
            columnWidth: 0.3
            ,height: 400
            ,width: '35%'
            ,border: false
            ,autoScroll: true
            ,items: [{
                id: 'panel-package-info'
                ,xtype: 'panel'
                ,style: 'padding: ".5em"'
                ,height: 400
                ,autoScroll: true
                ,html: ''
            }]
        }]
    });
    MODx.panel.PackageDownload.superclass.constructor.call(this,config);
    this.loadTemplates();   
    
    var t = Ext.getCmp('tree-package-download');
    t.on('click',function(n,e) {
        var p = Ext.getCmp('panel-package-info');
        var detailEl = p.body;
        if(n && n.attributes){
            var data = n.attributes;
            if (this.tpls[data.type]) {
                detailEl.hide();
                this.tpls[data.type].overwrite(detailEl, data.data || {});
                detailEl.slideIn('l', {stopFx:true,duration:'.2'});
            }
        } else {
            detailEl.update('');
        }
    },this);
};
Ext.extend(MODx.panel.PackageDownload,MODx.Panel,{
    tpls: {}
    
    ,loadTemplates: function() {
        this.tpls = {
            version: new Ext.XTemplate(
                '<div class="details" style="padding: 1em;">'
                ,'<tpl for=".">'
                    ,'<div class="details-info">'
                    ,'<h3>{name}</h3>'
                    ,'<b>'+_('version')+':</b>&nbsp;'
                    ,'<span>{version}</span><br />'
                    ,'<b>'+_('release')+':</b>&nbsp;'
                    ,'<span>{release}</span><br />'
                    ,'<b>Release Date:</b>&nbsp;'
                    ,'<span>{releasedon}</span>'
                    ,'<br /><br /><p>{description}</p></div>'
                ,'</tpl>'
                ,'</div>'
            )
            ,'package': new Ext.XTemplate(
                '<div class="details" style="padding: 1em;">'
                ,'<tpl for=".">'
                    ,'<div class="details-info">'
                    ,'<h3>{name}</h3>'
                    ,'<p>{description}</p></div>'
                ,'</tpl></div>'
            )
            ,category: new Ext.XTemplate(
                '<div class="details" style="padding: 1em;">'
                ,'<tpl for=".">'
                    ,'<div class="details-info">'
                    ,'<h3>{name}</h3>'
                    ,'<p>{description}</p></div>'
                ,'</tpl></div>'
            ) 
            ,repository: new Ext.XTemplate(
                '<div class="details" style="padding: 1em;">'
                ,'<tpl for=".">'
                    ,'<div class="details-info">'
                    ,'<h3>{name}</h3>'
                    ,'<p>{description}</p></div>'
                ,'</tpl></div>'
            )
        };
        for (var i in this.tpls) { this.tpls[i].compile(); } 
    }
    
});
Ext.reg('panel-package-download',MODx.panel.PackageDownload);

/**
 * @class MODx.tree.PackageDownload
 * @extends MODx.tree.CheckboxTree
 * @param {Object} config An object of config properties
 * @xtype tree-package-download
 */
MODx.tree.PackageDownload = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'tree-package-download'
        ,baseParams: {
            action: 'getPackages'
            ,provider: ''
        }
    });
    MODx.tree.PackageDownload.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.PackageDownload,MODx.tree.CheckboxTree,{
    setProvider: function(p) {
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'workspace/providers.php'
            ,params: {
                action: 'getPackages'
                ,provider: p
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.loadRemoteData(r.object);
                },scope:this}
            }
        });
    }
});
Ext.reg('tree-package-download',MODx.tree.PackageDownload);