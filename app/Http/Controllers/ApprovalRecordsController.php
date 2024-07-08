<?php

namespace App\Http\Controllers;

use App\Models\ChecklistRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalRecordsController extends Controller
{
    public function index(Request $request)
    {
        $approvalRecords = ChecklistRecord::notVerified()->get();

        return view('dashboard.approval_records.index', compact('approvalRecords'));
    }

    public function approveChecklist(Request $request)
    {
        $checklistRecordIds = (array) $request->checklist_record_id;

        $checklistRecords = ChecklistRecord::whereIn('id', $checklistRecordIds)->get();
        $oldChecklistRecords = $checklistRecords->toArray();

        ChecklistRecord::whereIn('id', $checklistRecordIds)->update([
            'status_verif' => 'verified',
            'updated_at' => now(),
        ]);

        // Reload the updated records
        $newChecklistRecords = ChecklistRecord::whereIn('id', $checklistRecordIds)->get()->toArray();

        activity('persetujuan_pengecekan')
            ->withProperties([
                'old' => $oldChecklistRecords,
                'new' => $newChecklistRecords,
                'changes' => $newChecklistRecords,
            ])
            ->causedBy(auth()->user())
            ->log('Menyetujui pengecekan item P3K');

        return redirect()->route('approval-records.index');
    }
}
