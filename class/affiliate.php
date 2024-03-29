<?php
/**
 * Created by PhpStorm.
 * User: nimrod
 * Date: 07/23/17
 * Time: 13:36
 */

class AFMAffiliate
{
	private $ID = null;
	private $user = null;

	public function ID() { return $this->ID; }
	public function user() { return $this->user; }
	public function skype(){ return get_user_meta($this->ID,"skype_user",true); }
	public function phone(){ return get_user_meta($this->ID,"phone",true); }
	public function fullname(){ return $this->user->display_name; }
	public function email(){ return $this->user->user_email; }
	public function website(){ return $this->user->user_url; }
	public function deal() { return get_user_meta($this->ID,"afm_deal",true); }
	public function pixel() { return get_user_meta($this->ID,"afm_pixel",true); }
	public function integration() { 
		$integration = get_user_meta($this->ID,"afm_integration",true);

		if(empty($integration)) return [];

		return json_decode($integration, true);
	}
	public function exposeLeads() { return get_user_meta($this->ID, "expose_leads", true) == "1"; }

	private function __construct()
	{}

	public static function fromAffiliateEmail($email)
	{
		$aff = new AFMAffiliate();

		$aff->user = get_user_by("email",$email);

		if(!$aff->user) return null;

		if(!in_array(AffiliatesManagement::AFM_ROLE_NAME,$aff->user->roles))
			return null;

		return $aff;
	}

	public static function fromAffiliateId($id)
	{
		$aff = new AFMAffiliate();
		$aff->ID = $id;

		$aff->user = get_user_by("ID",$id);

		if(!$aff->user) return null;

		if(!in_array(AffiliatesManagement::AFM_ROLE_NAME,$aff->user->roles))
			return null;

		return $aff;
	}

	public static function fromWPUser($user)
	{
		if(!$user) return null;
		
		if(!in_array(AffiliatesManagement::AFM_ROLE_NAME,$user->roles))
			return null;

		$aff = new AFMAffiliate();
		$aff->ID = $user->ID;
		$aff->user = $user;

		return $aff;
	}

	public static function fromCurrentUser()
	{
		$id = get_current_user_id();

		if($id == 0)
			throw new Exception("No user is logged in, cannot load affiliate details");

		return AFMAffiliate::fromAffiliateId($id);
	}

	public function setDeal($deal)
	{
		update_user_meta($this->ID,"afm_deal",$deal);
	}

	public function getProductPayout($productId, $isFirst) {
		$payouts = $this->getProductPayouts();

		foreach($payouts as $payout) {
			if($payout["product_id"] == $productId && $payout["is_first"] == $isFirst) return $payout;
		}

		return false;
	}

	public function getProductPayouts() {
		$payouts = get_user_meta($this->ID(),'product_payouts',true);
		if($payouts)
			$payouts = json_decode($payouts, true);
		else 
			$payouts = [];
		return $payouts;
	}

	public function updateProductPayout($productId, $isFirst, $payout) {
		$payouts = $this->getProductPayouts();

		$found = false;
		foreach($payouts as &$ppay) {
			if($ppay["product_id"] == $productId && $ppay["is_first"] == $isFirst) {
				$ppay["payout"] = $payout;
				$found = true;
				break;
			}
		}

		if(!$found) {
			$payouts[] = [
				"product_id" => $productId,
				"is_first" => $isFirst,
				"payout" => $payout
			];
		}
		update_user_meta($this->ID(),'product_payouts',json_encode($payouts));
	}

	public function deleteProductPayout($productId, $isFirst) {
		$payouts = $this->getProductPayouts();

		foreach($payouts as $key => $ppay) {
			if($ppay["product_id"] == $productId && $ppay["is_first"] == $isFirst) {
				unset($payouts[$key]);
				break;
			}
		}

		update_user_meta($this->ID(),'product_payouts',json_encode($payouts));
	}

	public function createLink($landingPage)
	{
		global $wpdb;

		$url = $landingPage ? get_permalink($landingPage) : home_url();

		$wpdb->insert("afm_links",
			[
				"aff_id" => $this->ID,
				"url" => trailingslashit($url)
			]);
	}

