@extends('layouts.app')
@section('title','Admin Dashboard')

@section('content')
<div class="container-fluid py-4">
<div class="row">
{{-- Sidebar --}}
<div class="col-md-2 sidebar">
    <div class="px-3 mb-3"><small class="text-muted text-uppercase fw-bold">Admin Panel</small></div>
    <nav class="nav flex-column">
        <a class="nav-link active" href="{{ route('admin.dashboard') }}"><i class="bi bi-grid me-2"></i>Dashboard</a>
        <a class="nav-link" href="{{ route('admin.events') }}">
            <i class="bi bi-calendar-event me-2"></i>Events
            @if($stats['pending_events'] > 0)<span class="badge bg-warning text-dark ms-1">{{ $stats['pending_events'] }}</span>@endif
        </a>
        <a class="nav-link" href="{{ route('admin.users') }}"><i class="bi bi-people me-2"></i>Users</a>
        <a class="nav-link" href="{{ route('admin.escrow') }}"><i class="bi bi-shield-lock me-2"></i>Escrow</a>
        <a class="nav-link" href="{{ route('admin.transactions') }}"><i class="bi bi-arrow-left-right me-2"></i>Transactions</a>
        <a class="nav-link" href="{{ route('admin.refunds') }}">
            <i class="bi bi-arrow-counterclockwise me-2"></i>Refunds
            @if($stats['pending_refunds'] > 0)<span class="badge bg-danger ms-1">{{ $stats['pending_refunds'] }}</span>@endif
        </a>
        <a class="nav-link" href="{{ route('admin.withdrawals') }}">
            <i class="bi bi-bank me-2"></i>Withdrawals
            @if($stats['pending_withdrawals'] > 0)<span class="badge bg-danger ms-1">{{ $stats['pending_withdrawals'] }}</span>@endif
        </a>
    </nav>
</div>

{{-- Main Content --}}
<div class="col-md-10 px-4">
    <h4 class="fw-bold mb-4">
        <i class="bi bi-shield-check me-2 text-primary"></i>Admin Dashboard
        <span class="text-muted fs-6 fw-normal ms-2">{{ now()->format('l, d M Y') }}</span>
    </h4>

    {{-- Stats Row 1 --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#5c2d91,#7b4db5)">
                <div class="small opacity-75">Total Users</div>
                <div class="fs-3 fw-bold">{{ number_format($stats['total_users']) }}</div>
                <i class="bi bi-people-fill" style="opacity:.3;font-size:2rem;position:absolute;right:1rem;bottom:.5rem"></i>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#0d6efd,#0dcaf0)">
                <div class="small opacity-75">Total Events</div>
                <div class="fs-3 fw-bold">{{ $stats['total_events'] }}</div>
                <i class="bi bi-calendar3" style="opacity:.3;font-size:2rem;position:absolute;right:1rem;bottom:.5rem"></i>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#f7971e,#ffd200)">
                <div class="small opacity-75">In Escrow (XAF)</div>
                <div class="fs-5 fw-bold">{{ number_format($stats['total_held']) }}</div>
                <i class="bi bi-lock-fill" style="opacity:.3;font-size:2rem;position:absolute;right:1rem;bottom:.5rem"></i>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#28a745,#20c997)">
                <div class="small opacity-75">Total Revenue (XAF)</div>
                <div class="fs-5 fw-bold">{{ number_format($stats['total_revenue']) }}</div>
                <i class="bi bi-graph-up-arrow" style="opacity:.3;font-size:2rem;position:absolute;right:1rem;bottom:.5rem"></i>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row g-3 mb-4">
        @if($stats['pending_events'] > 0)
        <div class="col-auto">
            <a href="{{ route('admin.events').'?status=pending' }}" class="btn btn-warning">
                <i class="bi bi-hourglass-split me-1"></i>{{ $stats['pending_events'] }} Events Pending Approval
            </a>
        </div>
        @endif
        @if($stats['pending_refunds'] > 0)
        <div class="col-auto">
            <a href="{{ route('admin.refunds').'?status=pending' }}" class="btn btn-danger">
                <i class="bi bi-arrow-counterclockwise me-1"></i>{{ $stats['pending_refunds'] }} Refund Requests
            </a>
        </div>
        @endif
        @if($stats['pending_withdrawals'] > 0)
        <div class="col-auto">
            <a href="{{ route('admin.withdrawals').'?status=pending' }}" class="btn btn-info text-white">
                <i class="bi bi-bank me-1"></i>{{ $stats['pending_withdrawals'] }} Withdrawal Requests
            </a>
        </div>
        @endif
        @if($stats['total_held'] > 0)
        <div class="col-auto">
            <a href="{{ route('admin.escrow') }}" class="btn btn-primary">
                <i class="bi bi-shield-lock me-1"></i>Manage Escrow ({{ number_format($stats['total_held']) }} XAF held)
            </a>
        </div>
        @endif
    </div>

    <div class="row g-4">
        {{-- Recent Transactions --}}
        <div class="col-md-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span><i class="bi bi-arrow-left-right me-2"></i>Recent Transactions</span>
                    <a href="{{ route('admin.transactions') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light"><tr><th>Ref</th><th>Type</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
                        <tbody>
                            @forelse($recent_transactions as $txn)
                            <tr>
                                <td><code class="small">{{ Str::limit($txn->reference,15) }}</code></td>
                                <td><span class="badge bg-secondary small">{{ $txn->type }}</span></td>
                                <td class="fw-semibold small">{{ number_format($txn->amount,2) }}</td>
                                <td><span class="badge bg-success small">{{ $txn->status }}</span></td>
                                <td class="text-muted small">{{ $txn->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">No transactions yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pending Events --}}
        <div class="col-md-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span><i class="bi bi-hourglass me-2"></i>Pending Events</span>
                    <a href="{{ route('admin.events') }}" class="btn btn-sm btn-outline-primary">All Events</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($pending_events as $event)
                    <a href="{{ route('admin.events.show', $event->id) }}"
                       class="list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between">
                            <strong class="small">{{ Str::limit($event->title,30) }}</strong>
                            <span class="badge badge-pending">Pending</span>
                        </div>
                        <div class="text-muted small">{{ $event->organiser->name }} · {{ $event->event_date->format('d M Y') }}</div>
                    </a>
                    @empty
                    <div class="list-group-item text-muted text-center py-3">No pending events.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
