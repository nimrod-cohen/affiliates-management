(function ($) {
  $(document).ready(function () {
    $('select.page_selection option').click(function () {
      var parent = $(this).closest('select');

      $(this).prop('selected', false);

      if (parent.attr('id') == 'off_pages') $('#on_pages').append($(this));
      else $('#off_pages').append($(this));
    });

    $('#submit_landingpages').click(function (ev) {
      $('select#on_pages option').each(function () {
        $(this).prop('selected', true);
      });
    });
  });

  $(document).ready(function () {
    $('#deal_type').change(function () {
      var val = $(this).val();

      $('tr[deal-id]').each(function () {
        if ($(this).attr('deal-id') != val && val != afm_admin.MIXED_CPA_REVSHARE)
          $(this).addClass('hidden');
        else $(this).removeClass('hidden');
      });
    });
  });

  JSUtils.domReady(() => {
    var tabs = document.querySelectorAll('#aff-tabs a');
    var tabViews = document.querySelectorAll('.tab-view');
    tabs.forEach(tab =>
      tab.addEventListener('click', () => {
        tabs.forEach(tb => tb.classList.remove('nav-tab-active'));
        tabViews.forEach(body => body.classList.remove('active'));

        var id = 'tab-' + tab.getAttribute('id').replace('-tab', '');
        document.querySelector('#' + id).classList.add('active');
        tab.classList.add('nav-tab-active');
      })
    );
  });

  $(document).ready(function () {
    $('div.tablenav-pages a.paging-button').click(function () {
      var page = $(this).data('page');
      $('input#current-page-selector').val(page);
      $(this).closest('form').submit();
    });

    $('table#accounting-table tbody tr').click(function () {
      var month = $(this).data('month');

      var detailSelector = "tr.payment-details-row[data-row-month='" + month + "']";
      if ($(detailSelector).length == 0) {
        $(this).after(
          "<tr class='payment-details-row' data-row-month='" +
            month +
            "' ><td></td><td class='payment-details-container' colspan='5'><i class='fa fa-cog fa-spin'></i></td></tr>"
        );

        var affId = $(this).closest('table').data('affiliate-id');
        fetchPayments($(detailSelector), month, affId);
      } else if ($(detailSelector).is(':visible')) $(detailSelector).hide();
      else $(detailSelector).show();
    });
  });

  function fetchPayments(row, month, affId) {
    $.ajax({
      url: afm_admin.ajax_url,
      type: 'post',
      data: {
        action: 'payment_history',
        security: afm_admin.nonce,
        aff_id: affId,
        month: month
      },
      success: function (response) {
        var res = JSON.parse(response);

        $('#current_balance').html(res.balance);

        if (res.rows.length == 0) {
          row.find('td.payment-details-container').html('no results');
          return;
        }

        var html =
          '<table>' +
          '<thead><tr><th>Payment date</th><th>Sum</th><th>Comment</th><th></th></tr></thead>' +
          '<tbody>';
        for (var i = 0; i < res.rows.length; i++) {
          html +=
            "<tr data-row-id='" +
            res.rows[i].id +
            "'><td>" +
            res.rows[i].action_date +
            '</td><td>' +
            res.rows[i].paid +
            '</td><td>' +
            res.rows[i].comment +
            '</td>' +
            "<td><button class='delete-row'>Delete</button></td>";
        }
        html += '</tbody></table>';
        row.find('td.payment-details-container').html(html);
      }
    });
  }

  $(document).on('click', 'button.delete-row', function () {
    $(this).prop('disabled', true);
    $(this).html("<i class='fa fa-cog fa-spin'></i><span> deleting</span>");

    var masterRow = $(this).closest('tr.payment-details-row');

    var paymentId = $(this).closest('tr').data('row-id');
    var affId = $('table#accounting-table').data('affiliate-id');
    var month = $(this).closest('tr.payment-details-row').data('row-month');

    $.ajax({
      url: afm_admin.ajax_url,
      type: 'post',
      data: {
        action: 'delete_payment_history',
        security: afm_admin.nonce,
        payment_id: paymentId,
        aff_id: affId,
        month: month
      },
      success: function (response) {
        var res = JSON.parse(response);

        $('#current_balance').html(res.balance);

        if (res.rows.length == 0) {
          masterRow.find('td.payment-details-container').html('no results');
          return;
        }

        var html =
          '<table>' +
          '<thead><tr><th>Payment date</th><th>Sum</th><th>Comment</th><th></th></tr></thead>' +
          '<tbody>';
        for (var i = 0; i < res.rows.length; i++) {
          html +=
            "<tr data-row-id='" +
            res.rows[i].id +
            "'><td>" +
            res.rows[i].action_date +
            '</td><td>' +
            res.rows[i].paid +
            '</td><td>' +
            res.rows[i].comment +
            '</td>' +
            "<td><button class='delete-row'>Delete</button></td>";
        }
        html += '</tbody></table>';
        masterRow.find('td.payment-details-container').html(html);
      }
    });
  });
})(jQuery);
