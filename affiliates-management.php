<?php
/*
 Plugin Name: Affiliates Management
 Plugin URI: http://longrunplan.com/plugins/affiliates-manager
 Description: Affiliate management plugin
 Version: 1.2.11
 Author: Nimrod Cohen
 Author URI: http://google.com?q=Nimrod+Cohen
 License: GPL2

/*
Affiliates Management is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Affiliates Management is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

Affiliates Management menu icon was downloaded from the noun project, https://thenounproject.com
The icon was designed by Gregor Cresnar (https://thenounproject.com/grega.cresnar)
Icon url: https://thenounproject.com/grega.cresnar/collection/advertising/?i=416700

*/


class AffiliatesManagement
{
	private $error = false;
	const AFM_ROLE_NAME = "AFM Affiliate";
	const AFM_KEEP_DAYS = 30;
	const AFM_DEFAULT_CURRENCY = 'USD';
	const AFM_MENU_POSITION = 74;// put it above tools
	const AFM_ICON = "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMTAwIDEyNSIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTAwIDEwMCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PHBhdGggZD0iTTI1LjcsMzUuN3YyLjRDMzIuNiwzNyw0NCwzNC4xLDUyLDI2LjVjMC4xLTEuNCwxLjMtMi41LDIuOC0yLjNjMS4zLDAuMiwyLjIsMS4zLDIuMiwyLjZ2MTQuNWMzLjIsMS4yLDUuNSw0LjMsNS41LDcuOSAgYzAsMy42LTIuMyw2LjctNS41LDcuOXYxNC42YzAsMS4zLTAuOSwyLjUtMi4yLDIuNmMtMS41LDAuMi0yLjctMC45LTIuOC0yLjNjLTgtNy41LTE5LjMtMTAuNS0yNi4yLTExLjZ2Mi40aC01LjlsMi41LDkuOSAgYzAuNCwxLjYsMS45LDIuNywzLjUsMi43YzAuNywwLDEuMywwLjUsMS40LDEuMWwwLjksMy45YzAuMiwwLjctMC4zLDEuMy0xLDEuM2gtMi44aC02LjloLTAuNmMtMC41LDAtMS0wLjQtMS4xLTAuOWwtNC40LTE4SDEwICBjLTMuMSwwLTUuNy0yLjUtNS43LTUuN3YtMi4ySDIuNmMtMSwwLTEuOS0wLjktMS45LTEuOXYtNy4xYzAtMSwwLjktMS45LDEuOS0xLjloMS43di0yLjVjMC0zLjEsMi41LTUuNyw1LjctNS43SDI1Ljd6IE05OS4zLDQ5LjIgIGMwLDQuOC0zLjksOC43LTguNyw4LjdjLTMuNywwLTYuOS0yLjQtOC4xLTUuN0g2Ny4ydi02aDE1LjNjMS4yLTMuMyw0LjQtNS43LDguMS01LjdDOTUuNCw0MC41LDk5LjMsNDQuNCw5OS4zLDQ5LjJ6IE05My4zLDQ5LjIgIGMwLTEuNS0xLjItMi43LTIuNy0yLjdTODgsNDcuNyw4OCw0OS4yYzAsMS41LDEuMiwyLjcsMi43LDIuN1M5My4zLDUwLjcsOTMuMyw0OS4yeiBNOTAuNywxOC40YzQuOCwwLDguNywzLjksOC43LDguNyAgYzAsNC44LTMuOSw4LjctOC43LDguN2MtMy43LDAtNi45LTIuNC04LjEtNS43aC0zLjFsLTEwLDEwLjJsLTQuMy00LjJsMTEuOC0xMmg1LjZDODMuOCwyMC44LDg2LjksMTguNCw5MC43LDE4LjR6IE05MC43LDI0LjQgIGMtMS41LDAtMi43LDEuMi0yLjcsMi43YzAsMS41LDEuMiwyLjcsMi43LDIuN3MyLjctMS4yLDIuNy0yLjdDOTMuMywyNS42LDkyLjEsMjQuNCw5MC43LDI0LjR6IE05OS4zLDcxLjNjMCw0LjgtMy45LDguNy04LjcsOC43ICBjLTMuNywwLTYuOS0yLjQtOC4xLTUuN2gtNS42bC0xMS44LTEybDQuMy00LjJsMTAsMTAuMmgzLjFjMS4yLTMuMyw0LjQtNS43LDguMS01LjdDOTUuNCw2Mi43LDk5LjMsNjYuNiw5OS4zLDcxLjN6IE05My4zLDcxLjMgIGMwLTEuNS0xLjItMi43LTIuNy0yLjdTODgsNjkuOSw4OCw3MS4zYzAsMS41LDEuMiwyLjcsMi43LDIuN1M5My4zLDcyLjgsOTMuMyw3MS4zeiIgZmlsbD0id2hpdGUiLz48L3N2Zz4=";
	const AFM_CURRENCIES = [
		"US Dollars" => 'ISD',
		"Israel New Shekels" => "ILS",
		"Euro" => "EUR"
	];

