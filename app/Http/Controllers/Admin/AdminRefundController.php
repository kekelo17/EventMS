<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use App\Services\EscrowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRefundController extends Controller
{
    public function __construct(private EscrowService $escrow) {}

    public function index()
    {
        $refunds = RefundRequest::with(['user','payment.ticket.event'])->latest()->paginate(20);
        return view('admin.refunds.index', compact('refunds'));
    }

    public function show(RefundRequest $refund)
    {
        $refund->load('user','payment.ticket.event','processedBy');
        return view('admin.refunds.show', compact('refund'));
    }

    public function approve(Request $request, RefundRequest $refund)
    {
        $request->validate([
            'refund_amount' => 'nullable|numeric|min:0.01|max:'.$refund->amount_requested,
            'admin_note'    => 'nullable|string|max:500',
        ]);

        $refund->admin_note = $request->admin_note;
        $refund->save();

        try {
            $this->escrow->refundToClient($refund, Auth::id(), $request->refund_amount ?? null);
            return back()->with('success', 'Refund approved and processed.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, RefundRequest $refund)
    {
        $request->validate(['admin_note' => 'required|string|max:500']);
        $this->escrow->rejectRefund($refund, Auth::id(), $request->admin_note);
        return back()->with('success', 'Refund rejected.');
    }
}
