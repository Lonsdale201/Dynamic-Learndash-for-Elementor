<?php
namespace Learndash_Dynamic_Tag\Global_Tags;

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}


class User_Available_Courses_Count extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'user-available-courses-count';
    }

    public function get_title() {
        return __( 'User Available Courses Count', 'elementor-pro' );
    }

    public function get_group() {
        return 'learndash-global';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    protected function _register_controls() {
    }

    public function render() {
        if ( ! is_user_logged_in() ) {
            echo '';
            return;
        }

        $user_id = get_current_user_id();
        $courses_owned = learndash_user_get_enrolled_courses($user_id, array(), true);

        $course_count = count($courses_owned);
        echo esc_html( $course_count );
    }
    
}

class User_Completed_Courses_Count extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'user-completed-courses-count';
    }

    public function get_title() {
        return __( 'User Completed Courses Count', 'elementor-pro' );
    }

    public function get_group() {
        return 'learndash-global';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY, \Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY ];
    }

    protected function _register_controls() {
    }

    public function render() {
        if ( ! is_user_logged_in() ) {
            echo '';
            return;
        }

        $user_id = get_current_user_id();
        $course_info = get_user_meta($user_id, '_sfwd-course_progress', true);
        $completed_courses_count = 0;

        if (!empty($course_info)) {
            foreach ($course_info as $course_id => $progress) {
                if (!empty($progress['completed']) && intval($progress['completed']) > 0) {
                    $completed_courses_count++;
                }
            }
        }

        echo esc_html( $completed_courses_count );
    }
    
}

class User_Course_Points extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'user-course-points';
    }

    public function get_title() {
        return __( 'User Course Points', 'elementor-pro' );
    }

    public function get_group() {
        return 'learndash-global';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY, \Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY ];
    }

    protected function _register_controls() {
       
    }

    public function render() {
        if ( ! is_user_logged_in() ) {
            echo '';
            return;
        }

        $user_id = get_current_user_id();
        $user_points = learndash_get_user_course_points($user_id);

        if ( $user_points == 0 ) {
            echo ''; 
            return;
        }

        echo esc_html( $user_points );
    }
}

class User_Achieved_Certificates_Count extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'user-achieved-certificates-count';
    }

    public function get_title() {
        return __( 'User Achieved Certificates Count', 'elementor-pro' );
    }

    public function get_group() {
        return 'learndash-global';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY, \Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY ];
    }

    protected function _register_controls() {
    }

    public function render() {
        if ( ! is_user_logged_in() ) {
            echo '';
            return;
        }

        $user_id = get_current_user_id();
        $certificates_count = learndash_get_certificate_count( $user_id );

        if ( $certificates_count > 0 ) {
            echo esc_html( $certificates_count );
        }
    }
}

