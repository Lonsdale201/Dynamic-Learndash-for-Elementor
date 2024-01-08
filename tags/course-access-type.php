<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


use Elementor\Core\DynamicTags\Tag;

class Course_Access_Type extends \Elementor\Core\DynamicTags\Tag {

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
    }

    public function render() {
        global $post;

        
        if ( 'sfwd-courses' !== get_post_type( $post ) ) {
            echo '';
            return;
        }

        $settings = $this->get_settings();
        $visibility = $settings['visibility'];
        $course_id = $post->ID;

        if ( 'enrolled' === $visibility && ! sfwd_lms_has_access( $course_id, get_current_user_id() ) ) {
            echo '';
            return;
        }

        $access_type = learndash_get_course_meta_setting($course_id, 'course_price_type');
        $access_type_text = '';

       
        switch ($access_type) {
            case 'open':
                $access_type_text = __( 'Open', 'learndash' );
                break;
            case 'free':
                $access_type_text = __( 'Free', 'learndash' );
                break;
            case 'paynow':
                $access_type_text = __( 'Buy Now', 'learndash' );
                break;
            case 'subscribe':
                $access_type_text = __( 'Subscription', 'learndash' );
                break;
            case 'closed':
                $access_type_text = __( 'Closed', 'learndash' );
                break;
            default:
                $access_type_text = __( 'Unknown', 'learndash' );
        }

        echo esc_html( $access_type_text );
    }
}
