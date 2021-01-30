<?php
/**
 * Created by PhpStorm.
 * User: nimrod
 * Date: 07/24/17
 * Time: 16:12
 */

class AFMStats
{
	public static function byUser($userId)
	{
		global $wpdb;

		//verify he is not locked already
		$lockAff = self::whoLocks($userId);

		if($lockAff)
			return $lockAff;

		$sql = "SELECT aff_id,link_id
			FROM afm_events
			WHERE user_id = %d
			and DATEDIFF(CURRENT_TIMESTAMP,ts) < %d
			ORDER BY ts DESC LIMIT 1";

		$sql = $wpdb->prepare($sql,$userId,AffiliatesManagement::AFM_KEEP_DAYS);

		$result = $wpdb->get_row($sql,ARRAY_A);

		if(!$result || !is_array($result) || count($result) == 0 || $result["aff_id"] == 0)
			return false;

		return $result;
	}

	public static function firstPayment($userId)
	{
		global $wpdb;

		$sql = "SELECT ts
			FROM afm_events
			WHERE user_id = %d
			AND event = 'first_deposit'
			ORDER BY ts ASC LIMIT 1";

		$sql = $wpdb->prepare($sql,$userId);

		$result = $wpdb->get_var($sql);

		if(!$result)
			return false;
		return strtotime($result);
	}

	public static function whoLocks($userId)
	{
		global $wpdb;

		$result = [
			'aff_id' => get_user_meta($userId,'affiliate_id',true),
			'link_id' => get_user_meta($userId,'affiliate_link_id',true)
		];

		if(strlen($result["aff_id"]) > 0 && strlen($result["link_id"]) > 0) return $result;

		$sql = "SELECT aff_id,link_id
			FROM afm_events
			WHERE user_id = %d
			AND event = %s
			ORDER BY ts ASC LIMIT 1";

		$sql = $wpdb->prepare($sql,$userId,AffiliatesManagement::lockingEvent());

		$result = $wpdb->get_row($sql, ARRAY_A);

		if(!$result || !is_array($result) || count($result) == 0 || $result["aff_id"] == 0)
			return false;

		return $result;
	}

	/*
	 * We don't check for locks by tracker, because at the moment this is only used during registration
	 */
	public static function byTracker($trackerId,$checkLock = false)
	{
		global $wpdb;

		if($checkLock)
			throw new Exception("We should retrieve user id by tracker, and then check who locks");

		$sql = "SELECT aff_id,link_id
			FROM afm_events
			WHERE tracked_id = %s
			and DATEDIFF(CURRENT_TIMESTAMP,ts) < %d
			ORDER BY ts DESC LIMIT 1";

		$sql = $wpdb->prepare($sql,$trackerId,AffiliatesManagement::AFM_KEEP_DAYS);

		$result = $wpdb->get_row($sql,ARRAY_A);

		if(!$result || !is_array($result) || count($result) == 0)
			return false;

		return $result;
	}

	static function affLinkStats($affId,$page, $limit)
	{
		global $wpdb;

		$sql = "select afml.id as link_id,afml.url,afml.aff_id,DATE_FORMAT(afml.created,'%s') as created,event,count(1) as counter,sum(amount) as amount
			FROM afm_links afml
			LEFT OUTER JOIN afm_events afme on afml.id = afme.link_id
			WHERE afml.aff_id = %d
			AND afml.is_deleted = 0
			GROUP BY afml.id,event
			ORDER BY afml.id
			LIMIT %d,%d";

		$sql = $wpdb->prepare($sql, '%d/%m/%Y %H:%i', $affId, $page, $limit);

		$rows = $wpdb->get_results($sql, ARRAY_A);

		$result = [];
		foreach($rows as $row)
		{
			$link = $row["link_id"];
			if(!isset($result[$link]))
				$result[$link] = [
					"id" => $link,
					"aff_id" => $row["aff_id"],
					"ftd_amount" => 0,
					"retention_amount" => 0,
					"created" => $row["created"],
					"url" => $row["url"],
					"first_deposit" => 0,
					"deposit" => 0,
					"click" => 0,
					"register" => 0
				];

			$result[$link][$row["event"]] = $row["counter"];

			if($row["event"] == "first_deposit")
				$result[$row["link_id"]]["ftd_amount"] = $row["amount"];
			if($row["event"] == "deposit")
				$result[$row["link_id"]]["retention_amount"] = $row["amount"];
		}

		return $result;
	}

	static function event($linkId,$affId,$trackerId,$userId,$event,$source = null,$medium = null,$campaign = null,$content = null,$term = null,$amount = 0, $productId = null)
	{
		global $wpdb;

		//check if need to lock
		$lockingEvents = AffiliatesManagement::lockingEvent();
		if(!is_array($lockingEvents)) $lockingEvents = [$lockingEvents];

		if ($userId != 0 &&	in_array($event, $lockingEvents) &&	!self::whoLocks($userId)) {
			update_user_meta($userId,'affiliate_id',$affId);
			update_user_meta($userId,'affiliate_link_id',$linkId);
		}

		

		$sql = "INSERT INTO afm_events (link_id,aff_id,tracked_id,user_id,event,source,medium,campaign,content,term,amount,product_id)
			VALUES (%d,%d,%s,%d,%s,%s,%s,%s,%s,%s,%f,%d)";

		$sql = $wpdb->prepare($sql,$linkId,$affId,$trackerId,$userId,$event,
			$source,$medium,$campaign,$content,$term,$amount, $productId);

		$wpdb->query($sql);
	}
}