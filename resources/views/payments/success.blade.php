@extends('layouts.app')
@section('title','Payment Successful')
@section('content')
<div class="container py-5 text-center" style="max-width:560px">
    <div class="mb-4">
        <div class="display-1 text-success mb-3">✅</div>
        <h2 class="fw-bold">Payment Successful!</h2>
        <p class="text-muted">Your ticket has been confirmed and funds are safely held in escrow.</p>
    </div>

    <div class="card mb-4">
        <div class="card-body text-start">
            <div class="row mb-2">
                <div class="col-5 text-muted">Transaction Ref</div>
                <div class="col-7"><code>{{ $payment->transaction_ref }}</code></div>
            </div>
            <div class="row mb-2">
                <div class="col-5 text-muted">Event</div>
                <div class="col-7 fw-semibold">{{ $payment->ticket->event->title }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-5 text-muted">Date</div>
                <div class="col-7">{{ $payment->ticket->event->event_date->format('D d M Y, H:i') }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-5 text-muted">Ticket Code</div>
                <div class="col-7"><code class="text-primary fw-bold">{{ $payment->ticket->ticket_code }}</code></div>
            </div>
            <div class="row mb-2">
                <div class="col-5 text-muted">Qty</div>
                <div class="col-7">{{ $payment->ticket->quantity }}</div>
            </div>
            <hr>
            <div class="row">
                <div class="col-5 text-muted">Amount Paid</div>
                <div class="col-7 fs-5 fw-bold text-primary">{{ number_format($payment->amount,2) }} {{ $payment->currency }}</div>
            </div>
        </div>
    </div>

    <div class="alert alert-info small mb-4">
        <i class="bi bi-shield-lock me-1"></i>
        Funds held in <strong>escrow</strong> and will be released to the organiser after the event.
    </div>

    <div class="d-flex gap-2 justify-content-center">
        <a href="{{ route('client.tickets.show', $payment->ticket->id) }}" class="btn btn-primary">
            <i class="bi bi-ticket-perforated me-1"></i>View My Ticket
        </a>
        <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">Browse More Events</a>
    </div>
</div>
@endsection