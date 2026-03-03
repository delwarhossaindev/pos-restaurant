@extends('layouts.app')

@section('title', 'রিপোর্ট')
@section('page-title', 'বিক্রির রিপোর্ট')

@section('content')
<div class="content-area">
    <!-- Date Filter -->
    <div class="card mb-4">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold mb-1">শুরুর তারিখ</label>
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold mb-1">শেষের তারিখ</label>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search me-1"></i>রিপোর্ট দেখুন</button>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <a href="{{ route('reports.index', ['start_date' => today()->format('Y-m-d'), 'end_date' => today()->format('Y-m-d')]) }}" class="btn btn-outline-secondary btn-sm">আজ</a>
                    <a href="{{ route('reports.index', ['start_date' => now()->startOfWeek()->format('Y-m-d'), 'end_date' => today()->format('Y-m-d')]) }}" class="btn btn-outline-secondary btn-sm">এই সপ্তাহ</a>
                    <a href="{{ route('reports.index', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => today()->format('Y-m-d')]) }}" class="btn btn-outline-secondary btn-sm">এই মাস</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card bg-gradient-orange">
                <div class="icon"><i class="fas fa-taka-sign"></i></div>
                <p>মোট বিক্রি</p>
                <h3>৳{{ number_format($totalSales, 0) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#2ecc71,#27ae60)">
                <div class="icon"><i class="fas fa-receipt"></i></div>
                <p>মোট অর্ডার</p>
                <h3>{{ $totalOrders }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#3498db,#2980b9)">
                <div class="icon"><i class="fas fa-calculator"></i></div>
                <p>গড় অর্ডার মূল্য</p>
                <h3>৳{{ number_format($avgOrderValue, 0) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#e74c3c,#c0392b)">
                <div class="icon"><i class="fas fa-ban"></i></div>
                <p>বাতিল অর্ডার</p>
                <h3>{{ $cancelledOrders }}</h3>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Daily Sales Chart -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><i class="fas fa-chart-bar me-2 text-primary"></i>দৈনিক বিক্রির তালিকা</div>
                <div class="card-body">
                    <canvas id="dailyChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><i class="fas fa-chart-pie me-2 text-primary"></i>পেমেন্ট পদ্ধতি</div>
                <div class="card-body">
                    <canvas id="paymentChart" height="200"></canvas>
                    <div class="mt-3">
                        @foreach($paymentMethods as $pm)
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span>{{ $pm->method === 'cash' ? 'নগদ' : ($pm->method === 'card' ? 'কার্ড' : ($pm->method === 'bkash' ? 'বিকাশ' : 'নগদ অ্যাপ')) }}</span>
                            <div>
                                <span class="badge bg-primary me-2">{{ $pm->count }}টি</span>
                                <strong>৳{{ number_format($pm->total, 0) }}</strong>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Items -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><i class="fas fa-trophy me-2 text-warning"></i>সেরা বিক্রিত আইটেম</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr><th>#</th><th>আইটেম</th><th>পরিমাণ</th><th class="text-end">আয়</th></tr>
                        </thead>
                        <tbody>
                            @forelse($topItems as $i => $item)
                            <tr>
                                <td>
                                    @if($i === 0) 🥇
                                    @elseif($i === 1) 🥈
                                    @elseif($i === 2) 🥉
                                    @else {{ $i + 1 }}
                                    @endif
                                </td>
                                <td>{{ $item->name }}</td>
                                <td><span class="badge bg-success">{{ $item->total_qty }}</span></td>
                                <td class="text-end"><strong>৳{{ number_format($item->total_amount, 0) }}</strong></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">কোনো ডেটা নেই</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Types -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><i class="fas fa-chart-pie me-2 text-info"></i>অর্ডারের ধরন</div>
                <div class="card-body">
                    @foreach($orderTypes as $ot)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <span class="me-2">
                                @if($ot->order_type === 'dine_in') 🍽️ ডাইন ইন
                                @elseif($ot->order_type === 'takeaway') 🥡 টেকওয়ে
                                @else 🛵 ডেলিভারি
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="badge bg-primary me-2">{{ $ot->count }} অর্ডার</span>
                            <strong>৳{{ number_format($ot->total, 0) }}</strong>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Daily Table -->
            <div class="card mt-4">
                <div class="card-header"><i class="fas fa-calendar me-2"></i>দৈনিক রিপোর্ট</div>
                <div class="card-body p-0" style="max-height:250px; overflow-y:auto;">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr><th>তারিখ</th><th>অর্ডার</th><th class="text-end">বিক্রি</th></tr>
                        </thead>
                        <tbody>
                            @forelse($dailySales as $day)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($day->date)->format('d M Y') }}</td>
                                <td>{{ $day->count }}</td>
                                <td class="text-end"><strong>৳{{ number_format($day->total, 0) }}</strong></td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">কোনো ডেটা নেই</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-gradient-orange { background: linear-gradient(135deg, #FF6B35, #e55a28); }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Daily Sales Chart
const dailyCtx = document.getElementById('dailyChart').getContext('2d');
new Chart(dailyCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($dailySales->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->toArray()) !!},
        datasets: [{
            label: 'বিক্রি (৳)',
            data: {!! json_encode($dailySales->pluck('total')->toArray()) !!},
            borderColor: '#FF6B35',
            backgroundColor: 'rgba(255,107,53,0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#FF6B35',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => '৳' + v.toLocaleString() } } }
    }
});

// Payment Methods Chart
@if($paymentMethods->count() > 0)
const payCtx = document.getElementById('paymentChart').getContext('2d');
new Chart(payCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($paymentMethods->map(fn($m) => $m->method === 'cash' ? 'নগদ' : ($m->method === 'card' ? 'কার্ড' : ($m->method === 'bkash' ? 'বিকাশ' : 'নগদ অ্যাপ')))->toArray()) !!},
        datasets: [{
            data: {!! json_encode($paymentMethods->pluck('total')->toArray()) !!},
            backgroundColor: ['#FF6B35', '#3498db', '#e74c3c', '#2ecc71'],
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
@endif
</script>
@endpush
