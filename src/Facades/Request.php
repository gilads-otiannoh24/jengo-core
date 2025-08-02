<?php

namespace Jengo\Facades;

use CodeIgniter\HTTP\Response;

/**
 * Class Request
 *
 * A static proxy for CodeIgniter's IncomingRequest instance, allowing intuitive
 * static access to HTTP request data and metadata using method overloading.
 *
 * This class wraps the global `request()` helper and provides access to all common
 * HTTP input methods such as `getPost()`, `getGet()`, `getJSON()`, `getCookie()`, etc.
 * It simplifies calling request methods in a static context while preserving full
 * IDE autocompletion via PHPDoc annotations.
 *
 * Example:
 *     Request::getPost('email');
 *     Request::getJSON(true);
 *     Request::getHeaderLine('Content-Type');
 *
 * It also includes a static `validate()` method that integrates CodeIgniter's
 * validation service with auto-detection of input sources (JSON or POST),
 * and returns either a boolean or a RedirectResponse with error flashdata.
 *
 * @package Jengo\Facades
 * 
 * @method static mixed getGet(string|null $index = null, int|null $filter = null, $flags = null)
 * @method static mixed getPost(string|null $index = null, int|null $filter = null, $flags = null)
 * @method static mixed getGetPost($index = null, $filter = null, $flags = null)
 * @method static mixed getPostGet($index = null, $filter = null, $flags = null)
 * @method static mixed getVar(string|null $index = null, int|null $filter = null, $flags = null)
 * @method static array getRawInput()
 * @method static array|bool|float|int|stdClass|null getJSON(bool $assoc = false, int $depth = 512, int $options = 0)
 * @method static mixed getJsonVar($index = null, bool $assoc = false, ?int $filter = null, $flags = null)
 * @method static string|null getServer(string $index)
 * @method static string|null getHeaderLine(string $name)
 * @method static string getUserAgent()
 * @method static string getIPAddress()
 * @method static bool isAJAX()
 * @method static bool isSecure()
 * @method static string getMethod(bool $upper = false)
 * @method static bool is(string $type)
 * @method static \CodeIgniter\HTTP\URI getUri()
 * @method static string getBody()
 * @method static string getLocale()
 * @method static string getPath()
 * @method static bool isCLI()
 * @method static string negotiate(string $type, array $supported, bool $strictMatch = false)
 * @method static string setPath(string $path, \Config\App|null $config = null)
 * @method static string getDefaultLocale()
 * @method static \Codeigniter\HTTP\IncomingRequest setValidLocales(array $locales)
 * @method static \Codeigniter\HTTP\IncomingRequest setLocale(string $locale)
 * @method static mixed getRawInputVar($index = null, ?int $filter = null, $flags = null)
 * @method static array|bool|float|int|object|string|null getCookie($index = null, $filter = null, $flags = null)
 * @method static \CodeIgniter\HTTP\Files\UploadedFile|null getFile(string $fileID)
 * @method static array|null getFileMultiple(string $fileID)
 * @method static array getFiles()
 * @method static array|string|null getOldInput(string $key)
 */

class Request
{
    public static function __callStatic($method, $args)
    {
        return request()->$method(...$args);
    }

    public static function validate(array $rules): bool|Response
    {
        /** @var \CodeIgniter\Validation\ValidationInterface */
        $validator = service("validation");
        $request = request();

        $validator->setRules($rules);

        $data = match (true) {
            !empty($request->getJSON(true)) => $request->getJSON(true),
            !empty($request->getPost()) => $request->getPost(),
            default => [],
        };

        $success = $validator->run($data);

        if (!$success) {
            return redirect()
                ->back()
                ->with("errors", $validator->getErrors())
                ->send();
        }

        return $success;
    }
}
