Ext.namespace('MODx');

Array.prototype.in_array = function(p_val) {
    for(var i = 0, l = this.length; i < l; i++) {
        if(this[i] == p_val) {
            return true;
        }
    }
    return false;
};


var loadingBox;
var showSpinner = function() {
    Ext.Msg.show({
        title: _('please_wait')
        ,msg: _('loading')
        ,width:240
        ,progress:true
        ,closable:false
    });
};
var hideSpinner = function(){
    Ext.Msg.hide();
};
Ext.onReady(function() {
    Ext.Ajax.on('beforerequest',showSpinner,this);
    Ext.Ajax.on('requestcomplete',hideSpinner,this);
    Ext.Ajax.on('requestexception',hideSpinner,this);
});

function $(el){
    if (!el) return null;
    var type = Ext.type(el);
    if (type == 'string'){
        el = document.getElementById(el);
        type = (el) ? 'element' : false;
    }
    if (type != 'element') return null;
    return el;
};

Ext.form.setCheckboxValues = function(form,id,mask) {
    var f, n=0;
    while ((f = form.findField(id+n)) != null) {
        f.setValue((mask & (1<<n))?'true':'false');
        n++;
    } 
};

Ext.form.getCheckboxMask = function(cbgroup) {
    var mask='';
    if (typeof(cbgroup) != "undefined") {
        if ((typeof(cbgroup)=="string"))
            mask = cbgroup+'';
        else
            for(var i = 0, len = cbgroup.length; i < len; i++)
                mask += (mask != '' ? ',' : '')+(cbgroup[i]-0);
    }
    return mask;
};

var isInteger = function(s) {
    var i;
    for (i = 0; i < s.length; i++) {   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < '0') || (c > '9'))) return false;
    }
    // All characters are numbers.
    return true;
};


var modJSONReader = new Ext.data.JsonReader({
    successProperty:'success'
    ,totalProperty: 'total'
    ,root: 'data'
},['id','msg']);



function findPos(obj) {
    var curleft = 0;
    var curtop = 0;
    if (obj.offsetParent) {
        curleft = obj.offsetLeft;
        curtop = obj.offsetTop;
        while (obj = obj.offsetParent) {
            curleft += obj.offsetLeft;
            curtop += obj.offsetTop;
        }
    }
    return [curleft,curtop];
};


var _progressID = 0;
var _progressTime = function(v,id,msg) {
    msg = msg || _('saving');
    if (_progressID == id && v < 11) 
        Ext.MessageBox.updateProgress(v/10,msg);
};

var _resetProgress = function() {
    _progressID = _progressID + 1;
};






Ext.form.BasicForm.prototype.append = function() {
  // Create a new layout object
  var layout = new Ext.form.Layout();
  // Keep track of added fields that are form fields (isFormField)
  var fields = [];
  // Add all the fields on to the layout stack
  layout.stack.push.apply(layout.stack, arguments);

  // Add only those fields that are form fields to the 'fields' array
  for(var i = 0; i < arguments.length; i++) {
    if(arguments[i].isFormField) {
      fields.push(arguments[i]);
    }
  }

  // Render the layout
  layout.render(this.el);

  // If we found form fields add them to the form's items collection and render the
  // fields into their containers created by the layout
  if(fields.length > 0) {
    this.items.addAll(fields);

    // Render each field
    for(var i = 0; i < fields.length; i++) {
      fields[i].render('x-form-el-' + fields[i].id);
    }
  }

  return this;
};




Ext.form.AMPMField = function(id,v) {
    return new Ext.form.ComboBox({
        store: new Ext.data.SimpleStore({
            fields: ['ampm'],
            data: [['am'],['pm']]
        }),
        displayField: 'ampm',
        hiddenName: id,
        mode: 'local',
        editable: false,
        forceSelection: true,
        triggerAction: 'all',
        width: 60,
        value: v || 'am'
    });
};

