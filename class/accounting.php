<?php
/**
 * Created by PhpStorm.
 * User: nimrod
 * Date: 07/24/17
 * Time: 16:12
 */

class AFMAccounting
{
	static function find($affId, $userId, $orderId) {
		global $wpdb;

		$sql = "SELECT * FROM afm_accounting_log 
			WHERE aff_id = %d 
			AND user_id = %d
			AND order_id = %s 
			AND is_deleted = 0";

		$sql = $wpdb->prepare($sql, $affId, $userId, $orderId);
		return $wpdb->get_row($sql, ARRAY_A);
	}

	static function apply($affId, $userId, $timestamp,$ftd = 0,$retention = 0,$paid = 0,$orderId = null,$comment = null)
	{
		global $wpdb;

		$sql = "SELECT id FROM afm_accounting_log 
			WHERE aff_id = %d 
			AND user_id = %d
			AND order_id = %s 
			AND is_deleted = 0";

		$sql = $wpdb->prepare($sql, $affId, $userId, $orderId);

		$id = $wpdb->get_var($sql);

		//order id should not be zero, but for old time sake
		if($id != null && intval($orderId) > 0) {
			$sql = "UPDATE afm_accounting_log 
			set ftd_revenue = %f,
			retention_revenue = %f,
			paid = %f
			WHERE id = %d";

			$sql = $wpdb->prepare($sql, $ftd, $retention, $paid, $id);

			$wpdb->query($sql);
		}	else {
			$sql = "INSERT INTO afm_accounting_log (aff_id, user_id,action_date,ftd_revenue,retention_revenue,paid,order_id,comment,is_deleted )
				VALUES ( %d, %d, %s, %f, %f, %f, %d, %s, 0 )";

			$sql = $wpdb->prepare($sql, $affId, $userId, date('Y-m-d', $timestamp), $ftd, $retention, $paid, $orderId, $comment);

			$wpdb->query($sql);
		}

		AFMAccounting::recalculateAccounting($affId, $timestamp);
	}

	static function recalculateAccounting($affId,$month) {
		global $wpdb;
		$sql = "SELECT SUM(ftd_revenue) AS ftd, SUM(retention_revenue) AS retention, SUM(paid) as paid
		FROM afm_accounting_log
		WHERE year(action_date) = %d and month(action_date) = %d and aff_id = %d and is_deleted  = 0
		";
		$sql = $wpdb->prepare($sql, date('Y', $month), date('m', $month), $affId);

		$sums = $wpdb->get_row($sql, ARRAY_A);

		$sql = "INSERT INTO afm_accounting
					(aff_id,month, ftd_revenue, retention_revenue,paid)
					VALUES (%d, %s, %f, %f, %f)
					ON DUPLICATE KEY UPDATE
					ftd_revenue = %f, retention_revenue = %f, paid = %f";

		$sql = $wpdb->prepare($sql,$affId,date('Y-m-01',$month),$sums["ftd"],$sums["retention"],$sums["paid"],$sums["ftd"],$sums["retention"],$sums["paid"]);

		$wpdb->query($sql);
	}

	static function byAffiliate($affId,$page,$pageSize = null, &$count = null)
	{
		global $wpdb;

		$pageSize = !$pageSize ? PHP_INT_MAX : $pageSize;
		$page = $page * $pageSize;

		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM afm_accounting WHERE aff_id = %d ORDER BY month DESC LIMIT %d,%d";

		$sql = $wpdb->prepare($sql,$affId,$page,$pageSize);

		$results = $wpdb->get_results($sql,ARRAY_A);

		if(isset($count))
			$count = $wpdb->get_var("SELECT FOUND_ROWS();");

		return $results;
	}

	static function paymentLog($affId, $month, $exposeLeads)
	{
		AFMAffiliate::fromAffiliateId($affId);

		global $wpdb;

		$sql = "SELECT al.* {EXPOSE}
			FROM afm_accounting_log al
			LEFT OUTER JOIN wp_users u on u.id = al.user_id 
			WHERE is_deleted = 0 
			AND aff_id = %d 
			AND year(action_date) = year(%s) 
			AND month(action_date) = month(%s)
			ORDER BY id ASC";

$sql = str_replace('{EXPOSE}',$exposeLeads ? ", u.display_name" : "", $sql);

		$sql = $wpdb->prepare($sql,$affId,$month,$month);

		return $wpdb->get_results($sql,ARRAY_A);
	}

	static function deletePayment($affId,$paymentId)
	{
		global $wpdb;

		$sql = "SELECT * FROM afm_accounting_log WHERE id = %d";

		$sql = $wpdb->prepare($sql,$paymentId);

		$result = $wpdb->get_row($sql,ARRAY_A);

		if($result["is_deleted"] == 1)
			return;

		$sql = "UPDATE afm_accounting_log
			SET is_deleted = 1
			WHERE id = %d";

		$sql = $wpdb->prepare($sql,$paymentId);

		$wpdb->query($sql);

		self::recalculateAccounting($affId,strtotime($result["action_date"]));
	}
}