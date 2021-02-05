<?php
/**
 * Created by PhpStorm.
 * User: nimrod
 * Date: 07/24/17
 * Time: 16:12
 */

class AFMAccounting
{
	static function apply($affId, $userId, $month,$ftd = 0,$retention = 0,$paid = 0,$orderId = null,$comment = null, $noLog = false)
	{
		global $wpdb;

		$sql = "INSERT INTO afm_accounting
					(aff_id,month, ftd_revenue, retention_revenue,paid)
					VALUES (%d, %s, %f, %f, %f)
					ON DUPLICATE KEY UPDATE
					ftd_revenue = ftd_revenue + %f, retention_revenue = retention_revenue + %f, paid = paid + %f";

		$sql = $wpdb->prepare($sql,$affId,date('Y-m-d',$month),$ftd,$retention,$paid,$ftd,$retention,$paid);

		$wpdb->query($sql);

		if(!$noLog) {
			$sql = "INSERT INTO afm_accounting_log (aff_id, user_id,action_date,ftd_revenue,retention_revenue,paid,order_id,comment,is_deleted )
				VALUES ( %d, %d, CURRENT_TIMESTAMP, %f, %f, %f, %d, %s, 0 )";

			$sql = $wpdb->prepare($sql, $affId, $userId, $ftd, $retention, $paid, $orderId, $comment);

			$wpdb->query($sql);
		}
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

	static function paymentLog($affId,$month)
	{
		global $wpdb;

		$sql = "SELECT * FROM afm_accounting_log WHERE is_deleted = 0 and aff_id = %d and year(action_date) = year(%s) and month(action_date) = month(%s) ORDER BY id ASC";

		$sql = $wpdb->prepare($sql,$affId,$month,$month);

		return $wpdb->get_results($sql,ARRAY_A);
	}

	static function deletePayment($affId,$month,$paymentId)
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

		self::apply($affId,$result["user_id"],strtotime($month),0,0,$result["paid"]*-1,null,null,true);
	}
}