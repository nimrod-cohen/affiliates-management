<?php
/**
 * Created by PhpStorm.
 * User: nimrod
 * Date: 07/24/17
 * Time: 16:12
 */

class AFMCreatives
{
	const PAGE_SIZE = 30;
	static function all($page, $limit)
	{

		$query_images_args = ['post_type' => 'attachment',
			'post_mime_type' => 'image',
			'post_status' => 'inherit',
			'posts_per_page' =>  $limit,
			'paged'	=> $page,
			'tax_query' => [
				[
					'taxonomy' => 'category',
					'field' => 'slug',
					'terms' => 'banner-farm'
				]
			]
		];

		$results = new WP_Query( $query_images_args );

		$images = array();
		foreach ( $results->posts as $image ) {
			$images[] = wp_get_attachment_url( $image->ID );
		}

		return $images;
	}
}