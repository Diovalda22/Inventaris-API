<?php

namespace App\Http\Controllers;

use App\Models\logs;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function index()
    {
        $logs = logs::with('user')->get();
        return response()->json($logs);
    }
}
