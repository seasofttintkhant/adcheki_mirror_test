<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index');
    }

    public function serverResources()
    {
        $serverResources = shell_exec('top -n 20 -l 1');
        return view('admin.partials.dashboard._server_resources', compact('serverResources'));
    }
}
