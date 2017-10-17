(function(){
    if(!window["remodler"])
        window["remodaler"] =
        {
            types : {
                ALERT : 0,
                CONFIRM :1
            },
            _initialized : false,
            _options : null,
            show : function(options)
            {
                var self = this;

                self._options = options;

                if(!self._initialized)
                    self._init();

                if(typeof self._options.type == "undefined")
                    self._options.type = self.types.ALERT;

                jQuery(".remodal h2[data-remodal-title]").text(self._options.title);
                jQuery(".remodal p[data-remodal-message]").html(self._options.message);
                jQuery(".remodal button[data-remodal-action='confirm']").text(self._options.confirmText);
                jQuery(".remodal button[data-remodal-action='cancel']").text(self._options.cancelText);

                if(self._options.type == self.types.ALERT)
                    jQuery(".remodal button[data-remodal-action='cancel']").hide();
                else if(self._options.type == self.types.CONFIRM)
                    jQuery(".remodal button[data-remodal-action='cancel']").show();

                jQuery(document).on('confirmation', '.remodal', function () {
                    self._confirm();
                });

                if(typeof this._options.init == "function")
                    this._options.init();

                self._show();
            },

            _show : function()
            {
                jQuery(".remodal-bg").css("display","flex");
            },

            _confirm : function()
            {
                console.log('Confirmation button clicked');
                if(typeof this._options.confirm == "function")
                    this._options.confirm(jQuery());
            },

            _init : function()
            {
                jQuery(document.body).append("<div class='remodal-bg'>" +
                    "<div class='remodal' data-remodal-id='modal'>" +
                    "<button data-remodal-action='close' class='remodal-close'>&#x00D7;</button>" +
                    "<h2 data-remodal-title></h2>" +
                    "<p data-remodal-message></p>"+
                    "<button data-remodal-action='confirm' class='remodal-confirm'>OK</button>" +
                    "<button data-remodal-action='cancel' class='remodal-cancel'>cancel</button> " +
                    "</div>" +
                    "</div>");

                jQuery(".remodal-confirm").click(function(ev) {
                    ev.preventDefault();
                    jQuery(".remodal-bg").hide();
                    jQuery(".remodal").trigger("confirmation");
                });

                jQuery(".remodal-cancel, .remodal-close").click(function(ev) {
                    ev.preventDefault();
                    jQuery(".remodal-bg").hide();
                    jQuery(".remodal").trigger("cancellation");
                });

                this._initialized = true;
            }
        }
})();