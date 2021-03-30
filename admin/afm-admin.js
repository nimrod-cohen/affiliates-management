JSUtils.domReady(() => {
  let attachBtn = document.getElementById('attach-user-to-affiliate');
  attachBtn &&
    attachBtn.addEventListener('click', e => {
      e.preventDefault();
      remodaler.show({
        title: 'Attach User to affiliate',
        message: 'Enter user ID',
        type: remodaler.types.INPUT,
        confirmText: 'Attach',
        confirm: async val => {
          let affId = document.querySelector('#affiliate-page').getAttribute('affiliate-id');
          let response = await JSUtils.fetch(afm_admin.ajax_url, {
            action: 'attach_user_to_affiliate',
            user_id: val,
            affiliate_id: affId
          });

          notifications.show(response.message, response.error ? 'error' : 'success');
        }
      });
    });
});

//handle allowed landing pages selection/deselection.
JSUtils.domReady(() => {
  let options = document.querySelectorAll('select.page_selection option');
  options.forEach(option =>
    option.addEventListener('click', e => {
      var parent = e.target.closest('select');

      e.target.selected = false;

      if (parent.getAttribute('id') == 'off_pages')
        document.getElementById('on_pages').appendChild(e.target);
      else document.getElementById('off_pages').appendChild(e.target);
    })
  );

  //mark them as selected before submittion, so they will be posted to server.
  let submit = document.querySelector('#submit_landingpages');
  submit &&
    submit.addEventListener('click', () => {
      let opts = document.querySelectorAll('select#on_pages option');
      for (var i = 0; i < opts.length; i++) {
        //don't use .forEach - its async and will not be on time before posting.
        opts[i].selected = true;
      }
    });
});

JSUtils.domReady(() => {
  document.getElementById('deal_type').addEventListener('change', e => {
    var val = e.target.value;

    let dealRows = document.querySelectorAll('tr[deal-id]');

    dealRows.forEach(dealRow => {
      if (dealRow.getAttribute('deal-id') != val && val != afm_admin.MIXED_CPA_REVSHARE)
        dealRow.classList.add('hidden');
      else dealRow.classList.remove('hidden');
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

JSUtils.domReady(() => {
  JSUtils.addGlobalEventListener(document, '#payouts-table .delete-payout', 'click', async e => {
    e.preventDefault();
    const tr = e.target.closest('tr');
    let productId = tr.getAttribute('product_id');
    let isFirst = tr.getAttribute('is_first');
    let affId = tr.closest('table').getAttribute('affiliate-id');

    await JSUtils.fetch(afm_admin.ajax_url, {
      action: 'delete_product_payout',
      affiliate_id: affId,
      product_id: productId,
      is_first: isFirst
    });

    tr.parentNode.removeChild(tr);
  });
});

JSUtils.domReady(() => {
  let pagingButtons = document.querySelectorAll('div.tablenav-pages a.paging-button');
  pagingButtons.forEach(btn =>
    btn.addEventListener('click', e => {
      var page = e.target.getAttribute('data-page');
      document.querySelector('input#current-page-selector').value = page;
      e.target.closest('form').submit();
    })
  );

  const fetchPayments = (masterRow, month, affId) => {
    JSUtils.fetch(afm_admin.ajax_url, {
      action: 'payment_history',
      security: afm_admin.nonce,
      aff_id: affId,
      month: month
    }).then(response => renderPayoutHistory(masterRow, response));
  };

  let rows = document.querySelectorAll('table#accounting-table tbody tr');
  rows.forEach(row =>
    row.addEventListener('click', e => {
      var month = row.getAttribute('data-month');

      let masterRow = document.querySelector(`tr.payment-details-row[data-row-month='${month}']`);
      if (!masterRow) {
        row.insertAdjacentHTML(
          'afterend',
          `<tr class='payment-details-row' data-row-month='${month}' >
          <td></td>
          <td class='payment-details-container' colspan='6'><i class='fa fa-cog fa-spin'></i></td>
        </tr>`
        );

        var affId = row.closest('table').getAttribute('data-affiliate-id');
        masterRow = document.querySelector(`tr.payment-details-row[data-row-month='${month}']`);
        fetchPayments(masterRow, month, affId);
      } else {
        masterRow.style.display = masterRow.style.display === 'none' ? 'table-row' : 'none';
      }
    })
  );

  const renderPayoutHistory = (monthRow, data) => {
    document.querySelector('#current_balance').innerText = data.balance;

    if (data.rows.length == 0) {
      monthRow.querySelector('td.payment-details-container').innerHTML = 'no results';
      return;
    }

    var html =
      '<table>' +
      '<thead><tr><th>Payment date</th><th>User</th><th>Transaction Id</th><th>Sum</th><th>Paid</th><th>Comment</th><th></th></tr></thead>' +
      '<tbody>';
    for (var i = 0; i < data.rows.length; i++) {
      let row = data.rows[i];
      html +=
        "<tr data-row-id='" +
        row.id +
        "'><td>" +
        row.action_date +
        '</td><td>' +
        row.display_name +
        '</td><td>' +
        row.order_id +
        '</td><td>' +
        row.payout +
        '</td><td>' +
        row.paid +
        '</td><td>' +
        row.comment +
        '</td>' +
        "<td><button class='delete-row'>Delete</button></td>";
    }
    html += '</tbody></table>';
    monthRow.querySelector('td.payment-details-container').innerHTML = html;
  };

  JSUtils.addGlobalEventListener(document, 'button.delete-row', 'click', e => {
    e.target.disabled = true;
    e.target.innerHTML = "<i class='fa fa-cog fa-spin'></i><span> deleting</span>";

    let masterRow = e.target.closest('tr.payment-details-row');

    var paymentId = e.target.closest('tr').getAttribute('data-row-id');
    var affId = document.querySelector('table#accounting-table').getAttribute('data-affiliate-id');
    var month = e.target.closest('tr.payment-details-row').getAttribute('data-row-month');

    JSUtils.fetch(afm_admin.ajax_url, {
      action: 'delete_payment_history',
      security: afm_admin.nonce,
      payment_id: paymentId,
      aff_id: affId,
      month: month
    }).then(response => renderPayoutHistory(masterRow, response));
  });
});
