@extends('layouts.app')

@section('title', 'বিল - ' . $order->order_number)
@section('page-title', 'বিল পরিশোধ')

@section('content')
<div class="content-area">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Invoice Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-file-invoice me-2"></i>ইনভয়েস - {{ $order->order_number }}</span>
                    <span class="badge bg-light text-dark fs-6">{{ $order->table ? 'টেবিল ' . $order->table->table_number : 'টেকওয়ে' }}</span>
                </div>
                <div class="card-body">
                    @php $settings = \App\Models\Setting::getValues(); @endphp
                    <div class="text-center mb-4 pb-3 border-bottom">
                        <h4 class="mb-1">{{ $settings['restaurant_name'] ?? 'POS Restaurant' }}</h4>
                        <div class="text-muted small">{{ $settings['restaurant_address'] ?? '' }}</div>
                        <div class="text-muted small">{{ $settings['restaurant_phone'] ?? '' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="small text-muted">অর্ডার নং</div>
                            <div class="fw-bold">{{ $order->order_number }}</div>
                        </div>
                        <div class="col-6 text-end">
                            <div class="small text-muted">তারিখ ও সময়</div>
                            <div class="fw-bold">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>আইটেম</th>
                                <th class="text-center">পরিমাণ</th>
                                <th class="text-end">দাম</th>
                                <th class="text-end">মোট</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->menuItem?->name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">৳{{ number_format($item->unit_price, 0) }}</td>
                                <td class="text-end"><strong>৳{{ number_format($item->total_price, 0) }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end text-muted">সাবটোটাল</td>
                                <td class="text-end">৳{{ number_format($order->subtotal, 0) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end text-muted">ভ্যাট ({{ $settings['tax_rate'] ?? 5 }}%)</td>
                                <td class="text-end">৳{{ number_format($order->tax_amount, 0) }}</td>
                            </tr>
                            @if($order->discount_amount > 0)
                            <tr>
                                <td colspan="3" class="text-end text-danger">ছাড়</td>
                                <td class="text-end text-danger">-৳{{ number_format($order->discount_amount, 0) }}</td>
                            </tr>
                            @endif
                            <tr class="table-primary">
                                <td colspan="3" class="text-end fw-bold fs-5">মোট পরিমাণ</td>
                                <td class="text-end fw-bold fs-5">৳{{ number_format($order->total_amount, 0) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if(!$order->payment)
            <!-- Payment Section -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-money-bill me-2"></i>পেমেন্ট গ্রহণ করুন
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <label class="form-label fw-bold">পেমেন্ট পদ্ধতি বেছে নিন</label>
                            <div class="row g-2">
                                @foreach(['cash' => ['নগদ', 'fa-money-bill-wave', 'success'], 'card' => ['কার্ড', 'fa-credit-card', 'info'], 'bkash' => ['বিকাশ', 'fa-mobile-alt', 'warning'], 'nagad' => ['নগদ অ্যাপ', 'fa-wallet', 'danger']] as $method => [$label, $icon, $color])
                                <div class="col-6">
                                    <div class="payment-opt border-2 border rounded-3 p-3 text-center cursor-pointer {{ $method === 'cash' ? 'border-success bg-success bg-opacity-10' : 'border-secondary' }}"
                                         data-method="{{ $method }}" onclick="selectPayment(this, '{{ $method }}')">
                                        <i class="fas {{ $icon }} fa-lg text-{{ $color }} d-block mb-1"></i>
                                        <span class="fw-bold">{{ $label }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="bg-light rounded-3 p-3">
                                <div class="text-center mb-3">
                                    <div class="text-muted">মোট বিল</div>
                                    <h2 class="text-primary fw-bold">৳{{ number_format($order->total_amount, 0) }}</h2>
                                </div>
                                <div id="cashSection">
                                    <div class="mb-3">
                                        <label class="form-label">প্রদত্ত টাকা</label>
                                        <input type="number" id="paidAmt" class="form-control form-control-lg text-center"
                                               value="{{ ceil($order->total_amount) }}" oninput="calcChange()">
                                    </div>
                                    <div class="alert alert-info text-center d-none" id="changeInfo">
                                        ফেরত: <strong>৳<span id="changeAmt">0</span></strong>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-success btn-lg fw-bold" onclick="processPayment({{ $order->id }})">
                                        <i class="fas fa-check me-2"></i>পেমেন্ট নিশ্চিত করুন
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Cash Buttons -->
                    <div class="mt-3" id="quickCash">
                        <div class="text-muted small mb-2">দ্রুত নির্বাচন:</div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach([50, 100, 200, 500, 1000, 2000] as $amt)
                            <button class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('paidAmt').value={{ $amt }}; calcChange()">
                                ৳{{ $amt }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <strong>পেমেন্ট সম্পন্ন!</strong> পদ্ধতি: {{ $order->payment->method_label }}
                <a href="{{ route('payment.receipt', $order->id) }}" class="btn btn-sm btn-outline-success ms-3" target="_blank">
                    <i class="fas fa-print me-2"></i>রিসিট প্রিন্ট
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.payment-opt { cursor: pointer; transition: all 0.2s; }
.payment-opt:hover { transform: scale(1.02); }
.payment-opt.selected { border-color: #FF6B35 !important; background: #fff8f5 !important; }
</style>
@endpush

@push('scripts')
<script>
let selectedMethod = 'cash';

function selectPayment(el, method) {
    document.querySelectorAll('.payment-opt').forEach(e => {
        e.classList.remove('selected', 'border-success', 'bg-success', 'bg-opacity-10');
        e.classList.add('border-secondary');
    });
    el.classList.add('selected');
    el.classList.remove('border-secondary');
    selectedMethod = method;
    document.getElementById('cashSection').style.display = method === 'cash' ? '' : 'none';
    document.getElementById('quickCash').style.display = method === 'cash' ? '' : 'none';
}

function calcChange() {
    const paid = parseFloat(document.getElementById('paidAmt').value) || 0;
    const total = {{ $order->total_amount }};
    const change = paid - total;
    const info = document.getElementById('changeInfo');
    if (change >= 0) {
        document.getElementById('changeAmt').textContent = change.toFixed(0);
        info.classList.remove('d-none');
    } else {
        info.classList.add('d-none');
    }
}

async function processPayment(orderId) {
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>প্রক্রিয়া হচ্ছে...';

    const paidAmount = selectedMethod === 'cash'
        ? parseFloat(document.getElementById('paidAmt').value)
        : {{ $order->total_amount }};

    const res = await fetch(`/billing/${orderId}/pay`, {
        method: 'POST',
        headers: window.ajaxHeaders,
        body: JSON.stringify({
            method: selectedMethod,
            paid_amount: paidAmount,
        })
    });

    const data = await res.json();
    if (data.success) {
        window.location.href = data.receipt_url;
    } else {
        alert(data.message || 'ত্রুটি হয়েছে');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check me-2"></i>পেমেন্ট নিশ্চিত করুন';
    }
}

calcChange();
</script>
@endpush
