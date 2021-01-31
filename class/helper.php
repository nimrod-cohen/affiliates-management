<?php
/**
 * Created by PhpStorm.
 * User: nimrod
 * Date: 08/09/17
 * Time: 15:20
 */
class AFMHelper
{
	const PAGE_SIZE = 20;

	static function pagingButton($total, $currentPage, $targetPage, $title)
	{
		if ($targetPage < 1 || $targetPage > $total || $targetPage == $currentPage)
			return "<span class='tablenav-pages-navspan' aria-hidden='true'>" . $title . "</span> ";
		else
			return "<a class='paging-button' href='javascript:void(null);' data-page='" . $targetPage . "'><span  aria-hidden='true'>" . $title . "</span></a>";
	}

	static function tablePaging($total, $currentPage, $pageSize)
	{
		$maxPages = ceil($total / $pageSize);
		return "<div class='tablenav'>
					<div class='tablenav-pages' style='float: right;padding: 10px'>
						<span class='displaying-num'>" . $total . " rows.</span>
						<span class='pagination-links'>" .
		self::pagingButton($maxPages, $currentPage, 1, "«") .
		self::pagingButton($maxPages, $currentPage, $currentPage - 1, "‹") .
		"</span>
					<span class='paging-input'>
						<label for='current-page-selector' class='screen-reader-text'>current page</label>
						<input class='current-page' id='current-page-selector' type='text' name='paged' value='" . $currentPage . "' size='3' aria-describedby='table-paging'> of
						<span class='total-pages'>" . $maxPages . "</span>
					</span>
					<span class='paging-input'>" .
		self::pagingButton($maxPages, $currentPage, $currentPage + 1, "›") .
		self::pagingButton($maxPages, $currentPage, $maxPages, "»") .
		"</span>
		</div>
		</div>";
	}

	public static function reflectConstants($class)
	{
		$class = new ReflectionClass($class);
		return $class->getConstants();
	}

	public static function reflectConstantName($class,$value)
	{
		$constants = array_reverse(self::reflectConstants($class));

		foreach($constants as $name => $val)
			if($val == $value)
				return $name;

		throw new Exception("Unknown constant requested ".$value);
	}

	public static function formatMoney($sum)
	{
		$fmt = new NumberFormatter( 'en_US', NumberFormatter::CURRENCY );
		return $fmt->formatCurrency($sum, get_option("afm-currency",AffiliatesManagement::AFM_DEFAULT_CURRENCY));
	}
}

class AFM_DealType
{
	const CPA = 1;
	const REVENUE_SHARE = 2;
	const MIXED_CPA_AND_REVEUE_SHARE = 3;
	const PRODUCT_PAYOUT = 4;

	public static function toString($type)
	{
		return AFMHelper::reflectConstantName("AFM_DealType",$type);
	}

	public static function getTypes()
	{
		return AFMHelper::reflectConstants("AFM_DealType");
	}

	public static function parseDeal($deal)
	{
		switch($deal["type"])
		{
			case AFM_DealType::CPA:
				return "CPA of ".AFMHelper::formatMoney($deal["CPA"]);
			case AFM_DealType::REVENUE_SHARE:
				$period = $deal["REVSHARE_PERIOD"];
				if($period == 0)
					$period = "Lifetime";
				else
					$period .= " months";
				return $period." Revenue share of %".$deal["REVSHARE"];
			case AFM_DealType::MIXED_CPA_AND_REVEUE_SHARE:
				$period = $deal["REVSHARE_PERIOD"];
				if($period == 0)
					$period = "Lifetime";
				else
					$period .= " months";
				return "CPA of ".AFMHelper::formatMoney($deal["CPA"]).", and ".$period." revenue share of %".$deal["REVSHARE"];
			default:
				throw new Exception("unknown deal type");
		}
	}
}
