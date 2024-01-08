<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


add_action( 'jet-engine/modules/dynamic-visibility/conditions/register', function( $conditions_manager ) {

    class LD_Current_User_Completed_Current_Lesson extends \Jet_Engine\Modules\Dynamic_Visibility\Conditions\Base {

        public function get_id() {
            return 'ld-current-user-completed-current-lesson';
        }

        public function get_name() {
            return __( 'User Completed Current Lesson', 'jet-engine' );
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
            $lesson_id = $post->ID;
            $course_id = learndash_get_course_id( $lesson_id );

            if ( 'sfwd-lessons' != get_post_type( $lesson_id ) ) {
                return false;
            }

            $completed = learndash_is_lesson_complete( $user_id, $lesson_id, $course_id );

            return $completed;
        }

        public function is_for_fields() {
            return false; 
        }

        public function need_value_detect() {
            return false; 
        }

    }

    $conditions_manager->register_condition( new LD_Current_User_Completed_Current_Lesson() );

} );
