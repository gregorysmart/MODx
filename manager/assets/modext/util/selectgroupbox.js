/**
 * This code is a marriage of GroupComboBox and SelectBox,
 * both third party contributions to the EXT forum.  I started with
 * SelectBox, which is a very clean extension for making the combo
 * behave like a normal HTTP select box, and then extracted the excellent
 * parts of GroupComboBox that provide the support for optgroups.
 * 
 * The pastor who married them:  lgerndt
 */
Ext.form.SelectGroupBox = function(config){
    this.searchResetDelay = 1000;
    config = config || {};
    config = Ext.apply(config || {}, {
        editable: false,
        forceSelection: true,
        rowHeight: false,
        lastSearchTerm: false,
        triggerAction: 'all'
    });
    
    // the records in our combo have an 'optgroup' field and a 'text' field.  If the 'optgroup' field is not empty, the 'text' field
    // will be, and vice versa.  We use this fact to render the group headers with a different class than the items in the group.
    var cls = 'x-combo-list';
    this.tpl = new Ext.XTemplate(
        '<tpl for=".">',
        
        // if the length of the 'optgroup' field is non-zero, render an optgroup title div
        '<tpl if="optgroup.length &gt; 0">',
            '<div class="'+cls+'-item x-combo-list-hd">{' + config.groupField + '}</div>',
        '</tpl>',
        
        // if the length of the 'text' field is non-zero, render an item div
        '<tpl if="text.length &gt; 0">',
            '<div class="'+cls+'-item x-combo-list-groupitem">{' + config.displayField + '}</div>',
        '</tpl>',
        
        '</tpl>'
    );
    Ext.form.SelectGroupBox.superclass.constructor.apply(this, arguments);
    
    this.lastSelectedIndex = this.selectedIndex || 0;
};

