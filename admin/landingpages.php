<?php
	$pages = get_pages();

	$onPages = [];
	$offPages = [];
	$selectedPages = get_option("afm_landingpages",[]);

	foreach($pages as $page)
	{
		if(in_array($page->ID,$selectedPages))
			$onPages[] = ["id"=>$page->ID,"title"=>$page->post_title];
		else
			$offPages[] = ["id"=>$page->ID,"title"=>$page->post_title];
	}
?>
<h1>Landing Pages</h1>
<form method="post" action="" novalidate="novalidate">
	<input type="hidden" name="action" value="save_landingpages"/>
	<input type="hidden" name="active_page" value="landingpages"/>
	<div class="lp_container">
		<div class="lp_titles_container">
			<label class="lp_list_title">Available pages</label>
			<label class="lp_list_title">Allowed as Landing Page</label>
		</div>
		<select id="off_pages" class="page_selection" multiple="1">
		<?php foreach($offPages as $page) { ?>
			<option value="<?php echo $page["id"]; ?>"><?php echo $page["title"];?></option>
		<?php } ?>
		</select>
		<select id="on_pages" name="on_pages[]" class="page_selection" multiple="1">
		<?php foreach($onPages as $page) { ?>
			<option value="<?php echo $page["id"]?>"><?php echo $page["title"];?></option>
		<?php } ?>
		</select>
	</div>
	<p class="submit"><input type="submit" id="submit_landingpages" class="button button-primary" value="Save Changes"></p>
</form>
