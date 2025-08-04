<?php


namespace Tests\Support\Controllers;

use App\Controllers\BaseController;
use Jengo\Core\Facades\Request;

class TestController extends BaseController
{
    public function index()
    {
        Request::validate([
            'name' => 'required|string',
            'age' => 'required|integer',
        ]);

        return response()->setJSON([
            'name' => Request::input("name"),
        ]);
    }
}