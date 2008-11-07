var RM = function(config) {
	config = config || {};
	RM.superclass.constructor.call(this,config);
};
Ext.extend(RM,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {}
});
Ext.reg('rm',RM);

var RM = new RM();