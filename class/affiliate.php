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
	public function skype(){ return get_user_meta($this->ID,"skype_user",true); }
	public function phone(){ return get_user_meta($this->ID,"phone",true); }
	public function fullname(){ return $this->user->display_name; }
	public function email(){ return $this->user->user_email; }
	public function website(){ return $this->user->user_url; }
	public function deal() { return get_user_meta($this->ID,"afm_deal",true); }

	private function __construct()
	{}

	public static function fromAffiliateId($id)
	{
		$aff = new AFMAffiliate();
		$aff->ID = $id;

		$aff->user = get_user_by("ID",$id);

		if(!in_array(AffiliatesManagement::AFM_ROLE_NAME,$aff->user->roles))
			throw new Exception("User ".$id." is not an affiliate");

		return $aff;
	}

	public static function fromWPUser($user)
	{
		if(!in_array(AffiliatesManagement::AFM_ROLE_NAME,$user->roles))
			throw new Exception("User ".$user->ID." is not an affiliate");

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

	public function createLink()
	{
		global $wpdb;

		$wpdb->insert("afm_links",
			[
				"aff_id" => $this->ID,
				"url" => trailingslashit(home_url())
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
		$mandatoryFields = ["full_name" => "Full Name",
			"phone" => "Phone",
			"site_url" => "Website Url",
			"user_login" => "Affiliate Email"];

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

		update_user_meta($args["ID"],"phone",$args["phone"]);
		update_user_meta($args["ID"],"skype_user",$args["skype_user"]);

		if(isset($args["deal"]))
			update_user_meta($args["ID"],"afm_deal",$args["deal"]);
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

	public function pay($amount,$comment)
	{
		$thisMonth = strtotime( 'first day of ' . date( 'F Y'));
		AFMAccounting::apply($this->ID(),$thisMonth,0,0,$amount,$comment);
	}

	public function compensate($userId,$amount,$isFirst,$orderDetails)
	{
		$deal = $this->deal();

		$firstPaymentDate = AFMStats::firstPayment($userId);

		$thisMonth = strtotime( 'first day of ' . date( 'F Y'));

		$cpa = null;
		$revShare = null;

		switch($deal["type"])
		{
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

		AFMAccounting::apply($this->ID(),$thisMonth,$cpa,$revShare);
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