<?php
	// All custom validations can be added in here as functions
	// Validations.php is then to be included in HTML so functions can be used

	// Check if value is empty or not
	function has_presence($value) {
		return isset($value) && trim($value) !== "";
	}

	// Check minimum length: if length of value is above min
	function validate_min_length($value, $min) {
		return strlen($value) > $min;
	}

	// Build error messages
	function form_errors($errors=array()) {
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
?>