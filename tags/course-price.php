<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class Course_Price extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'course-price';
    }

    public function get_title() {
        return __( 'Course Price', 'elementor-pro' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    protected function _register_controls() {
        $this->add_control(
            'free_text',
            [
                'label' => __( 'Free Text', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Free', 'elementor-pro' ),
            ]
        );

        $this->add_control(
            'enrolled_text',
            [
                'label' => __( 'Enrolled Text', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'You have this course', 'elementor-pro' ),
            ]
        );

        $this->add_control(
            'price_type_visibility',
            [
                'label' => __( 'Price Type Visibility', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'show_all',
                'options' => [
                    'show_all' => __( 'Show All Types', 'elementor-pro' ),
                    'show_if_has_price' => __( 'Show Only if Has Price', 'elementor-pro' ),
                ],
            ]
        );
    }

    public function render() {
        global $post;
    
        $course_id = $post->ID;
        $user_id = get_current_user_id();
    
        if ( sfwd_lms_has_access( $course_id, $user_id ) ) {
            echo esc_html($this->get_settings('enrolled_text'));
            return;
        }
    
        // get course price
        $course_price_details = learndash_get_course_price( $course_id, $user_id );
        $visibility = $this->get_settings( 'price_type_visibility' );
        $free_text = $this->get_settings( 'free_text' );
    
        if ( is_array( $course_price_details ) ) {
            $price = $course_price_details['price'];
            $currency_code = isset( $course_price_details['currency'] ) ? $course_price_details['currency'] : '';
            $formatted_price = learndash_get_price_formatted( $price, $currency_code );

            if ( 'show_only_if_has_price' === $visibility ) {
                if ( 'open' === $course_price_details['type'] || 'free' === $course_price_details['type'] ) {
                    echo esc_html( $free_text );
                    return;
                } elseif ( empty( $formatted_price ) ) {
                    echo '';
                    return;
                }
            }
    
            if ( 'open' === $course_price_details['type'] || 'free' === $course_price_details['type'] ) {
                echo esc_html( $free_text );
            } elseif ( ! empty( $formatted_price ) ) {
                echo esc_html( $formatted_price );
            }
        } else {
            echo '';
        }
    }
}
