<?php
session_start();
require_once 'functions/user.php';
require_once 'functions/database.php';
require_once 'functions/functions.php';

// Check if user is logged in
if (!user\user_logged_in()) {
    functions\json_respond('ERROR', 'Login required!');
    exit;
}

// Get logged in user's ID
$user_id = $_SESSION['user_id'];

if ($_GET) {
    $data = json_decode($_GET['data'], true);
    $method = $_GET['method'];

    if ($method === 'get_profile') {
        // Get user data from database
        $user = user\get_user($user_id);

        if ($user) {
            functions\json_respond('OK', 'Profile data retrieved successfully', ['user' => $user]);
        } else {
            functions\json_respond('ERROR', 'Failed to retrieve profile data');
        }
    } else {
        functions\json_respond('ERROR', 'Unknown method!');
    }
    exit;
} elseif ($_POST) {
    $data = json_decode($_POST['data'], true);
    $method = $_POST['method'];

    if ($method === 'update_profile') {
        // Get the driving license ID from form data
        $drivers_license_id = $data['drivers_license_id'];

        // Update the driving license
        if (user\update_drivers_license($user_id, $drivers_license_id)) {
            functions\json_respond('OK', 'Profile updated successfully');
        } else {
            functions\json_respond('ERROR', 'Failed to update profile');
        }
    } elseif ($method === 'change_password') {
        $current_password = $data['current_password'];
        $new_password = $data['new_password'];
        $confirm_password = $data['confirm_password'];

        // Verify current password
        if (!user\authenticate_user($_SESSION['email_address'], $current_password)) {
            functions\json_respond('ERROR', 'Current password is incorrect');
            exit;
        }

        // Verify new password matches confirmation
        if ($new_password !== $confirm_password) {
            functions\json_respond('ERROR', 'New passwords do not match');
            exit;
        }

        // Update password
        if (user\update_password($user_id, $new_password)) {
            functions\json_respond('OK', 'Password updated successfully');
        } else {
            functions\json_respond('ERROR', 'Failed to update password');
        }
    } else {
        functions\json_respond('ERROR', 'Unknown method!');
    }
    exit;
}

// If we get here, the request method is not supported
functions\json_respond('ERROR', 'Unsupported request method');
?>