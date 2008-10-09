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
        ,renderTo: Ext.getBody()
        ,items: [
            new Ext.BoxComponent({
                region: 'north'
                ,el: 'modx_tm_div'
                ,height: 26
                ,margin: '0 0 0 0'
            }),{
                region: 'center'
                ,el: 'modx_content_div'
                ,autoHeight: true
                ,layout: 'fit'
                ,minSize: 250
            },{
                region: 'west'
                ,id: 'west-panel'
                ,split: true
                ,width: '25%'
                ,minSize: 200
                ,collapsible: true
                ,layout: 'accordion'
                ,layoutConfig: { 
                    animate: true
                    ,autoWidth: true
                    ,titleCollapse: true
                }
                ,defaults: { 
                    border: false
                    ,autoScroll: true
                    ,fitToFrame: true
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
            }
        ]
    });
    MODx.Layout.superclass.constructor.call(this,config);
    
    this.loadTopBar();
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
            ,width:'100%'
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
});
Ext.reg('modx-layout',MODx.Layout);