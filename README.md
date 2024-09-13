# Lattice WP All Export Functions

A collection of functions specifically designed for the WP All Export Function Editor. These functions are used to manipulate and manage course data and their statuses.

## Functions

### wc_order_status( $status )

Returns a human-readable order status.

### course_status( $status )

Returns a human-readable course status.

### unserialize_data( $data )

Unserializes the provided data.

### convert_timestamp_to_human_readable( $timestamp )

Converts a timestamp to a human-readable date format.

### get_users_enrolled_by_course( $course_id )

Retrieves the users enrolled in a specific course.

### get_total_users_enrolled_by_course( $course_id )

Retrieves the total number of users enrolled in a specific course.

### get_user_in_group( $user_id )

Checks if a user is in a specific group and returns the group name.

### get_courses_and_lessons( $data )

Retrieves all courses and their lessons from the serialized data.

### get_course_name( $course_id )

Retrieves the course name by course ID.

### get_lesson_name( $lesson_id )

Retrieves the lesson name by lesson ID.

## Author

John Lomat
[johnlomat.vercel.app](https://johnlomat.vercel.app/)