	public function deleteLink($linkId)
	{
		global $wpdb;

		$wpdb->update("afm_links",
			["is_deleted" => 1],
			["id" => $linkId]);
	}

	private static function validate($args,$checkPass)
	{
		$mandatoryFields = [
			"full_name" => "Full Name",
			"phone" => "Phone",
			"user_login" => "Affiliate Email"
		];

		if($checkPass)
			$mandatoryFields = array_merge($mandatoryFields,
				["user_pass" => "Desired Password","user_pass_2" => "Repeat Password"]);

		foreach($mandatoryFields as $field => $title)
			if(!isset($args[$field]) || strlen($args[$field]) == 0)
				throw new Exception($title." is missing");

		if($checkPass && $args["user_pass"] != $args["user_pass_2"])
			throw new Exception("Passwords Mismatch");
	}

	public static function register($args)
	{
		self::validate($args,true);

		$userId = wp_insert_user([
			"user_login" => $args["user_login"],
			"user_url" => $args["site_url"],
			"user_pass" => $args["user_pass"],
			"user_email" => $args["user_login"],
			"display_name" => $args["full_name"],
			"role" => AffiliatesManagement::AFM_ROLE_NAME
		]);

		if(is_wp_error($userId))
			throw new Exception("Could not create Affiliate for these details, it might be that your email is already a user in the system");

		update_user_meta($userId,"phone",$args["phone"]);

		if(isset($args["skype"]))
			update_user_meta($userId,"skype_user",$args["skype"]);

		$aff = AFMAffiliate::fromAffiliateId($userId);
		$aff->setDeal(AffiliatesManagement::defaultDeal());
	}

	public static function adminUpdate($args)
	{
		wp_update_user($args);

		if(isset($args["phone"]))	update_user_meta($args["ID"],"phone",$args["phone"]);
		if(isset($args["skype_user"])) update_user_meta($args["ID"],"skype_user",$args["skype_user"]);
		if(isset($args["deal"])) update_user_meta($args["ID"],"afm_deal",$args["deal"]);
		if(isset($args["expose_leads"])) update_user_meta($args["ID"],"expose_leads",$args["expose_leads"] ? "1" : "0");
	}

	public static function update($args)
	{
		$userId = get_current_user_id();

		self::validate($args,false);

		$arr = [
			"ID" => $userId,
			"user_login" => $args["user_login"],
			"user_url" => $args["site_url"],
			"display_name" => $args["full_name"],
			"user_email" => $args["user_login"],
			"phone" => $args["phone"]
		];

		if(isset($args["user_pass"]) && strlen($args["user_pass"] >0))
			$arr["user_pass"] = $args["user_pass"];

		$arr["skype_user"] = isset($args["skype"]) ? $args["skype"] : "";

		self::adminUpdate($arr);
	}

	private static function firePixelSmoove($smoove, $data, $event) {	
		$name = explode(" ",$data["name"]);
		$firstName = array_shift($name);
		$lastName = join(" ",$name);
	
		$listId = $smoove["lead_list"];
		switch($event) {
			case "customer":
				$listId = $smoove["customer_list"];
			case "lead":
			default:
				break;

		}

		if(!$listId || strlen($listId) == 0) return;

		$body = [
			"email" => $data["email"],
			"firstName" => $firstName,
			"lastName" => $lastName,
			"cellPhone" => $data["phone"],
			"lists_ToSubscribe" => [$listId]
		];
		
		$url = add_query_arg( [
			'updateIfExists' => "true",
			'restoreIfDeleted' => "true",
			'restoreIfUnsubscribed' => "true",
			'overrideNullableValue' => "true"
		], 'https://rest.smoove.io/v1/Contacts' );
		
		if(wp_get_environment_type() != "production") return;

		$request = new WP_Http();
		$result = $request->request(
			$url,
			[
				"method" => "POST",
				"httpversion" => "1.1",
				"blocking" => true,
				"headers" => [
					"Content-Type" => "application/json",
					"authorization" => "Bearer ".$smoove["api_key"]
				],
				"body" => json_encode($body)
			]
		);
		if(!isset($result["response"]["code"]) || $result["response"]["code"] != 200) {
			ValueSchool::log(json_encode($result));
		}	
	}