Ext.extend(Ext.form.SelectGroupBox, Ext.form.ComboBox, {
    lazyInit: false,
    
    //-----------------------------------------------------------------------------------------------
    // from GroupComboBox
    //-----------------------------------------------------------------------------------------------

    groupField: undefined,
    
    initEvents : function(){
        Ext.form.SelectGroupBox.superclass.initEvents.apply(this, arguments);
        // you need to use keypress to capture upper/lower case and shift+key, but it doesn't work in IE
        this.el.on('keydown', this.keySearch, this, true);
        this.on('beforeselect', this.beforeSelect, this, true);
        this.cshTask = new Ext.util.DelayedTask(this.clearSearchHistory, this);
    },

    onViewClick : function(doFocus)
    {
        var index = this.view.getSelectedIndexes()[0];
        var r = this.store.getAt(index);
        if(r)
        {
            if(r.data.optgroup.length)
            {
                this.selectNext();
            }
            else
            {
                this.onSelect(r, index);
            }
        }
        if(doFocus !== false){
            this.el.focus();
        }
    },
    
    
    onViewOver : function(e, t)
    {
        if(this.inKeyMode){ // prevent key nav and mouse over conflicts
            return;
        }
        var item = this.view.findItemFromChild(t);
        if(item){
            var index = this.view.indexOf(item);
            //console.log(index);
            if(Ext.get(item).hasClass('x-combo-list-hd')) 
            {             
                //this.selectNext();
            }
            else
            {
                this.select(index, false);
            }
        }
    },
    
    selectNext : function(){
        var ct = this.store.getCount();
        if(ct > 0)
        {
            var index = this.selectedIndex;
            if(index < ct-1)
            {
                var r = this.store.getAt(index+1);
                if(r.data.optgroup.length)
                {
                    this.selectedIndex += 1;
                    this.selectNext();
                }
                else
                {
                    this.select(index+1);
                }
            }
            else
            {
                this.selectedIndex = -1;
                this.selectNext();
            }
        }
    },

    selectPrev : function()
    {
        var ct = this.store.getCount();
        if(ct > 0)
        {
            var index = this.selectedIndex;
            var r;
            if(index === 0)
            {
                r = this.store.getAt(ct-1);
                if(r.data.optgroup.length)
                {
                    this.selectedIndex = ct;
                    this.selectPrev();
                }
                else
                {
                    this.select(ct-1);
                }
            }
            else
            {
                r = this.store.getAt(index-1);
                if(r.data.optgroup.length)
                {
                    this.selectedIndex -= 1;
                    this.selectPrev();
                }
                else
                {
                    this.select(index-1);
                }
            }
        }
    },
   
    beforeSelect : function(combo, record, index){
        if (record.data.text == MYCOMPANY.Resources.Common.Labels.editCategories)
        {
            // Because the author implemented a handler for mouseup, he introduced a sort of bug:
            // we can get called here twice, once while expanded, and again after closed.  We only want
            // to do Add/Edit categories... once, so we check for expanded, but we want to return false
            // both times, so that this item never stays selected.
            if (combo.isExpanded())
            {
                this.collapse();
                window.location["href"] = MYCOMPANY.Url.getBasePath() + "pages/main/edit-categories.jsf";
            }
            return false; // return false to cancel the selection of this item
        }
        return true;
    },
   
   onLoad : function(){
        if(!this.hasFocus){
            return;
        }
        if(this.store.getCount() > 0){
            this.expand();
            this.restrictHeight();
            if(this.lastQuery == this.allQuery){
                if(this.editable){
                    this.el.dom.select();
                }
                if(!this.selectByValue(this.value, true)){
                    this.selectNext(); // changed from this.select(0, true);
                }
            }else{
                this.selectNext();
                if(this.typeAhead && this.lastKey != Ext.EventObject.BACKSPACE && this.lastKey != Ext.EventObject.DELETE){
                    this.taTask.delay(this.typeAheadDelay);
                }
            }
        }else{
            this.onEmptyResults();
        }
    },
    
    //-----------------------------------------------------------------------------------------------
    // from SelectBox
    //-----------------------------------------------------------------------------------------------

    keySearch : function(e, target, options) {
        var raw = e.getKey();
        var key = String.fromCharCode(raw);
        var startIndex = 0;

        if( !this.store.getCount() ) {
            return;
        }

        switch(raw) {
            case Ext.EventObject.HOME:
                e.stopEvent();
                this.selectFirst();
                return;

            case Ext.EventObject.END:
                e.stopEvent();
                this.selectLast();
                return;

            case Ext.EventObject.PAGEDOWN:
                this.selectNextPage();
                e.stopEvent();
                return;

            case Ext.EventObject.PAGEUP:
                this.selectPrevPage();
                e.stopEvent();
                return;
        }

        // skip special keys other than the shift key
        if( (e.hasModifier() && !e.shiftKey) || e.isNavKeyPress() || e.isSpecialKey() ) {
            return;
        }
        if( this.lastSearchTerm == key ) {
            startIndex = this.lastSelectedIndex;
        }
        this.search(this.displayField, key, startIndex);
        this.cshTask.delay(this.searchResetDelay);
    },

    onRender : function(ct, position) {
        this.store.on('load', this.calcRowsPerPage, this);
        Ext.form.SelectGroupBox.superclass.onRender.apply(this, arguments);
        if( this.mode == 'local' ) {
            this.calcRowsPerPage();
        }
    },

    onSelect : function(record, index, skipCollapse){
        if(this.fireEvent('beforeselect', this, record, index) !== false){
            this.setValue(record.data[this.valueField || this.displayField]);
            if( !skipCollapse ) {
                this.collapse();
            }
            this.lastSelectedIndex = index + 1;
            this.fireEvent('select', this, record, index);
        }
    },
    
    render : function(ct) {
        Ext.form.SelectGroupBox.superclass.render.apply(this, arguments);
        if( Ext.isSafari ) {
            this.el.swallowEvent('mousedown', true);
        }
        this.el.unselectable();
        this.innerList.unselectable();
        this.trigger.unselectable();
        this.innerList.on('mouseup', function(e, target, options) {
            if( target.id && target.id == this.innerList.id ) {
                return;
            }
            this.onViewClick();
        }, this);

        this.innerList.on('mouseover', function(e, target, options) {
            if( target && target.id && target.id == this.innerList.id ) {
                return;
            }
            this.lastSelectedIndex = this.view.getSelectedIndexes()[0] + 1;
            this.cshTask.delay(this.searchResetDelay);
        }, this);

        this.trigger.un('click', this.onTriggerClick, this);
        this.trigger.on('mousedown', function(e, target, options) {
            e.preventDefault();
            this.onTriggerClick();
        }, this);

        this.on('collapse', function(e, target, options) {
            Ext.getDoc().un('mouseup', this.collapseIf, this);
        }, this, true);

        this.on('expand', function(e, target, options) {
            Ext.getDoc().on('mouseup', this.collapseIf, this);
        }, this, true);
    },

    clearSearchHistory : function() {
        this.lastSelectedIndex = 0;
        this.lastSearchTerm = false;
    },

    selectFirst : function() {
        this.focusAndSelect(this.store.data.first());
    },

    selectLast : function() {
        this.focusAndSelect(this.store.data.last());
    },

    selectPrevPage : function() {
        if( !this.rowHeight ) {
            return;
        }
        var index = Math.max(this.selectedIndex-this.rowsPerPage, 0);
        this.focusAndSelect(this.store.getAt(index));
    },

    selectNextPage : function() {
        if( !this.rowHeight ) {
            return;
        }
        var index = Math.min(this.selectedIndex+this.rowsPerPage, this.store.getCount() - 1);
        this.focusAndSelect(this.store.getAt(index));
    },

    search : function(field, value, startIndex) {
        field = field || this.displayField;
        this.lastSearchTerm = value;
        var index = this.store.find.apply(this.store, arguments);
        if( index !== -1 ) {
            this.focusAndSelect(index);
        }
    },

    focusAndSelect : function(record) {
        var index = typeof record === 'number' ? record : this.store.indexOf(record);
        this.select(index, this.isExpanded());
        this.onSelect(this.store.getAt(record), index, this.isExpanded());
    },

    calcRowsPerPage : function() {
        if( this.store.getCount() ) {
            this.rowHeight = Ext.fly(this.view.getNode(0)).getHeight();
            this.rowsPerPage = this.maxHeight / this.rowHeight;
        } else {
            this.rowHeight = false;
        }
    }
});