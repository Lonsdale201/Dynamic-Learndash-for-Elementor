<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class Topics_Counter extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'topics-counter';
    }

    public function get_title() {
        return __( 'Topics Counter', 'elementor-pro' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    protected function _register_controls() {
        // Nincsenek szükséges kontrollok
    }

    public function render() {
        global $post;

        if ( 'sfwd-courses' !== get_post_type( $post ) ) {
            echo '';
            return;
        }

        $course_id = $post->ID;
        $lessons = learndash_get_lesson_list($course_id);
        $topic_count = 0;

        foreach ($lessons as $lesson) {
            $topics = learndash_topic_dots($lesson->ID, false, 'array');
            if (is_array($topics)) {
                $topic_count += count($topics);
            }
        }

        if ($topic_count > 0) {
            echo esc_html( $topic_count );
        }
        // Ha nincs témák, nem jelenítünk meg semmit, és az Elementor kezeli a fallbacket
    }
}
