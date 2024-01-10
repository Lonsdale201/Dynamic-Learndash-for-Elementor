<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action( 'jet-engine/modules/dynamic-visibility/conditions/register', function( $conditions_manager ) {

    class Course_Access_Type extends \Jet_Engine\Modules\Dynamic_Visibility\Conditions\Base {

        public function get_id() {
            return 'course-access-type';
        }

        public function get_name() {
            return __( 'Course Access Type', 'jet-engine' );
        }

        public function get_group() {
            return 'learndash';
        }

        public function get_custom_controls() {
            return array(
                'course_access_type' => array(
                    'label'    => __( 'Select Access Type', 'jet-engine' ),
                    'type'     => 'select',
                    'options'  => array(
                        'open'      => __( 'Open', 'jet-engine' ),
                        'free'      => __( 'Free', 'jet-engine' ),
                        'paynow'    => __( 'Buy now', 'jet-engine' ),
                        'subscribe' => __( 'Recurring', 'jet-engine' ),
                        'closed'    => __( 'Closed', 'jet-engine' ),
                    ),
                    'default'  => 'open',
                ),
            );
        }

        public function check( $args = array() ) {
            $course_id = get_the_ID();

            if ( 'sfwd-courses' !== get_post_type( $course_id ) ) {
                return false;
            }

            $course_access_type = learndash_get_setting( $course_id, 'course_price_type' );
            $selected_access_type = isset( $args['condition_settings']['course_access_type'] ) ? $args['condition_settings']['course_access_type'] : 'open';

            $type = isset( $args['type'] ) ? $args['type'] : 'show';

            if ( 'hide' === $type ) {
                return $course_access_type !== $selected_access_type;
            } else {
                return $course_access_type === $selected_access_type;
            }
        }

        public function is_for_fields() {
            return false;
        }

        public function need_value_detect() {
            return false;
        }
    }

    $conditions_manager->register_condition( new Course_Access_Type() );
});
