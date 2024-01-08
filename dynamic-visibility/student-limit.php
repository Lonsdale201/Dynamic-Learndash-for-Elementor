<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


add_action( 'jet-engine/modules/dynamic-visibility/conditions/register', function( $conditions_manager ) {

    class LD_Student_Limit_Reached extends \Jet_Engine\Modules\Dynamic_Visibility\Conditions\Base {

        public function get_id() {
            return 'ld-student-limit-reached';
        }

        public function get_name() {
            return __( 'Reached Student Limit', 'jet-engine' );
        }

        public function get_group() {
            return 'learndash';
        }

        public function check( $args = array() ) {
            global $post;
        
            // Ellenőrizzük, hogy a kurzus típusú-e a bejegyzés
            if ( 'sfwd-courses' !== get_post_type( $post ) ) {
                return false;
            }
        
            $course_id = $post->ID;
            $user_id = get_current_user_id();
        
            // Ellenőrizzük, hogy a felhasználó regisztrált és hozzáfér-e a kurzushoz
            if ( $user_id && sfwd_lms_has_access( $course_id, $user_id ) ) {
                return false;
            }
        
            // Itt kezdődik a student limit ellenőrzése
            $seat_limit = $this->get_seat_limit($course_id);
        
            if ( '' === $seat_limit ) {
                return false;
            }
        
            $enrolled_users = $this->get_enrolled_users_count( $course_id );
            $limit_reached = ($enrolled_users >= $seat_limit);
        
            $type = isset( $args['type'] ) ? $args['type'] : 'show';
        
            if ( 'hide' === $type ) {
                return !$limit_reached;
            } else {
                return $limit_reached;
            }
        }
        
        

        private function get_seat_limit($post_id) {
            $post_meta = get_post_meta($post_id, '_sfwd-courses', true);
            return isset($post_meta['sfwd-courses_course_seats_limit']) ? $post_meta['sfwd-courses_course_seats_limit'] : '';
        }

        private function get_enrolled_users_count($course_id) {
            $user_query_args = array(
                'meta_query' => array(
                    array(
                        'key'     => 'course_' . $course_id . '_access_from',
                        'compare' => 'EXISTS'
                    )
                )
            );
            $user_query = new \WP_User_Query( $user_query_args );
            return $user_query->get_total();
        }

        public function is_for_fields() {
            return false; 
        }

        public function need_value_detect() {
            return false; 
        }

    }

    $conditions_manager->register_condition( new LD_Student_Limit_Reached() );

} );
