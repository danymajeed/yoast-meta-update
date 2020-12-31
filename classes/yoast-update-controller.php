<?php 

class YoastUpdateController {
	protected $namespace = 'wc/v3';

	protected $rest_base = 'meta/(?P<id>\d+)';

	public function update_meta_tags( $request ) {
		$data = $request->get_json_params();
		$id = $request['id'];
		$product = wc_get_product( $id );
		if (!$product){
			$error = new WP_Error( '404', 'Product Not Found' );
			wp_send_json_error( $error );
		}
		if (!isset($data['meta_desc']) && !isset($data['title'])){
			$error = new WP_Error( '400', 'Bad Request' );
			wp_send_json_error( $error );
		}
		foreach ($data as $key => $value){
			$yoast_field = '';
			if ($key == 'meta_desc'){
				$yoast_field = '_yoast_wpseo_metadesc';
			}
			elseif ($key == 'title'){
				$yoast_field = '_yoast_wpseo_title';
			}
			$updated_value = update_post_meta($id, $yoast_field, $value);
		}
		return wp_send_json_success($data, '200');
	}

	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				'methods' => 'PUT',
				'callback' => array( $this, 'update_meta_tags' ),
				'args' => array(
				  'id' => array(
					'validate_callback' => function($param, $request, $key) {
					  return is_numeric( $param );
					}
				  ),
				),
			)
		);
	}
}