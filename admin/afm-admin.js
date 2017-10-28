(function($)
{
	$(document).ready(function () {
		$("#deal_type").change(function(){
			var val = $(this).val();

			$("tr[deal-id]").each(function(){
				if($(this).attr("deal-id") != val && val != afm_admin.MIXED_CPA_REVSHARE)
					$(this).addClass("hidden");
				else
					$(this).removeClass("hidden");
			});

		});
	});

	$(document).ready(function () {
		var tabsContainer = $(".nav-tab-wrapper");
		tabsContainer.find("a").click(function () {
			var thisTab = $(this);
			var allTabs = $(".tab-view");
			tabsContainer.find("a").removeClass("nav-tab-active");
			allTabs.removeClass("active");
			var id = "tab-" + thisTab.attr("id").replace("-tab", "");
			$("#" + id).addClass("active");
			thisTab.addClass("nav-tab-active");
		});
	});

	$(document).ready(function(){
		$('div.tablenav-pages a.paging-button').click(function()
		{
			var page = $(this).data('page');
			$('input#current-page-selector').val(page);
			$(this).closest('form').submit();
		});
	});

})(jQuery);