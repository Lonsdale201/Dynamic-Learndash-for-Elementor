<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action( 'jet-engine/modules/dynamic-visibility/conditions/register', function( $conditions_manager ) {

    class Course_Certificates_Available extends \Jet_Engine\Modules\Dynamic_Visibility\Conditions\Base {

        public function get_id() {
            return 'course-certificates-available';
        }

        public function get_name() {
            return __( 'Course Certificates Available', 'jet-engine' );
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

            $certificate_link = learndash_get_course_certificate_link( $course_id, $user_id );
            $certificate_available = !empty($certificate_link);

            $type = isset( $args['type'] ) ? $args['type'] : 'show';

            if ( 'hide' === $type ) {
                return !$certificate_available;
            } else {
                return $certificate_available; 
            }
        }

        public function is_for_fields() {
            return false; 
        }

        public function need_value_detect() {
            return false; 
        }

    }

    $conditions_manager->register_condition( new Course_Certificates_Available() );

} );
