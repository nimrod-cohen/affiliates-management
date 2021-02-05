//initialize infinity scrolling for banner farm
JSUtils.domReady(() => {
  if (afm_info.logged_in !== '1') return;

  const shortestColumn = () => {
    var c2 = document.querySelector('#bannerFarm .banner_col_2').children.length;
    var c1 = document.querySelector('#bannerFarm .banner_col_1').children.length;
    var c3 = document.querySelector('#bannerFarm .banner_col_3').children.length;

    if (c1 > c2) return 2;
    else if (c2 > c3) return 3;
    else return 1;
  };

  const wireEvents = () => {
    let banners = document.querySelectorAll('.banner_box');
    //remove partially attached events
    banners.forEach(banner => {
      banner.removeEventListener('mouseenter', addSuggestDownload);
      banner.removeEventListener('mouseleave', removeSuggestDownload);
    });

    banners.forEach(banner => {
      banner.addEventListener('mouseenter', addSuggestDownload);
      banner.addEventListener('mouseleave', removeSuggestDownload);
    });
  };

  const addSuggestDownload = ev => {
    const img = ev.target.querySelector('img');
    const url = img.getAttribute('src');

    ev.target.insertAdjacentHTML(
      'beforeend',
      `<div class='hover_shade'>
				<a target='_blank' download href='${url}'>
					<div class='download_image button primary'>
						<i class='fa fa-download'></i>
					</div>
        </a>
        <span class='image-size small'>${img.naturalWidth}x${img.naturalHeight}</span>
			</div>`
    );
  };

  const removeSuggestDownload = ev => {
    let shade = ev.target.querySelector('.hover_shade');
    shade.parentNode.removeChild(shade);
  };

  const getCreatives = async page => {
    let response = await JSUtils.fetch(afm_info.ajax_url, {
      action: 'afm_get_creatives',
      security: afm_info.nonce,
      page: page
    });

    if (response.length === 0) {
      return true; //finished
    }

    var currCol = shortestColumn();

    for (var i = 0; i < response.length; i++) {
      var html = `<div class='banner_box'><span class='middle_helper'></span><img src='${response[i]}'/></div>`;
      document.querySelector('div.banner_col_' + currCol).insertAdjacentHTML('beforeend', html);

      currCol++;
      currCol = currCol == 4 ? 1 : currCol;
    }

    wireEvents();

    return response.length < afm_info.paging_size;
  };

  window.bannerFarmScroll = new InfinityScroll('#bannerFarm', getCreatives);
});

//leads
JSUtils.domReady(() => {
  if (afm_info.logged_in !== '1') return;

  let input = document.querySelector('#leads-month');
  var currentQuery = {
    year: input.getAttribute('year'),
    month: input.getAttribute('month')
  };

  if (afm_info.expose_leads !== '1') {
    sensitives = document.querySelectorAll('#leads-table thead th.sensitive');
    sensitives.forEach(field => (field.innerText = ''));
  }

  const getLeads = async page => {
    let response = await JSUtils.fetch(afm_info.ajax_url, {
      action: 'search_leads',
      ...currentQuery,
      page: page
    });

    var leadsTable = document.querySelector('#leads-table tbody');

    if (!response) {
      alert('Failed to retrieve leads', 'error');
      if (leadsTable.childNodes.length === 0) {
        leadsTable.insertAdjacentHTML('beforeend', '<tr><td colspan=6>No data found</td></tr>');
      }
      return true; //stop it from running again
    }

    if (response.length === 0) {
      if (leadsTable.childNodes.length === 0) {
        leadsTable.insertAdjacentHTML('beforeend', '<tr><td colspan=6>No data found</td></tr>');
      }
      return true; //finished
    }

    response.forEach(row => {
      leadsTable.insertAdjacentHTML(
        'beforeend',
        `<tr user-id=${row.ID}>
        <td>${row.user_registered}</td>
        <td>${row.display_name}</td>
        <td>${row.user_email || ''}</td>
        <td>${row.phone || ''}</td>
        <td>${row.deposits}</td>
      </tr>`
      );
    });

    return response.length < afm_info.paging_size;
  };

  document.querySelector('#search-leads').addEventListener('click', e => {
    e.preventDefault();

    var leadsTable = document.querySelector('#leads-table tbody');
    leadsTable.replaceChildren();

    let input = document.querySelector('#leads-month');
    currentQuery = {
      year: input.getAttribute('year'),
      month: input.getAttribute('month')
    };

    window.leadsInfinityScroll = new InfinityScroll('#leads-scroller', getLeads);
  });
});

//initialize tab navigation
JSUtils.domReady(() => {
  if (afm_info.logged_in !== '1') return;

  let tabsContainer = document.querySelector('.nav-tabs');

  links = tabsContainer.querySelectorAll('a');
  links.forEach(link =>
    link.addEventListener('click', e => {
      var thisTab = e.target;
      var allTabs = document.querySelectorAll('.tab-body');
      links.forEach(lk => lk.classList.remove('nav-tab-active'));
      allTabs.forEach(tb => tb.classList.remove('active'));
      var id = 'tab-' + thisTab.getAttribute('id').replace('-tab', '');
      document.querySelector('#' + id).classList.add('active');
      thisTab.classList.add('nav-tab-active');
    })
  );
});

//links
JSUtils.domReady(() => {
  if (afm_info.logged_in !== '1') return;

  document.querySelector('#new-link').addEventListener('click', ev => {
    ev.preventDefault();

    var form = ev.target.closest('form');

    form.querySelector("input[name='affiliate_action']").value = 'create_link';

    if (afm_info.landing_pages.length > 0) {
      remodaler.show({
        title: 'Landing Page',
        message: 'Choose Landing Page',
        type: remodaler.types.INPUT,
        values: afm_info.landing_pages,
        confirmText: 'Create',
        confirm: function (val) {
          form.querySelector("input[name='landing_page_id']").value = val;
          form.submit();
        }
      });
    } else form.submit();
  });

  JSUtils.addGlobalEventListener(document, '.delete-link', 'click', ev => {
    ev.preventDefault();

    var linkId = ev.target.getAttribute('link-id');

    remodaler.show({
      title: 'Delete Link',
      message: 'Delete link ' + linkId + '?',
      type: remodaler.types.CONFIRM,
      confirmText: 'Yes, delete',
      confirm: () => {
        var form = ev.target.closest('form');
        form.querySelector("input[name='link_id']").value = linkId;
        form.querySelector("input[name='affiliate_action']").value = 'delete_link';
        form.submit();
      }
    });
  });

  //copiables
  let arr = document.querySelectorAll('.copiable .button');

  arr.forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      e.stopPropagation();
      let text = e.target.closest('.copiable').querySelector('span:first-child').innerText;
      JSUtils.copyToClipboard(text);
      window.notifications.show('Link copied to clipboard successfully', 'success');
    });
  });
});
