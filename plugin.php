<?php
// plugin.php
namespace Learndash_Dynamic_Tag;


if (!defined('ABSPATH')) {
    die();
}

class Plugin {
    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action('init', array($this, 'init'));
    }
	
    public function init() {

        if (class_exists('Jet_Engine')) {
            // JetEngine macros
            include_once('jetengine-macros/user-puchased-courses.php');
            include_once('jetengine-macros/macro-course-access-types.php');
			include_once('jetengine-macros/course-prerequisites.php');

            // Dynamic Visibility modul
            if ($this->is_dynamic_visibility_module_active()) {
                include_once('dynamic-visibility/enrolled-user.php');
                include_once('dynamic-visibility/course-not-purchased.php');
                include_once('dynamic-visibility/completed-course.php');
                include_once('dynamic-visibility/student-limit.php');
                include_once('dynamic-visibility/not-enough-points.php');
                include_once('dynamic-visibility/current-lesson-finished.php');
                include_once('dynamic-visibility/user-course-access.php');
                include_once('dynamic-visibility/course-access-type.php');
            }
        }

		include_once('tags/dynamic-tags-manager.php');
    }

    private function is_dynamic_visibility_module_active() {
        $is_active = function_exists('jet_engine') && jet_engine()->modules->is_module_active('dynamic-visibility');
        return $is_active;
    }
}

if (!function_exists('Plugin')) {
    function Plugin() {
        return Plugin::instance();
    }
}

Plugin();
