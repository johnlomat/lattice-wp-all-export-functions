<?php
/**
 * Plugin Name: Lattice WP All Export Functions
 * Author: John Lomat
 * Author URI: https://johnlomat.vercel.app/
 * Description: A collection of functions specifically designed for the WP All Export Function Editor. These functions are used to manipulate and manage course data and their statuses.
 * Version: 1.0
 */

/**
 * Returns a human-readable order status.
 *
 * @param string $status The order status.
 * @return string Human-readable order status.
 */
function wc_order_status( $status ) {
    if ( 'wc-completed' === $status ) {
        return 'Completed';
    }

    if ( 'wc-processing' === $status ) {
        return 'Processing';
    }

    return $status;
}

/**
 * Returns a human-readable course status.
 *
 * @param string $status The course status.
 * @return string Human-readable course status.
 */
function course_status( $status ) {
    if ( 'completed' === $status ) {
        return 'Completed';
    }

    if ( 'in_progress' === $status ) {
        return 'In-progress';
    }

    if ( 'not_started' === $status ) {
        return 'Not Started';
    }

    return $status;
}

/**
 * Unserializes the provided data.
 *
 * @param string $data The serialized data.
 * @return mixed The unserialized data.
 */
function unserialize_data( $data ) {
    return unserialize( $data );
}

/**
 * Converts a timestamp to a human-readable date format.
 *
 * @param int $timestamp The timestamp to convert.
 * @return string The formatted date string.
 */
function convert_timestamp_to_human_readable( $timestamp ) {
    return date( 'Y-m-d H:i:s', $timestamp );
}

/**
 * Retrieves the users enrolled in a specific course.
 *
 * @param int $course_id The ID of the course.
 * @return string A human-readable list of user emails enrolled in the course.
 */
function get_users_enrolled_by_course( $course_id ) {
    $user_args     = learndash_get_users_for_course( $course_id );
    $user_new_args = (array) $user_args;
    $user_ids      = $user_new_args['query_vars']['include'];

    // Initialize an array to hold user emails
    $user_emails = [];

    // Loop through each user ID to get the user email
    foreach ( $user_ids as $user_id ) {
        $user_info = get_userdata( $user_id );
        if ( $user_info ) {
            $user_emails[] = $user_info->user_email; // Get the email of the user
        }
    }

    // Convert the array of emails into a human-readable list
    if ( ! empty( $user_emails ) ) {
        return implode( ', ', $user_emails ); // Join emails with a comma and space
    }

    return 'No users enrolled in this course.'; // Return a message if no users are found
}

/**
 * Retrieves the total number of users enrolled in a specific course.
 *
 * @param int $course_id The ID of the course.
 * @return int The total number of users enrolled in the course.
 */
function get_total_users_enrolled_by_course( $course_id ) {
    $user_args      = learndash_get_users_for_course( $course_id );
    $user_new_args  = (array) $user_args;
    $user_ids       = $user_new_args['query_vars']['include'];
    $total_enrolled = count( $user_ids );

    return $total_enrolled;
}

function get_user_in_group( $user_id ) {
    $customer_with_subscription = 539;
    $credit_customer            = 1494;
    $lattice_employees          = 1496;
    $channel_premium            = 1502;
    $channel_select             = 1505;

    if ( learndash_is_user_in_group( $user_id, $customer_with_subscription ) ) {
        return 'Customer with Subscription';
    } elseif ( learndash_is_user_in_group( $user_id, $credit_customer ) ) {
        return 'Credit Customer';
    } elseif ( learndash_is_user_in_group( $user_id, $lattice_employees ) ) {
        return 'Lattice Employees';
    } elseif ( learndash_is_user_in_group( $user_id, $channel_premium ) ) {
        return 'Channel: Premium';
    } elseif ( learndash_is_user_in_group( $user_id, $channel_select ) ) {
        return 'Channel: Free';
    }
}

/**
 * Retrieves all courses and their lessons from the serialized data.
 *
 * @param string $data The serialized course data.
 * @return string Formatted string of courses and their lessons.
 */
function get_courses_and_lessons( $data ) {
    // Unserialize the data
    $unserialized_data = unserialize_data( $data );

    $output = '';

    // Loop through each course
    foreach ( $unserialized_data as $course_id => $course_data ) {
        $output .= 'Course: ' . get_course_name( $course_id ) . "\n"; // Get course name

        $output .= "Lessons Progress:\n";

        // Loop through lessons
        foreach ( $course_data['lessons'] as $lesson_id => $status ) {
            $lesson_status = $status ? 'Completed' : 'Not Started';
            $lesson_name   = get_lesson_name( $lesson_id ); // Get lesson name
            $output .= "$lesson_name: $lesson_status\n";
        }

        // Retrieve total lessons from the course data
        $total_lessons = $course_data['total'] ?? 0; // Use null coalescing operator for safety
        $output .= "Total Lessons: $total_lessons\n";

        // Retrieve completed lessons from the course data
        $completed_lessons = $course_data['completed'] ?? 0; // Use null coalescing operator for safety
        $output .= "Completed Lessons: $completed_lessons\n";

        // Retrieve status from the course data and make it human-readable
        $status = course_status( $course_data['status'] ?? '' ); // Use null coalescing operator for safety
        $output .= "Status: $status\n";

        // Example of using the timestamp conversion function
        if ( isset( $course_data['timestamp'] ) ) {
            $formatted_date = convert_timestamp_to_human_readable( $course_data['timestamp'] );
            $output .= "Timestamp: $formatted_date\n";
        }

        $output .= "\n"; // Add a newline for better readability
    }

    return $output;
}

/**
 * Retrieves the course name by course ID.
 *
 * @param int $course_id The course ID.
 * @return string The course name.
 */
function get_course_name( $course_id ) {
    $course = get_post( $course_id );

    return ( $course && ! is_wp_error( $course ) ) ? $course->post_title : 'Unknown Course';
}

/**
 * Retrieves the lesson name by lesson ID.
 *
 * @param int $lesson_id The lesson ID.
 * @return string The lesson name.
 */
function get_lesson_name( $lesson_id ) {
    $lesson = get_post( $lesson_id );

    return ( $lesson && ! is_wp_error( $lesson ) ) ? $lesson->post_title : 'Unknown Lesson';
}
?>
