<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


if ( class_exists( 'Jet_Engine' ) ) {

    add_action( 'jet-engine/modules/dynamic-visibility/conditions/register', function( $conditions_manager ) {

        class LD_Current_User_Purchased_Current_Course extends \Jet_Engine\Modules\Dynamic_Visibility\Conditions\Base {

            public function get_id() {
                return 'ld-current-user-purchased-current-course';
            }

            public function get_name() {
                return __( 'Current user enrolled course', 'jet-engine' );
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

                if ( 'sfwd-courses' != get_post_type( $course_id ) ) {
                    return false;
                }

                $user_courses = ld_get_mycourses( $user_id );
                $has_purchased = in_array( $course_id, $user_courses );

                $type = isset( $args['type'] ) ? $args['type'] : 'show';

                if ( 'hide' === $type ) {
                    return !$has_purchased;
                } else {
                    return $has_purchased; 
                }
            }

            public function is_for_fields() {
                return false; 
            }

            public function need_value_detect() {
                return false; 
            }

        }

        $conditions_manager->register_condition( new LD_Current_User_Purchased_Current_Course() );

    } );

}
