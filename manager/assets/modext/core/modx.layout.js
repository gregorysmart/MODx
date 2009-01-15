Ext.onReady(function() {    
    MODx.load({ xtype: 'modx-layout' });
});
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
        
    if (!Ext.isIE) {
        Ext.get('modx-dashboard').fadeIn();
        Ext.get('modx-frame-ct').fadeOut();
        Ext.get('modx-container').fadeOut();
    }
    
    this.loadTrees();
    
    Ext.applyIf(config,{
        id: 'modx-layout'
    });
    MODx.Layout.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Layout,Ext.Component,{        
    /**
     * Loads the trees for the layout
     * 
     * @access protected
     */
    loadTrees: function() {
        this.rtree = MODx.load({
            xtype: 'tree-resource'
            ,el: 'modx_resource_tree'
            ,id: 'modx_resource_tree'
        });
        this.eltree = MODx.load({
            xtype: 'tree-element'
            ,el: 'modx_element_tree'
            ,id: 'modx_element_tree' 
        });
        this.ftree = MODx.load({
            xtype: 'tree-directory'
            ,el: 'modx_file_tree'
            ,id: 'modx_file_tree'
            ,hideFiles: false
            ,title: ''
        });
        
        MODx.load({
            xtype: 'panel'
            ,applyTo: 'modx-trees-div'
            ,cls: 'modx-accordion'
            ,minSize: 100
            ,minHeight: 300
            ,maxHeight: 400
            ,split: true
            ,collapsible: true
            ,hideBorders: true
            ,resizable: true
            ,stateful: false
            ,autoHeight: true
            ,margin: '0 0 0 0'
            ,anchor: '1 1'
            ,layout: 'accordion'
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
            }
            ,items: [{                
                title: _('resources')
                ,contentEl: 'modx_rt_div'
                ,resizeEl: 'modx_resource_tree'
            },{
                title: _('elements')
                ,contentEl: 'modx_et_div'
                ,resizeEl: 'modx_element_tree'
            },{
                title: _('files')
                ,contentEl: 'modx_ft_div'
                ,resizeEl: 'modx_file_tree'
            }]
        });
    }
    
    ,refreshTrees: function() {
        this.rtree.refresh();
        this.eltree.refresh();
        this.ftree.refresh();
    }
});
Ext.reg('modx-layout',MODx.Layout);

/**
 * Handles layout functions. In module format for easier privitization.
 * @class MODx.LayoutMgr
 */
MODx.LayoutMgr = function() {
    var _activeMenu = 'menu0';
    var _dashboardActive = Ext.isIE ? false : true;
    
    return {
        loadFrame: function(a,p) {
            Ext.get('modx-frame').dom.src = '?a='+a+'&'+(p || '');
            if (!Ext.isIE) { 
                this.hideDashboard();
            }
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
        ,showDashboard: function() {
            if (_dashboardActive) return false;
            var o = { duration: .3 };
            Ext.get('modx-container').fadeOut(o);
            Ext.get('modx-frame-ct').fadeOut(o);
            Ext.get('modx-dashboard').fadeIn(o);
            _dashboardActive = true;
            return false;
        }
        ,hideDashboard: function() {
            if (!_dashboardActive) return false;
            var o = { duration: .3 };
            Ext.get('modx-container').fadeIn(o);
            Ext.get('modx-frame-ct').fadeIn(o);
            Ext.get('modx-dashboard').fadeOut(o);
            _dashboardActive = false;
            return false;
        }
    }
}();

/* aliases for quicker reference */
MODx.loadFrame = MODx.LayoutMgr.loadFrame;
MODx.showDashboard = MODx.LayoutMgr.showDashboard;
MODx.hideDashboard = MODx.LayoutMgr.hideDashboard;
MODx.changeMenu = MODx.LayoutMgr.changeMenu;