<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


add_action( 'jet-engine/modules/dynamic-visibility/conditions/register', function( $conditions_manager ) {

    class LD_Current_User_Completed_Current_Course extends \Jet_Engine\Modules\Dynamic_Visibility\Conditions\Base {

        public function get_id() {
            return 'ld-current-user-completed-current-course';
        }

        public function get_name() {
            return __( 'User Completed Course', 'jet-engine' );
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

            $completed = learndash_course_completed( $user_id, $course_id );
         
            $type = isset( $args['type'] ) ? $args['type'] : 'show';

            if ( 'hide' === $type ) {
                return !$completed;
            } else {
                return $completed; 
            }
        }

        public function is_for_fields() {
            return false; 
        }

        public function need_value_detect() {
            return false; 
        }

    }

    $conditions_manager->register_condition( new LD_Current_User_Completed_Current_Course() );

} );
