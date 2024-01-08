<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


use Elementor\Core\DynamicTags\Tag;
use Elementor\Controls_Manager;

class Course_Prerequisites_List extends Tag {

    public function get_name() {
        return 'course-prerequisites-list';
    }

    public function get_title() {
        return __( 'Course Prerequisites List', 'learndash_dynamic_tag' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    protected function _register_controls() {

        $this->add_control(
            'list_format',
            [
                'label' => __( 'List Format', 'learndash_dynamic_tag' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'list' => __( 'List', 'learndash_dynamic_tag' ),
                    'inline' => __( 'Inline', 'learndash_dynamic_tag' ),
                ],
                'default' => 'list',
            ]
        );

        $this->add_control(
            'linkable',
            [
                'label' => __( 'Linkable', 'learndash_dynamic_tag' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Yes', 'learndash_dynamic_tag' ),
                'label_off' => __( 'No', 'learndash_dynamic_tag' ),
                'return_value' => 'yes',
            ]
        );
    }

   public function render() {
    global $post;

    if ('sfwd-courses' !== get_post_type($post)) {
        return;
    }

    $course_id = $post->ID;
    $prerequisites = learndash_get_course_prerequisite($course_id);
    $settings = $this->get_settings();

    if (is_array($prerequisites) && !empty($prerequisites)) {
        if ('inline' === $settings['list_format']) {
            $titles = [];
            foreach ($prerequisites as $prerequisite_id) {
                if (get_post_type($prerequisite_id) == 'sfwd-courses') {
                    $prerequisite_title = get_the_title($prerequisite_id);
                    $prerequisite_link = get_permalink($prerequisite_id);

                    $titles[] = ('yes' === $settings['linkable']) ?
                        '<a href="' . esc_url($prerequisite_link) . '" target="_blank">' . esc_html($prerequisite_title) . '</a>' :
                        esc_html($prerequisite_title);
                }
            }
            echo implode(', ', $titles);
        } else {
            echo '<ul>';
            foreach ($prerequisites as $prerequisite_id) {
                if (get_post_type($prerequisite_id) == 'sfwd-courses') {
                    $prerequisite_title = get_the_title($prerequisite_id);
                    $prerequisite_link = get_permalink($prerequisite_id);

                    echo '<li>' . ('yes' === $settings['linkable'] ?
                        '<a href="' . esc_url($prerequisite_link) . '" target="_blank">' . esc_html($prerequisite_title) . '</a>' :
                        esc_html($prerequisite_title)) . '</li>';
                }
            }
            echo '</ul>';
        }
    }
}

}
