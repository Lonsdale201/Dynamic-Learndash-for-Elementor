<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


add_action( 'jet-engine/register-macros', function() {

    class LD_Course_Prerequisites_Query extends \Jet_Engine_Base_Macros {

        public function macros_tag() {
            return 'ld_course_prerequisites_query';
        }

        public function macros_name() {
            return 'LD Course Prerequisites Query';
        }

        public function macros_args() {
            return array();
        }

        public function macros_callback( $args = array() ) {
            global $post;

            $course_id = isset( $post ) ? $post->ID : '';

            if ( 'sfwd-courses' !== get_post_type( $course_id ) ) {
                return 'This is not a course';
            }

            $prerequisites = learndash_get_course_prerequisite( $course_id );

            if ( is_array( $prerequisites ) && !empty( $prerequisites ) ) {
                return implode( ',', $prerequisites );
            } else {
                return 'No prerequisites found for this course';
            }
        }
    }

    new LD_Course_Prerequisites_Query();

});
