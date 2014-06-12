/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package js.Jet
 * @subpackage dojoExtension
 */
Jet.dojoExtensions = {
    handleExtensions: function() {
        if(window["dijit"] && dijit.layout.BorderContainer) {
            dojo.extend( dijit.layout.BorderContainer, {
                    liveSplitters: false,
                    gutters: false
                }
            );
        }

        if(window["dijit"] && dijit._Widget && !dijit._Widget.prototype.getParentWidget) {
            dojo.extend( dijit._Widget, {
                    getParentWidget: function(){
                        if(!this.domNode) {
                            return false;
                        }
                        return this.domNode["parentNode"] ?
                                dijit.getEnclosingWidget(this.domNode.parentNode)
                                :
                                false;
                    },

                    getParentWidgetID: function(){
                        var parent = this.getParentWidget();
                        return parent ? parent.id : false;
                    },

                    focusWidget: function(){
                        var path_to_widget = [this.id];
                        var parent_ID = this.getParentWidgetID();

                        while(parent_ID){
                            path_to_widget.push(parent_ID);
                            parent_ID = dijit.byId(parent_ID).getParentWidgetID();
                        }

                        for( var i=path_to_widget.length - 1; i>=0; i-- ){
                            var parent_widget = dijit.byId(path_to_widget[i]);

                            if(parent_widget["show"]){
                                parent_widget.show();
                            }

                            if(parent_widget["selectChild"] && i > 0){
                                parent_widget.selectChild(path_to_widget[i-1]);
                            }
                        }

                        if(this["isFocusable"] && this["focus"]){
                            try {
                                this.focus();
                            } catch(e){}
                        }
                    }
                }
            );
        }



        if(window["cbtree"] && cbtree.CheckBoxTree && !cbtree.CheckBoxTree.prototype.uncheckAll) {
            dojo.extend(cbtree.CheckBoxTree, {
                uncheckAll: function( onComplete ) {
                    this.model.uncheck({ID:"*"}, onComplete);
                },
                checkAll: function( onComplete ) {
                    this.model.check({ID:"*"}, onComplete);
                },
                setChecked: function( IDs, onComplete ) {
                    var _this = this;
                    var checkboxStrict_state = this.model.checkboxStrict;
                    this.model.checkboxStrict = false;

                    this.model.store.fetch({
                        queryOptions: {deep:true},
                        onItem: function(storeItem, request) {

                            var ID = _this.model.store.getValue(storeItem, "ID", "");
                            var checked = false;

                            for(var i in IDs) {
                                if(IDs[i]==ID) {
                                    checked=true;
                                    break;
                                }
                            }

                            _this.model.updateCheckbox( storeItem, checked );
                        },
                        onComplete: function() {
                            _this.model.checkboxStrict = checkboxStrict_state;
                            if(onComplete) {
                                onComplete();
                            }
                        }
                    });
                },
                getChecked: function() {
                    var _this = this;

                    var IDs = [];

                    this.model.store.fetch({
                        queryOptions: {deep:true},
                        onItem: function(storeItem, request) {
                            if(_this.model.store.getValue(storeItem, _this.model.checkboxIdent)) {
                                IDs.push(_this.model.store.getValue(storeItem, "ID"));
                            }
                        }
                    });

                    return IDs;
                }

            });
        }

        if(window["dijit"] && dijit.Tree && !dijit.Tree.prototype.reload) {

            dojo.extend(dijit.Tree, {
                reload: function() {
                    var selected_ID = null;
                    if(this.selectedItem) {
                        selected_ID = this.model.getIdentity(this.selectedItem);
                    }

                    if(this.dndController) {
                        this.dndController.selectNone();
                    }

                    this._itemNodesMap = {};
                    this.rootNode.state = "UNCHECKED";

                    this.rootNode.destroyRecursive();


                    this.postMixInProperties();
                    this._load();

                    if(selected_ID) {
                        this.openByID(selected_ID);
                    }
                },
                searchPath: function(search_ID, parent_node, path){

                    if(!parent_node) { parent_node = this.model.root; }
                    if(!path) { path = []; }

                    var ID = this.model.getIdentity(parent_node);

                    path.push(ID);
                    if(ID == search_ID){
                        return path;
                    }

                    for( var i in parent_node.children ){
                        var path_branch = path.slice(0);

                        var s_path = this.searchPath(search_ID, parent_node.children[i], path_branch );
                        if(s_path){
                            return s_path;
                        }
                    }
                    return null;
                },
                openByID: function( ID ) {
                    var _this = this;

                    var callback = function(){

                        var path = _this.searchPath( ID );

                        if(path && path.length) {
                            _this.set("path", path);
                        }
                    };

                    if( this.model.store ) {
                        this.model.store.fetch({ onComplete: callback });

                    } else {
                        callback();
                    }

                }
            });
        }

    }
};

dojo.addOnLoad( function() {
    Jet.dojoExtensions.handleExtensions();
} );