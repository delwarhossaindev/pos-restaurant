@extends('layouts.app')

@section('title', 'সব অর্ডার')
@section('page-title', 'সব অর্ডার')

@section('content')
<div class="content-area">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-clipboard-list me-2 text-primary"></i>অর্ডার তালিকা</span>
            <a href="{{ route('pos') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>নতুন অর্ডার
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>অর্ডার নং</th>
                            <th>টেবিল/ধরন</th>
                            <th>স্টাফ</th>
                            <th>আইটেম</th>
                            <th>মোট</th>
                            <th>অবস্থা</th>
                            <th>পেমেন্ট</th>
                            <th>সময়</th>
                            <th>একশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>
                                @if($order->table)
                                    <span class="badge bg-info">টেবিল {{ $order->table->table_number }}</span>
                                @else
                                    <span class="badge bg-secondary">
                                        {{ $order->order_type === 'takeaway' ? 'টেকওয়ে' : 'ডেলিভারি' }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ $order->user?->name }}</td>
                            <td>{{ $order->items->count() }}</td>
                            <td><strong>৳{{ number_format($order->total_amount, 0) }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                            </td>
                            <td>
                                @if($order->payment)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>পরিশোধিত</span>
                                @else
                                    <span class="badge bg-warning text-dark">বকেয়া</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $order->created_at->format('d/m H:i') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary" title="বিস্তারিত">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(!$order->payment)
                                    <a href="{{ route('billing.show', $order->id) }}" class="btn btn-sm btn-success" title="বিল">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </a>
                                    @else
                                    <a href="{{ route('payment.receipt', $order->id) }}" class="btn btn-sm btn-outline-secondary" title="রিসিট">
                                        <i class="fas fa-receipt"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center py-4 text-muted">কোনো অর্ডার নেই</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
