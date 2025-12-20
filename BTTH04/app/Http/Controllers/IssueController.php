<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issue;
use App\Models\Computer;


class IssueController extends Controller
{
    public function index()
    {
        $issues = Issue::with('computer')->paginate(10);
        return view('issues.index', compact('issues'));
    }

    public function create(){
        $computers = Computer::all();
        return view('issues.create', compact('computers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'computer_id' => 'required|exists:computers,id',
            'reported_by' => 'nullable|string|max:50',
            'description' => 'required|string',
            'urgency' => 'required|in:Low,Medium,High',
        ]);

        Issue::create([
            'computer_id' => $validated['computer_id'],
            'reported_by' => $validated['reported_by'] ?? null,
            'reported_date' => now(),
            'description' => $validated['description'],
            'urgency' => $validated['urgency'],
            'status' => 'Open',
        ]);

        return redirect()->route('issues.index')
                        ->with('success', 'Thêm sự cố mới thành công!');
    }

    public function edit(Issue $issue)
    {
        $computers = Computer::all();
        return view('issues.edit', compact('issue', 'computers'));
    }

    public function update(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'computer_id' => 'required|exists:computers,id',
            'reported_by' => 'nullable|string|max:50',
            'reported_date' => 'required|date',
            'description' => 'required|string',
            'urgency' => 'required|in:Low,Medium,High',
            'status' => 'required|in:Open,In Progress,Resolved',
        ]);

        $issue->update($validated);
        
        return redirect()->route('issues.index')
                        ->with('success', 'Cập nhật sự cố thành công!');
    }

    public function destroy(Issue $issue)
    {
        $issue->delete();
        
        return redirect()->route('issues.index')
                        ->with('success', 'Xóa sự cố thành công!');
    }
}
