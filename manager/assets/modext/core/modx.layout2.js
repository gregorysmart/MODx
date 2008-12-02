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
    
    this.createIFrame();
    this.loadTrees();
    
    Ext.applyIf(config,{
        layout: 'border'
        ,renderTo: 'modx-container'
        ,id: 'modx-layout'
        ,cls: 'modx-container'
        ,border: false
        ,items: [
            new Ext.BoxComponent({
                region: 'top'
                ,applyTo: 'modx-header'
                ,cls: 'modx-header'
                ,height: 100
                ,margin: '0 0 0 0'
            }),{
                region: 'center'
                ,layout: 'fit'
                ,applyTo: 'modx_content_div'
                ,autoHeight: true
                ,minSize: 250
            },{
                region: 'west'
                ,applyTo: 'modx-trees-div'
                ,cls: 'modx-accordion'
                ,width: '95%'
                ,autoHeight: true
                ,minSize: 200
                ,collapsible: true
                ,resizable: true
                ,layout: 'accordion'
                ,layoutConfig: { 
                    animate: true
                    ,autoWidth: true
                    ,titleCollapse: true
                }
                ,defaults: { 
                    border: false
                    ,autoHeight: true
                    ,autoScroll: true
                    ,fitToFrame: true
                }
                ,items: [{
                    title: _('resources')
                    //,xtype: 'tree-resource'
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
            }
        ]
    });
    MODx.Layout.superclass.constructor.call(this,config);
        
    //this.loadTopBar();
};
Ext.extend(MODx.Layout,Ext.Viewport,{
    /**
     * Creates the iframe in which the main content is loaded
     * 
     * @access protected
     */
    createIFrame: function() {
        Ext.DomHelper.insertFirst(Ext.get('modx_content_div'),{
            tag: 'iframe'
            ,id: 'modx_content'
            ,frameBorder: 0
            ,height:'100%'
            ,width:'98%'
            ,anchor:'1 1'
            ,style: 'padding:0; margin:0; border: 0; background: white;'
            ,src: MODx.config.manager_url+'index.php?a='+(this.config.start || '1')
        });
    }
    
    /**
     * Loads the topbar
     * 
     * @access protected
     */
    ,loadTopBar: function() {
        MODx.load({ xtype: 'modx-topmenu' });
    }
    
    /**
     * Loads the trees for the layout
     * 
     * @access protected
     */
    ,loadTrees: function() {
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
    }
    
    ,refreshTrees: function() {
        this.rtree.refresh();
        this.eltree.refresh();
        this.ftree.refresh();
    }
});
Ext.reg('modx-layout',MODx.Layout);


/* TODO: redo these into some sort of class to make quicker */
MODx.loadFrame = function(a,p) {
    Ext.get('modx_content').dom.src = '?a='+a+'&'+(p || '');
    MODx.hideDashboard();
    return false;
};
MODx.activeMenu = 'menu0';
MODx.changeMenu = function(a,sm) {
    if (sm === MODx.activeMenu) return false;
    
    Ext.get(sm).addClass('active');
    var om = Ext.get(MODx.activeMenu);
    if (om) om.removeClass('active');
    MODx.activeMenu = sm;
    return false;
};
MODx.logout = function() {
    MODx.msg.confirm({
        title: _('logout')
        ,text: _('logout_confirm')
        ,url: MODx.config.connectors_url+'security/logout.php'
        ,params: {
            action: 'logout'
        }
        ,listeners: {
            'success': {fn:function() { location.href = '../'; },scope:this}
        }
    });
};

MODx.showDashboard = function() {
    if (MODx.dashboardActive) return false;
    var o = { duration: .3 };
    Ext.get('modx-container').fadeOut(o);
    Ext.get('modx-frame-ct').fadeOut(o);
    Ext.get('modx-dashboard').fadeIn(o);
    MODx.dashboardActive = true;
};
MODx.hideDashboard = function() {
    if (!MODx.dashboardActive) return false;
    var o = { duration: .3 };
    Ext.get('modx-container').fadeIn(o);
    Ext.get('modx-frame-ct').fadeIn(o);
    Ext.get('modx-dashboard').fadeOut(o);
    MODx.dashboardActive = false;
};

Ext.onReady(function() {
    Ext.get('modx-dashboard').fadeOut();
});