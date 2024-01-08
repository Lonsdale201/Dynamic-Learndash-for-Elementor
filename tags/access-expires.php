<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


use Elementor\Core\DynamicTags\Tag;

class Access_Expires extends Tag {

    public function get_name() {
        return 'access-expires';
    }

    public function get_title() {
        return __( 'Access Expires', 'learndash_dynamic_tag' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    protected function _register_controls() {
        $this->add_control(
            'no_expiry_text',
            [
                'label'   => __( 'No Expiry Text', 'learndash_dynamic_tag' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Lifetime', 'learndash_dynamic_tag' ),
            ]
        );
        $this->add_control(
            'output_format',
            [
                'label'   => __( 'Output Format', 'learndash_dynamic_tag' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'plain_date'  => __( 'Plain Date', 'learndash_dynamic_tag' ),
                    'custom_text' => __( 'Custom Text', 'learndash_dynamic_tag' ),
                ],
                'default' => 'plain_date',
            ]
        );

        $this->add_control(
            'custom_text_template',
            [
                'label'       => __( 'Custom Text Template', 'learndash_dynamic_tag' ),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'default'     => __( 'Expires on %expirity_date%, %expirity_day% days left', 'learndash_dynamic_tag' ),
                'condition'   => [
                    'output_format' => 'custom_text',
                ],
            ]
        );
    }
    public function render() {
        $user_id = get_current_user_id();
        $course_id = get_the_ID();
    
        if ('sfwd-courses' !== get_post_type($course_id) || !$user_id || !sfwd_lms_has_access($course_id, $user_id)) {
            echo '';
            return;
        }
    
        $expiry = ld_course_access_expires_on($course_id, $user_id);
    
        if (!$expiry) {
            echo wp_kses_post($this->get_settings('no_expiry_text'));
        } else {
            $settings = $this->get_settings();
            if ('custom_text' === $settings['output_format']) {
                $expiry_date = date_i18n(get_option('date_format'), $expiry);
                $days_left = ceil(($expiry - current_time('timestamp')) / DAY_IN_SECONDS);
    
                $custom_text = str_replace('%expirity_date%', $expiry_date, $settings['custom_text_template']);
                $custom_text = str_replace('%expirity_day%', $days_left, $custom_text);
    
                echo wp_kses_post($custom_text);
            } else {
                echo wp_kses_post(date_i18n(get_option('date_format'), $expiry));
            }
        }
    }
    
}

new Access_Expires();
