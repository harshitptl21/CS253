<?php
namespace user;
require_once 'functions.php';
require_once 'database.php';
use functions;

define("USER_TABLE", 'user');

// Table Definition
$user_table_definition = USER_TABLE . "
(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(64) NOT NULL,
    last_name VARCHAR(64) NOT NULL,
    email_address VARCHAR(128) NOT NULL UNIQUE,
    drivers_license_id VARCHAR(64),
    gender BINARY(1) NOT NULL,
    password VARCHAR(64) NOT NULL
)";

/**
 * Encrypts the given password.
 * @param password Input password.
 * @return string the salted and hashed string of the given password.
 */
function encrypt_password($password)
{
    $salt1 = "qm&h*";
    $salt2 = "ez!@";
    return hash('sha256', $salt1 . $password . $salt2);
}

/**
 * Authenticates the user.
 * @param email the email address of the user.
 * @param password the password entered by the user.
 * @return row the associative row of the user if authenticated, NULL otherwise.
 */
function authenticate_user($email, $password)
{
    global $connection;
    $s_email = functions\sanitize_string($email);
    $query = "SELECT * FROM " . USER_TABLE . " WHERE email_address='$s_email'";
    $result = mysqli_query($connection, $query);
    if (!$result)
        die("Database access failed: " . mysqli_error());
    elseif (mysqli_num_rows($result)) {
        $row = mysqli_fetch_assoc($result);
        $input_token = encrypt_password($password);
        if ($row['password'] == $input_token) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email_address'] = $row['email_address'];
            $_SESSION['first_name'] = $row['first_name'];
            return $row;
        } else {
            return NULL;
        }
    }
}

/**
 * Logs out the user by destroying session.
 * @return status LOGGED_OUT if user logged out,
 *         NOT_LOGGED_IN if user is not logged in.
 */
function logout_user()
{
    if (!isset($_SESSION['user_id']))
        return 'NOT_LOGGED_IN';
    $_SESSION = array();
    if (session_id() != "" or isset($_COOKIE[session_name()]))
        setcookie(session_name(), '', time() - 2592000, '/');
    session_destroy();
    return 'LOGGED_OUT';
}

/**
 * Whether the user is logged in.
 * @return boolean whether the user is logged in.
 */
function user_logged_in()
{
    return isset($_SESSION['user_id']);
}

/**
 * Get User user's data if the user is logged in
 * @return if the user is logged in, returns current user's data
 * @return otherwise if the user is not logged in, returns NULL
 */

function get_logged_in_user()
{
    $data_array = array();
    if (isset($_SESSION['user_id'])) {
        $data_array['id'] = $_SESSION['user_id'];
        return $data_array;
    } else {
        return NULL;
    }
}
/**
 * Adds the user to the database.
 * @param data associative array containing all of the user information.
 * @return boolean whether the operation was successful.
 */
function add_user($data)
{
    global $connection;
    $user_table = USER_TABLE;
    $first_name = functions\sanitize_string($data['first_name']);
    $last_name = functions\sanitize_string($data['last_name']);
    $email_address = functions\sanitize_string($data['email_address']);
    $drivers_license_id = functions\sanitize_string($data['drivers_license_id']);
    $gender = (int) $data['gender'];  // Cast directly to int without sanitizing
    $password = encrypt_password($data['password']);

    // Validate gender
    if ($gender !== 0 && $gender !== 1) {
        error_log("Invalid gender value: " . $gender);
        return false;
    }

    $query = "INSERT INTO $user_table (
            first_name,
            last_name,
            email_address,
            drivers_license_id,
            gender,
            password
        ) VALUES (
            '$first_name',
            '$last_name',
            '$email_address',
            '$drivers_license_id',
            $gender,
            '$password')";
    if (mysqli_query($connection, $query))
        return true;
    error_log("Failed to create user: " . mysqli_error($connection));
    return false;
}

/**
 * Gets the user specified by id.
 * @param id the row id of the user.
 * @return row the user row in the database without password, NULL otherwise.
 */
function get_user($id)
{
    global $connection;
    $s_id = functions\sanitize_string($id);
    $user_table = USER_TABLE;
    $query = "SELECT id,
                     first_name, 
                     last_name, 
                     email_address,
                     drivers_license_id,
                     gender 
              FROM  $user_table WHERE id=$s_id";
    $result = mysqli_query($connection, $query);
    if (!$result)
        return NULL;
    elseif (mysqli_num_rows($result))
        return mysqli_fetch_assoc($result);
    return NULL;
}

/**
 * Checks whether the user exists.
 * @param email the email address of the user.
 * @return boolean whether the user exists.
 */
function user_exists($email)
{
    global $connection;
    $s_email = functions\sanitize_string($email);
    $query = "SELECT * FROM " . USER_TABLE . " WHERE email_address='$s_email'";
    if (mysqli_num_rows(mysqli_query($connection, $query)))
        return true;
    else
        return false;
}

/**
 * Updates the user's driving license ID.
 * @param user_id the ID of the user to update
 * @param drivers_license_id the new driving license ID (can be empty)
 * @return boolean whether the operation was successful
 */
function update_drivers_license($user_id, $drivers_license_id)
{
    global $connection;
    $user_table = USER_TABLE;
    $s_user_id = functions\sanitize_string($user_id);
    $s_license = functions\sanitize_string($drivers_license_id);

    $query = "UPDATE $user_table SET drivers_license_id='$s_license' WHERE id=$s_user_id";

    if (mysqli_query($connection, $query)) {
        return true;
    }
    error_log("Failed to update driving license: " . mysqli_error($connection));
    return false;
}

/**
 * Updates the password for a user.
 * @param email the email address of the user
 * @param new_password the new password to set
 * @return bool true if successful, false otherwise
 */
function update_password($email, $new_password)
{
    global $connection;
    $user_table = USER_TABLE;
    $s_email = functions\sanitize_string($email);
    $encrypted_password = encrypt_password($new_password);

    $query = "UPDATE $user_table SET password='$encrypted_password' WHERE email_address='$s_email'";

    if (mysqli_query($connection, $query)) {
        return true;
    }
    error_log("Failed to update password: " . mysqli_error($connection));
    return false;
}
?>
