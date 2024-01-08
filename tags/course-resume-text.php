<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class Course_Resume_Text extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'course-resume-text';
    }

    public function get_title() {
        return __( 'Course Resume Text', 'elementor-pro' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    protected function _register_controls() {
        $this->add_control(
            'in_progress_text',
            [
                'label' => __( 'In Progress', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Continue Learning',
            ]
        );

        $this->add_control(
            'not_started_text',
            [
                'label' => __( 'Not Started', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Start Course',
            ]
        );

        $this->add_control(
            'completed_text',
            [
                'label' => __( 'Completed', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Read More',
            ]
        );

        $this->add_control(
            'user_not_have_course_text',
            [
                'label' => __( 'User Not Have Course', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Get Started',
            ]
        );
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
            $resume_step_id = learndash_user_progress_get_first_incomplete_step( $user_id, $course_id );
            $completed = learndash_course_completed( $user_id, $course_id );

            if ( $completed ) {
                echo $this->get_settings( 'completed_text' );
            } elseif ( $resume_step_id ) {
                echo $this->get_settings( 'in_progress_text' );
            } else {
                echo $this->get_settings( 'not_started_text' );
            }
        } else {
            echo $this->get_settings( 'user_not_have_course_text' );
        }
    }
}
