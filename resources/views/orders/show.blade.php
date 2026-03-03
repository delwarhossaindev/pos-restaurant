@extends('layouts.app')

@section('title', 'অর্ডার বিস্তারিত')
@section('page-title', 'অর্ডার: ' . $order->order_number)

@section('content')
<div class="content-area">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-receipt me-2"></i>অর্ডারের আইটেম</span>
                    <span class="badge bg-{{ $order->status_color }} fs-6">{{ $order->status_label }}</span>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr><th>আইটেম</th><th class="text-center">পরিমাণ</th><th class="text-end">দাম</th><th class="text-end">মোট</th></tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->menuItem?->name }}</strong>
                                    @if($item->notes)<br><small class="text-muted">{{ $item->notes }}</small>@endif
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">৳{{ number_format($item->unit_price, 0) }}</td>
                                <td class="text-end"><strong>৳{{ number_format($item->total_price, 0) }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr><td colspan="3" class="text-end">সাবটোটাল</td><td class="text-end">৳{{ number_format($order->subtotal, 0) }}</td></tr>
                            <tr><td colspan="3" class="text-end">ভ্যাট</td><td class="text-end">৳{{ number_format($order->tax_amount, 0) }}</td></tr>
                            @if($order->discount_amount > 0)
                            <tr><td colspan="3" class="text-end text-danger">ছাড়</td><td class="text-end text-danger">-৳{{ number_format($order->discount_amount, 0) }}</td></tr>
                            @endif
                            <tr class="fw-bold"><td colspan="3" class="text-end fs-5">মোট</td><td class="text-end fs-5 text-primary">৳{{ number_format($order->total_amount, 0) }}</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Info -->
            <div class="card mb-3">
                <div class="card-header"><i class="fas fa-info-circle me-2"></i>অর্ডারের তথ্য</div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr><td class="text-muted">অর্ডার নং</td><td><strong>{{ $order->order_number }}</strong></td></tr>
                        <tr><td class="text-muted">টেবিল</td><td>{{ $order->table ? 'টেবিল ' . $order->table->table_number : 'N/A' }}</td></tr>
                        <tr><td class="text-muted">ধরন</td><td>{{ $order->order_type === 'dine_in' ? 'ডাইন ইন' : ($order->order_type === 'takeaway' ? 'টেকওয়ে' : 'ডেলিভারি') }}</td></tr>
                        <tr><td class="text-muted">অতিথি</td><td>{{ $order->guests }}</td></tr>
                        <tr><td class="text-muted">স্টাফ</td><td>{{ $order->user?->name }}</td></tr>
                        <tr><td class="text-muted">সময়</td><td>{{ $order->created_at->format('d M Y, H:i') }}</td></tr>
                    </table>
                </div>
            </div>

            <!-- Status Update -->
            @if(!$order->payment)
            <div class="card mb-3">
                <div class="card-header"><i class="fas fa-tasks me-2"></i>অবস্থা পরিবর্তন</div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @foreach(['confirmed' => 'নিশ্চিত', 'preparing' => 'তৈরি হচ্ছে', 'ready' => 'প্রস্তুত', 'served' => 'পরিবেশিত', 'cancelled' => 'বাতিল'] as $status => $label)
                        @if($order->status !== $status)
                        <button class="btn btn-sm btn-outline-{{ $status === 'cancelled' ? 'danger' : 'primary' }}"
                                onclick="updateStatus('{{ $status }}')">{{ $label }}</button>
                        @endif
                        @endforeach
                    </div>
                    <a href="{{ route('billing.show', $order->id) }}" class="btn btn-success w-100 mt-2">
                        <i class="fas fa-file-invoice-dollar me-2"></i>বিল করুন
                    </a>
                </div>
            </div>
            @endif

            @if($order->payment)
            <div class="card border-success">
                <div class="card-header bg-success text-white"><i class="fas fa-check-circle me-2"></i>পেমেন্ট সম্পন্ন</div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr><td class="text-muted">পদ্ধতি</td><td>{{ $order->payment->method_label }}</td></tr>
                        <tr><td class="text-muted">পরিমাণ</td><td>৳{{ number_format($order->payment->amount, 0) }}</td></tr>
                        <tr><td class="text-muted">প্রদত্ত</td><td>৳{{ number_format($order->payment->paid_amount, 0) }}</td></tr>
                        <tr><td class="text-muted">ফেরত</td><td>৳{{ number_format($order->payment->change_amount, 0) }}</td></tr>
                    </table>
                    <a href="{{ route('payment.receipt', $order->id) }}" class="btn btn-outline-success w-100 mt-2" target="_blank">
                        <i class="fas fa-print me-2"></i>রিসিট প্রিন্ট
                    </a>
                </div>
            </div>
            @endif

            <a href="{{ route('orders.index') }}" class="btn btn-secondary w-100 mt-3">
                <i class="fas fa-arrow-left me-2"></i>ফিরে যান
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
async function updateStatus(status) {
    if (!confirm('অবস্থা পরিবর্তন করবেন?')) return;
    const res = await fetch('{{ route("orders.status", $order->id) }}', {
        method: 'POST',
        headers: window.ajaxHeaders,
        body: JSON.stringify({ status })
    });
    const data = await res.json();
    if (data.success) location.reload();
    else alert('ত্রুটি হয়েছে');
}
</script>
@endpush
