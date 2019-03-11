!function(t){"use strict";var e=function(n){this.defaultOptions=t.extend(!0,{id:e.newGuid(),buttons:[],data:{},onshow:null,onshown:null,onhide:null,onhidden:null},e.defaultOptions),this.indexedButtons={},this.registeredButtonHotkeys={},this.draggableData={isMouseDown:!1,mouseOffset:{}},this.realized=!1,this.opened=!1,this.initOptions(n),this.holdThisInstance()};e.NAMESPACE="bootstrap-dialog",e.TYPE_DEFAULT="type-default",e.TYPE_INFO="type-info",e.TYPE_PRIMARY="type-primary",e.TYPE_SUCCESS="type-success",e.TYPE_WARNING="type-warning",e.TYPE_DANGER="type-danger",e.DEFAULT_TEXTS={},e.DEFAULT_TEXTS[e.TYPE_DEFAULT]="Information",e.DEFAULT_TEXTS[e.TYPE_INFO]="Information",e.DEFAULT_TEXTS[e.TYPE_PRIMARY]="Information",e.DEFAULT_TEXTS[e.TYPE_SUCCESS]="Success",e.DEFAULT_TEXTS[e.TYPE_WARNING]="Warning",e.DEFAULT_TEXTS[e.TYPE_DANGER]="Danger",e.SIZE_NORMAL="size-normal",e.SIZE_LARGE="size-large",e.BUTTON_SIZES={},e.BUTTON_SIZES[e.SIZE_NORMAL]="",e.BUTTON_SIZES[e.SIZE_LARGE]="btn-lg",e.ICON_SPINNER="glyphicon glyphicon-asterisk",e.ZINDEX_BACKDROP=1040,e.ZINDEX_MODAL=1050,e.defaultOptions={type:e.TYPE_PRIMARY,size:e.SIZE_NORMAL,cssClass:"",title:null,message:null,nl2br:!0,closable:!0,closeByBackdrop:!0,closeByKeyboard:!0,spinicon:e.ICON_SPINNER,autodestroy:!0,draggable:!1},e.configDefaultOptions=function(n){e.defaultOptions=t.extend(!0,e.defaultOptions,n)},e.dialogs={},e.openAll=function(){t.each(e.dialogs,function(t,e){e.open()})},e.closeAll=function(){t.each(e.dialogs,function(t,e){e.close()})},e.prototype={constructor:e,initOptions:function(e){return this.options=t.extend(!0,this.defaultOptions,e),this},holdThisInstance:function(){return e.dialogs[this.getId()]=this,this},initModalStuff:function(){return this.setModal(this.createModal()).setModalDialog(this.createModalDialog()).setModalContent(this.createModalContent()).setModalHeader(this.createModalHeader()).setModalBody(this.createModalBody()).setModalFooter(this.createModalFooter()),this.getModal().append(this.getModalDialog()),this.getModalDialog().append(this.getModalContent()),this.getModalContent().append(this.getModalHeader()).append(this.getModalBody()).append(this.getModalFooter()),this},createModal:function(){var e=t('<div class="modal" tabindex="-1"></div>');return e.prop("id",this.getId()),e},getModal:function(){return this.$modal},setModal:function(t){return this.$modal=t,this},createModalDialog:function(){return t('<div class="modal-dialog"></div>')},getModalDialog:function(){return this.$modalDialog},setModalDialog:function(t){return this.$modalDialog=t,this},createModalContent:function(){return t('<div class="modal-content"></div>')},getModalContent:function(){return this.$modalContent},setModalContent:function(t){return this.$modalContent=t,this},createModalHeader:function(){return t('<div class="modal-header"></div>')},getModalHeader:function(){return this.$modalHeader},setModalHeader:function(t){return this.$modalHeader=t,this},createModalBody:function(){return t('<div class="modal-body"></div>')},getModalBody:function(){return this.$modalBody},setModalBody:function(t){return this.$modalBody=t,this},createModalFooter:function(){return t('<div class="modal-footer"></div>')},getModalFooter:function(){return this.$modalFooter},setModalFooter:function(t){return this.$modalFooter=t,this},createDynamicContent:function(t){var e=null;return e="function"==typeof t?t.call(t,this):t,"string"==typeof e&&(e=this.formatStringContent(e)),e},formatStringContent:function(t){return this.options.nl2br?t.replace(/\r\n/g,"<br />").replace(/[\r\n]/g,"<br />"):t},setData:function(t,e){return this.options.data[t]=e,this},getData:function(t){return this.options.data[t]},setId:function(t){return this.options.id=t,this},getId:function(){return this.options.id},getType:function(){return this.options.type},setType:function(t){return this.options.type=t,this},getSize:function(){return this.options.size},setSize:function(t){return this.options.size=t,this},getCssClass:function(){return this.options.cssClass},setCssClass:function(t){return this.options.cssClass=t,this},getTitle:function(){return this.options.title},setTitle:function(t){return this.options.title=t,this.updateTitle(),this},updateTitle:function(){if(this.isRealized()){var t=null!==this.getTitle()?this.createDynamicContent(this.getTitle()):this.getDefaultText();this.getModalHeader().find("."+this.getNamespace("title")).html("").append(t)}return this},getMessage:function(){return this.options.message},setMessage:function(t){return this.options.message=t,this.updateMessage(),this},updateMessage:function(){if(this.isRealized()){var t=this.createDynamicContent(this.getMessage());this.getModalBody().find("."+this.getNamespace("message")).html("").append(t)}return this},isClosable:function(){return this.options.closable},setClosable:function(t){return this.options.closable=t,this.updateClosable(),this},setCloseByBackdrop:function(t){return this.options.closeByBackdrop=t,this},canCloseByBackdrop:function(){return this.options.closeByBackdrop},setCloseByKeyboard:function(t){return this.options.closeByKeyboard=t,this},canCloseByKeyboard:function(){return this.options.closeByKeyboard},getSpinicon:function(){return this.options.spinicon},setSpinicon:function(t){return this.options.spinicon=t,this},addButton:function(t){return this.options.buttons.push(t),this},addButtons:function(e){var n=this;return t.each(e,function(t,e){n.addButton(e)}),this},getButtons:function(){return this.options.buttons},setButtons:function(t){return this.options.buttons=t,this.updateButtons(),this},getButton:function(t){return"undefined"!=typeof this.indexedButtons[t]?this.indexedButtons[t]:null},getButtonSize:function(){return"undefined"!=typeof e.BUTTON_SIZES[this.getSize()]?e.BUTTON_SIZES[this.getSize()]:""},updateButtons:function(){return this.isRealized()&&(0===this.getButtons().length?this.getModalFooter().hide():this.getModalFooter().find("."+this.getNamespace("footer")).html("").append(this.createFooterButtons())),this},isAutodestroy:function(){return this.options.autodestroy},setAutodestroy:function(t){this.options.autodestroy=t},getDefaultText:function(){return e.DEFAULT_TEXTS[this.getType()]},getNamespace:function(t){return e.NAMESPACE+"-"+t},createHeaderContent:function(){var e=t("<div></div>");return e.addClass(this.getNamespace("header")),e.append(this.createTitleContent()),e.prepend(this.createCloseButton()),e},createTitleContent:function(){var e=t("<div></div>");return e.addClass(this.getNamespace("title")),e},createCloseButton:function(){var e=t("<div></div>");e.addClass(this.getNamespace("close-button"));var n=t('<button class="close">&times;</button>');return e.append(n),e.on("click",{dialog:this},function(t){t.data.dialog.close()}),e},createBodyContent:function(){var e=t("<div></div>");return e.addClass(this.getNamespace("body")),e.append(this.createMessageContent()),e},createMessageContent:function(){var e=t("<div></div>");return e.addClass(this.getNamespace("message")),e},createFooterContent:function(){var e=t("<div></div>");return e.addClass(this.getNamespace("footer")),e},createFooterButtons:function(){var n=this,o=t("<div></div>");return o.addClass(this.getNamespace("footer-buttons")),this.indexedButtons={},t.each(this.options.buttons,function(t,i){i.id||(i.id=e.newGuid());var s=n.createButton(i);n.indexedButtons[i.id]=s,o.append(s)}),o},createButton:function(e){var n=t('<button class="btn"></button>');return n.addClass(this.getButtonSize()),n.prop("id",e.id),"undefined"!=typeof e.icon&&""!==t.trim(e.icon)&&n.append(this.createButtonIcon(e.icon)),"undefined"!=typeof e.label&&n.append(e.label),n.addClass("undefined"!=typeof e.cssClass&&""!==t.trim(e.cssClass)?e.cssClass:"btn-default"),"undefined"!=typeof e.hotkey&&(this.registeredButtonHotkeys[e.hotkey]=n),n.on("click",{dialog:this,$button:n,button:e},function(t){var e=t.data.dialog,n=t.data.$button,o=t.data.button;"function"==typeof o.action&&o.action.call(n,e),o.autospin&&n.toggleSpin(!0)}),this.enhanceButton(n),n},enhanceButton:function(t){return t.dialog=this,t.toggleEnable=function(t){var e=this;return e.prop("disabled",!t).toggleClass("disabled",!t),e},t.enable=function(){var t=this;return t.toggleEnable(!0),t},t.disable=function(){var t=this;return t.toggleEnable(!1),t},t.toggleSpin=function(e){var n=this,o=n.dialog,i=n.find("."+o.getNamespace("button-icon"));return e?(i.hide(),t.prepend(o.createButtonIcon(o.getSpinicon()).addClass("icon-spin"))):(i.show(),t.find(".icon-spin").remove()),n},t.spin=function(){var t=this;return t.toggleSpin(!0),t},t.stopSpin=function(){var t=this;return t.toggleSpin(!1),t},this},createButtonIcon:function(e){var n=t("<span></span>");return n.addClass(this.getNamespace("button-icon")).addClass(e),n},enableButtons:function(e){return t.each(this.indexedButtons,function(t,n){n.toggleEnable(e)}),this},updateClosable:function(){return this.isRealized()&&this.getModalHeader().find("."+this.getNamespace("close-button")).toggle(this.isClosable()),this},onShow:function(t){return this.options.onshow=t,this},onShown:function(t){return this.options.onshown=t,this},onHide:function(t){return this.options.onhide=t,this},onHidden:function(t){return this.options.onhidden=t,this},isRealized:function(){return this.realized},setRealized:function(t){return this.realized=t,this},isOpened:function(){return this.opened},setOpened:function(t){return this.opened=t,this},handleModalEvents:function(){return this.getModal().on("show.bs.modal",{dialog:this},function(t){var e=t.data.dialog;return e.showPageScrollBar(!0),"function"==typeof e.options.onshow?e.options.onshow(e):void 0}),this.getModal().on("shown.bs.modal",{dialog:this},function(t){var e=t.data.dialog;"function"==typeof e.options.onshown&&e.options.onshown(e),e.showPageScrollBar(!0)}),this.getModal().on("hide.bs.modal",{dialog:this},function(t){var e=t.data.dialog;return"function"==typeof e.options.onhide?e.options.onhide(e):void 0}),this.getModal().on("hidden.bs.modal",{dialog:this},function(e){var n=e.data.dialog;"function"==typeof n.options.onhidden&&n.options.onhidden(n),n.isAutodestroy()&&t(this).remove(),n.showPageScrollBar(!1)}),this.getModal().on("click",{dialog:this},function(t){t.target===this&&t.data.dialog.isClosable()&&t.data.dialog.canCloseByBackdrop()&&t.data.dialog.close()}),this.getModal().on("keyup",{dialog:this},function(t){27===t.which&&t.data.dialog.isClosable()&&t.data.dialog.canCloseByKeyboard()&&t.data.dialog.close()}),this.getModal().on("keyup",{dialog:this},function(e){var n=e.data.dialog;if("undefined"!=typeof n.registeredButtonHotkeys[e.which]){var o=t(n.registeredButtonHotkeys[e.which]);!o.prop("disabled")&&o.focus().trigger("click")}}),this},makeModalDraggable:function(){return this.options.draggable&&(this.getModalHeader().addClass(this.getNamespace("draggable")).on("mousedown",{dialog:this},function(t){var e=t.data.dialog;e.draggableData.isMouseDown=!0;var n=e.getModalContent().offset();e.draggableData.mouseOffset={top:t.clientY-n.top,left:t.clientX-n.left}}),this.getModal().on("mouseup mouseleave",{dialog:this},function(t){t.data.dialog.draggableData.isMouseDown=!1}),t("body").on("mousemove",{dialog:this},function(t){var e=t.data.dialog;e.draggableData.isMouseDown&&e.getModalContent().offset({top:t.clientY-e.draggableData.mouseOffset.top,left:t.clientX-e.draggableData.mouseOffset.left})})),this},showPageScrollBar:function(e){t(document.body).toggleClass("modal-open",e)},updateZIndex:function(){var t=Object.keys(e.dialogs).length;if(t>1){var n=this.getModal(),o=n.data("bs.modal").$backdrop;n.css("z-index",e.ZINDEX_MODAL+20*(t-1)),o.css("z-index",e.ZINDEX_BACKDROP+20*(t-1))}return this},realize:function(){return this.initModalStuff(),this.getModal().addClass(e.NAMESPACE).addClass(this.getType()).addClass(this.getSize()).addClass(this.getCssClass()),this.getModalFooter().append(this.createFooterContent()),this.getModalHeader().append(this.createHeaderContent()),this.getModalBody().append(this.createBodyContent()),this.getModal().modal({backdrop:"static",keyboard:!1,show:!1}),this.makeModalDraggable(),this.handleModalEvents(),this.setRealized(!0),this.updateButtons(),this.updateTitle(),this.updateMessage(),this.updateClosable(),this},open:function(){return!this.isRealized()&&this.realize(),this.getModal().modal("show"),this.updateZIndex(),this.setOpened(!0),this},close:function(){return this.getModal().modal("hide"),this.isAutodestroy()&&delete e.dialogs[this.getId()],this.setOpened(!1),this}},e.newGuid=function(){return"xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g,function(t){var e=16*Math.random()|0,n="x"===t?e:3&e|8;return n.toString(16)})},e.show=function(t){return new e(t).open()},e.alert=function(){var n={},o={type:e.TYPE_PRIMARY,title:null,message:null,closable:!0,buttonLabel:"OK",callback:null};return n="object"==typeof arguments[0]&&arguments[0].constructor==={}.constructor?t.extend(!0,o,arguments[0]):t.extend(!0,o,{message:arguments[0],closable:!1,buttonLabel:"OK",callback:"undefined"!=typeof arguments[1]?arguments[1]:null}),new e({type:n.type,title:n.title,message:n.message,closable:n.closable,data:{callback:n.callback},onhide:function(t){!t.getData("btnClicked")&&t.isClosable()&&"function"==typeof t.getData("callback")&&t.getData("callback")(!1)},buttons:[{label:n.buttonLabel,action:function(t){t.setData("btnClicked",!0),"function"==typeof t.getData("callback")&&t.getData("callback")(!0),t.close()}}]}).open()},e.confirm=function(t,n){return new e({title:"Confirmation",message:t,closable:!1,data:{callback:n},buttons:[{label:"Cancel",action:function(t){"function"==typeof t.getData("callback")&&t.getData("callback")(!1),t.close()}},{label:"OK",cssClass:"btn-primary",action:function(t){"function"==typeof t.getData("callback")&&t.getData("callback")(!0),t.close()}}]}).open()},e.init=function(){var t="undefined"!=typeof module&&module.exports;t?module.exports=e:"function"==typeof define&&define.amd?define("bootstrap-dialog",function(){return e}):window.BootstrapDialog=e},e.init()}(window.jQuery);