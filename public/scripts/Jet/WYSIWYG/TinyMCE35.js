var Jet_WYSIWYG = new function() {

    this._loaded = {};
    this._content = {};
	this._enabled = {};

    this.init = function(node_ID, config_name, onChange) {

        this._loaded[node_ID] = false;

        var config = Jet_WYSIWYG_editor_configs[config_name];
        config.elements = node_ID;

        config.setup = function(ed) {
            ed.onInit.add(function() {
                Jet_WYSIWYG._loaded[node_ID] = true;

                if(Jet_WYSIWYG._content[node_ID]) {
                    ed.setContent(Jet_WYSIWYG._content[node_ID]);
                    delete Jet_WYSIWYG._content[node_ID];
                }
            });

	        //ed.getBody().setAttribute('contenteditable', Jet_WYSIWYG._content[node_ID]?'true':'false' );

            if(onChange) ed.onChange.add(onChange);
        }

        tinymce.init( config );
    };

    this.addOnChenge = function(node_ID, onChange) {
        this.getEditor(node_ID).onChange.add(onChange);
    };

    this.disableEditor = function(node_ID){
        tinymce.execCommand('mceRemoveControl', false, node_ID);
    };

    this.enableEditor = function(node_ID){
        tinyMCE.execCommand('mceAddControl', false, node_ID);
    };

    this.toggleEditor = function(node_ID){
        if (!tinyMCE.get(node_ID))
            this.enableEditor(node_ID);
        else
            this.disableEditor(node_ID);
    };

    this.getEditor = function( node_ID ) {
        return tinyMCE.get(node_ID);
    };
    
    this.getEditorNode = function( node_ID ) {
		return this.getEditor(node_ID).contentAreaContainer;
    };

    this.setContent = function(node_ID, content) {
        if(!content) {
            content = "<p></p>";
        }

        if(Jet_WYSIWYG._loaded[node_ID]===undefined) {
            return;
        }

        if(!Jet_WYSIWYG._loaded[node_ID]) {
            Jet_WYSIWYG._content[node_ID] = content;
        } else {
            tinyMCE.get(node_ID).setContent(content);
        }
    };

    this.getContent = function(node_ID) {
        return tinyMCE.get(node_ID).getContent();
    };

	this.disable = function(node_ID){
		if(Jet_WYSIWYG._loaded[node_ID]===undefined) {
			return;
		}
		if(!Jet_WYSIWYG._loaded[node_ID]) {
			this._enabled[node_ID] = false;
			return;
		}
		tinyMCE.get(node_ID).getBody().setAttribute('contenteditable', 'false');
	};

	this.enable = function(node_ID){
		if(Jet_WYSIWYG._loaded[node_ID]===undefined) {
			return;
		}
		if(!Jet_WYSIWYG._loaded[node_ID]) {
			this._enabled[node_ID] = true;
			return;
		}
		tinyMCE.get(node_ID).getBody().setAttribute('contenteditable', 'true');
	};
};

