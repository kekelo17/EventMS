<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Services\EscrowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminWithdrawalController extends Controller
{
    public function __construct(private EscrowService $escrow) {}

    public function index()
    {
        $withdrawals = WithdrawalRequest::with('organiser','event')->latest()->paginate(20);
        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function approve(Request $request, WithdrawalRequest $withdrawal)
    {
        $request->validate(['admin_note' => 'nullable|string|max:300']);
        try {
            $this->escrow->approveWithdrawal($withdrawal, Auth::id(), $request->admin_note ?? '');
            return back()->with('success', 'Withdrawal approved and marked as paid.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, WithdrawalRequest $withdrawal)
    {
        $request->validate(['admin_note' => 'required|string|max:300']);
        $this->escrow->rejectWithdrawal($withdrawal, Auth::id(), $request->admin_note);
        return back()->with('success', 'Withdrawal rejected.');
    }
}

