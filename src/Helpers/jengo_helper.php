<?php
if (!function_exists("getFileVersion")) {
    /**
     * Generates a version string for a file based on the environment.
     * 
     * In a production environment, the version string is derived from the application's release version.
     * In non-production environments, the version string is based on the current timestamp to ensure
     * that the file is not cached by the browser.
     * 
     * @return string The version string prefixed with "?v=".
     */
    function getFileVersion(): string
    {
        return "?v=" . (ENVIRONMENT === 'production' ? config("Jengo")->releaseVersion : time());
    }
}

if (!function_exists('page')) {
    /**
     * Shorthand for getting a jengo page view
     * 
     * @param string $name Name of the page
     * @param array $data Data to be passed to the page
     * @param array $options Options for the page
     * @return string
     */
    function page(string $name, array $data = [], array $options = []): string
    {
        return view("pages/$name", $data, $options);
    }
}

/**
 * Encrypts an id using the provided key
 *
 * @param int|string $id Id to be encrypted
 *
 * @return string
 */
function encrypt(int|string $id): string
{
    $encrypter = service('encrypter');
    $encryptedId = $encrypter->encrypt("$id", ['key' => getenv('encryption.key')]);

    $urlSafeencryptedId = base64_encode($encryptedId);

    return str_replace(['/', '+', '='], ['-', '_', ''], $urlSafeencryptedId);
}

/**
 * Decrypts an encrypted id string from the encryptId function
 *
 * @param string $encryptedId Encrypted id string
 *
 * @return string
 */
function decrypt(string $encryptedId): string|int
{
    $encrypter = service('encrypter');
    // Add padding back if missing
    $padding = strlen($encryptedId) % 4;
    if ($padding > 0) {
        $encryptedId .= str_repeat('=', 4 - $padding);
    }

    $decryptedId = $encrypter->decrypt(base64_decode(str_replace(['-', '_'], ['/', '+'], $encryptedId)), ['key' => getenv('encryption.key')]);

    return $decryptedId;
}