	public static function version() {
		$plugin_data = get_plugin_data(__FILE__);
		return $plugin_data['Version'];	
	}

	function __construct()
	{
		register_activation_hook(__FILE__, [$this, "activate"]);
		register_deactivation_hook(__FILE__, [$this, "deactivate"]);

		$this->upgradeDB();

		$location = get_option("afm-tracker-script-location", "header");
		if ($location == "header")
			add_action("wp_head", [$this, "injectTracker"]);
		else
			add_action("wp_footer", [$this, "injectTracker"]);

		add_action('admin_menu', [$this, 'addSettingsMenu']);

		add_action( 'template_redirect', [$this, 'showAffiliatePage'] );

		add_action("init", [$this, "checkAffiliateActions"]);
		add_action('init', [$this, 'addAttachmentTaxonomies']);
		add_action('admin_init', [$this, 'addBannerFarmCategory']);

		add_action('wp_ajax_afm_log', [$this, 'logEvent']);
		add_action('wp_ajax_nopriv_afm_log', [$this, 'logEvent']);

		add_action('wp_ajax_afm_get_creatives', [$this, 'getCreatives']);

		//subscribe to WPSC payment notifications
		add_action('wpsc/user_payment', [$this, "logPayments_WPSC"]);
		//subscribe to WooCommerce payment notifications
		add_action('woocommerce_payment_complete', [$this, "logPayments_WC"]);

		//susbcribe to user registrations, bind visitor to user id.
		add_action("user_register", [$this, "logRegistration"]);
		add_action("wpsc/user_authenticated", [$this, "logEmailAuthentication"]);

		//enqueue css/js to admin
		add_action('admin_enqueue_scripts', [$this, 'registerAdminAssets']);

		//get payment history ajax calls
		add_action('wp_ajax_payment_history', [$this, 'paymentHistory']);
		add_action('wp_ajax_delete_payment_history', [$this, 'delPayment']);

		//get leads table
		add_action('wp_ajax_search_leads',[$this, 'searchLeads']);

		add_action('wp_ajax_create_affiliate_link', [$this, 'createAffiliateLink']);
		add_action('wp_ajax_delete_affiliate_link', [$this, 'deleteAffiliateLink']);

		add_action('wp_ajax_delete_product_payout', [$this, 'deleteProductPayout']);
		add_action('wp_ajax_attach_user_to_affiliate', [$this, 'attachUserToAffiliate']);
		add_action('wp_ajax_rebalance_affiliate', [$this, 'rebalanceAffiliate']);
		add_action('wp_ajax_create_affiliate', [$this, 'createAffiliate']);
	}

	function createAffiliateLink() {
		$affiliate = AFMAffiliate::fromCurrentUser();
		$affiliate->createLink(isset($_POST["landing_page_id"]) ? $_POST["landing_page_id"] : false);

		echo json_encode(['error' => false]);
		die;
	}

	function createAffiliate() {
		$tempPwd = wp_generate_password();

		$vars = [
			'user_login' => $_POST["email"],
			'phone' => $_POST["phone"],
			'full_name' => $_POST["full_name"],
			'user_pass' => $tempPwd,
			'user_pass_2' => $tempPwd
		];

		AFMAffiliate::register($vars);

		echo json_encode(['error' => false, 'message' => 'Affiliate created successfully']);
		die;
	}

	function deleteAffiliateLink() {
		$affiliate = AFMAffiliate::fromCurrentUser();
		$linkId = $_POST["link_id"];
		$affiliate->deleteLink($linkId);

		echo json_encode(['error' => false]);
		die;
	}

	function searchLeads() {
		$affilate = AFMAffiliate::fromCurrentUser();
		$user = isset($_POST["user"]) ? trim($_POST["user"]) : false;

		$result = $affilate->getLeads($_REQUEST["year"], $_REQUEST["month"],$user, $_REQUEST["page"]);

		echo json_encode($result);
		die;
	}

	function deactivate()
	{
		wp_clear_scheduled_hook('afm_do_calculations');
	}

