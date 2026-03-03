@extends('layouts.app')

@section('title', 'কিচেন ডিসপ্লে')
@section('page-title', 'কিচেন ডিসপ্লে সিস্টেম (KDS)')

@push('styles')
<style>
body { background: #0d1117; }
.main-content { background: #0d1117; }
.topbar { background: #161b22; border-color: #30363d; }
.topbar .page-title { color: #e6edf3; }
.kitchen-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px; padding: 20px; }
.kitchen-card {
    border-radius: 12px; overflow: hidden; border: 2px solid;
}
.kitchen-card.pending { border-color: #e67e22; background: #1e1108; }
.kitchen-card.confirmed { border-color: #3498db; background: #081620; }
.kitchen-card.preparing { border-color: #2ecc71; background: #081508; }
.kitchen-card .card-header-kitchen {
    padding: 12px 15px; font-weight: 700; font-size: 0.95rem;
    display: flex; justify-content: space-between; align-items: center;
}
.pending .card-header-kitchen { background: #e67e22; color: #fff; }
.confirmed .card-header-kitchen { background: #3498db; color: #fff; }
.preparing .card-header-kitchen { background: #2ecc71; color: #fff; }
.kitchen-card .card-body-kitchen { padding: 12px; }
.kitchen-item {
    display: flex; align-items: center; padding: 8px;
    border-bottom: 1px solid rgba(255,255,255,0.1); color: #e6edf3;
    gap: 10px;
}
.kitchen-item .qty {
    background: #FF6B35; color: #fff; width: 28px; height: 28px;
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-weight: 700; flex-shrink: 0; font-size: 0.85rem;
}
.kitchen-item .item-name { flex: 1; font-size: 0.9rem; font-weight: 500; }
.kitchen-item .item-status { font-size: 0.7rem; opacity: 0.7; }
.timer { font-size: 0.8rem; font-family: monospace; color: #ffc107; }
.kitchen-actions { padding: 10px 15px; display: flex; gap: 8px; background: rgba(0,0,0,0.3); }
.kitchen-actions .btn { font-size: 0.8rem; padding: 6px 12px; flex: 1; }
.empty-kitchen { text-align: center; padding: 60px 20px; color: #555; }
.section-title { color: #8b949e; padding: 15px 20px 5px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }
.pulse { animation: pulse 2s infinite; }
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center px-4 py-2" style="background:#161b22; border-bottom:1px solid #30363d;">
    <div class="d-flex gap-3">
        <span class="badge bg-warning px-3 py-2 fs-6">অপেক্ষায়: {{ $pendingOrders->count() }}</span>
        <span class="badge bg-primary px-3 py-2 fs-6">তৈরি হচ্ছে: {{ $orders->where('status','preparing')->count() }}</span>
        <span class="badge bg-success px-3 py-2 fs-6">নিশ্চিত: {{ $orders->where('status','confirmed')->count() }}</span>
    </div>
    <div>
        <button class="btn btn-sm btn-outline-success" onclick="location.reload()">
            <i class="fas fa-sync me-1"></i>রিফ্রেশ
        </button>
        <span class="text-muted small ms-2" id="lastRefresh"></span>
    </div>
</div>

@if($pendingOrders->count() > 0)
<div class="section-title text-warning">⚠️ নতুন অর্ডার - নিশ্চিত করুন</div>
<div class="kitchen-grid">
    @foreach($pendingOrders as $order)
    <div class="kitchen-card pending" id="order-{{ $order->id }}">
        <div class="card-header-kitchen">
            <div>
                <div>{{ $order->order_number }}</div>
                <div class="small opacity-75">
                    {{ $order->table ? 'টেবিল ' . $order->table->table_number : 'টেকওয়ে' }}
                    • {{ $order->guests }} জন
                </div>
            </div>
            <div class="timer" id="timer-{{ $order->id }}" data-created="{{ $order->created_at->timestamp }}">00:00</div>
        </div>
        <div class="card-body-kitchen">
            @foreach($order->items as $item)
            <div class="kitchen-item">
                <div class="qty">{{ $item->quantity }}</div>
                <div class="item-name">{{ $item->menuItem?->name }}</div>
                @if($item->notes)<div class="small text-warning">{{ $item->notes }}</div>@endif
            </div>
            @endforeach
            @if($order->notes)
            <div class="mt-2 p-2 rounded" style="background:rgba(255,193,7,0.1); color:#ffc107; font-size:0.8rem">
                <i class="fas fa-sticky-note me-1"></i>{{ $order->notes }}
            </div>
            @endif
        </div>
        <div class="kitchen-actions">
            <button class="btn btn-warning" onclick="updateOrderStatus({{ $order->id }}, 'confirmed')">
                <i class="fas fa-check me-1"></i>নিশ্চিত করুন
            </button>
            <button class="btn btn-success" onclick="updateOrderStatus({{ $order->id }}, 'preparing')">
                <i class="fas fa-fire me-1"></i>রান্না শুরু
            </button>
        </div>
    </div>
    @endforeach
</div>
@endif

@if($orders->count() > 0)
<div class="section-title text-info">🔥 রান্না চলছে</div>
<div class="kitchen-grid">
    @foreach($orders as $order)
    <div class="kitchen-card {{ $order->status }}" id="order-{{ $order->id }}">
        <div class="card-header-kitchen">
            <div>
                <div>{{ $order->order_number }}</div>
                <div class="small opacity-75">
                    {{ $order->table ? 'টেবিল ' . $order->table->table_number : 'টেকওয়ে' }}
                </div>
            </div>
            <div class="timer" id="timer-{{ $order->id }}" data-created="{{ $order->created_at->timestamp }}">00:00</div>
        </div>
        <div class="card-body-kitchen">
            @foreach($order->items as $item)
            <div class="kitchen-item">
                <div class="qty">{{ $item->quantity }}</div>
                <div>
                    <div class="item-name">{{ $item->menuItem?->name }}</div>
                    @if($item->notes)<div class="small text-warning">{{ $item->notes }}</div>@endif
                </div>
                <div>
                    <span class="badge bg-{{ $item->status === 'ready' ? 'success' : ($item->status === 'preparing' ? 'warning' : 'secondary') }}" style="font-size:0.65rem">
                        {{ $item->status === 'ready' ? 'প্রস্তুত' : ($item->status === 'preparing' ? 'তৈরি হচ্ছে' : 'অপেক্ষায়') }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        <div class="kitchen-actions">
            @if($order->status === 'confirmed')
            <button class="btn btn-primary" onclick="updateOrderStatus({{ $order->id }}, 'preparing')">
                <i class="fas fa-fire me-1"></i>রান্না শুরু
            </button>
            @endif
            <button class="btn btn-success" onclick="updateOrderStatus({{ $order->id }}, 'ready')">
                <i class="fas fa-check-double me-1"></i>প্রস্তুত!
            </button>
        </div>
    </div>
    @endforeach
</div>
@endif

@if($pendingOrders->count() === 0 && $orders->count() === 0)
<div class="empty-kitchen">
    <i class="fas fa-check-circle fa-4x mb-3" style="color:#2ecc71"></i>
    <h4 style="color:#4a5568">সব অর্ডার সম্পন্ন!</h4>
    <p style="color:#555">কোনো পেন্ডিং অর্ডার নেই।</p>
</div>
@endif
@endsection

@push('scripts')
<script>
// Update timers
function updateTimers() {
    document.querySelectorAll('.timer').forEach(el => {
        const created = parseInt(el.dataset.created);
        const now = Math.floor(Date.now() / 1000);
        const diff = now - created;
        const mins = Math.floor(diff / 60).toString().padStart(2, '0');
        const secs = (diff % 60).toString().padStart(2, '0');
        el.textContent = `${mins}:${secs}`;
        if (diff > 1800) el.style.color = '#e74c3c'; // Red after 30 min
        else if (diff > 900) el.style.color = '#e67e22'; // Orange after 15 min
    });
}
setInterval(updateTimers, 1000);
updateTimers();

// Auto refresh
setInterval(() => location.reload(), 60000); // Refresh every minute
document.getElementById('lastRefresh').textContent = '(প্রতি মিনিটে আপডেট)';

async function updateOrderStatus(orderId, status) {
    const res = await fetch(`/kitchen/${orderId}/status`, {
        method: 'POST',
        headers: window.ajaxHeaders,
        body: JSON.stringify({ status })
    });
    const data = await res.json();
    if (data.success) {
        location.reload();
    }
}
</script>
@endpush
