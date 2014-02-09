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
 * @subpackage EditArea
 */
dojo.declare("Jet.EditArea", [], {
    module_instance: null,

    ID: "",
    opened: false,

    _current_pane: null,

    constructor: function( module_instance, area_ID ) {
        this.module_instance = module_instance;
        this.ID = area_ID;

        this.list_pane = module_instance.getWidgetByID(area_ID + "_list");
        this.dialog_pane = module_instance.getWidgetByID(area_ID + "_dialog");
        this.container = module_instance.getWidgetByID(area_ID + "_container");
        this.item_title = module_instance.getNodeByID(area_ID + "_item_title");
        this.list_title_opened = module_instance.getNodeByID(area_ID + "_list_title_opened");
        this.list_title_closed = module_instance.getNodeByID(area_ID + "_list_title_closed");

        this._current_pane = this.list_pane;

    },

    open: function( title ) {
        this.setTitle(title);

        if(this.opened) return;

        var w = this._current_pane.w;
        var h = this._current_pane.h;

        this.list_title_closed.style.display="none";
        this.list_title_opened.style.display="inline";

        this._current_pane.domNode.style.display="none";

        this.dialog_pane.domNode.style.display="block";
        this.dialog_pane.resize({w:w,h:h});
        this._current_pane = this.dialog_pane;

        this.container.layout();
        this.opened = true;
    },

    close: function() {
        if(!this.opened) return;

        var w = this._current_pane.w;
        var h = this._current_pane.h;

        this.item_title.innerHTML = '';
        this.list_title_opened.style.display="none";
        this.list_title_closed.style.display="inline";

        this._current_pane.domNode.style.display="none";

        this.list_pane.domNode.style.display="block";
        this.list_pane.resize({w:w,h:h});

        this.container.layout();
        this._current_pane = this.list_pane;
        this.opened = false;
    },

    setTitle: function( title ) {
        this.item_title.innerHTML = title;
    }

});