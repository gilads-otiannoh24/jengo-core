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
        return "?v=" . (ENVIRONMENT === 'production' ? config("Jengo\Config\Jengo")->releaseVersion : time());
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
