//initialize infinity scrolling for banner farm
JSUtils.domReady(() => {
  if (!afm_info.logged_in) return;

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
    var url = ev.target.querySelector('img').getAttribute('src');
    ev.target.insertAdjacentHTML(
      'beforeend',
      `<div class='hover_shade'>
				<a target='_blank' download href='${url}'>
					<div class='download_image button primary'>
						<i class='fa fa-download'></i>
					</div>
				</a>
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

    return response.length < afm_info.creatives_per_page;
  };

  window.bannerFarmScroll = new InfinityScroll('#bannerFarm', getCreatives);
});

//initialize tab navigation
JSUtils.domReady(() => {
  let tabsContainer = document.querySelector('.nav-tabs');
  if (!tabsContainer) return;

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

JSUtils.domReady(() => {
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

  //leads
  document.querySelector('#search-leads').addEventListener('click', () => {
    let input = document.querySelector('#leads-month');

    JSUtils.fetch(afm_info.ajax_url, {
      action: 'search-leads',
      month: input.getAttribute('month'),
      year: input.getAttribute('year')
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
      alert('done');
    });
  });
});
