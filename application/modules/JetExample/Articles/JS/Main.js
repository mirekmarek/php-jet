dojo.require('dijit.form.SimpleTextarea');
dojo.require('dijit.form.MultiSelect');
dojo.require('dojox.grid.EnhancedGrid');
dojo.require('dojox.grid.enhanced.plugins.Pagination');
dojo.require('dojo.data.ObjectStore');

Jet.require('Jet.modules.Module');
Jet.require('Jet.Form');
Jet.require('Jet.Trash');
Jet.require('Jet.Formatter');
Jet.require('Jet.EditArea');

Jet.declare('Jet.module.JetExample\\Articles.Main', [Jet.modules.Module], {
    module_name: 'JetExample\\Articles',
    module_label: Jet.translate('Articles'),

    form: null,
    trash: null,
    grid: null,
    edit_area: null,


    initializeUI: function(){
        this._initDialog();
        this._initForm();
        this._initGrid();
        this._initTrash();
        this._initSignals();
    },

    _initSignals: function() {
        var _this = this;
	    this.addSignalCallback(this.module_name+'/new', function(){ _this.reloadGrid(); });
	    this.addSignalCallback(this.module_name+'/updated', function(){ _this.reloadGrid(); });
	    this.addSignalCallback(this.module_name+'/deleted', function(){ _this.reloadGrid(); });
    },

    _initDialog: function() {
        this.edit_area = new Jet.EditArea(this, 'article_edit');
    },

    _initForm: function() {
        var _this = this;

        this.form = new Jet.Form(
            this,
            'article',
            this.getData('article_form_fields_definition'),
            {
                save_button:['article_save'],
                afterAdd: function(response_data) {
                    dojo.publish(_this.module_name+'/new');
                    _this.edit(response_data.ID);
                },
                afterUpdate: function() {
                    dojo.publish(_this.module_name+'/updated');
                },
                beforeEdit: function( data ) {
                    _this.edit_area.open(Jet.translate('Loading ...'));
                },
                onEdit: function( data ) {
                    _this.edit_area.open(data.title);
                },
                onNew: function() {
                    _this.edit_area.open( Jet.translate('New Article') );
                }
            } );
    },

    _initGrid: function() {
        this.grid = this.getDataGrid( 'articles_grid', this.form.store, 'edit' );

    },

    _initTrash: function() {
        var _this = this;
        this.trash = new Jet.Trash(this, 'article_trash', this.form.store, {
            source_widget_grid: this.grid,
            itemAvatarCreator: function(item) {
                return '<div>'+item['title']+'</div>';
            },
            afterDelete: function() {
                dojo.publish(_this.module_name+'/deleted');
            }
        });
    },

    reloadGrid: function() {
        this.grid.reload();
    },

    add: function(ID) {
        this.form.new();
    },

    edit: function(ID) {
        this.form.edit(ID);
    },

    save: function( close ) {
        this.form.save();
    },

    close: function() {
        var _this = this;

        this.form.cancelCheck(
            Jet.translate('Warning!'),
            Jet.translate('Article is not saved! Do you really want to exit?'),
            function() {
                _this.edit_area.close();
            }
        );
    }
});
