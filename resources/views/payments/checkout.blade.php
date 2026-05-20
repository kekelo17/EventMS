@extends('layouts.app')
@section('title','Checkout')

@section('content')
<div class="container py-5" style="max-width:640px">

    {{-- Order Summary --}}
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="bi bi-ticket-perforated-fill text-primary fs-5"></i>
            <span>Order Summary</span>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Event</span>
                <strong>{{ $ticket->event->title }}</strong>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Date</span>
                <span>{{ $ticket->event->event_date->format('D, d M Y H:i') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Venue</span>
                <span>{{ $ticket->event->venue }}</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Qty</span>
                <span>{{ $ticket->quantity }} ticket(s)</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between fs-5 fw-bold">
                <span>Total</span>
                <span class="text-primary">{{ number_format($ticket->total_price,2) }} XAF</span>
            </div>
        </div>
    </div>

    {{-- Escrow Notice --}}
    <div class="alert alert-info d-flex gap-2 mb-4">
        <i class="bi bi-shield-lock-fill fs-5 mt-1"></i>
        <div>
            <strong>Secured by Escrow</strong><br>
            <small>Your payment will be held safely in escrow until the event is confirmed. You can request a refund if needed.</small>
        </div>
    </div>

    {{-- Payment Form --}}
    <div class="card">
        <div class="card-header">
            <i class="bi bi-credit-card me-2"></i>Payment Details
        </div>
        <div class="card-body">

            {{-- Method Toggle --}}
            <div class="btn-group w-100 mb-4" role="group">
                <input type="radio" class="btn-check" name="method_toggle" id="method_card"  checked>
                <label class="btn btn-outline-primary" for="method_card">
                    <i class="bi bi-credit-card me-1"></i> Card
                </label>
                <input type="radio" class="btn-check" name="method_toggle" id="method_momo">
                <label class="btn btn-outline-success" for="method_momo">
                    <i class="bi bi-phone me-1"></i> Mobile Money
                </label>
            </div>

            <form method="POST" action="{{ route('client.pay.process', $ticket->id) }}" id="payForm">
                @csrf

                {{-- CARD SECTION --}}
                <div id="cardSection">
                    <input type="hidden" name="payment_method" value="card" id="payMethod">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Card Number</label>
                        <input type="text" name="card_number" class="form-control @error('card_number') is-invalid @enderror"
                               placeholder="1234 5678 9012 3456" maxlength="19"
                               oninput="formatCard(this)" autocomplete="cc-number">
                        @error('card_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div id="cardBrand" class="text-muted small mt-1"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cardholder Name</label>
                        <input type="text" name="card_name" class="form-control @error('card_name') is-invalid @enderror"
                               placeholder="JOHN DOE" autocomplete="cc-name">
                        @error('card_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">Expiry (MM/YY)</label>
                            <input type="text" name="card_expiry" class="form-control @error('card_expiry') is-invalid @enderror"
                                   placeholder="12/27" maxlength="5" oninput="formatExpiry(this)" autocomplete="cc-exp">
                            @error('card_expiry')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">CVV</label>
                            <input type="password" name="card_cvv" class="form-control @error('card_cvv') is-invalid @enderror"
                                   placeholder="•••" maxlength="4" autocomplete="cc-csc">
                            @error('card_cvv')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- MOBILE MONEY SECTION --}}
                <div id="momoSection" style="display:none">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Provider</label>
                        <select name="mobile_provider" class="form-select @error('mobile_provider') is-invalid @enderror">
                            <option value="">Select provider</option>
                            <option value="MTN">MTN Mobile Money</option>
                            <option value="Orange">Orange Money</option>
                            <option value="other">Other</option>
                        </select>
                        @error('mobile_provider')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mobile Number</label>
                        <input type="tel" name="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror"
                               placeholder="+237 6XXXXXXXX">
                        @error('mobile_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="alert alert-warning small">
                        <i class="bi bi-info-circle me-1"></i>
                        A push notification will be sent to your phone to confirm payment.
                        <strong>(Simulated)</strong>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                        <i class="bi bi-lock-fill me-2"></i>
                        Pay {{ number_format($ticket->total_price,2) }} XAF Securely
                    </button>
                </div>

                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="bi bi-shield-check me-1"></i>
                        256-bit SSL encrypted · Funds held in escrow
                    </small>
                </div>
            </form>
        </div>
    </div>

    <div class="text-center mt-3">
        <a href="{{ route('events.show', $ticket->event_id) }}" class="text-muted small">
            <i class="bi bi-arrow-left me-1"></i>Cancel and go back
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
const cardSection = document.getElementById('cardSection');
const momoSection = document.getElementById('momoSection');
const payMethod   = document.getElementById('payMethod');

document.getElementById('method_card').addEventListener('change', () => {
    cardSection.style.display = '';
    momoSection.style.display = 'none';
    payMethod.value = 'card';
});
document.getElementById('method_momo').addEventListener('change', () => {
    cardSection.style.display = 'none';
    momoSection.style.display = '';
    payMethod.value = 'mobile_money';
});

function formatCard(input) {
    let v = input.value.replace(/\D/g,'').substring(0,16);
    input.value = v.replace(/(.{4})/g,'$1 ').trim();
    // Brand detection
    const brand = document.getElementById('cardBrand');
    if      (v.startsWith('4'))    brand.textContent = '💳 Visa';
    else if (['51','52','53','54','55'].includes(v.substring(0,2))) brand.textContent = '💳 Mastercard';
    else if (['34','37'].includes(v.substring(0,2))) brand.textContent = '💳 American Express';
    else brand.textContent = '';
    // sync hidden input (strip spaces)
    input.name = 'card_number';
    input.addEventListener('submit', () => { input.value = v; });
}

function formatExpiry(input) {
    let v = input.value.replace(/\D/g,'');
    if (v.length >= 2) v = v.substring(0,2) + '/' + v.substring(2,4);
    input.value = v;
}

document.getElementById('payForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing…';
    btn.disabled = true;
    // strip spaces from card number
    const cn = this.querySelector('[name="card_number"]');
    if (cn) cn.value = cn.value.replace(/\s/g,'');
});
</script>
@endpush
