<?php
    // All custom validations can be added in here as functions
    // Validations.php is then to be included in HTML so functions can be used

$errors = array();

    // Check if value is empty or not
function has_presence($value)
{
    return isset($value) && trim($value) !== "";
}

function validate_presences($fields_required)
{
	global $errors;
    foreach ($fields_required as $field) {
            $value = $_POST[$field];
        if (!has_presence($value)) {
            $errors[$field] = fieldname_as_text($field) . " can't be blank";
        }
    }
}

    // Check minimum length: if length of value is above min
function has_min_length($value, $min)
{
    return strlen($value) > $min;
}

function validate_min_length($fields_min_length, $min_length)
{
    global $errors;
    foreach ($fields_min_length as $field) {
            $value = $_POST[$field];
        if (!has_min_length($value, $min_length)) {
            $errors[$field] = fieldname_as_text($field) . " is too short. Minimum of " . $min_length . " characters are required.";
        }
    }
}

function validate_special_chars($fields_checked) {
    global $errors;
    foreach ($fields_checked as $field) {
        $value = $_POST[$field];
        if(preg_match('/[^A-Za-z0-9\-\s]/', $value)) {
            $errors[$field] = fieldname_as_text($field) . " contains special characters. Special characters not allowed.";
        }
    }
}
    
    // Build error messages
function form_errors($errors = array())
{
    $output = "";
    if (!empty($errors)) {
        $output .= "<div> Please fix the following errors:";
        $output .= "<ul>";
        foreach ($errors as $key => $error) {
            $output .= "<li>{$error}</li>";
        }
        $output .= "</ul>";
        $output .= "</div>";
    }
    return $output;
}

    // Format text
function fieldname_as_text($fieldname)
{
    $fieldname = str_replace("_", " ", $fieldname);
    $fieldname = ucfirst($fieldname);
    return $fieldname;
}
function regex_clean($string) {
    $clean_string = str_replace(' ', '-', $string);
    $clean_string = preg_replace('/[^A-Za-z0-9\-]/', '', $clean_string);
    $clean_string = str_replace('-', '|', $clean_string);
    $clean_string = preg_replace('/-+/', '-', $clean_string); // Replaces multiple hyphens with single one.
    return $clean_string;
}

function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}
