<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Course_Certificates_Link extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'certificates-link';
    }

    public function get_title() {
        return __( 'Certificates Link', 'learndash_dynamic_tag' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::URL_CATEGORY ];
    }

    public function render() {
        global $post;

        if ('sfwd-courses' !== get_post_type($post)) {
            return;
        }

        $course_id = $post->ID;
        $user_id = get_current_user_id();
        $certificate_link = learndash_get_course_certificate_link($course_id, $user_id);

        if (!empty($certificate_link)) {
            echo esc_url($certificate_link);
        }
    }
}
