<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action( 'jet-engine/modules/dynamic-visibility/conditions/register', function( $conditions_manager ) {

class Course_Access_Expiry extends \Jet_Engine\Modules\Dynamic_Visibility\Conditions\Base {

    public function get_id() {
        return 'course-access-expiry';
    }

    public function get_name() {
        return __( 'Course Access Expiry', 'jet-engine' );
    }

    public function get_group() {
        return 'learndash';
    }

    public function check( $args = array() ) {
        $user_id = get_current_user_id();
        $course_id = get_the_ID();

        if ( 'sfwd-courses' !== get_post_type( $course_id ) || !$user_id || !sfwd_lms_has_access( $course_id, $user_id ) ) {
            return false;
        }

        $expiry = ld_course_access_expires_on( $course_id, $user_id );

        if ( !$expiry ) {
            // Ha nincs lejárati idő beállítva, akkor a feltétel alapján döntünk.
            $type = isset( $args['type'] ) ? $args['type'] : 'show';
            return ( 'hide' === $type );
        } else {
            // Ellenőrizzük, hogy a jelenlegi idő nagyobb-e, mint a lejárati idő.
            $current_time = current_time( 'timestamp' );
            $is_expired = $current_time > $expiry;

            $type = isset( $args['type'] ) ? $args['type'] : 'show';

            if ( 'hide' === $type ) {
                return $is_expired;
            } else {
                return !$is_expired;
            }
        }
    }

    public function is_for_fields() {
        return false;
    }

    public function need_value_detect() {
        return false;
    }
}

$conditions_manager->register_condition( new Course_Access_Expiry() );
});
