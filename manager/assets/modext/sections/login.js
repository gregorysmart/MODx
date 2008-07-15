Ext.onReady(function() {
    if (top.frames.length!=0) {
        top.location=self.document.location;
    }
});
var loginHandler = function(opt,s,r) {
    var r = Ext.decode(r.responseText);
    if (r.success) {
       top.document.location.href = (r.object.id != undefined) ? './index.php?id=' + r.object.id : './';
    } else FormHandler.errorExt(r);
}
var doLogin = function() {
    return FormHandler.send('loginfrm', 'login', loginHandler);
}