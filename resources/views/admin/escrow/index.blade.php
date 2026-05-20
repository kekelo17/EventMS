@extends('layouts.app')
@section('title','Escrow Management')

@section('content')
<div class="container-fluid py-4">
<div class="row">
{{-- Sidebar --}}
<div class="col-md-2 sidebar">
    <nav class="nav flex-column">
        <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-grid me-2"></i>Dashboard</a>
        <a class="nav-link" href="{{ route('admin.events') }}"><i class="bi bi-calendar-event me-2"></i>Events</a>
        <a class="nav-link" href="{{ route('admin.users') }}"><i class="bi bi-people me-2"></i>Users</a>
        <a class="nav-link active" href="{{ route('admin.escrow') }}"><i class="bi bi-shield-lock me-2"></i>Escrow</a>
        <a class="nav-link" href="{{ route('admin.transactions') }}"><i class="bi bi-arrow-left-right me-2"></i>Transactions</a>
        <a class="nav-link" href="{{ route('admin.refunds') }}">
            <i class="bi bi-arrow-counterclockwise me-2"></i>Refunds
            @php $pr = \App\Models\RefundRequest::where('status','pending')->count() @endphp
            @if($pr)<span class="badge bg-danger ms-1">{{ $pr }}</span>@endif
        </a>
        <a class="nav-link" href="{{ route('admin.withdrawals') }}">
            <i class="bi bi-bank me-2"></i>Withdrawals
            @php $pw = \App\Models\WithdrawalRequest::where('status','pending')->count() @endphp
            @if($pw)<span class="badge bg-danger ms-1">{{ $pw }}</span>@endif
        </a>
    </nav>
</div>

{{-- Content --}}
<div class="col-md-10 px-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-shield-lock me-2 text-primary"></i>Escrow Management</h4>

    {{-- Totals --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card" style="background:linear-gradient(135deg,#f7971e,#ffd200)">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="small opacity-75">Currently Held</div>
                        <div class="fs-4 fw-bold">{{ number_format($totals['held'],2) }} XAF</div>
                    </div>
                    <i class="bi bi-lock-fill fs-2 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="background:linear-gradient(135deg,#28a745,#20c997)">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="small opacity-75">Released to Organisers</div>
                        <div class="fs-4 fw-bold">{{ number_format($totals['released'],2) }} XAF</div>
                    </div>
                    <i class="bi bi-check-circle-fill fs-2 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="background:linear-gradient(135deg,#dc3545,#ff6b6b)">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="small opacity-75">Total Refunded</div>
                        <div class="fs-4 fw-bold">{{ number_format($totals['refunded'],2) }} XAF</div>
                    </div>
                    <i class="bi bi-arrow-counterclockwise fs-2 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Held Payments Table --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-clock me-2"></i>Payments Held in Escrow</span>
            <span class="badge bg-warning text-dark">{{ $held_payments->total() }} held</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ref</th>
                            <th>Event</th>
                            <th>Client</th>
                            <th>Organiser</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($held_payments as $payment)
                        <tr>
                            <td><code class="small">{{ $payment->transaction_ref }}</code></td>
                            <td>
                                <div class="fw-semibold small">{{ Str::limit($payment->ticket->event->title,30) }}</div>
                                <div class="text-muted small">{{ $payment->ticket->event->event_date->format('d M Y') }}</div>
                            </td>
                            <td>
                                <div class="small">{{ $payment->user->name }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ $payment->user->email }}</div>
                            </td>
                            <td>
                                <div class="small">{{ $payment->ticket->event->organiser->name }}</div>
                            </td>
                            <td class="fw-bold text-primary">{{ number_format($payment->amount,2) }} {{ $payment->currency }}</td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $payment->payment_method === 'card' ? '💳 '.$payment->card_brand.' ****'.$payment->card_last4 : '📱 Mobile Money' }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                {{-- Release Modal Trigger --}}
                                <button class="btn btn-sm btn-success me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#releaseModal{{ $payment->id }}">
                                    <i class="bi bi-send"></i> Release
                                </button>

                                {{-- View refund request --}}
                                @if(!$payment->refundRequest)
                                <a href="{{ route('admin.refunds') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @else
                                <a href="{{ route('admin.refunds.show', $payment->refundRequest->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-arrow-counterclockwise"></i> Refund Req
                                </a>
                                @endif
                            </td>
                        </tr>

                        {{-- Release Modal --}}
                        <div class="modal fade" id="releaseModal{{ $payment->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title"><i class="bi bi-send me-2"></i>Release Funds</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.escrow.release', $payment->id) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <p>You are about to release <strong>{{ number_format($payment->amount,2) }} {{ $payment->currency }}</strong> from escrow to:</p>
                                            <div class="alert alert-light">
                                                <strong>Organiser:</strong> {{ $payment->ticket->event->organiser->name }}<br>
                                                <strong>Event:</strong> {{ $payment->ticket->event->title }}<br>
                                                <strong>Ticket Ref:</strong> {{ $payment->ticket->ticket_code }}
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Admin Notes (optional)</label>
                                                <textarea name="notes" class="form-control" rows="2" placeholder="e.g. Event completed successfully"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="bi bi-check-lg me-1"></i>Confirm Release
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No payments currently held in escrow.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($held_payments->hasPages())
        <div class="card-footer">{{ $held_payments->links() }}</div>
        @endif
    </div>

    {{-- Recently Released --}}
    <div class="card mt-4">
        <div class="card-header"><i class="bi bi-check-circle me-2"></i>Recently Released</div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr><th>Ref</th><th>Event</th><th>Organiser</th><th>Amount</th><th>Released At</th></tr>
                </thead>
                <tbody>
                    @forelse($released as $p)
                    <tr>
                        <td><code class="small">{{ $p->transaction_ref }}</code></td>
                        <td class="small">{{ Str::limit($p->ticket->event->title,35) }}</td>
                        <td class="small">{{ $p->ticket->event->organiser->name }}</td>
                        <td class="text-success fw-bold">{{ number_format($p->amount,2) }}</td>
                        <td class="text-muted small">{{ $p->released_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No releases yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>
@endsection
