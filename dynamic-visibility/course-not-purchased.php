<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


add_action( 'jet-engine/modules/dynamic-visibility/conditions/register', function( $conditions_manager ) {

    class Course_Not_Purchased extends \Jet_Engine\Modules\Dynamic_Visibility\Conditions\Base {

        public function get_id() {
            return 'course-not-purchased';
        }

        public function get_name() {
            return __( 'User Has Not Enrolled this Course', 'jet-engine' );
        }

        public function get_group() {
            return 'learndash';
        }

        public function check( $args = array() ) {
            $user_id = get_current_user_id();
            global $post;
            $course_id = $post->ID;

            if ( 'sfwd-courses' != get_post_type( $course_id ) ) {
                return false;
            }

            $has_access = sfwd_lms_has_access( $course_id, $user_id );

            if ( 'hide' === $args['type'] ) {
                return $has_access;
            } else {
                return !$has_access;
            }
        }

        public function is_for_fields() {
            return false;
        }

        public function need_value_detect() {
            return false;
        }

    }

    $conditions_manager->register_condition( new Course_Not_Purchased() );

} );
