<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


use Elementor\Core\DynamicTags\Tag;
use Elementor\Controls_Manager;

class Student_Limit extends Tag {

    public function get_name() {
        return 'student-limit';
    }

    public function get_title() {
        return __( 'Student Limit', 'elementor-pro' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    protected function _register_controls() {
        $this->add_control(
            'format',
            [
                'label' => __( 'Returned Count', 'elementor-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'plain' => __( 'Plain Count', 'elementor-pro' ),
                    'custom' => __( 'Custom Format', 'elementor-pro' ),
                ],
                'default' => 'plain',
            ]
        );

        $this->add_control(
            'custom_format',
            [
                'label' => __( 'Custom Format', 'elementor-pro' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Limit: %current_seat%, Remaining: %remaining_seat%',
                'condition' => [
                    'format' => 'custom',
                ],
                'description' => __( 'Use the macros %current_seat% to output the current seat limit and %remaining_seat% to output the remaining seats.', 'elementor-pro' ),
            ]
        );    
    }

    public function render() {
        global $post;
        if ('sfwd-courses' !== get_post_type($post)) {
            return;
        }
    
        $course_id = $post->ID;
        $seat_limit = $this->get_seat_limit($course_id);
    
        if (is_numeric($seat_limit)) {
            $enrolled_users_count = $this->get_enrolled_users_count($course_id);
            $remaining_seats = max(0, $seat_limit - $enrolled_users_count);
    
            $settings = $this->get_settings();
            if ('custom' === $settings['format']) {
                $custom_format = $settings['custom_format'];
                $formatted_output = str_replace('%current_seat%', $seat_limit, $custom_format);
                $formatted_output = str_replace('%remaining_seat%', $remaining_seats, $formatted_output);
                echo $formatted_output;
            } else {
                echo esc_html($seat_limit);
            }
        }
    }

    private function get_seat_limit($post_id) {
        $post_meta = get_post_meta($post_id, '_sfwd-courses', true);
        if (isset($post_meta['sfwd-courses_course_seats_limit']) && is_numeric($post_meta['sfwd-courses_course_seats_limit']) && $post_meta['sfwd-courses_course_seats_limit'] > 0) {
            return (int)$post_meta['sfwd-courses_course_seats_limit'];
        }
        return '';
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
    
}
