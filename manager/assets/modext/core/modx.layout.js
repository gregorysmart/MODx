/**
 * Loads the MODx Ext-driven Layout
 * 
 * @class MODx.Layout
 * @extends Ext.Viewport
 * @param {Object} config An object of config options.
 * @xtype modx-layout
 */
MODx.Layout = function(config){
    config = config || {};
    this.config = config;
    Ext.BLANK_IMAGE_URL = MODx.config.manager_url+'assets/ext2/resources/images/default/s.gif';
    
    this.loadTrees();
    
    Ext.applyIf(config,{
        id: 'modx-layout'
    });
    MODx.Layout.superclass.constructor.call(this,config);
    this.loadKeys();
};
Ext.extend(MODx.Layout,Ext.Component,{    
    loadTrees: function() {
        var a = Ext.get('modx-accordion');
        if (!a) return;
        
        this.rtree = MODx.load({
            xtype: 'modx-tree-resource'
            ,el: 'modx_resource_tree'
            ,id: 'modx_resource_tree'
        });
        this.eltree = MODx.load({
            xtype: 'modx-tree-element'
            ,el: 'modx_element_tree'
            ,id: 'modx_element_tree' 
        });
        this.ftree = MODx.load({
            xtype: 'modx-tree-directory'
            ,el: 'modx_file_tree'
            ,id: 'modx_file_tree'
            ,hideFiles: false
            ,title: ''
        });
        
        MODx.load({
            xtype: 'panel'
            ,applyTo: 'modx-accordion-content'
            ,minSize: 100
            ,minHeight: 300
            ,maxHeight: 400
            ,split: true
            ,collapsible: true
            ,hideBorders: true
            ,resizable: true
            ,stateful: false
            ,autoHeight: true
            ,layout: 'accordion'
            ,border: false
            ,layoutConfig: { 
                animate: true
                ,autoWidth: true
                ,autoScroll: true
                ,titleCollapse: true
                ,fill: true
            }
            ,defaults: {
                autoScroll: true
                ,fitToFrame: true
                ,autoHeight: true
                ,maxHeight: 450
                ,height: 450
                ,cls: 'modx-accordion-panel'
            }
            ,items: this.setupAccordion()
        });
    }
    
    ,loadKeys: function() {
        Ext.KeyMap.prototype.stopEvent = true;
        var k = new Ext.KeyMap(Ext.get(document));        
        k.addBinding({
            key: Ext.EventObject.H
            ,ctrl: true
            ,shift: Ext.isMac ? false : true
            ,fn: this.toggleAccordion
            ,scope: this
            ,stopEvent: true
        });
        k.addBinding({
            key: Ext.EventObject.N
            ,ctrl: true
            ,shift: Ext.isMac ? false : true
            ,fn: function() {
                Ext.getCmp('modx_resource_tree').quickCreate(document,{},'modResource','web',0);
            }
            ,stopEvent: true
        });
        k.addBinding({
            key: Ext.EventObject.Z
            ,ctrl: true
            ,shift: Ext.isMac ? false : true
            ,fn: MODx.clearCache
            ,scope: this
            ,stopEvent: true
        });
    }
    
    ,refreshTrees: function() {
        this.rtree.refresh();
        this.eltree.refresh();
        this.ftree.refresh();
    }
    
    ,setupAccordion: function() {
        var it = [];
        var lps = MODx.loadAccordionPanels();
        if (lps.length > 0) {
            for(var x=0;x<lps.length;x=x+1) {
                it.push(lps[x]);
            }
        }
        
        it.push({                
            title: _('web_resources')
            ,contentEl: 'modx_rt_div'
            ,resizeEl: 'modx_resource_tree'
            ,id: 'modx-resource-tree-panel'
        });
        it.push({
            title: _('content_elements')
            ,contentEl: 'modx_et_div'
            ,resizeEl: 'modx_element_tree'
            ,id: 'modx-element-tree-panel'
        });
        it.push({
            title: _('files')
            ,contentEl: 'modx_ft_div'
            ,resizeEl: 'modx_file_tree'
            ,id: 'modx-file-tree-panel'
        });
        
        return it;
    }
    ,accordionVisible: true
    ,toggleAccordion: function() {
        this.accordionVisible ? this.removeAccordion(.3) : this.showAccordion(.3);
        this.accordionVisible = !this.accordionVisible;
    }
    ,removeAccordion: function(d) {
        this.cleanupContent(false);
        Ext.get('modx-accordion').slideOut('l',{
            remove: false
            ,useDisplay: true
            ,duration: d || .1
        });
    }
    ,showAccordion: function(d) {
        this.cleanupContent(true);
        Ext.get('modx-accordion').slideIn('l',{
            remove: false
            ,useDisplay: true
            ,duration: d || .1
        });
    }
    ,cleanupContent: function(mode) {
        var c = Ext.get('modx-content');
        c.setStyle('width',mode ? '74%' : '98%');
        c.repaint();
        Ext.select('.x-portlet, .x-column-inner, .x-panel-body').each(function(el,ar,i) {
            el.setStyle('width','100%');
            el.repaint();
        },this);
        Ext.select('.x-portal-column').each(function(el,ar,i) {
            el.setStyle('width','97%');
            el.repaint();
        },this);
    }
});
Ext.reg('modx-layout',MODx.Layout);

/**
 * Handles layout functions. In module format for easier privitization.
 * @class MODx.LayoutMgr
 */
MODx.LayoutMgr = function() {
    var _activeMenu = 'menu0';    
    return {
        loadPage: function(a,p) {
            location.href = '?a='+a+'&'+(p || '');
            return false;
        }
        ,changeMenu: function(a,sm) {
            if (sm === _activeMenu) return false;
            
            Ext.get(sm).addClass('active');
            var om = Ext.get(_activeMenu);
            if (om) om.removeClass('active');
            _activeMenu = sm;
            return false;
        }
    }
}();

/* aliases for quicker reference */
MODx.loadPage = MODx.LayoutMgr.loadPage;
MODx.showDashboard = MODx.LayoutMgr.showDashboard;
MODx.hideDashboard = MODx.LayoutMgr.hideDashboard;
MODx.changeMenu = MODx.LayoutMgr.changeMenu;