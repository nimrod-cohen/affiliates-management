<?php
/**
 * Created by PhpStorm.
 * User: nimrod
 * Date: 07/24/17
 * Time: 16:12
 */

class AFMAccounting
{
	static function apply($affId,$month,$ftd = 0,$retention = 0,$paid = 0,$comment = null)
	{
		global $wpdb;

		$sql = "INSERT INTO afm_accounting
					(aff_id,month, ftd_revenue, retention_revenue,paid,comment)
					VALUES(%d, %s, %f, %f, %f, %s)
					ON DUPLICATE KEY UPDATE
					ftd_revenue = ftd_revenue + %f, retention_revenue = retention_revenue + %f, paid = paid + %f, comment = CONCAT_WS('\n',comment,%s)";

		$sql = $wpdb->prepare($sql,$affId,date('Y-m-d',$month),$ftd,$retention,$paid,$comment,$ftd,$retention,$paid,$comment);

		$wpdb->query($sql);
	}

	static function byAffiliate($affId,$page,$pageSize = null)
	{
		global $wpdb;

		$pageSize = !$pageSize ? PHP_INT_MAX : $pageSize;
		$page = $page * $pageSize;

		$sql = "SELECT * FROM afm_accounting WHERE aff_id = %d ORDER BY month DESC LIMIT %d,%d";

		$sql = $wpdb->prepare($sql,$affId,$page,$pageSize);

		return $wpdb->get_results($sql,ARRAY_A);
	}
}