	public static function firePixel($affId, $affLinkId, $event, $data) {

		$aff = AFMAffiliate::fromAffiliateId($affId);
		if(!$aff) return;

		$integration = $aff->integration();

		if(isset($integration["smoove"]) &&
			isset($integration["smoove"]["enabled"]) && 
			$integration["smoove"]["enabled"] == true
			) {
			self::firePixelSmoove($integration["smoove"], $data, $event);
		}
	}

	public static function setIntegration($data)
	{
		$userId = get_current_user_id();

		update_user_meta($userId,"afm_integration",json_encode($data));
	}

	public static function setPixel($pixel)
	{
		$userId = get_current_user_id();

		update_user_meta($userId,"afm_pixel",$pixel);
	}

	public function pay($amount,$comment, $date)
	{
		$date = strtotime($date);
		if(!$date)
			$date = strtotime( 'first day of ' . date('F Y'));
		AFMAccounting::apply($this->ID(), null, $date, 0, 0, $amount, null, $comment);
	}

	public function getLeads($year, $month, $search, $page) {
		global $wpdb;
		$exposeLeads = $this->exposeLeads();

		$sql = "select u.ID, REGEXP_REPLACE(u.display_name, '\@.*','') as display_name, {EXPOSE} uml.meta_value as 'link_id',
			DATE_FORMAT(u.user_registered, %s) as user_registered, IFNULL(deposits.amount,0) as deposits
			FROM wp_users u
			INNER JOIN wp_usermeta umaf on umaf.user_id = u.ID and umaf.meta_key = 'affiliate_id' and umaf.meta_value = %d
			LEFT OUTER JOIN wp_usermeta uml on uml.user_id = u.ID and uml.meta_key = 'affiliate_link_id'
			LEFT OUTER JOIN wp_usermeta ump on ump.user_id = u.ID and ump.meta_key = 'user_phone'
			LEFT OUTER JOIN (SELECT aal.user_id, SUM(ftd_revenue+retention_revenue) as amount
											FROM afm_accounting_log aal
											WHERE aal.aff_id = %d 
											AND aal.is_deleted <> '1'
											group by aal.user_id)
											AS deposits ON deposits.user_id = u.ID
			WHERE year(user_registered) = %d
			AND month(user_registered) = %d";

		$sql = str_replace('{EXPOSE}',$exposeLeads ? "u.user_email, ump.meta_value as 'phone'," : "", $sql);

		$args = ["%d-%m-%Y", $this->ID, $this->ID, $year, $month];

		if(strlen($search)) {
			$search = '%'.$wpdb->esc_like($search).'%';
			$sql .= " AND ( user_email like %s or display_name like %s )";
			$args[] = $search;
			$args[] = $search;
		}

		$sql .= " ORDER BY user_registered ASC limit ".AFMHelper::PAGE_SIZE." offset ".(($page-1) * AFMHelper::PAGE_SIZE);

		$sql = $wpdb->prepare($sql, ...$args);

		$result = $wpdb->get_results($sql, ARRAY_A);
		return $result;
	}

	public function detachUser($userId) {
		global $wpdb;
		//select all user events:
		$sql = "SELECT DISTINCT tracked_id FROM afm_events where user_id = %d";
		$sql = $wpdb->prepare($sql,$userId);
		$result = $wpdb->get_results($sql, ARRAY_A);
		
		$trackers = ["'non-existant-tracker'"]; //so that in clause won't fail
		foreach($result as $row) {
			$tracker = trim($row["tracked_id"]);
			if(strlen($tracker) > 0) $trackers[] = "'".$tracker."'";
		}
		$trackers = implode(",",$trackers);

		//set all afm_events to this affiliate
		$sql = "UPDATE afm_events SET aff_id = NULL, link_id = NULL WHERE user_id = %d OR tracked_id in (".$trackers.")";
		$sql = $wpdb->prepare($sql, $userId);
		$wpdb->query($sql);

		//decmpensate affiliate
		update_user_meta($userId, "affiliate_id", null);
		update_user_meta($userId, "affiliate_link_id", null);

		AFMAccounting::deleteUserPayments($this->ID(), $userId);
	}

