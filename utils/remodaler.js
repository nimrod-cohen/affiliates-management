(function () {
  if (!window['remodler'])
    window['remodaler'] = {
      types: {
        ALERT: 0,
        CONFIRM: 1,
        INPUT: 2
      },
      _initialized: false,
      _options: null,
      show: function (options) {
        var self = this;

        self._options = options;

        if (!self._initialized) self._init();

        if (typeof self._options.type == 'undefined') self._options.type = self.types.ALERT;

        jQuery('.remodal h2[data-remodal-title]').text(self._options.title);
        jQuery('.remodal p[data-remodal-message]').html(self._options.message);

        if (self._options.type == self.types.INPUT) {
          if (typeof self._options.values == 'undefined')
            jQuery('.remodal p[data-remodal-input]').html(
              "<input type='text' name='remodal-data-input'>"
            );
          else {
            var select = "<select name='remodal-data-input'>";
            for (var i = 0; i < self._options.values.length; i++) {
              var val = self._options.values[i];
              select += "<option value='" + val.value + "'>" + val.title + '</option>';
            }
            select += '</select>';
            jQuery('.remodal p[data-remodal-input]').html(select);
          }
        }

        jQuery(".remodal button[data-remodal-action='confirm']").text(self._options.confirmText);
        jQuery(".remodal button[data-remodal-action='cancel']").text(self._options.cancelText);

        if (self._options.type == self.types.ALERT)
          jQuery(".remodal button[data-remodal-action='cancel']").hide();
        else if (self._options.type == self.types.CONFIRM || self._options.type == self.types.INPUT)
          jQuery(".remodal button[data-remodal-action='cancel']").show();

        if (self._options.type != self.types.INPUT) jQuery('[data-remodal-input]').hide();
        else jQuery('[data-remodal-input]').show();

        jQuery(document).on('confirmation', '.remodal', function () {
          var val = jQuery('.remodal [name=remodal-data-input]').val();
          self._confirm(val);
        });

        if (typeof this._options.init == 'function') this._options.init();

        self._show();
      },

      _show: function () {
        jQuery('.remodal-bg').css('display', 'flex');
      },

      _confirm: function (val) {
        if (typeof this._options.confirm == 'function') this._options.confirm(val);
      },

      _init: function () {
        jQuery(document.body).append(
          "<div class='remodal-bg'>" +
            "<div class='remodal' data-remodal-id='modal'>" +
            "<button data-remodal-action='close' class='remodal-close'>&#x00D7;</button>" +
            '<h2 data-remodal-title></h2>' +
            '<p data-remodal-message></p>' +
            '<p data-remodal-input></p>' +
            "<button data-remodal-action='confirm' class='remodal-confirm'>OK</button>" +
            "<button data-remodal-action='cancel' class='remodal-cancel'>cancel</button> " +
            '</div>' +
            '</div>'
        );

        jQuery('.remodal-confirm').click(function (ev) {
          ev.preventDefault();
          jQuery('.remodal-bg').hide();
          jQuery('.remodal').trigger('confirmation');
        });

        jQuery('.remodal-cancel, .remodal-close').click(function (ev) {
          ev.preventDefault();
          jQuery('.remodal-bg').hide();
          jQuery('.remodal').trigger('cancellation');
        });

        this._initialized = true;
      }
    };
})();
