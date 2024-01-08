<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


use Elementor\Core\DynamicTags\Tag;

class Awarded_Points extends Tag {

    public function get_name() {
        return 'awarded-points';
    }

    public function get_title() {
        return __( 'Awarded Points', 'learndash_dynamic_tag' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ 
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY 
        ];
    }

    public function render() {
        global $post;

        if ( 'sfwd-courses' !== get_post_type( $post ) ) {
            echo '';
            return;
        }
        $awarded_points = learndash_get_setting($post, 'course_points');
        if ( !empty($awarded_points) && $awarded_points > 0 ) {
            echo esc_html( $awarded_points );
        }
    }
}
