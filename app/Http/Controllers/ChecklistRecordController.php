<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChecklistRecordController extends Controller
{
    public function index()
    {
        return view('dashboard.checklist_records.index');
    }
}
