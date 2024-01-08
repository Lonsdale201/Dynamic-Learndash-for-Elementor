<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class Lessons_Number extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'lessons-number';
    }

    public function get_title() {
        return __( 'Lessons Number', 'elementor-pro' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    protected function _register_controls() {
        $this->add_control(
            'visibility',
            [
                'label' => __( 'Visibility', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'everyone' => __( 'Everybody see', 'elementor-pro' ),
                    'enrolled' => __( 'Only Enrolled see', 'elementor-pro' ),
                ],
                'default' => 'everyone',
            ]
        );

        $this->add_control(
            'formatting',
            [
                'label' => __( 'Formatting', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'plain' => __( 'Plain Number', 'elementor-pro' ),
                    'formatted' => __( 'Formatted Number', 'elementor-pro' ),
                ],
                'default' => 'plain',
            ]
        );
    }

    private function get_course_id() {
        global $post;

        if ( is_a( $post, 'WP_Post' ) && 'sfwd-courses' === get_post_type( $post ) ) {
            return $post->ID;
        }
    }

    public function render() {
        $course_id = $this->get_course_id();

        if ( !$course_id ) {
            echo '';
            return;
        }
    
        $settings = $this->get_settings();
        $visibility = $settings['visibility'];
        $formatting = $settings['formatting'];
    
        if ( 'enrolled' === $visibility && ! sfwd_lms_has_access( $course_id, get_current_user_id() ) ) {
            echo '';
            return;
        }
    
        $lessons = learndash_get_course_lessons_list( $course_id );
        $completed_lessons_count = learndash_course_get_completed_steps( get_current_user_id(), $course_id );
    
        if ( 'formatted' === $formatting ) {
            $output = $completed_lessons_count . '/' . count( $lessons );
        } else {
            $output = count( $lessons );
        }
    
        echo esc_html( $output );
    }

    
}