	function upgradeDB() {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		global $wpdb;

		$version = get_option('affiliates-management-version',0);

		if(version_compare('1.2.1', $version, '>')) {
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE `afm_links` (
				`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
				`aff_id` BIGINT(20) NOT NULL,
				`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`url` VARCHAR(1000) NOT NULL,
				`is_deleted` BINARY(1) NOT NULL DEFAULT 0,
				PRIMARY KEY  (`id`)
			) " . $charset_collate;

			dbDelta($sql);

			$sql = "CREATE TABLE `afm_events` (
				`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
				`link_id` BIGINT(20) NOT NULL,
				`aff_id` BIGINT(20) NOT NULL,
				`tracked_id` VARCHAR(100) NOT NULL,
				`user_id` BIGINT(20) NULL,
				`event` VARCHAR(100) NOT NULL,
				`ts` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`source` VARCHAR(100) NULL,
				`medium` VARCHAR(100) NULL,
				`campaign` VARCHAR(100) NULL,
				`content` VARCHAR(100) NULL,
				`term` VARCHAR(100) NULL,
				`amount` DECIMAL (10,2) NULL,
				`product_id` BIGINT(20) NULL,
				PRIMARY KEY  (`id`, `link_id`, `tracked_id`),
				INDEX `SECONDARY` (`user_id` ASC),
				INDEX `EVENT_BY_TS` (`ts` ASC)
			) " . $charset_collate;

			dbDelta($sql);

			$sql = "CREATE TABLE `afm_accounting` (
				`aff_id` BIGINT(20),
				`month` DATE NOT NULL,
				`ftd_revenue` DECIMAL(10,2) NOT NULL DEFAULT 0,
				`retention_revenue` DECIMAL(10,2) NOT NULL DEFAULT 0,
				`paid` DECIMAL(10,2) NOT NULL DEFAULT 0,
				PRIMARY KEY (`aff_id`,`month`)
			) " . $charset_collate;

			dbDelta($sql);

			$sql = "CREATE TABLE `afm_accounting_log` (
				`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
				`aff_id` BIGINT(20),
				`action_date` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
				`ftd_revenue` DECIMAL(10,2) NULL,
				`retention_revenue` DECIMAL(10,2) NULL,
				`paid` DECIMAL(10,2) NULL,
				`user_id` BIGINT(20) NULL,
				`order_id` BIGINT(20) NULL,
				`comment` VARCHAR(1000) NULL,
				`is_deleted` BINARY(1) NOT NULL DEFAULT 0,
				PRIMARY KEY (`id`)
			) " . $charset_collate;

			dbDelta($sql);

			$version = '1.2.1';
			update_option('affiliates-management-version',$version);
		}
		if(version_compare('1.2.2', $version, '>')) {
			$sql = "ALTER TABLE `afm_accounting_log` modify COLUMN `order_id` VARCHAR(100) NULL";
			$wpdb->query($sql);

			$sql = "update afm_accounting_log	set user_id = (select student_id from wp_payments where transaction_ref = order_id limit 1)";
			$wpdb->query($sql);

			$version = '1.2.2';
			update_option('affiliates-management-version',$version);
		}

