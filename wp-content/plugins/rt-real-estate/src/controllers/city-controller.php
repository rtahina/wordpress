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
		$cities =  get_posts( array(
            'post_type' => $this->post_type,
            'numberposts' => $per_page,
            'paged' => $page,
            'orderby' => $order_by,
            'order' => $order
        ) );

		$template_dat['cities'] = array();

		foreach( $cities as $city ) {
			$city_data = array();
			$city_data['ID'] = $city->ID;
			$city_data['permalink'] = get_the_permalink( $city->ID );
			$city_data['name'] = $city->post_title;
			$city_data['description'] = $city->post_content;
			
			if (has_post_thumbnail( $city->ID ) ) {
				$image = get_the_post_thumbnail( $city->ID );
			} else {
				$image = '';
			}
			$city_data['image'] = $image;

			$this->city_data['cities'][] = $city_data;
		}

		return $this->city_data;
	}
}