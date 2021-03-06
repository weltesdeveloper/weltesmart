<?php

define("PBKDF2_HASH_ALGORITHM", "sha256"); //SHA256 HASH ALGORITHM
define("PBKDF2_ITERATIONS", 1000);
define("PBKDF2_SALT_BYTE_SIZE", 24);
define("PBKDF2_HASH_BYTE_SIZE", 24);

define("HASH_SECTIONS", 4);
define("HASH_ALGORITHM_INDEX", 0);
define("HASH_ITERATION_INDEX", 1);
define("HASH_SALT_INDEX", 2);
define("HASH_PBKDF2_INDEX", 3);

// Use this to create hashed password using mcrypt
function create_hash($password) {
    // format: algorithm:iterations:salt:hash
    $salt = base64_encode(mcrypt_create_iv(PBKDF2_SALT_BYTE_SIZE, MCRYPT_DEV_URANDOM));
    return PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $salt . ":" .
            base64_encode(pbkdf2(
                            PBKDF2_HASH_ALGORITHM, $password, $salt, PBKDF2_ITERATIONS, PBKDF2_HASH_BYTE_SIZE, true
    ));
}

// Use this to validate between regular string and hashed password
// Return 1 if matched
function validate_password($password, $correct_hash) {
    $params = explode(":", $correct_hash);
    if (count($params) < HASH_SECTIONS) {
        return false;
    }
    $pbkdf2 = base64_decode($params[HASH_PBKDF2_INDEX]);
    return slow_equals(
            $pbkdf2, pbkdf2(
                    $params[HASH_ALGORITHM_INDEX], $password, $params[HASH_SALT_INDEX], (int) $params[HASH_ITERATION_INDEX], strlen($pbkdf2), true
            )
    );
}

// Compares two strings $a and $b in length-constant time.
function slow_equals($a, $b) {
    $diff = strlen($a) ^ strlen($b);
    for ($i = 0; $i < strlen($a) && $i < strlen($b); $i++) {
        $diff |= ord($a[$i]) ^ ord($b[$i]);
    }
    return $diff === 0;
}

function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false) {
    $algorithm = strtolower($algorithm);
    if (!in_array($algorithm, hash_algos(), true)) {
        trigger_error('PBKDF2 ERROR: Invalid hash algorithm.', E_USER_ERROR);
    }
    if ($count <= 0 || $key_length <= 0) {
        trigger_error('PBKDF2 ERROR: Invalid parameters.', E_USER_ERROR);
    }

    if (function_exists("hash_pbkdf2")) {
        // The output length is in (4-bits) if $raw_output is false!
        if (!$raw_output) {
            $key_length = $key_length * 2;
        }
        return hash_pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output);
    }

    $hashlength = strlen(hash($algorithm, "", true));
    $block_count = ceil($key_length / $hashlength);

    $output = "";
    for ($i = 1; $i <= $block_count; $i++) {
        $last = $salt . pack("N", $i);
        $last = $xorsum = hash_hmac($algorithm, $last, $password, true);
        for ($j = 1; $j < $count; $j++) {
            $xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
        }
        $output .= $xorsum;
    }

    if ($raw_output) {
        return substr($output, 0, $key_length);
    } else {
        return bin2hex(substr($output, 0, $key_length));
    }
}

?>