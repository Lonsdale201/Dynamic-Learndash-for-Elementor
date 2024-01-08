<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Dynamic_Tags_Manager {

    public function __construct() {
        add_action( 'elementor/dynamic_tags/register_tags', [ $this, 'register_dynamic_tags' ] );
    }

    public function register_dynamic_tags( $dynamic_tags ) {

        // Register  'learndash-course' 
        \Elementor\Plugin::$instance->dynamic_tags->register_group( 'learndash-course', [
            'title' => 'LearnDash Course'
        ]);

         // Register 'learndash-global' 
         \Elementor\Plugin::$instance->dynamic_tags->register_group( 'learndash-global', [
            'title' => 'LearnDash Global'
        ]);

        $this->include_global_tags($dynamic_tags);

        // tag register
        $this->include_and_register_tag( $dynamic_tags, 'Awarded_Points' );
        $this->include_and_register_tag( $dynamic_tags, 'Course_Access_Type' );
        $this->include_and_register_tag( $dynamic_tags, 'Last_Activity' );
        $this->include_and_register_tag( $dynamic_tags, 'Course_Price' );
        $this->include_and_register_tag( $dynamic_tags, 'Course_Resume_Link' );
        $this->include_and_register_tag( $dynamic_tags, 'Enrolled_Users_Count' );
        $this->include_and_register_tag( $dynamic_tags, 'Lessons_Number' );
        $this->include_and_register_tag( $dynamic_tags, 'Course_Progress_Percentage' );
        $this->include_and_register_tag( $dynamic_tags, 'Quiz_Numbers' );
        $this->include_and_register_tag( $dynamic_tags, 'Required_Points' );
        $this->include_and_register_tag( $dynamic_tags, 'Course_Resume_Text' );
        $this->include_and_register_tag( $dynamic_tags, 'Student_Limit' );
        $this->include_and_register_tag( $dynamic_tags, 'Topics_Counter' );
        $this->include_and_register_tag( $dynamic_tags, 'User_Course_Status' );
        $this->include_and_register_tag( $dynamic_tags, 'Course_Prerequisites_List' );
        $this->include_and_register_tag( $dynamic_tags, 'Course_Materials' );
        $this->include_and_register_tag( $dynamic_tags, 'Access_Expires' );
    }

    private function include_and_register_tag( $dynamic_tags, $tag ) {
        $file_name = str_replace( '_', '-', strtolower( $tag ) );
        $file_path = __DIR__ . '/' . $file_name . '.php';

        if ( file_exists( $file_path ) ) {
            require_once $file_path;
            $class_name = __NAMESPACE__ . '\\' . str_replace( ' ', '_', $tag );
            if ( class_exists( $class_name ) ) {
                $dynamic_tags->register_tag( new $class_name() );
            } else {
            }
        } else {
        }
    }

    private function include_global_tags( $dynamic_tags ) {
        $file_path = __DIR__ . '/learndash-global-tags.php';


        if ( file_exists( $file_path ) ) {
            require_once $file_path;

            $global_tags = [
                'Global_Tags\\User_Available_Courses_Count',
                'Global_Tags\\User_Completed_Courses_Count',
                'Global_Tags\\User_Course_Points'
            ];

            foreach ($global_tags as $tag) {
                $class_name = __NAMESPACE__ . '\\' . $tag;
                if ( class_exists( $class_name ) ) {
                    $dynamic_tags->register_tag( new $class_name() );
                } else {
                }
            }
        } else {
        }
    }
}

new Dynamic_Tags_Manager();
