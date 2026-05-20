@extends('layouts.app')
@section('title','Refund Requests')

@section('content')
<div class="container-fluid py-4">
<div class="row">
<div class="col-md-2 sidebar">
    <nav class="nav flex-column">
        <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-grid me-2"></i>Dashboard</a>
        <a class="nav-link" href="{{ route('admin.events') }}"><i class="bi bi-calendar-event me-2"></i>Events</a>
        <a class="nav-link" href="{{ route('admin.users') }}"><i class="bi bi-people me-2"></i>Users</a>
        <a class="nav-link" href="{{ route('admin.escrow') }}"><i class="bi bi-shield-lock me-2"></i>Escrow</a>
        <a class="nav-link" href="{{ route('admin.transactions') }}"><i class="bi bi-arrow-left-right me-2"></i>Transactions</a>
        <a class="nav-link active" href="{{ route('admin.refunds') }}"><i class="bi bi-arrow-counterclockwise me-2"></i>Refunds</a>
        <a class="nav-link" href="{{ route('admin.withdrawals') }}"><i class="bi bi-bank me-2"></i>Withdrawals</a>
    </nav>
</div>

<div class="col-md-10 px-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-arrow-counterclockwise me-2 text-warning"></i>Refund Requests</h4>

    {{-- Filter Tabs --}}
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.refunds') }}">All</a></li>
        <li class="nav-item"><a class="nav-link {{ request('status')=='pending' ? 'active' : '' }}" href="{{ route('admin.refunds').'?status=pending' }}">
            Pending <span class="badge bg-warning text-dark ms-1">{{ \App\Models\RefundRequest::where('status','pending')->count() }}</span></a></li>
        <li class="nav-item"><a class="nav-link {{ request('status')=='approved' ? 'active' : '' }}" href="{{ route('admin.refunds').'?status=approved' }}">Approved</a></li>
        <li class="nav-item"><a class="nav-link {{ request('status')=='rejected' ? 'active' : '' }}" href="{{ route('admin.refunds').'?status=rejected' }}">Rejected</a></li>
    </ul>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>Client</th><th>Event</th><th>Amount Paid</th>
                        <th>Requested</th><th>Reason</th><th>Status</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($refunds as $refund)
                    <tr>
                        <td class="text-muted small">{{ $refund->id }}</td>
                        <td>
                            <div class="fw-semibold small">{{ $refund->user->name }}</div>
                            <div class="text-muted" style="font-size:.75rem">{{ $refund->user->email }}</div>
                        </td>
                        <td class="small">{{ Str::limit($refund->payment->ticket->event->title, 30) }}</td>
                        <td class="fw-bold">{{ number_format($refund->payment->amount,2) }}</td>
                        <td class="text-danger fw-bold">{{ number_format($refund->amount_requested,2) }}</td>
                        <td><span class="small text-muted">{{ Str::limit($refund->reason, 40) }}</span></td>
                        <td>
                            @php
                                $sc = match($refund->status) { 'pending'=>'badge-pending', 'approved'=>'badge-approved', 'rejected'=>'badge-rejected', default=>'bg-secondary' };
                            @endphp
                            <span class="badge {{ $sc }}">{{ ucfirst($refund->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.refunds.show', $refund->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Review
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No refund requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($refunds->hasPages())
        <div class="card-footer">{{ $refunds->links() }}</div>
        @endif
    </div>
</div>
</div>
</div>
@endsection
