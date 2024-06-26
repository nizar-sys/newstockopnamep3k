<?php

namespace App\Http\Controllers;

use App\Models\ChecklistRecord;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function dashboard()
    {
        $data = [
            'count_user' => User::count(),
            'count_room' => Room::count(),
            'count_approval' => ChecklistRecord::notVerified()->count(),
        ];

        return view('dashboard.index', compact('data'));
    }
}
