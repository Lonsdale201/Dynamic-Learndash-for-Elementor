<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class Enrolled_Users_Count extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'enrolled-users-count';
    }

    public function get_title() {
        return __( 'Enrolled Users Count', 'elementor-pro' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ 
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY
        ];
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
                    'author' => __( 'Only Author see', 'elementor-pro' ),
                    'admin' => __( 'Only Admin see', 'elementor-pro' ),
                ],
                'default' => 'everyone',
            ]
        );
    }

    public function render() {
        global $post;

        if ( 'sfwd-courses' !== get_post_type( $post ) ) {
            return;
        }

        $settings = $this->get_settings();
        $visibility = $settings['visibility'];

        $course_id = $post->ID;
        $current_user_id = get_current_user_id();
        $course_author_id = get_post_field( 'post_author', $course_id );

        if ( ('enrolled' === $visibility && ! sfwd_lms_has_access( $course_id, $current_user_id )) ||
             ('author' === $visibility && $current_user_id !== $course_author_id) ||
             ('admin' === $visibility && ! current_user_can( 'administrator' )) ) {
            return;
        }

        $user_query_args = array(
            'meta_query' => array(
                array(
                    'key'     => 'course_' . $course_id . '_access_from',
                    'compare' => 'EXISTS'
                )
            )
        );
        $user_query = new \WP_User_Query( $user_query_args );
        $enrolled_users_count = $user_query->get_total();

        if ( $enrolled_users_count > 0 ) {
            echo esc_html( $enrolled_users_count );
        }
    }
}
