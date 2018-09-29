(function($){

	$(document).ready(function ()
	{
		var tabsContainer = $(".nav-tab-wrapper");
		tabsContainer.find("a").click(function ()
		{
			var thisTab = $(this);
			var allTabs = $(".tab-view");
			tabsContainer.find("a").removeClass("nav-tab-active");
			allTabs.removeClass("active");
			var id = "tab-"+thisTab.attr("id").replace("-tab", "");
			$("#" + id).addClass("active");
			thisTab.addClass("nav-tab-active");
		});

		$("#btnNewLink").click(function(ev)
		{
			ev.preventDefault();

			var form = $(this).closest("form");
			form.find("input[name='affiliate_action']").val("create_link");

			if(afm_info.landing_pages.length > 0)
			{
				remodaler.show({
					title: "Landing Page",
					message: "Choose Landing Page",
					type: remodaler.types.INPUT,
					values: afm_info.landing_pages,
					confirmText: "Create",
					confirm: function (val) {
						form.find("input[name='landing_page_id']").val(val);
						form.submit();
					}
				});
			}
			else
				form.submit();
		});

		$(".delete-link").click(function(ev)
		{
			ev.preventDefault();

			var self = this;
			var linkId = $(self).attr("link-id");

			remodaler.show({
				title: "Delete Link",
				message: "Delete link "+linkId + "?",
				type: remodaler.types.CONFIRM,
				confirmText : "Yes, delete",
				confirm: function () {
					var form = $(self).closest("form");
					form.find("input[name='link_id']").val(linkId);
					form.find("input[name='affiliate_action']").val("delete_link");
					form.submit();
				}
			});
		});
	});

	window["infinityScroller"] = {

		isRetrieving : false,
		isDone : false,
		page : 1,

		init : function()
		{
			$(document).on("mouseenter",".banner_box",this.addSuggestDownload);
			$(document).on("mouseleave",".banner_box",this.removeSuggestDownload);
			$("#bannerFarm").on("scroll",this.handleScroll);

		},

		handleScroll : function (ev) {
			console.log("scroll height: "+this.scrollHeight);
			console.log("scroll top: "+$(this).scrollTop());
			console.log("box size: "+$("#bannerFarm").height());
			console.log("is locked: "+infinityScroller.isRetrieving);

			if((this.scrollHeight - ($(this).scrollTop() + $("#bannerFarm").height()) > 300)
				|| infinityScroller.isRetrieving == true
				|| infinityScroller.isDone == true)
				return;

			infinityScroller.isRetrieving = true;
			console.log("adding retrieval indicator");

			infinityScroller.getCreatives();
		},

		addSuggestDownload : function()
		{
			var url = $(this).find("img").attr("src");
			$(this).append("<div class='hover_shade'><a target='_blank' download href='"+url+"'><div class='download_image'></div></a></div>");
		},

		removeSuggestDownload : function()
		{
			$(this).find(".hover_shade").remove();
		},

		appendCreatives : function (response) {
			console.log("page "+infinityScroller.page+" returned.");

			infinityScroller.page++;

			var arr = JSON.parse(response);

			console.log("found "+arr.length+" results.");

			if(arr.length < afm_info.creatives_per_page)
				infinityScroller.isDone = true;

			var currCol = infinityScroller.shortestColumn();

			for(var i = 0; i < arr.length; i++)
			{
				var html = "<div class='banner_box'><span class='middle_helper'></span><img src='"+arr[i]+"'/></div>";

				$("div.banner_col_"+currCol).append(html);

				currCol++;
				currCol = currCol == 4 ? 1 : currCol;
			}
		},

		getCreatives : function()
		{
			console.log("getting more");
			if(infinityScroller.isDone == true)
				return;

			console.log("calling server for page "+infinityScroller.page);

			jQuery.ajax({
				url: afm_info.ajax_url,
				type: 'post',
				data: {
					action: 'afm_get_creatives',
					security: afm_info.nonce,
					page: infinityScroller.page
				},
				success: this.appendCreatives
				}).done(function(){
				console.log("removing retrieval indicator");
				infinityScroller.isRetrieving == false;
			});
		},

		shortestColumn : function ()
		{
			var c1 = $("#bannerFarm .banner_col_1").children().length;
			var c2 = $("#bannerFarm .banner_col_2").children().length;
			var c3 = $("#bannerFarm .banner_col_3").children().length;

			if(c1 > c2)
				return 2;
			else if(c2 > c3)
				return 3;
			else
				return 1;
		}
	}

	$(document).ready(function(){
		window.infinityScroller.init();
		window.infinityScroller.getCreatives();
	});

})(jQuery);