Ext.form.HourField = function(id,name,v){
    return new Ext.form.ComboBox({
        store: new Ext.data.SimpleStore({
            fields: ['hour'],
            data: [[1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12]]
        }),
        displayField: 'hour',
        mode: 'local',
        triggerAction: 'all',
        width: 60,
        forceSelection: true,
        rowHeight: false,
        editable: false,
        value: v || 1,
        transform: id
    }); 
};


Ext.override(Ext.tree.TreeNodeUI,{
    hasClass : function(className){
        var el = Ext.fly(this.elNode);
        return className && (' '+el.dom.className+' ').indexOf(' '+className+' ') != -1;
    }
});


// allows for messages in JSON responses
Ext.override(Ext.form.Action.Submit,{         
    handleResponse : function(response){        
        var m = Ext.decode(response.responseText); // shaun 7/11/07
        if(this.form.errorReader){
            var rs = this.form.errorReader.read(response);
            var errors = [];
            if(rs.records){
                for(var i = 0, len = rs.records.length; i < len; i++) {
                    var r = rs.records[i];
                    errors[i] = r.data;
                }
            }
            if(errors.length < 1){
                errors = null;
            }
            return {
                success : rs.success,
                message : m.message, // shaun 7/11/07
                object : m.object, // shaun 7/18/07
                errors : errors
            };
        }
        return Ext.decode(response.responseText);
    }
});





/**
 * @class Ext.form.ColorField
 * @extends Ext.form.TriggerField
 * Provides a very simple color form field with a ColorMenu dropdown.
 * Values are stored as a six-character hex value without the '#'.
 * I.e. 'ffffff'
 * @constructor
 * Create a new ColorField
 * <br />Example:
 * <pre><code>
var cf = new Ext.form.ColorField({
    fieldLabel: 'Color',
    hiddenName:'pref_sales',
    showHexValue:true
});
</code></pre>
 * @param {Object} config
 */
Ext.form.ColorField = function(config){
    Ext.form.ColorField.superclass.constructor.call(this, config);
    this.on('render', this.handleRender);
};

