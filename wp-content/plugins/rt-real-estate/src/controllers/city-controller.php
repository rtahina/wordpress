<?php
namespace RTRealEstate\Controllers;

/**
 * Course Controller Class.
 */
class CityController {

	/**
	 * Post Type.
	 *
	 * @var string
	 */
	protected $post_type;

    public function __construct()
    {
        $this->post_type = 'city';
    }

	/**
	 * Returns post type paginated array.
	 *
	 * @return null|array
	 */
	public function archive( $order_by = 'ID', $order = 'asc', $per_page = 10, $page = 0 ) {
		
		$city_data = [];

		$cities =  get_posts( array(
            'post_type' => $this->post_type,
            'numberposts' => $per_page,
            'paged' => $page,
            'orderby' => $order_by,
            'order' => $order
        ) );

		$template_dat['cities'] = array();

		foreach( $cities as $city ) {
			$data = array();
			$data['ID'] = $city->ID;
			$data['permalink'] = get_the_permalink( $city->ID );
			$data['name'] = $city->post_title;
			$data['description'] = $city->post_content;
			
			if (has_post_thumbnail( $city->ID ) ) {
				$image = get_the_post_thumbnail( $city->ID );
			} else {
				$image = '';
			}
			$data['image'] = $image;

			$city_data['cities'][] = $data;
		}

		return $city_data;
	}
}