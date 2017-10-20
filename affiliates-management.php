<?php
/*
 Plugin Name: Affiliates Management
 Plugin URI: http://longrunplan.com/plugins/affiliates-manager
 Description: Affiliate management plugin
 Version: 1.0
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
	const AFM_MENU_POSITION = 74;// put it above tools
	const AFM_ICON = "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMTAwIDEyNSIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTAwIDEwMCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PHBhdGggZD0iTTI1LjcsMzUuN3YyLjRDMzIuNiwzNyw0NCwzNC4xLDUyLDI2LjVjMC4xLTEuNCwxLjMtMi41LDIuOC0yLjNjMS4zLDAuMiwyLjIsMS4zLDIuMiwyLjZ2MTQuNWMzLjIsMS4yLDUuNSw0LjMsNS41LDcuOSAgYzAsMy42LTIuMyw2LjctNS41LDcuOXYxNC42YzAsMS4zLTAuOSwyLjUtMi4yLDIuNmMtMS41LDAuMi0yLjctMC45LTIuOC0yLjNjLTgtNy41LTE5LjMtMTAuNS0yNi4yLTExLjZ2Mi40aC01LjlsMi41LDkuOSAgYzAuNCwxLjYsMS45LDIuNywzLjUsMi43YzAuNywwLDEuMywwLjUsMS40LDEuMWwwLjksMy45YzAuMiwwLjctMC4zLDEuMy0xLDEuM2gtMi44aC02LjloLTAuNmMtMC41LDAtMS0wLjQtMS4xLTAuOWwtNC40LTE4SDEwICBjLTMuMSwwLTUuNy0yLjUtNS43LTUuN3YtMi4ySDIuNmMtMSwwLTEuOS0wLjktMS45LTEuOXYtNy4xYzAtMSwwLjktMS45LDEuOS0xLjloMS43di0yLjVjMC0zLjEsMi41LTUuNyw1LjctNS43SDI1Ljd6IE05OS4zLDQ5LjIgIGMwLDQuOC0zLjksOC43LTguNyw4LjdjLTMuNywwLTYuOS0yLjQtOC4xLTUuN0g2Ny4ydi02aDE1LjNjMS4yLTMuMyw0LjQtNS43LDguMS01LjdDOTUuNCw0MC41LDk5LjMsNDQuNCw5OS4zLDQ5LjJ6IE05My4zLDQ5LjIgIGMwLTEuNS0xLjItMi43LTIuNy0yLjdTODgsNDcuNyw4OCw0OS4yYzAsMS41LDEuMiwyLjcsMi43LDIuN1M5My4zLDUwLjcsOTMuMyw0OS4yeiBNOTAuNywxOC40YzQuOCwwLDguNywzLjksOC43LDguNyAgYzAsNC44LTMuOSw4LjctOC43LDguN2MtMy43LDAtNi45LTIuNC04LjEtNS43aC0zLjFsLTEwLDEwLjJsLTQuMy00LjJsMTEuOC0xMmg1LjZDODMuOCwyMC44LDg2LjksMTguNCw5MC43LDE4LjR6IE05MC43LDI0LjQgIGMtMS41LDAtMi43LDEuMi0yLjcsMi43YzAsMS41LDEuMiwyLjcsMi43LDIuN3MyLjctMS4yLDIuNy0yLjdDOTMuMywyNS42LDkyLjEsMjQuNCw5MC43LDI0LjR6IE05OS4zLDcxLjNjMCw0LjgtMy45LDguNy04LjcsOC43ICBjLTMuNywwLTYuOS0yLjQtOC4xLTUuN2gtNS42bC0xMS44LTEybDQuMy00LjJsMTAsMTAuMmgzLjFjMS4yLTMuMyw0LjQtNS43LDguMS01LjdDOTUuNCw2Mi43LDk5LjMsNjYuNiw5OS4zLDcxLjN6IE05My4zLDcxLjMgIGMwLTEuNS0xLjItMi43LTIuNy0yLjdTODgsNjkuOSw4OCw3MS4zYzAsMS41LDEuMiwyLjcsMi43LDIuN1M5My4zLDcyLjgsOTMuMyw3MS4zeiIgZmlsbD0id2hpdGUiLz48L3N2Zz4=";

	function __construct()
	{
		register_activation_hook( __FILE__, [$this,"activate"] );
		register_deactivation_hook( __FILE__, [$this,"deactivate"] );

		$location = get_option("afm-tracker-script-location","header");
		if($location == "header")
			add_action("wp_head",[$this,"injectTracker"]);
		else
			add_action("wp_footer",[$this,"injectTracker"]);

		add_action( 'admin_menu', [$this,'addSettingsMenu']);

		//add affiliate scripts/css
		add_action( 'wp_enqueue_scripts', [$this, 'addAFMAssets' ]);

		add_shortcode("affiliates_management",[$this,'showAffiliatesApp']);

		add_action("init",[$this,"checkAffiliateActions"]);
		add_action( 'init', [$this,'addAttachmentTaxonomies'] );

		add_action( 'wp_ajax_afm_log', [$this, 'logEvent' ]);
		add_action( 'wp_ajax_nopriv_afm_log', [$this, 'logEvent' ]);

		add_action( 'wp_ajax_afm_get_creatives', [$this, 'getCreatives' ]);

		//subscribe to WPSC payment notifications
		add_action('wpsc/user_payment',[$this,"logPayments_WPSC"]);
		//subscribe to WooCommerce payment notifications
		add_action('woocommerce_payment_complete',[$this,"logPayments_WC"]);

		//susbcribe to user registrations, bind visitor to user id.
		add_action("user_register",[$this,"logRegistration"]);
		add_action("wpsc/user_authenticated",[$this,"logEmailAuthentication"]);

		//enqueue css/js to admin
		add_action('admin_enqueue_scripts',[$this,'registerAdminAssets']);

		//run financial calculations
		add_action('afm_do_calculations', [$this,'doCalculations']);
	}

	function doCalculations()
	{
	}

	function deactivate()
	{
		wp_clear_scheduled_hook('afm_do_calculations' );
	}

	function activate()
	{
		$affPage = self::getAffiliatesPage();

		if(count($affPage) == 0)
		{
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

		if(!get_role(self::AFM_ROLE_NAME))
		{
			add_role(self::AFM_ROLE_NAME, 'AFM Affiliate',array());
		}

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE afm_links (
			id BIGINT(20) NOT NULL AUTO_INCREMENT,
			aff_id BIGINT(20) NOT NULL,
			created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			url VARCHAR(1000) NOT NULL,
			is_deleted BINARY(1) NOT NULL DEFAULT 0,
			PRIMARY KEY  (id)
		) ".$charset_collate;

		dbDelta( $sql );

		$sql = "CREATE TABLE afm_events (
			id BIGINT(20) NOT NULL AUTO_INCREMENT,
			link_id BIGINT(20) NOT NULL,
			aff_id BIGINT(20) NOT NULL,
			tracked_id VARCHAR(100) NOT NULL,
			user_id BIGINT(20) NULL,
			event VARCHAR(100) NOT NULL,
			ts TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			source VARCHAR(100) NULL,
			medium VARCHAR(100) NULL,
			campaign VARCHAR(100) NULL,
			content VARCHAR(100) NULL,
			term VARCHAR(100) NULL,
			amount DECIMAL (10,2) NULL,
			PRIMARY KEY  (id, link_id, tracked_id),
			INDEX `SECONDARY` (`user_id` ASC),
			INDEX `EVENT_BY_TS` (`ts` ASC)
		) ". $charset_collate;

		dbDelta($sql);

		$sql = "CREATE TABLE afm_accounting (
			aff_id BIGINT(20),
			month Date NOT NULL,
			ftd_revenue Decimal(10,2) NOT NULL DEFAULT '0',
			retention_revenue Decimal(10,2) NOT NULL DEFAULT '0',
			paid Decimal(10,2) NOT NULL DEFAULT '0',
			comment VarChar(1000) NOT NULL,
			PRIMARY KEY(aff_id,month)
			) ".$charset_collate;

		dbDelta( $sql );

		if (! wp_next_scheduled ( 'afm_do_calculations' )) {
			wp_schedule_event( time(), 'hourly', 'afm_do_calculations' );
		}
	}

	function getCreatives()
	{
		check_ajax_referer( 'afm-nonce', 'security' );

		$page = $_POST["page"];

		$creatives = AFMCreatives::all($page,AFMCreatives::PAGE_SIZE);

		echo json_encode($creatives);
		die;
	}

	/*
	 * logging payments done by WPSC
	 */
	function logPayments_WC($orderId)
	{
		$order = new WC_Order( $orderId );

		$userId = $order->get_user_id();

		if(!$userId)
			return;

		$stats = AFMStats::byUser($userId);

		$isFirst = AFMStats::firstPayment($userId) == false;

		if(!$stats)
			return;

		$affiliate = AFMAffiliate::fromAffiliateId($stats["aff_id"]);

		$amount = $order->get_total();

		$amount = apply_filters("afm_post_charged_amount",$amount,$affiliate,$order);

		AFMStats::event($stats["link_id"],$affiliate->ID(),"",$userId,$isFirst ? "first_deposit" : "deposit","","","","","",$amount);

		//add to accounting
		$affiliate->compensate($userId, $amount,$isFirst,$order);
	}

	/*
	 * logging payments done by WPSC
	 */
	function logPayments_WPSC($args)
	{

		$stats = AFMStats::byUser($args["user_id"]);

		if($stats)
		{
			$affiliate = AFMAffiliate::fromAffiliateId($stats["aff_id"]);

			$amount = apply_filters("afm_post_charged_amount",$args["amount"],$affiliate,$args);

			AFMStats::event($stats["link_id"],$affiliate->ID(),"",$args["user_id"],$args["is_first"] ? "first_deposit" : "deposit","","","","","",$amount);

			//add to accounting
			$affiliate->compensate($args["user_id"], $amount,$args["is_first"],$args);
		}
	}

	function logEmailAuthentication($user)
	{
		$stats = AFMStats::byUser($user->ID);
		if($stats)
			AFMStats::event($stats["link_id"],$stats["aff_id"],"",$user->ID,"authenticate");
	}

	function logRegistration($userId)
	{
		if(!isset($_COOKIE["afm_usrid"]))
			return;

		$trackedId = $_COOKIE["afm_usrid"];

		$linkId = "";
		$affId = "";

		$aff = AFMStats::whoLocks($userId);

		if($aff)
		{
			$affId = $aff["aff_id"];
			$linkId = $aff["link_id"];
		}
		else if(!isset($_COOKIE["afm_link_id"]) || !isset($_COOKIE["afm_aff_id"]))
		{
			//try finding the user by his tracking code
			$event = AFMStats::byTracker($trackedId);

			$affId = $event["aff_id"];
			$linkId = $event["link_id"];
		}
		else
		{
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

	public static function moneyFormat($number)
	{
		setlocale(LC_MONETARY, 'en_US');
		return money_format('%.2n', $number);
	}
	function logEvent()
	{
		check_ajax_referer( 'afm-nonce', 'security' );

		$event = $_POST["event"];
		$utm = $_POST["utm"];

		AFMStats::event(
			$_POST["link_id"],
			$_POST["aff_id"],
			$_POST["tracker_id"],
			get_current_user_id(),
			$event,
			$utm["source"],
			$utm["medium"],
			$utm["campaign"],
			$utm["term"],
			$utm["content"],
			isset($_POST["amount"])? $_POST["amount"]: 0);

		echo json_encode(["success"=>true]);
		die;
	}

	static function getAffiliatesPage()
	{
		$posts = get_posts([
			'post_type' => 'page',
			'meta_key' => 'affiliates_page_id',
			'meta_value' => '1'
		]);

		if(count($posts) > 0)
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
		if(get_the_ID() != self::getAffiliatesPage()->ID)
			return;

		wp_register_style("afm-affiliate-css",plugin_dir_url(__FILE__)."screens".DIRECTORY_SEPARATOR."afm.css");
		wp_enqueue_style("afm-affiliate-css");

		wp_register_script("afm-affiliate-js",plugin_dir_url(__FILE__)."screens".DIRECTORY_SEPARATOR."afm.js",["jquery"]);
		wp_enqueue_script("afm-affiliate-js");

		wp_register_script("remodaler-js",plugin_dir_url(__FILE__)."screens".DIRECTORY_SEPARATOR."addons".DIRECTORY_SEPARATOR."remodaler.js",["jquery"]);
		wp_enqueue_script("remodaler-js");

		wp_register_style("remodaler-css",plugin_dir_url(__FILE__)."screens".DIRECTORY_SEPARATOR."addons".DIRECTORY_SEPARATOR."remodaler.css");
		wp_enqueue_style("remodaler-css");

		wp_localize_script( 'afm-affiliate-js',
			'afm_creatives_info', [
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('afm-nonce'),
				'creatives_per_page' => AFMCreatives::PAGE_SIZE
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
		wp_register_script("afm-tracker",plugin_dir_url(__FILE__)."afm_tracker.js",["jquery"]);
		wp_enqueue_script("afm-tracker");


		//TODO: move keep days to settings.

		wp_localize_script( 'afm-tracker', 'afm_server_info', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('afm-nonce'),
			'keep_days' => get_option("afm-keep-days",AffiliatesManagement::AFM_KEEP_DAYS)
		] );
	}

	/*
	 * Add settings menu
	 */
	function addSettingsMenu()
	{
		add_menu_page("Affiliates Management","Affiliates","manage_options","affiliates-management",[$this,"showMenu"],AffiliatesManagement::AFM_ICON,AffiliatesManagement::AFM_MENU_POSITION);
	}

	public function registerAdminAssets($hook)
	{
		if (!strstr($hook, "affiliates-management") )
			return;

		wp_register_style( 'afm-admin-css', plugin_dir_url(__FILE__).'admin/afm-admin.css');
		wp_register_script( 'afm-admin-js', plugin_dir_url(__FILE__).'admin/afm-admin.js');

		wp_enqueue_style( 'afm-admin-css');
		wp_enqueue_script( 'afm-admin-js');

		wp_localize_script( 'afm-admin-js', 'afm_admin', ['MIXED_CPA_REVSHARE' => AFM_DealType::MIXED_CPA_AND_REVEUE_SHARE] );
	}

	function showMenu()
	{
		$showUpdated = false;

		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			switch($_POST["action"])
			{
				case "save_settings":
					$showUpdated = true;
					$updateResult = $this->saveSettings();
					break;
				case "save_affilate_settings":
					$showUpdated = true;
					$updateResult = $this->saveAffiliateSettings();
					break;
				case "pay_affilate":
					$showUpdated = true;
					$aff = AFMAffiliate::fromAffiliateId($_POST["affiliate_id"]);
					$aff->pay($_POST["amount"],$_POST["comment"]);
					$updateResult = true;
					break;
				default:
					break;
			}
		}

		$page = isset($_GET["subpage"]) ? $_GET["subpage"] : "settings";

		require_once "admin".DIRECTORY_SEPARATOR.$page.".php";
	}

	function saveAffiliateSettings()
	{
		if( !wp_verify_nonce($_POST["wp_nonce"],"save_affiliate_details"))
			return false;

		$data = [
			"ID" => $_POST["affiliate_id"],
			"user_email" => $_POST["email"],
			"display_name" => $_POST["fullname"],
			"user_url" => $_POST["url"],
			"skype_user" => $_POST["skype"],
			"phone" => $_POST["aff_phone"],
			"deal" => [
				"type" => $_POST["deal_type"],
				"CPA" => isset($_POST["cpa"]) ? $_POST["cpa"] : "",
				"REVSHARE" => isset($_POST["revshare"]) ? $_POST["revshare"] : "",
				"REVSHARE_PERIOD" => isset($_POST["revshare_period"]) ? $_POST["revshare_period"] : "",
			]
		];

		if(isset($_POST["password"]))
			$data["user_pass"] = $_POST["password"];

		AFMAffiliate::adminUpdate($data);

		return true;
	}

	public static function lockingEvent()
	{
		return get_option("afm-locking-event","authenticate");
	}

	function saveSettings()
	{
		update_option("afm-tracker-script-location",$_POST["script_location"],true);
		update_option("afm-locking-event",$_POST["locking_event"],true);
		update_option("afm-default-deal",[
				"type" => $_POST["deal_type"],
				"CPA" => isset($_POST["cpa"]) ? $_POST["cpa"] : "",
				"REVSHARE" => isset($_POST["revshare"]) ? $_POST["revshare"] : "",
				"REVSHARE_PERIOD" => isset($_POST["revshare_period"]) ? $_POST["revshare_period"] : ""
		],true);
		update_option("afm-keep-days",$_POST["keep_days"],true);
		return true;
	}

	function showAffiliatesApp()
	{
		$page = isset($_GET["page"]) ? $_GET["page"] : "";

		switch ($page)
		{
			case "join_us":
				require_once("screens" . DIRECTORY_SEPARATOR . "join_us.php");
				break;
			case "lost_pass":
				require_once("screens" . DIRECTORY_SEPARATOR . "lost_pass.php");
				break;
			default:
				$userId = get_current_user_id();

				if ($userId == 0)
				{
					require_once("screens" . DIRECTORY_SEPARATOR . "login.php");
					return;
				}

				$data = get_userdata($userId);

				if (in_array(self::AFM_ROLE_NAME, $data->roles))
				{
					require_once("screens" . DIRECTORY_SEPARATOR . "home.php");
					return;
				}

				require_once("screens" . DIRECTORY_SEPARATOR . "login.php");
				break;
		}
	}

	function addAttachmentTaxonomies()
	{
		if(!is_object_in_taxonomy('attachment','category'))
			register_taxonomy_for_object_type( 'category', 'attachment' );
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
				case "create_link":
					$affiliate = AFMAffiliate::fromCurrentUser();
					$affiliate->createLink();
					break;
				case "delete_link":
					$affiliate = AFMAffiliate::fromCurrentUser();
					$linkId = $_POST["link_id"];
					$affiliate->deleteLink($linkId);
			}
		}
		catch(Exception $ex)
		{
			$this->error = $ex->getMessage();
		}

	}

	function doAffiliateLogin($args)
	{
		if(!isset($args["log"]) || strlen($args["log"]) == 0 || !isset($args["pwd"]) || strlen($args["pwd"] == 0))
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
}

include_once "class".DIRECTORY_SEPARATOR."affiliate.php";
include_once "class".DIRECTORY_SEPARATOR."creatives.php";
include_once "class".DIRECTORY_SEPARATOR."stats.php";
include_once "class".DIRECTORY_SEPARATOR."helper.php";
include_once "class".DIRECTORY_SEPARATOR."accounting.php";

$affmgr = new AffiliatesManagement();

