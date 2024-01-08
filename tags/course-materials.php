<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


use Elementor\Core\DynamicTags\Tag;

class Course_Materials extends Tag {

    public function get_name() {
        return 'course-materials';
    }

    public function get_title() {
        return __( 'Course Materials', 'learndash_dynamic_tag' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY];
    }

    public function render() {
        global $post;

        if ('sfwd-courses' !== get_post_type($post)) {
            echo '';
            return;
        }

        $course_id = $post->ID;
        $materials = learndash_get_setting($course_id, 'course_materials');

        if (!empty($materials)) {
            echo wp_kses_post($materials);
        }
    }
}
