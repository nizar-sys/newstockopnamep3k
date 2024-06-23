<?php

namespace App\Http\Controllers;

use App\Models\ChecklistRecord;
use Illuminate\Http\Request;

class ApprovalRecordsController extends Controller
{
    public function index(Request $request)
    {
        $approvalRecords = ChecklistRecord::notVerified()->get();

        return view('dashboard.approval_records.index', compact('approvalRecords'));
    }

    public function approveChecklist(Request $request)
    {
        if(is_array($request->checklist_record_id)){
            ChecklistRecord::whereIn('id', $request->checklist_record_id)->update([
                'status_verif' => 'verified',
                'updated_at' => now(),
            ]);
        } else {
            $checklistRecord = ChecklistRecord::findOrFail($request->checklist_record_id);
            $checklistRecord->update([
                'status_verif' => 'verified',
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('approval-records.index');
    }
}
