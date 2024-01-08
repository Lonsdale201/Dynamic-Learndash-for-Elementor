<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// user-purchased-courses.php
add_action( 'jet-engine/register-macros', function() {

    class LD_User_Courses_Access extends \Jet_Engine_Base_Macros {

        public function macros_tag() {
            return 'ld_user_courses_access';
        }

        public function macros_name() {
            return 'LD User Courses Access';
        }

       
        public function macros_args() {
            return array();
        }

        public function macros_callback( $args = array() ) {
            $user_id = get_current_user_id();

            if ( !$user_id ) {
                return 'User is not logged in';
            }

           
            $courses = ld_get_mycourses($user_id);

            if ( empty($courses) ) {
                return 'User has no course access';
            }

            
            return implode( ',', $courses );
        }
    }

    new LD_User_Courses_Access();

} );
