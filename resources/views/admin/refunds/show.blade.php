@extends('layouts.app')
@section('title','Review Refund #{{ $refund->id }}')

@section('content')
<div class="container py-4" style="max-width:860px">
    <a href="{{ route('admin.refunds') }}" class="btn btn-outline-secondary btn-sm mb-4">
        <i class="bi bi-arrow-left me-1"></i>Back to Refunds
    </a>

    <h4 class="fw-bold mb-4">
        <i class="bi bi-arrow-counterclockwise me-2 text-warning"></i>Refund Request #{{ $refund->id }}
        @php
            $sc = match($refund->status) { 'pending'=>'badge-pending', 'approved'=>'badge-approved', 'rejected'=>'badge-rejected', default=>'bg-secondary' };
        @endphp
        <span class="badge {{ $sc }} ms-2">{{ ucfirst($refund->status) }}</span>
    </h4>

    <div class="row g-4">
        {{-- Left: Details --}}
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-header">Client Information</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Name:</strong> {{ $refund->user->name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $refund->user->email }}</p>
                    <p class="mb-0"><strong>Phone:</strong> {{ $refund->user->phone ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Ticket & Event</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Event:</strong> {{ $refund->payment->ticket->event->title }}</p>
                    <p class="mb-1"><strong>Event Date:</strong> {{ $refund->payment->ticket->event->event_date->format('d M Y H:i') }}</p>
                    <p class="mb-1"><strong>Ticket Code:</strong> <code>{{ $refund->payment->ticket->ticket_code }}</code></p>
                    <p class="mb-1"><strong>Qty:</strong> {{ $refund->payment->ticket->quantity }}</p>
                    <p class="mb-0"><strong>Ticket Status:</strong> {{ ucfirst($refund->payment->ticket->status) }}</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Payment</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Ref:</strong> <code>{{ $refund->payment->transaction_ref }}</code></p>
                    <p class="mb-1"><strong>Amount Paid:</strong> <span class="text-primary fw-bold">{{ number_format($refund->payment->amount,2) }} {{ $refund->payment->currency }}</span></p>
                    <p class="mb-1"><strong>Method:</strong>
                        {{ $refund->payment->payment_method === 'card'
                            ? '💳 '.$refund->payment->card_brand.' ****'.$refund->payment->card_last4
                            : '📱 Mobile Money' }}
                    </p>
                    <p class="mb-0"><strong>Escrow Status:</strong>
                        <span class="badge {{ 'badge-'.str_replace('_to_organiser','d',str_replace('refunded','refunded',str_replace('held','held',$refund->payment->escrow_status))) }}">
                            {{ str_replace('_',' ', $refund->payment->escrow_status) }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Reason for Refund</div>
                <div class="card-body">
                    <p class="mb-1 text-muted">{{ $refund->reason }}</p>
                    <p class="mb-0 small text-muted">Requested: {{ $refund->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Right: Admin Action --}}
        <div class="col-md-5">
            @if($refund->status === 'pending')
            {{-- APPROVE --}}
            <div class="card border-success mb-3">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-check-circle me-2"></i>Approve Refund
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.refunds.approve', $refund->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Refund Amount</label>
                            <div class="input-group">
                                <input type="number" name="refund_amount" class="form-control"
                                       value="{{ $refund->amount_requested }}"
                                       min="0.01" max="{{ $refund->amount_requested }}" step="0.01">
                                <span class="input-group-text">{{ $refund->payment->currency }}</span>
                            </div>
                            <div class="form-text">Max: {{ number_format($refund->amount_requested,2) }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Admin Note</label>
                            <textarea name="admin_note" class="form-control" rows="3"
                                      placeholder="Optional note to client…"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Confirm refund approval?')">
                                <i class="bi bi-check-lg me-1"></i>Approve & Process Refund
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- REJECT --}}
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <i class="bi bi-x-circle me-2"></i>Reject Refund
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.refunds.reject', $refund->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea name="admin_note" class="form-control" rows="3"
                                      placeholder="Explain why the refund is rejected…" required></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Reject this refund request?')">
                                <i class="bi bi-x-lg me-1"></i>Reject Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @else
            {{-- Already Processed --}}
            <div class="card">
                <div class="card-header">Processing Details</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Status:</strong> <span class="badge {{ $sc }}">{{ ucfirst($refund->status) }}</span></p>
                    <p class="mb-1"><strong>Processed by:</strong> {{ $refund->processedBy?->name ?? 'System' }}</p>
                    <p class="mb-1"><strong>Processed at:</strong> {{ $refund->processed_at?->format('d M Y H:i') }}</p>
                    @if($refund->admin_note)
                    <p class="mb-0"><strong>Note:</strong> {{ $refund->admin_note }}</p>
                    @endif
                    @if($refund->status === 'approved')
                    <p class="mb-0 mt-2 text-success fw-bold">
                        Refunded: {{ number_format($refund->payment->refund_amount,2) }} {{ $refund->payment->currency }}
                    </p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
