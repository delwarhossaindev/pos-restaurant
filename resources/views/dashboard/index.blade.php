@extends('layouts.app')

@section('title', 'ড্যাশবোর্ড')
@section('page-title', 'ড্যাশবোর্ড')

@push('styles')
<style>
.bg-gradient-orange { background: linear-gradient(135deg, #FF6B35, #e55a28); }
.bg-gradient-green { background: linear-gradient(135deg, #2ecc71, #27ae60); }
.bg-gradient-blue { background: linear-gradient(135deg, #3498db, #2980b9); }
.bg-gradient-purple { background: linear-gradient(135deg, #9b59b6, #8e44ad); }
.table-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px; }
.table-item {
    background: #fff; border-radius: 10px; padding: 15px;
    text-align: center; cursor: pointer; transition: all 0.2s;
    border: 2px solid transparent;
}
.table-item:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
.table-item.available { border-color: #2ecc71; }
.table-item.occupied { border-color: #e74c3c; background: #fff5f5; }
.table-item.reserved { border-color: #f39c12; background: #fffaf0; }
.table-item.cleaning { border-color: #3498db; background: #f0f8ff; }
.table-item .table-num { font-size: 1.3rem; font-weight: 700; color: #333; }
.table-item .table-status { font-size: 0.7rem; margin-top: 5px; }
.order-row { cursor: pointer; }
.order-row:hover td { background: #fff8f5; }
</style>
@endpush

@section('content')
<div class="content-area">

    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-gradient-orange">
                <div class="icon"><i class="fas fa-taka-sign"></i></div>
                <p>আজকের বিক্রি</p>
                <h3>৳{{ number_format($todaySales, 0) }}</h3>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-gradient-green">
                <div class="icon"><i class="fas fa-receipt"></i></div>
                <p>আজকের অর্ডার</p>
                <h3>{{ $todayOrders }}</h3>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-gradient-blue">
                <div class="icon"><i class="fas fa-clock"></i></div>
                <p>সক্রিয় অর্ডার</p>
                <h3>{{ $activeOrders }}</h3>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-gradient-purple">
                <div class="icon"><i class="fas fa-chair"></i></div>
                <p>খালি টেবিল</p>
                <h3>{{ $availableTables }}/{{ $totalTables }}</h3>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Table Overview -->
        <div class="col-xl-5">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-chair me-2 text-primary"></i>টেবিল অবস্থা</span>
                    <a href="{{ route('tables.index') }}" class="btn btn-sm btn-outline-primary">সব দেখুন</a>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-3 mb-3 flex-wrap">
                        <span class="badge bg-success px-3 py-2">খালি: {{ $availableTables }}</span>
                        <span class="badge bg-danger px-3 py-2">ব্যস্ত: {{ $occupiedTables }}</span>
                        <span class="badge bg-warning px-3 py-2">অন্যান্য: {{ $totalTables - $availableTables - $occupiedTables }}</span>
                    </div>
                    <div class="table-grid">
                        @foreach($tables as $table)
                        <a href="{{ route('pos', ['table_id' => $table->id]) }}" class="text-decoration-none">
                            <div class="table-item {{ $table->status }}">
                                <div class="table-num">T{{ $table->table_number }}</div>
                                <div class="small text-muted">{{ $table->capacity }} আসন</div>
                                <div class="table-status">
                                    @if($table->status === 'available')
                                        <span class="text-success">খালি</span>
                                    @elseif($table->status === 'occupied')
                                        <span class="text-danger">ব্যস্ত</span>
                                        @if($table->activeOrder)
                                            <div class="text-muted" style="font-size:0.65rem">৳{{ number_format($table->activeOrder->total_amount) }}</div>
                                        @endif
                                    @else
                                        <span class="text-warning">{{ $table->status_label }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="col-xl-7">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-line me-2 text-primary"></i>সাপ্তাহিক বিক্রির গ্রাফ
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="150"></canvas>
                </div>
            </div>

            <!-- Top Items -->
            <div class="card mt-4">
                <div class="card-header">
                    <i class="fas fa-star me-2 text-warning"></i>সেরা আইটেম (আজকের)
                </div>
                <div class="card-body p-0">
                    @forelse($topItems as $i => $item)
                    <div class="d-flex align-items-center px-3 py-2 border-bottom">
                        <span class="badge bg-primary me-3">{{ $i + 1 }}</span>
                        <span class="flex-grow-1">{{ $item->name }}</span>
                        <span class="badge bg-success">{{ $item->order_items_count }} বার</span>
                    </div>
                    @empty
                    <div class="text-center py-3 text-muted">কোনো ডেটা নেই</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-receipt me-2 text-primary"></i>সাম্প্রতিক অর্ডার</span>
            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">সব অর্ডার</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>অর্ডার নং</th>
                            <th>টেবিল</th>
                            <th>আইটেম</th>
                            <th>মোট</th>
                            <th>অবস্থা</th>
                            <th>সময়</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr class="order-row" onclick="window.location='{{ route('orders.show', $order->id) }}'">
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>
                                @if($order->table)
                                    <span class="badge bg-info">টেবিল {{ $order->table->table_number }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $order->order_type === 'takeaway' ? 'টেকওয়ে' : 'ডেলিভারি' }}</span>
                                @endif
                            </td>
                            <td>{{ $order->items->count() }} আইটেম</td>
                            <td><strong>৳{{ number_format($order->total_amount, 0) }}</strong></td>
                            <td><span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                            <td class="text-muted small">{{ $order->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('billing.show', $order->id) }}" class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation()">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">কোনো অর্ডার নেই</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_column($weeklySales, 'date')) !!},
        datasets: [{
            label: 'বিক্রি (৳)',
            data: {!! json_encode(array_column($weeklySales, 'amount')) !!},
            backgroundColor: 'rgba(255, 107, 53, 0.7)',
            borderColor: '#FF6B35',
            borderWidth: 2,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { callback: v => '৳' + v.toLocaleString() } }
        }
    }
});
</script>
@endpush
