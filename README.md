# Dynamic-Learndash-for-Elementor-and-JetEngine
Build a better frontend for Learndash with Elementor and / or JetEngine

Dependencies of the plugin: learndash, Elementor pro
If the plugins are not available, you cannot use them.

**Descriptions will be available in English and Hungarian.**

**A leírások magyarul és angolul is megtalálható.**

## EN VERSION

## Instructions

* Recommended php version 8.0 and above
* Recommended elementor version: 3.18 and above
* Recommended Learndash version 4.10 and above

### How to download?

In the right section the green button: **<>Code**  click, and in the dropdown menu, select the **Download ZIP** option.

## What is this plugin do?

**Dynamic learndash for Elementor** brings together LearnDash and elementor with a completely different approach.

It does not contain any unnecessary features, only purely those that are necessary and useful to dynamically customize course templates without any restrictions. Currently it does not contain any retrievable data for Lessons, Topics, and Groups (single template). This will be added in the future, based on user demand.

Those who use jetEngine will have even more freedom, as new macro and dynamic visibility integrations are provided by the plugin.

> Here on github in the [wikipedia section you can find more information](https://github.com/Lonsdale201/Dynamic-Learndash-for-Elementor/wiki) about what the plugin offers.

## FAQ

**Do I need the Elementor integration plugin to work properly?**

*No, technically it is not required. Elementor pro alone is enough, as you can create templates natively. The few widgets that the integration plugin provides can be replaced by our plugin, By retrieving the data through the dynamic tags. So there are no compromises.*

**Where can I report a problem, bug?**

*Navigate to the issue section, click the green New issue button, then select whether you want to report a bug or have a feature request.*

**Is it possible to use the Learndash Elementor intergation plugin and this together?**

*Of course, but unnecessary. In Elementor, you can retrieve templates natively (course, lesson, etc). The advantage of the integration is that it gives you your own widgets (just a few basics), these are for simple general needs.
*Our plugin does not add any new ones. Instead, you can retrieve most of the course data information using dynamic tags, so you can put together any style of layout and design.*

### Changelog

## V0.3 - 2024.01.10

* Refactored some dynamic Tags:

- Course Access type
  - Removed Visibility option
  - Added new custom format
  - Added special Cross-enrolled text
- Lessons Number
  - New Completed lessons number output
  - Now both text and number category available for this tag
- Last Activity
  - New format select option (default, or custom date format)

## V0.2 - 2024.01.08

**New Dynamic tags:**

Learndash global (category)

* User Achieved Certificates Count

Learndash Course (category)

* Certificates Link
* - for button items and, you can set the link to the certificate returned by Learndash. This way they can access the certificate. Same as if you use the built-in shortcode.

**New JetEngine Dynamic Visibility modul**

* Course Certificates Available
* - You can set items to only show or hide (depending on your submissions) if the certificate is available to the user. (this is also only available in the course single template)



**Tweak already existing Dynamic tags:**

* User Available Courses Count
* User Completed Courses Count
* User Course Points

All these tags now can use both Text and Number category inputs.

* Course Prerequisites List  -> New selectable output formatting inside the tags: List, or Inline


## V0.1 initial release - 2024.01.07
