<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $activityLogs = Activity::orderByDesc('id')->get();

        return view('dashboard.activity_logs.index', compact('activityLogs'));
    }

    public function show(Activity $activityLog)
    {
        return view('dashboard.activity_logs.show', compact('activityLog'));
    }
}
