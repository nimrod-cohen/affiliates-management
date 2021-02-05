<form method="POST" action="">
	<div class="actions-bar row">
		<input type="text" readonly class="monthpicker" id="leads-month">
		<input type="text" class="" id="leads-name-or-email">
		<button class="button primary" id="search-leads">Search</button>
	</div>
	<div id="leads-scroller" class='table-wrapper'>
		<table id="leads-table" class="full-width">
			<thead>
			<tr>
				<th>Date</th>
				<th>Name</th>
				<th class='sensitive email'>Email</th>
				<th class='sensitive phone'>Phone</th>
				<th>Revenue</th>
				<th></th>
			</tr>
			</thead>
			<tbody>
				<tr><td colspan=6>No data found</td></tr>
			</tbody>
		</table>
	</div>
</form>