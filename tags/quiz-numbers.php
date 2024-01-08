<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class Quiz_Numbers extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'quiz-numbers';
    }

    public function get_title() {
        return __( 'Quiz Numbers', 'elementor-pro' );
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

        if ('sfwd-courses' !== get_post_type($post)) {
            echo '';
            return;
        }

        $course_id = $post->ID;
        $quiz_count = $this->count_quizzes_in_course($course_id);

        if ($quiz_count > 0) {
            echo esc_html($quiz_count);
        }
    }

    private function count_quizzes_in_course($course_id) {
        $quiz_count = 0;
    
        $lessons = learndash_get_lesson_list($course_id);
        foreach ($lessons as $lesson) {
            $lesson_quizzes = learndash_get_lesson_quiz_list($lesson->ID);
            $quiz_count += is_array($lesson_quizzes) ? count($lesson_quizzes) : 0;
    
            $topics = learndash_topic_dots($lesson->ID, false, 'array');
            if (is_array($topics)) {
                foreach ($topics as $topic) {
                    $topic_quizzes = learndash_get_lesson_quiz_list($topic->ID);
                    $quiz_count += is_array($topic_quizzes) ? count($topic_quizzes) : 0;
                }
            }
        }
    
        $course_quizzes = learndash_get_course_quiz_list($course_id);
        $quiz_count += is_array($course_quizzes) ? count($course_quizzes) : 0;
    
        return $quiz_count;
    }    
}