	public function attachUser($userId) {
		global $wpdb;
		//select all user events:
		$sql = "SELECT DISTINCT tracked_id FROM afm_events where user_id = %d";
		$sql = $wpdb->prepare($sql,$userId);
		$result = $wpdb->get_results($sql, ARRAY_A);
		
		$trackers = ["'non-existant-tracker'"]; //so that in clause won't fail
		foreach($result as $row) {
			$tracker = trim($row["tracked_id"]);
			if(strlen($tracker) > 0) $trackers[] = "'".$tracker."'";
		}
		$trackers = implode(",",$trackers);

		//set all afm_events to this affiliate
		$sql = "UPDATE afm_events SET aff_id = %d WHERE user_id = %d OR tracked_id in (".$trackers.")";
		$sql = $wpdb->prepare($sql, $this->ID(), $userId);
		$wpdb->query($sql);

		//compensate affiliate
		$sql = "SELECT `event`, amount, ts, product_id, source
				FROM afm_events 
				WHERE user_id = %d 
				AND event in ('deposit', 'first_deposit')
				ORDER BY ts ASC";
		$sql = $wpdb->prepare($sql, $userId);
		$result = $wpdb->get_results($sql, ARRAY_A);

		update_user_meta($userId, "affiliate_id", $this->ID());

		foreach($result as $row) {
			$this->compensate($userId, $row["amount"], $row["event"] == 'first_deposit', $row["source"], [
				"product_id" => $row["product_id"]
			]);
		}
	}

	public function compensate($userId,$amount,$isFirst,$orderId,$orderDetails)
	{
		$deal = $this->deal();
		$dealType = $deal["type"];
		$productPayout = false;
		if(isset($orderDetails["product_id"])) {
			$productPayout = $this->getProductPayout($orderDetails["product_id"],$isFirst);
			
			if($productPayout) {
				$dealType = AFM_DealType::PRODUCT_PAYOUT;
			}
		}

		$firstPaymentDate = AFMStats::firstPayment($userId);

		$cpa = null;
		$revShare = null;

		switch($dealType)
		{
			case AFM_DealType::PRODUCT_PAYOUT:
				$cpa = $productPayout["payout"];
				$cpa = apply_filters("afm_post_calculate_commission_product_payout",$cpa,$orderDetails,$this);
				break;
			case AFM_DealType::CPA:
				if($isFirst)
				{
					$cpa = $deal["CPA"];
					$cpa = apply_filters("afm_post_calculate_commission_cpa",$cpa,$orderDetails,$this);
				}
				break;
			case AFM_DealType::REVENUE_SHARE:
				//check if still paying on this user
				if($deal["REVSHARE_PERIOD"] == 0 || strtotime("+".$deal["REVSHARE_PERIOD"]." months",$firstPaymentDate) > strtotime("now") )
				{
					$revShare = $deal["REVSHARE"] / 100 * $amount;
					$revShare = apply_filters("afm_post_calculate_commission_revshare",$revShare,$orderDetails,$this);
				}
				break;
			case AFM_DealType::MIXED_CPA_AND_REVEUE_SHARE:
				if($isFirst)
				{
					$cpa = $deal["CPA"];
					$cpa = apply_filters("afm_post_calculate_commission_cpa",$cpa,$orderDetails,$this);
				}
				//check if still paying on this user
				if($deal["REVSHARE_PERIOD"] == 0 || strtotime("+".$deal["REVSHARE_PERIOD"]." months",$firstPaymentDate) > strtotime("now") )
				{
					$revShare = $deal["REVSHARE"] / 100 * $amount;
					$revShare = apply_filters("afm_post_calculate_commission_revshare",$revShare,$orderDetails,$this);
				}
				break;
		}

		AFMAccounting::apply($this->ID(), $userId, strtotime("now"),$cpa,$revShare,0,$orderId,null);
	}

	public function balance()
	{
		global $wpdb;

		$sql = "select sum(ftd_revenue + retention_revenue - paid) from afm_accounting where aff_id = ".$this->ID();

		return $wpdb->get_var($sql);
	}

	public function accounting($page,$pageSize)
	{
		return AFMAccounting::byAffiliate($this->ID(),$page,$pageSize);
	}
}