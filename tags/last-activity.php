<?php
namespace Learndash_Dynamic_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


use Elementor\Core\DynamicTags\Tag;

class Last_Activity extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'last-activity';
    }

    public function get_title() {
        return __( 'Last Activity', 'elementor-pro' );
    }

    public function get_group() {
        return 'learndash-course';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    public function render() {
        global $post;
    
        // Ellenőrizzük, hogy kurzus oldalon vagyunk-e
        if ( 'sfwd-courses' !== get_post_type( $post ) ) {
            echo '';
            return;
        }
    
        $course_id = $post->ID;
        $user_id = get_current_user_id();
    
        if ( empty( $user_id ) ) {
            echo '';
            return;
        }
    
        $args = array(
            'course_id' => $course_id,
            'post_id' => $course_id,
            'user_id'   => $user_id,
            'activity_type' => 'course', // Megadva az activity_type
            'order'     => 'DESC',
            'per_page'  => 1
        );
    
        $activity = learndash_get_user_activity( $args );
    
        if ( !empty( $activity ) ) {
            // Kiválasztjuk a legutóbbi aktivitást
            $last_activity_date = $activity->activity_updated;
    
            if ( !empty( $last_activity_date ) ) {
                // Formázás a WordPress dátum és idő beállításai szerint
                $date_format = apply_filters( 'learndash_date_time_formats', get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
                $formatted_date = date_i18n( $date_format, $last_activity_date );
                echo esc_html( $formatted_date );
            } else {
                echo ''; // Ha nincs aktivitás
            }
        } else {
            echo ''; // Ha nincs aktivitás
        }
    }
    
}
