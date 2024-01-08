<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class Course_Resume_Link extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'course-resume';
    }

    public function get_title() {
        return __( 'Course Resume URL', 'elementor-pro' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::URL_CATEGORY ];
    }

    protected function _register_controls() {
        // Nincsenek szükséges kontrollok
    }

    public function render() {
        global $post;

        if ( 'sfwd-courses' !== get_post_type( $post ) ) {
            echo '';
            return;
        }

        $course_id = $post->ID;
        $user_id = get_current_user_id();

        if ( $user_id && sfwd_lms_has_access( $course_id, $user_id ) ) {
            $course_status = learndash_course_status( $course_id, $user_id, true );

            if ( 'completed' !== $course_status ) {
                $resume_step_id = learndash_user_progress_get_first_incomplete_step( $user_id, $course_id );
                if ( $resume_step_id ) {
                    $resume_step_id = learndash_user_progress_get_parent_incomplete_step( $user_id, $course_id, $resume_step_id );
                    echo get_permalink( $resume_step_id );
                } else {
                    echo get_permalink( $course_id );
                }
            } else {
                echo get_permalink( $course_id );
            }
        } else {
            echo get_permalink( $course_id );
        }
    }
}