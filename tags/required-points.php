<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class Required_Points extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'required-points';
    }

    public function get_title() {
        return __( 'Required Points', 'elementor-pro' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    protected function _register_controls() {
       
    }

    public function render() {
        global $post;

        // Ellenőrizzük, hogy kurzus oldalon vagyunk-e
        if ( 'sfwd-courses' !== get_post_type( $post ) ) {
            echo '';
            return;
        }

        $required_points = learndash_get_setting($post, 'course_points_access');

        // Csak akkor jelenítünk meg valamit, ha a pontszám nagyobb mint 0
        if ( !empty($required_points) && $required_points > 0 ) {
            echo esc_html( $required_points );
        }
       
    }
}