		if(version_compare('1.2.10', $version, '>')) {
			$version = '1.2.10';
			update_option('affiliates-management-version',$version);
		}

	}

	function activate()
	{
		$affPage = self::getAffiliatesPage();

		if (!$affPage) {
			$page = array(
				'post_type' => 'page',
				'post_title' => "Affiliates",
				'post_content' => "[affiliates_management]",
				'post_status' => 'publish',
				'post_author' => 1,
				'meta_input' => [
					"affiliates_page_id" => '1'
				]
			);

			wp_insert_post($page);
		}

		if (!get_role(self::AFM_ROLE_NAME)) {
			add_role(self::AFM_ROLE_NAME, 'AFM Affiliate', array());
		}

		$this->upgradeDB();

		if (!wp_next_scheduled('afm_do_calculations')) {
			wp_schedule_event(time(), 'hourly', 'afm_do_calculations');
		}
	}

	function getCreatives()
	{
		$page = $_POST["page"];

		$creatives = AFMCreatives::all($page, AFMHelper::PAGE_SIZE);

		echo json_encode($creatives);
		die;
	}

	/*
	 * logging payments done by WPSC
	 */
	function logPayments_WC($orderId)
	{
		if(!class_exists("WC_Order")) return;

		$order = new WC_Order($orderId);

		$userId = $order->get_user_id();

		if (!$userId)
			return;

		$stats = AFMStats::byUser($userId);

		$isFirst = AFMStats::firstPayment($userId) == false;

		if (!$stats) return;

		$affiliate = AFMAffiliate::fromAffiliateId($stats["aff_id"]);

		if(!$affiliate) return;

		$amount = $order->get_total();

		$amount = apply_filters("afm_post_charged_amount", $amount, $affiliate, $order);

		AFMStats::event($stats["link_id"], $affiliate->ID(), "", $userId, $isFirst ? "first_deposit" : "deposit", "", "", "", "", "", $amount);

		//add to accounting
		$affiliate->compensate($userId, $amount, $isFirst, $orderId, $order);
	}

	static function logPayment($userId, $amount, $orderId, $details)
	{
		$stats = AFMStats::byUser($userId);

		$affiliate = null;

		if ($stats)	$affiliate = AFMAffiliate::fromAffiliateId($stats["aff_id"]);

		$isFirst = AFMStats::firstPayment($userId) == false;

		if(!$affiliate) { 
			//this transaction does not belong to an affiliate, 
			//we just log it so we could later attribute it to an affiliate if need be, and move on
			AFMStats::event(0, 0, "", $userId, $isFirst ? "first_deposit" : "deposit", "", "", "", "", "", $amount, $details["product_id"]);
			return;
		}

		$amount = apply_filters("afm_post_charged_amount", $amount, $affiliate, $orderId);

		AFMStats::event($stats["link_id"], $affiliate->ID(), "", $userId, $isFirst ? "first_deposit" : "deposit", $orderId, "", "", "", "", $amount, $details["product_id"]);

		//add to accounting
		$affiliate->compensate($userId, $amount, $isFirst, $orderId, $details);
	}

	static function logRefund($userId,$orderId,$affiliateId){
		//find payment record	
		$accountingLog = AFMAccounting::find($affiliateId, $userId, $orderId);

		if(!$accountingLog) return;

		$amount = -1 * ($accountingLog["ftd_revenue"] + $accountingLog["retention_revenue"] - $accountingLog["paid"]);

		AFMStats::event(0, $affiliateId, "", $userId, "refund", $orderId, "", "", "", "", $amount, 0);

		AFMAccounting::deletePayment($affiliateId, $accountingLog["id"]);

	}

	/*
	 * logging payments done by WPSC
	 */
	function logPayments_WPSC($args)
	{

		$stats = AFMStats::byUser($args["user_id"]);

		if (!$stats) return;

		$affiliate = AFMAffiliate::fromAffiliateId($stats["aff_id"]);

		if(!$affiliate) return;

		$amount = apply_filters("afm_post_charged_amount", $args["amount"], $affiliate, $args);

		AFMStats::event($stats["link_id"], $affiliate->ID(), "", $args["user_id"], $args["is_first"] ? "first_deposit" : "deposit", "", "", "", "", "", $amount);

		//add to accounting
		$affiliate->compensate($args["user_id"], $amount, $args["is_first"], $args["charge_id"], $args);
	}

	function logEmailAuthentication($args)
	{
		$stats = AFMStats::byUser($args["user_id"]);
		if ($stats)
			AFMStats::event($stats["link_id"], $stats["aff_id"], "", $args["user_id"], "authenticate");
	}

	function logRegistration($userId)
	{
		if (!isset($_COOKIE["afm_usrid"]))
			return;

		$trackedId = $_COOKIE["afm_usrid"];

		$linkId = "";
		$affId = "";

		$aff = AFMStats::whoLocks($userId);

		if ($aff) {
			$affId = $aff["aff_id"];
			$linkId = $aff["link_id"];
		} else if (!isset($_COOKIE["afm_link_id"]) || !isset($_COOKIE["afm_aff_id"])) {
			//try finding the user by his tracking code
			$event = AFMStats::byTracker($trackedId);

			$affId = $event["aff_id"];
			$linkId = $event["link_id"];
		} else {
			$linkId = $_COOKIE["afm_link_id"];
			$affId = $_COOKIE["afm_aff_id"];
		}

		AFMStats::event(
			$linkId,
			$affId,
			$trackedId,
			$userId,
			"register",
			isset($_COOKIE["afm_source"]) ? $_COOKIE["afm_source"] : "",
			isset($_COOKIE["afm_medium"]) ? $_COOKIE["afm_medium"] : "",
			isset($_COOKIE["afm_campaign"]) ? $_COOKIE["afm_campaign"] : "",
			isset($_COOKIE["afm_content"]) ? $_COOKIE["afm_content"] : "",
			isset($_COOKIE["afm_term"]) ? $_COOKIE["afm_term"] : "",
			0);
	}

	function logEvent()
	{
		$data = $_REQUEST["data"];
		$data = json_decode(stripslashes($data),true);
		$utm = $data["utm"];

		AFMStats::event(
			$data["link_id"],
			$data["aff_id"],
			$data["tracker_id"],
			get_current_user_id(),
			$data["event"],
			$utm["source"],
			$utm["medium"],
			$utm["campaign"],
			$utm["term"],
			$utm["content"],
			isset($data["amount"]) ? $data["amount"] : 0);

		die;
	}

	static function getAffiliatesPage()
	{
		$posts = get_posts([
			'post_type' => 'page',
			'meta_key' => 'affiliates_page_id',
			'meta_value' => '1'
		]);

		if (count($posts) > 0)
			return $posts[0];
		return null;
	}

	static function defaultDeal()
	{
		return get_option("afm-default-deal",
			[
				"type" => AFM_DealType::CPA,
				"CPA" => 30
			]);
	}

	function addAFMAssets()
	{
		if (get_the_ID() != self::getAffiliatesPage()->ID)
			return;

		wp_register_style("afm-affiliate-css", plugin_dir_url(__FILE__) . "screens" . DIRECTORY_SEPARATOR . "afm.css");
		wp_enqueue_style("afm-affiliate-css");

		wp_register_script("afm-utils-js", plugin_dir_url(__FILE__) . "utils" . DIRECTORY_SEPARATOR . "jsutils.js", []);
		wp_enqueue_script("afm-utils-js");

		wp_register_script("remodaler-js", plugin_dir_url(__FILE__) . "utils" . DIRECTORY_SEPARATOR . "remodaler.js", ["afm-utils-js"]);
		wp_enqueue_script("remodaler-js");
		wp_register_style("remodaler-css", plugin_dir_url(__FILE__) . "utils" . DIRECTORY_SEPARATOR . "remodaler.css");
		wp_enqueue_style("remodaler-css");

		wp_register_script("afm-affiliate-js", plugin_dir_url(__FILE__) . "screens" . DIRECTORY_SEPARATOR . "afm.js", ["afm-utils-js", "remodaler-js"]);
		wp_enqueue_script("afm-affiliate-js");

		wp_register_script("monthpicker-js", plugin_dir_url(__FILE__) . "utils" . DIRECTORY_SEPARATOR . "monthpicker.js", ["afm-utils-js"]);
		wp_enqueue_script("monthpicker-js");
		wp_register_style("monthpicker-css", plugin_dir_url(__FILE__) . "utils" . DIRECTORY_SEPARATOR . "monthpicker.css");
		wp_enqueue_style("monthpicker-css");

		wp_register_script("infinityscroll-js", plugin_dir_url(__FILE__) . "utils" . DIRECTORY_SEPARATOR . "infinityscroll.js", ["afm-utils-js"]);
		wp_enqueue_script("infinityscroll-js");

		wp_register_script("notifications-js", plugin_dir_url(__FILE__) . "utils" . DIRECTORY_SEPARATOR . "notifications.js", ["afm-utils-js"]);
		wp_enqueue_script("notifications-js");
		wp_register_style("notifications-css", plugin_dir_url(__FILE__) . "utils" . DIRECTORY_SEPARATOR . "notifications.css");
		wp_enqueue_style("notifications-css");
		

		$landingPages = get_option("afm_landingpages",[]);

		$lps = [];
		foreach($landingPages as $page)
		{
			$lps[] = [
				"title" => get_the_title($page),
				"value" => $page
			];

		}

		$exposeLeads = false;
		if(is_user_logged_in()) {
			$aff = AFMAffiliate::fromCurrentUser();
			$exposeLeads = $aff ? $aff->exposeLeads() : false;
		}

		wp_localize_script('afm-affiliate-js',
			'afm_info', [
				'ajax_url' => admin_url('admin-ajax.php'),
				'paging_size' => AFMHelper::PAGE_SIZE,
				'landing_pages' => $lps,
				'logged_in' => is_user_logged_in(),
				'expose_leads' => $exposeLeads ? "1" : "0"
			]);

	}

	static function getAffiliatesPageUrl()
	{
		$page = AffiliatesManagement::getAffiliatesPage();

		return get_page_link($page);
	}

	/*
	 * Injecting the tracking code to header.
	 */
	function injectTracker()
	{
		wp_register_script("afm-tracker", plugin_dir_url(__FILE__) . "afm_tracker.js", []);
		wp_enqueue_script("afm-tracker");

		//if this is an affiliate related visit, we'll load the pixel.
		$pixel = "";
		if(isset($_COOKIE["afm_aff_id"]) && $_COOKIE["afm_aff_id"] != "null")
		{
			$aff = new WP_User($_COOKIE["afm_aff_id"]);
			if($aff != null && $aff != false && !is_wp_error($aff))
			{
				$aff = AFMAffiliate::fromWPUser($aff);
				if($aff) {
					$pixel = $aff->pixel();
				}
			}
		}

		wp_localize_script('afm-tracker', 'afm_server_info', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'keep_days' => get_option("afm-keep-days", AffiliatesManagement::AFM_KEEP_DAYS),
			'aff_pixel' => $pixel
		]);
	}

	/*
	 * Add settings menu
	 */
	function addSettingsMenu()
	{
		add_menu_page("Affiliates Management", "Affiliates", "manage_options", "affiliates-management", [$this, "showMenu"], AffiliatesManagement::AFM_ICON, AffiliatesManagement::AFM_MENU_POSITION);
	}

	public function registerAdminAssets($hook)
	{
		if (!strstr($hook, "affiliates-management"))
			return;

		wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

		wp_register_script("afm-utils-js", plugin_dir_url(__FILE__) . "utils" . DIRECTORY_SEPARATOR . "jsutils.js", []);
		wp_enqueue_script("afm-utils-js");
		wp_register_style('afm-admin-css', plugin_dir_url(__FILE__) . 'admin/afm-admin.css');
		wp_enqueue_style('afm-admin-css');

		wp_register_script("remodaler-js", plugin_dir_url(__FILE__) . "utils" . DIRECTORY_SEPARATOR . "remodaler.js", ["afm-utils-js"]);
		wp_enqueue_script("remodaler-js");
		wp_register_style("remodaler-css", plugin_dir_url(__FILE__) . "utils" . DIRECTORY_SEPARATOR . "remodaler.css");
		wp_enqueue_style("remodaler-css");

		wp_register_script("notifications-js", plugin_dir_url(__FILE__) . "utils" . DIRECTORY_SEPARATOR . "notifications.js", ["afm-utils-js"]);
		wp_enqueue_script("notifications-js");
		wp_register_style("notifications-css", plugin_dir_url(__FILE__) . "utils" . DIRECTORY_SEPARATOR . "notifications.css");
		wp_enqueue_style("notifications-css");

		wp_register_script('afm-admin-js', plugin_dir_url(__FILE__) . 'admin/afm-admin.js', ['afm-utils-js', 'remodaler-js', 'notifications-js']);
		wp_enqueue_script('afm-admin-js');

		wp_localize_script('afm-admin-js', 'afm_admin',
			['MIXED_CPA_REVSHARE' => AFM_DealType::MIXED_CPA_AND_REVEUE_SHARE,
				'ajax_url' => admin_url('admin-ajax.php')]);
	}

	function showMenu()
	{
		$showUpdated = false;

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			switch ($_POST["action"]) {
				case "save_settings":
					$showUpdated = true;
					$updateResult = $this->saveSettings();
					break;
				case "save_affilate_settings":
					$showUpdated = true;
					$updateResult = $this->saveAffiliateSettings();
					break;
					case "save_affilate_deal":
						$showUpdated = true;
						$updateResult = $this->saveAffiliateDeal();
						break;
					case "save_landingpages":
					$showUpdated = true;
					$updateResult = $this->saveLandingPages();
					break;
				case "pay_affilate":
					$showUpdated = true;
					$aff = AFMAffiliate::fromAffiliateId($_POST["affiliate_id"]);
					$date = $_POST["payment_date"];
					$aff->pay($_POST["amount"], $_POST["comment"], $date);
					$updateResult = true;
					break;
				case "add_product_payout":
					$aff = AFMAffiliate::fromAffiliateId($_POST["affiliate_id"]);
					$aff->updateProductPayout($_POST["product_id"],isset($_POST["is_first"]) && $_POST["is_first"] == "on",$_POST["payout"]);
					break;
				default:
					break;
			}
		}

		$page = isset($_GET["subpage"]) ? $_GET["subpage"] : "settings";

		require_once "admin" . DIRECTORY_SEPARATOR . $page . ".php";
	}

	function deleteProductPayout() {
		$aff = $_POST["affiliate_id"];
		$aff = AFMAffiliate::fromAffiliateId($aff);
		$aff->deleteProductPayout($_POST["product_id"], isset($_POST["is_first"]) && $_POST["is_first"] == "1");
	}

	function  rebalanceAffiliate() {
		if(!current_user_can('administrator')) {
			$response = ["error" => true, "message" => "Unauthorized request"];
			echo json_encode($response);
			die;
		}

		$affId = $_POST["affiliate_id"];

		$aff = AFMAffiliate::fromAffiliateId($affId);

		AFMAccounting::recalculateAccounting($affId);

		$balance = AFMHelper::formatMoney($aff->balance());

		$response = ["error" => false, "message" => "Affiliate rebalanced successfully", "balance" => $balance];
		echo json_encode($response);
		die;
	}

	function attachUserToAffiliate() {
		if(!current_user_can('administrator')) {
			$response = ["error" => true, "message" => "Unauthorized request"];
			echo json_encode($response);
			die;
		}

		$affId = $_POST["affiliate_id"];
		$userId = $_POST["user_id"];
		$action = $_POST["attach"];

		$aff = AFMAffiliate::fromAffiliateId($affId);
		if($action == 'attach')
			$aff->attachUser($userId);
		else 
			$aff->detachUser($userId);

		$balance = AFMHelper::formatMoney($aff->balance());
		$response = ["error" => false, "message" => "User ".$action."ed successfully", "balance" => $balance ];
		echo json_encode($response);
		die;

	}


	function saveLandingPages()
	{
		$onPages = isset($_POST["on_pages"]) ? $_POST["on_pages"] : [];

		update_option("afm_landingpages",$onPages);

		return true;
	}

	function saveAffiliateSettings()
	{
		if (!wp_verify_nonce($_POST["wp_nonce"], "save_affiliate_details"))
			return false;

		$data = [
			"ID" => $_POST["affiliate_id"],
			"user_email" => $_POST["email"],
			"display_name" => $_POST["fullname"],
			"user_url" => $_POST["url"],
			"skype_user" => $_POST["skype"],
			"phone" => $_POST["aff_phone"],
		];

		if (isset($_POST["password"]))
			$data["user_pass"] = $_POST["password"];

		AFMAffiliate::adminUpdate($data);

		return true;
	}

	function saveAffiliateDeal()
	{
		if (!wp_verify_nonce($_POST["wp_nonce"], "save_affiliate_deal"))
			return false;

		$data = [
			"ID" => $_POST["affiliate_id"],
			"deal" => [
				"type" => $_POST["deal_type"],
				"CPA" => isset($_POST["cpa"]) ? $_POST["cpa"] : "",
				"REVSHARE" => isset($_POST["revshare"]) ? $_POST["revshare"] : "",
				"REVSHARE_PERIOD" => isset($_POST["revshare_period"]) ? $_POST["revshare_period"] : "",
			],
			"expose_leads" => isset($_POST["expose_leads"]) && $_POST["expose_leads"] == "on"
		];

		AFMAffiliate::adminUpdate($data);

		return true;
	}

	public static function lockingEvent()
	{
		return get_option("afm-locking-event", "authenticate");
	}

	function saveSettings()
	{
		update_option("afm-tracker-script-location", $_POST["script_location"], true);
		update_option("afm-locking-event", $_POST["locking_event"], true);
		update_option("afm-default-deal", [
			"type" => $_POST["deal_type"],
			"CPA" => isset($_POST["cpa"]) ? $_POST["cpa"] : "",
			"REVSHARE" => isset($_POST["revshare"]) ? $_POST["revshare"] : "",
			"REVSHARE_PERIOD" => isset($_POST["revshare_period"]) ? $_POST["revshare_period"] : ""
		], true);
		update_option("afm-keep-days", $_POST["keep_days"], true);
		update_option("afm-currency", $_POST["currency"], true);
		return true;
	}

	function showAffiliatePage() {
		$id = get_queried_object_id();
		global $wp;
		$loginPage = get_permalink($id);

		if(get_post_meta($id, 'affiliates_page_id',true) != '1') return;
		
		$page = isset($_GET["pg"]) ? $_GET["pg"] : (is_user_logged_in() ? "home" : "login");

		wp_styles();
		$this->addAFMAssets();

		if($page === 'logout') {
			wp_destroy_current_session();
			wp_clear_auth_cookie();
			wp_set_current_user( 0 );
			wp_redirect($loginPage);
			die;
		}

		include("screens".DIRECTORY_SEPARATOR."header.php");

		switch ($page) {
			case "join_us":
				include("screens" . DIRECTORY_SEPARATOR . "join_us.php");
				break;
			case "lost_pass":
				include("screens" . DIRECTORY_SEPARATOR . "lost_pass.php");
				break;
			default:
				$userId = get_current_user_id();

				if ($userId == 0) {
					include("screens" . DIRECTORY_SEPARATOR . "login.php");
					break;
				}

				$data = get_userdata($userId);

				if (in_array(self::AFM_ROLE_NAME, $data->roles)) {
					$page = "home";
					include("screens" . DIRECTORY_SEPARATOR . "home.php");
					break;
				}

				include("screens" . DIRECTORY_SEPARATOR . "login.php");
				break;
		}

		include("screens".DIRECTORY_SEPARATOR."footer.php");
		die;
	}

	function addAttachmentTaxonomies()
	{
		if (!is_object_in_taxonomy('attachment', 'category')) {
			register_taxonomy_for_object_type('category', 'attachment');
		}
		if (!is_object_in_taxonomy('attachment', 'post_tag')) {
			register_taxonomy_for_object_type('post_tag', 'attachment');
		}
	}

	function addBannerFarmCategory()
	{
		if(!term_exists('banner-farm','category',null))
			wp_insert_category([
				'cat_name'=>'Banner Farm',
				'category-slug' => 'banner-farm'
			]);
	}

	function checkAffiliateActions()
	{
		if($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["affiliate_action"]))
			return;

		$action = $_POST["affiliate_action"];

		try
		{
			switch($action)
			{
				case "do_login":
					$this->doAffiliateLogin($_POST);
					break;
				case "do_register":
					AFMAffiliate::register($_POST);
					wp_redirect(AffiliatesManagement::getAffiliatesPageUrl());
					break;
				case "do_save_details":
					AFMAffiliate::update($_POST);
					break;
				case "do_save_pixel":
					AFMAffiliate::setPixel(isset($_POST["pixel"]) ? trim($_POST["pixel"]) : "");
					$aff = AFMAffiliate::fromCurrentUser();
					$integration = $aff->integration();
					$smoove = ["enabled" => false];
					if(isset($_POST["chk_smoove"]) && $_POST["chk_smoove"] == "on") {
						$smoove = array_merge($_POST["smoove"],["enabled"=>true]);
					}
					$integration["smoove"] = $smoove;
					AFMAffiliate::setIntegration($integration);
					break;
			}
		}
		catch(Exception $ex)
		{
			$this->error = $ex->getMessage();
		}

	}

	function doAffiliateLogin($args)
	{
		if(!isset($args["log"]) || strlen($args["log"]) == 0 || !isset($args["pwd"]) || strlen($args["pwd"]) == 0)
			throw new Exception("Invalid email or password");

		$user = get_user_by("email",$args["log"]);

		if(!$user || is_wp_error($user))
			throw new Exception("Invalid email or password");

		if(!in_array(AffiliatesManagement::AFM_ROLE_NAME,$user->roles))
			throw new Exception("Invalid email or password");

		$user = wp_signon();

		if(is_wp_error($user))
			throw new Exception("Invalid email or password");

		$link = AffiliatesManagement::getAffiliatesPageUrl();

		wp_redirect($link);
	}

	private function getPaymentHistoryLines($affId, $month, $exposeLeads) {

		$result = AFMAccounting::paymentLog($affId,$month, $exposeLeads);

		foreach($result as &$row)
		{
			$row["payout"] = AFMHelper::formatMoney($row["ftd_revenue"] + $row["retention_revenue"]);
			$row["paid"] = AFMHelper::formatMoney($row["paid"]);
		}

		$result = ["rows" => $result];

		$aff = AFMAffiliate::fromAffiliateId($affId);
		$result["balance"] = AFMHelper::formatMoney($aff->balance());

		return $result;
	}

	function paymentHistory()
	{
		$user = wp_get_current_user();
		if(!$user) throw new Exception(("Not allowed"));

		$affId = $user->ID;
		$month = $_POST["month"];
		$exposeLeads = false;

		if(in_array('administrator', $user->roles)) {
			$affId = $_POST["aff_id"];
			$exposeLeads = true;
		} else {
			$aff = AFMAffiliate::fromCurrentUser();
			if(!$aff) throw new Exception("Not allowed");
			$exposeLeads = $aff->exposeLeads();
		}

		echo json_encode($this->getPaymentHistoryLines($affId, $month, $exposeLeads));
		die;
	}

	function delPayment()
	{
		$user = wp_get_current_user();
		if(!$user) throw new Exception(("Not allowed"));

		$affId = $_POST["aff_id"];
		$month = $_POST["month"];
		$paymentId = $_POST["payment_id"];
		$exposeLeads = false;

		if(in_array('administrator', $user->roles)) {
			$affId = $_POST["aff_id"];
			$exposeLeads = true;
		} else {
			$aff = AFMAffiliate::fromCurrentUser();
			if(!$aff) throw new Exception("Not allowed");
			$exposeLeads = $aff->exposeLeads();
		}

		AFMAccounting::deletePayment($affId,$paymentId);

		echo json_encode($this->getPaymentHistoryLines($affId, $month, $exposeLeads));
		die;
	}


}

include_once "class".DIRECTORY_SEPARATOR."affiliate.php";
include_once "class".DIRECTORY_SEPARATOR."creatives.php";
include_once "class".DIRECTORY_SEPARATOR."stats.php";
include_once "class".DIRECTORY_SEPARATOR."helper.php";
include_once "class".DIRECTORY_SEPARATOR."accounting.php";

$affmgr = new AffiliatesManagement();

