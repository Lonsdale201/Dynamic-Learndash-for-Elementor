<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


use Elementor\Core\DynamicTags\Tag;

class Last_Activity extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'last-activity';
    }

    public function get_title() {
        return __( 'Last Activity', 'elementor-pro' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    protected function _register_controls() {
        $this->add_control(
            'date_format',
            [
                'label' => __( 'Format', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'default' => __( 'Default', 'elementor-pro' ),
                    'custom' => __( 'Custom', 'elementor-pro' ),
                ],
                'default' => 'default',
            ]
        );

        $this->add_control(
            'custom_date_format',
            [
                'label' => __( 'Custom Date Format', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'date_format' => 'custom',
                ],
                'placeholder' => 'Y-m-d H:i:s',
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
    
        $settings = $this->get_settings();
        $date_format_option = $settings['date_format'];
        $custom_date_format = $settings['custom_date_format'];
    
        if ( empty( $user_id ) ) {
            echo '';
            return;
        }
    
        $args = array(
            'course_id' => $course_id,
            'post_id' => $course_id,
            'user_id'   => $user_id,
            'activity_type' => 'course', 
            'order'     => 'DESC',
            'per_page'  => 1
        );
    
        $activity = learndash_get_user_activity( $args );
    
        if ( !empty( $activity ) ) {
            $last_activity_date = $activity->activity_updated;
    
            if ( !empty( $last_activity_date ) ) {
                $date_format = 'custom' === $date_format_option && !empty($custom_date_format)
                                ? $custom_date_format
                                : get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
    
                $formatted_date = date_i18n( $date_format, $last_activity_date );
                echo esc_html( $formatted_date );
            } else {
                echo ''; 
            }
        } else {
            echo ''; 
        }
    }
    
    
}
