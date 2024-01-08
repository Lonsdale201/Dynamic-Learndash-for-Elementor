<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action( 'jet-engine/modules/dynamic-visibility/conditions/register', function( $conditions_manager ) {

class LD_Not_Have_Enough_Points extends \Jet_Engine\Modules\Dynamic_Visibility\Conditions\Base {

    public function get_id() {
        return 'ld-not-have-enough-points';
    }

    public function get_name() {
        return __( 'Not Have Enough Points', 'jet-engine' );
    }

    public function get_group() {
        return 'learndash';
    }

    public function check( $args = array() ) {
        $user_id = get_current_user_id();

        if ( !$user_id ) {
            return false;
        }

        global $post;
        $course_id = $post->ID;

        if ( 'sfwd-courses' !== get_post_type( $course_id ) ) {
            return false;
        }

        if ( sfwd_lms_has_access( $course_id, $user_id ) ) {
            return false;
        }

        $user_points = learndash_get_user_course_points( $user_id );
        $course_access_points = learndash_get_setting( $course_id, 'course_points_access' );

        if ( !empty($course_access_points) && ($user_points < $course_access_points) ) {
            return true; 
        }

        return false; 
    }

    public function is_for_fields() {
        return false; 
    }

    public function need_value_detect() {
        return false; 
    }

}

$conditions_manager->register_condition( new LD_Not_Have_Enough_Points() );

});
