<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action( 'jet-engine/register-macros', function() {

    class LD_Course_Access_Type_Query extends \Jet_Engine_Base_Macros {

        public function macros_tag() {
            return 'ld_course_access_type_query';
        }

        public function macros_name() {
            return 'LD Course Access Type Query';
        }

        public function macros_args() {
            return array(
                'access_type' => array(
                    'label'   => 'Access Type',
                    'type'    => 'select',
                    'options' => array(
                        'open'       => 'Open',
                        'free'       => 'Free',
                        'paynow'     => 'Buy Now',
                        'subscribe'  => 'Subscription',
                        'closed'     => 'Closed'
                    ),
                ),
            );
        }

        public function macros_callback( $args = array() ) {
            $access_type_selected = !empty( $args['access_type'] ) ? $args['access_type'] : '';

            // Használjuk a learndash_get_posts_by_price_type függvényt
            $course_ids = learndash_get_posts_by_price_type( 'sfwd-courses', $access_type_selected );

            return is_array( $course_ids ) ? implode( ',', $course_ids ) : 'No courses found for selected access type';
        }
    }

    new LD_Course_Access_Type_Query();

});
