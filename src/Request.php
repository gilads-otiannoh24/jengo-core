<?php

namespace Jengo;

use CodeIgniter\HTTP\RedirectResponse;

class Request
{
    public static function __callStatic($method, $args)
    {
        return request()->$method(...$args);
    }

    public static function validate(array $rules): bool|RedirectResponse
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
            return redirect()->back()->with("errors", $validator->getErrors());
        }

        return $success;
    }
}
