Ext.namespace('MODx');
Ext.onReady(function() {    
    new MODx.Layout();
});
/**
 * Loads the MODx Ext-driven Layout
 * 
 * @class MODx.Layout
 * @extends Ext.Viewport
 * @constructor
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
            },{
                region: 'west'
                ,id: 'west-panel'
                ,split: true
                ,width: '25%'
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
                    title: _('documents')
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
        new MODx.toolbar.TopMenu();
    }
    
    /**
     * Loads the trees for the layout
     * 
     * @access protected
     */
    ,loadTrees: function() {
        this.rtree = new MODx.tree.Document({
            el: 'modx_resource_tree'
            ,id: 'modx_document_tree'
        });
        this.eltree = new MODx.tree.Element({
            el: 'modx_element_tree'
            ,id: 'modx_element_tree' 
        });
        this.ftree = new MODx.tree.Directory({
            el: 'modx_file_tree'
            ,id: 'modx_file_tree'
            ,title: ''
        });
    }
});
Ext.reg('modx-layout',MODx.Layout);