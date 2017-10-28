<?php
/**
 * Created by PhpStorm.
 * User: nimrod
 * Date: 07/24/17
 * Time: 16:12
 */

class AFMAccounting
{
	static function apply($affId,$month,$ftd = 0,$retention = 0,$paid = 0,$order_id = null,$comment = null)
	{
		global $wpdb;

		$sql = "INSERT INTO afm_accounting
					(aff_id,month, ftd_revenue, retention_revenue,paid)
					VALUES (%d, %s, %f, %f, %f)
					ON DUPLICATE KEY UPDATE
					ftd_revenue = ftd_revenue + %f, retention_revenue = retention_revenue + %f, paid = paid + %f";

		$sql = $wpdb->prepare($sql,$affId,date('Y-m-d',$month),$ftd,$retention,$paid,$ftd,$retention,$paid);

		$wpdb->query($sql);

		$sql = "INSERT INTO afm_accounting_log (aff_id,action_date,ftd_revenue,retention_revenue,paid,order_id,comment,is_deleted )
				VALUES ( %d, CURRENT_TIMESTAMP, %f, %f, %f, %d, %s, 0 )";

		$sql = $wpdb->prepare($sql,$affId,$ftd,$retention,$paid,$order_id,$comment);

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
}