<?php
class ValidationService {
    public static function validateEmail($email) {
        if (empty($email)) {
            return ['valid' => false, 'error' => 'Email is required'];
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['valid' => false, 'error' => 'Invalid email format'];
        }
        
        return ['valid' => true, 'error' => ''];
    }

    public static function validatePassword($password) {
        if (empty($password)) {
            return ['valid' => false, 'error' => 'Password is required'];
        }
        
        if (strlen($password) < 6) {
            return ['valid' => false, 'error' => 'Password must be at least 6 characters long'];
        }
        
        return ['valid' => true, 'error' => ''];
    }
    public static function validateJMBG($jmbg) {
        if (empty($jmbg)) {
            return ['valid' => false, 'error' => 'JMBG is required'];
        }
        
        if (!preg_match('/^\d{13}$/', $jmbg)) {
            return ['valid' => false, 'error' => 'JMBG must be exactly 13 digits'];
        }
        
        return ['valid' => true, 'error' => ''];
    }

    public static function validateFullName($name) {
        if (empty($name) || trim($name) === '') {
            return ['valid' => false, 'error' => 'Full name is required'];
        }
        
        if (strlen(trim($name)) < 2) {
            return ['valid' => false, 'error' => 'Full name must be at least 2 characters long'];
        }
        
        return ['valid' => true, 'error' => ''];
    }

    public static function validateRequired($value, $fieldName) {
        if (empty($value) && $value !== '0') {
            return ['valid' => false, 'error' => "$fieldName is required"];
        }
        
        return ['valid' => true, 'error' => ''];
    }

    public static function validateUserRegistration($data) {
        $errors = [];

        // Validate full name
        $nameValidation = self::validateFullName($data['full_name'] ?? '');
        if (!$nameValidation['valid']) {
            $errors[] = $nameValidation['error'];
        }

        // Validate JMBG
        $jmbgValidation = self::validateJMBG($data['jmbg'] ?? '');
        if (!$jmbgValidation['valid']) {
            $errors[] = $jmbgValidation['error'];
        }

        // Validate email
        $emailValidation = self::validateEmail($data['email'] ?? '');
        if (!$emailValidation['valid']) {
            $errors[] = $emailValidation['error'];
        }

        // Validate password
        $passwordValidation = self::validatePassword($data['password'] ?? '');
        if (!$passwordValidation['valid']) {
            $errors[] = $passwordValidation['error'];
        }

        return [
            'valid' => count($errors) === 0,
            'errors' => $errors
        ];
    }

    public static function validateUserLogin($data) {
        $errors = [];

        // Validate email
        $emailValidation = self::validateEmail($data['email'] ?? '');
        if (!$emailValidation['valid']) {
            $errors[] = $emailValidation['error'];
        }

        // Validate password
        $passwordValidation = self::validateRequired($data['password'] ?? '', 'Password');
        if (!$passwordValidation['valid']) {
            $errors[] = $passwordValidation['error'];
        }

        return [
            'valid' => count($errors) === 0,
            'errors' => $errors
        ];
    }

    public static function validateCandidate($data) {
        $errors = [];

        // Validate full name
        $nameValidation = self::validateFullName($data['full_name'] ?? '');
        if (!$nameValidation['valid']) {
            $errors[] = $nameValidation['error'];
        }

        // Validate party_id
        if (!isset($data['party_id']) || !is_numeric($data['party_id'])) {
            $errors[] = 'Valid party ID is required';
        }

        // Validate position
        $positionValidation = self::validateRequired($data['position'] ?? '', 'Position');
        if (!$positionValidation['valid']) {
            $errors[] = $positionValidation['error'];
        }

        return [
            'valid' => count($errors) === 0,
            'errors' => $errors
        ];
    }

    public static function validateParty($data) {
        $errors = [];

        // Validate party name
        $nameValidation = self::validateRequired($data['party_name'] ?? '', 'Party name');
        if (!$nameValidation['valid']) {
            $errors[] = $nameValidation['error'];
        }

        return [
            'valid' => count($errors) === 0,
            'errors' => $errors
        ];
    }

    public static function validateVote($data) {
        $errors = [];

        // Validate candidate_id
        if (!isset($data['candidate_id']) || !is_numeric($data['candidate_id'])) {
            $errors[] = 'Valid candidate ID is required';
        }

        return [
            'valid' => count($errors) === 0,
            'errors' => $errors
        ];
    }

    public static function validateContactMessage($data) {
        $errors = [];

        // Validate name
        $nameValidation = self::validateRequired($data['name'] ?? '', 'Name');
        if (!$nameValidation['valid']) {
            $errors[] = $nameValidation['error'];
        }

        // Validate email
        $emailValidation = self::validateEmail($data['email'] ?? '');
        if (!$emailValidation['valid']) {
            $errors[] = $emailValidation['error'];
        }

        // Validate message
        $messageValidation = self::validateRequired($data['message'] ?? '', 'Message');
        if (!$messageValidation['valid']) {
            $errors[] = $messageValidation['error'];
        } else if (strlen(trim($data['message'])) < 10) {
            $errors[] = 'Message must be at least 10 characters long';
        }

        return [
            'valid' => count($errors) === 0,
            'errors' => $errors
        ];
    }

    public static function sanitizeInput($input) {
        if (!is_string($input)) {
            return $input;
        }
        
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    public static function sanitizeData($data) {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = self::sanitizeInput($value);
            } else if (is_array($value)) {
                $sanitized[$key] = self::sanitizeData($value);
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }
}
?>