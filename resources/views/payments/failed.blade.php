@extends('layouts.app')
@section('title','Payment Failed')
@section('content')
<div class="container py-5 text-center" style="max-width:520px">
    <div class="display-1 text-danger mb-3">❌</div>
    <h2 class="fw-bold text-danger">Payment Failed</h2>
    <p class="text-muted">Your payment could not be processed. No charge has been made.</p>
    <div class="alert alert-warning">{{ session('error', 'The transaction was declined. Please try again.') }}</div>
    <div class="d-flex gap-2 justify-content-center">
        <a href="{{ route('client.reserve', $ticket->event_id) }}" class="btn btn-primary">Try Again</a>
        <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">Browse Events</a>
    </div>
</div>
@endsection