Ext.extend(Ext.form.ColorField, Ext.form.TriggerField,  {
    /**
     * @cfg {Boolean} showHexValue
     * True to display the HTML Hexidecimal Color Value in the field
     * so it is manually editable.
     */
    showHexValue : true,
    
    /**
     * @cfg {String} triggerClass
     * An additional CSS class used to style the trigger button.  The trigger will always get the
     * class 'x-form-trigger' and triggerClass will be <b>appended</b> if specified (defaults to 'x-form-color-trigger'
     * which displays a calendar icon).
     */
    triggerClass : 'x-form-color-trigger',
    
    /**
     * @cfg {String/Object} autoCreate
     * A DomHelper element spec, or true for a default element spec (defaults to
     * {tag: "input", type: "text", size: "10", autocomplete: "off"})
     */
    // private
    defaultAutoCreate : {tag: "input", type: "text", size: "10",
                         autocomplete: "off", maxlength:"6"},
    
    /**
     * @cfg {String} lengthText
     * A string to be displayed when the length of the input field is
     * not 3 or 6, i.e. 'fff' or 'ffccff'.
     */
    lengthText: "Color hex values must be either 3 or 6 characters.",
    
    //text to use if blank and allowBlank is false
    blankText: "Must have a hexidecimal value in the format ABCDEF.",
    
    /**
     * @cfg {String} color
     * A string hex value to be used as the default color.  Defaults
     * to 'FFFFFF' (white).
     */
    defaultColor: '',
    
    maskRe: /[a-f0-9]/i,
    // These regexes limit input and validation to hex values
    regex: /[a-f0-9]/i,

    //private
    curColor: '',
    
    // private
    validateValue : function(value){
        if(!this.showHexValue) {
            return true;
        }
        if(value.length<1) {
            this.el.setStyle({
                'background-color':'#'+this.defaultColor
            });
            if(!this.allowBlank) {
                this.markInvalid(String.format(this.blankText, value));
                return false
            }
            return true;
        }
        this.setColor(value);
        return true;
    },

    // private
    validateBlur : function(){
        return !this.menu || !this.menu.isVisible();
    },
    
    // Manually apply the invalid line image since the background
    // was previously cleared so the color would show through.
    markInvalid : function( msg ) {
        Ext.form.ColorField.superclass.markInvalid.call(this, msg);
        this.el.setStyle({
            'background-image': 'url(../lib/resources/images/default/grid/invalid_line.gif)'
        });
    },

    /**
     * Returns the current color value of the color field
     * @return {String} value The hexidecimal color value
     */
    getValue : function(){
        return this.curValue || this.defaultValue || "FFFFFF";
    },

    /**
     * Sets the value of the color field.  Format as hex value 'FFFFFF'
     * without the '#'.
     * @param {String} hex The color value
     */
    setValue : function(hex){
        Ext.form.ColorField.superclass.setValue.call(this, hex);
        this.setColor(hex);
    },
    
    /**
     * Sets the current color and changes the background.
     * Does *not* change the value of the field.
     * @param {String} hex The color value.
     */
    setColor : function(hex) {
        this.curColor = hex;
        h = hex.substr(0,1) != '#' ? '#'+hex : hex;
        
        this.el.setStyle( {
            'background-color': h,
            'background-image': 'none'
        });
        if(!this.showHexValue) {
            /*this.el.setStyle({
                'text-indent': '-100px'
            });
            if(Ext.isIE) {
                this.el.setStyle({
                    'margin-left': '100px'
                });
            }*/
        }
    },
    
    handleRender: function() {
        this.setDefaultColor();
    },
    
    setDefaultColor : function() {
        this.setValue(this.defaultColor);
    },

    // private
    menuListeners : {
        select: function(m, d){
            this.setValue(d);
        },
        show : function(){ // retain focus styling
            this.onFocus();
        },
        hide : function(){
            this.focus();
            var ml = this.menuListeners;
            this.menu.un("select", ml.select,  this);
            this.menu.un("show", ml.show,  this);
            this.menu.un("hide", ml.hide,  this);
        }
    },
    
    //private
    handleSelect : function(palette, selColor) {
        this.setValue(selColor);
    },

    // private
    // Implements the default empty TriggerField.onTriggerClick function to display the ColorPicker
    onTriggerClick : function(){
        if(this.disabled){
            return;
        }
        if(this.menu == null){
            this.menu = new Ext.menu.ColorMenu();
            this.menu.palette.on('select', this.handleSelect, this );
        }
        this.menu.on(Ext.apply({}, this.menuListeners, {
            scope:this
        }));
        this.menu.show(this.el, "tl-bl?");
    }
});




/**
 * QTips to form fields
 */
Ext.form.Field.prototype.afterRender = Ext.form.Field.prototype.afterRender.createSequence(function() { 
    if (this.description) {
        Ext.QuickTips.register({
            target:  this.getEl()
            ,text: this.description
            ,enabled: true
        });
        var label = Ext.form.Field.findLabel(this);
        if(label){
            Ext.QuickTips.register({
                target:  label
                ,text: this.description
                ,enabled: true
            });
        }
    }
});
Ext.applyIf(Ext.form.Field,{
    findLabel: function(field) {
        var wrapDiv = null;
        var label = null;
        
        //find form-element and label?
        wrapDiv = field.getEl().up('div.x-form-element');
        if(wrapDiv){
            label = wrapDiv.child('label');
        }
        if(label){
            return label;
        }
        //find form-item and label
        wrapDiv = field.getEl().up('div.x-form-item');
        if(wrapDiv) {
            label = wrapDiv.child('label');        
        }
        if(label){
            return label;
        }
    }
});

/** 
 * Static Textfield
 */
MODx.StaticTextField = Ext.extend(Ext.form.TextField, {
    fieldClass: 'x-static-text-field',

    onRender: function() {
        this.readOnly = true;
        this.disabled = !this.initialConfig.submitValue;
        MODx.StaticTextField.superclass.onRender.apply(this, arguments);
    }
});
Ext.reg('statictextfield',MODx.StaticTextField);