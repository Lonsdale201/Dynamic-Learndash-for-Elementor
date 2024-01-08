<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class User_Course_Status extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'user-course-status';
    }

    public function get_title() {
        return __( 'User Course Status', 'elementor-pro' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    public function render() {
        $current_user = wp_get_current_user();

        if ( ! $current_user->exists() ) {
            return; // Ha a felhasználó nincs bejelentkezve, nem jelenítünk meg semmit
        }

        global $post;
        if ( 'sfwd-courses' !== get_post_type( $post ) ) {
            return;
        }

        $course_id = $post->ID;

        // Ellenőrizzük, hogy a felhasználó be van-e íratkozva a kurzusra
        if ( sfwd_lms_has_access( $course_id, $current_user->ID ) ) {
            $course_status = learndash_course_status( $course_id, $current_user->ID );
        } else {
            // Ha a felhasználó nincs beiratkozva, akkor a státusz "Not Enrolled"
            $course_status = __( 'Not Enrolled', 'learndash' );
        }

        // Fordítás a státusz szövegéhez
        echo esc_html( $course_status );
    }
}
