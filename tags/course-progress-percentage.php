<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


// use Elementor\Core\DynamicTags\Tag;
// use Elementor\Controls_Manager;

class Course_Progress_Percentage  extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'course-progress-percentage';
    }

    public function get_title() {
        return __( 'Course Progress Percentage', 'elementor-pro' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY ];
    }

    public function render() {
        global $post;

        if ( 'sfwd-courses' !== get_post_type( $post ) ) {
            echo '';
            return;
        }

        $user_id = get_current_user_id();
        $course_id = $post->ID;

        $course_progress = learndash_course_progress(array(
            'user_id'   => $user_id,
            'course_id' => $course_id,
            'array'     => true
        ));

        if ( !empty($course_progress) && isset($course_progress['percentage']) ) {
            echo intval($course_progress['percentage']); 
        } else {
            echo '0'; 
        }
    }
}
