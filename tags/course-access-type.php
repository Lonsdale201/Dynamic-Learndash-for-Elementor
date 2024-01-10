<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

use Elementor\Core\DynamicTags\Tag;

class Course_Access_Type extends Tag {

    public function get_name() {
        return 'course-access-type';
    }

    public function get_title() {
        return __( 'Course Access Type', 'elementor-pro' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    protected function _register_controls() {
        $this->add_control(
            'format_type',
            [
                'label' => __( 'Format Type', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'default' => __( 'Learndash Default', 'elementor-pro' ),
                    'custom' => __( 'Custom Format', 'elementor-pro' ),
                ],
                'default' => 'default',
            ]
        );

        $this->add_text_control_for_course_access_type('open', __( 'Open', 'elementor-pro' ));
        $this->add_text_control_for_course_access_type('free', __( 'Free', 'elementor-pro' ));
        $this->add_text_control_for_course_access_type('paynow', __( 'Buy Now', 'elementor-pro' ));
        $this->add_text_control_for_course_access_type('subscribe', __( 'Subscription', 'elementor-pro' ));
        $this->add_text_control_for_course_access_type('closed', __( 'Closed', 'elementor-pro' ));
        $this->add_text_control_for_course_access_type('enrolled', __( 'Enrolled', 'elementor-pro' ));
    }

    private function add_text_control_for_course_access_type($type, $label) {
        $this->add_control(
            "custom_text_{$type}",
            [
                'label' => $label,
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'format_type' => 'custom',
                ],
            ]
        );
    }

    public function render() {
        global $post;

        if ( 'sfwd-courses' !== get_post_type( $post ) ) {
            echo '';
            return;
        }

        $settings = $this->get_settings();
        $format_type = $settings['format_type'];
        $course_id = $post->ID;
        $user_id = get_current_user_id();

        $is_user_enrolled = sfwd_lms_has_access( $course_id, $user_id );
        $access_type = learndash_get_course_meta_setting($course_id, 'course_price_type');

        if ($is_user_enrolled && $format_type === 'custom' && !empty($settings['custom_text_enrolled'])) {
            echo esc_html( $settings['custom_text_enrolled'] );
        } else {
            $access_type_text = $this->get_access_type_text($access_type, $settings, $format_type);
            echo esc_html( $access_type_text );
        }
    }

    private function get_access_type_text($access_type, $settings, $format_type) {
        if ($format_type === 'custom' && isset($settings["custom_text_{$access_type}"])) {
            return $settings["custom_text_{$access_type}"];
        }

        // Default texts
        switch ($access_type) {
            case 'open':
                return __( 'Open', 'learndash' );
            case 'free':
                return __( 'Free', 'learndash' );
            case 'paynow':
                return __( 'Buy Now', 'learndash' );
            case 'subscribe':
                return __( 'Subscription', 'learndash' );
            case 'closed':
                return __( 'Closed', 'learndash' );
            default:
                return __( 'Unknown', 'learndash' );
        }
    }

}
