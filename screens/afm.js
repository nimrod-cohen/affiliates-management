JSUtils.domReady(() => {
  var tabsContainer = document.querySelector('.nav-tabs');
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

  document.querySelector('#btnNewLink').addEventListener('click', ev => {
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
});

class InfinityScroller {
  isRetrieving = false;
  isDone = false;
  page = 1;

  init = () => {
    let banners = document.querySelectorAll('.banner_box');
    //remove partially attached events
    banners.forEach(banner => {
      banner.removeEventListener('mouseenter', this.addSuggestDownload);
      banner.removeEventListener('mouseleave', this.removeSuggestDownload);
    });

    banners.forEach(banner => {
      banner.addEventListener('mouseenter', this.addSuggestDownload);
      banner.addEventListener('mouseleave', this.removeSuggestDownload);
    });
    document.querySelector('#bannerFarm').addEventListener('scroll', this.handleScroll);
  };

  handleScroll = ev => {
    if (
      this.scrollHeight -
        (window.pageYOffset + document.querySelector('#bannerFarm').offsetHeight) >
        300 ||
      this.isRetrieving == true ||
      this.isDone == true
    )
      return;

    this.isRetrieving = true;

    this.getCreatives();
  };

  addSuggestDownload = ev => {
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

  removeSuggestDownload = ev => {
    let shade = ev.target.querySelector('.hover_shade');
    shade.parentNode.removeChild(shade);
  };

  appendCreatives = response => {
    this.page++;

    var arr = JSON.parse(response);

    if (arr.length < afm_info.creatives_per_page) this.isDone = true;

    var currCol = this.shortestColumn();

    for (var i = 0; i < arr.length; i++) {
      var html = `<div class='banner_box'><span class='middle_helper'></span><img src='${arr[i]}'/></div>`;
      document.querySelector('div.banner_col_' + currCol).insertAdjacentHTML('beforeend', html);

      currCol++;
      currCol = currCol == 4 ? 1 : currCol;
    }

    this.init();
  };

  getCreatives = () => {
    if (this.isDone == true) return;

    jQuery
      .ajax({
        url: afm_info.ajax_url,
        type: 'post',
        data: {
          action: 'afm_get_creatives',
          security: afm_info.nonce,
          page: this.page
        },
        success: this.appendCreatives
      })
      .done(() => {
        this.isRetrieving == false;
      });
  };

  shortestColumn = () => {
    var c2 = document.querySelector('#bannerFarm .banner_col_2').children.length;
    var c1 = document.querySelector('#bannerFarm .banner_col_1').children.length;
    var c3 = document.querySelector('#bannerFarm .banner_col_3').children.length;

    if (c1 > c2) return 2;
    else if (c2 > c3) return 3;
    else return 1;
  };
}

JSUtils.domReady(() => {
  window.infinityScroller = new InfinityScroller();
  window.infinityScroller.getCreatives();
});

JSUtils.domReady(() => {
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
