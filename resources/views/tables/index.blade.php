@extends('layouts.app')

@section('title', 'টেবিল ম্যানেজমেন্ট')
@section('page-title', 'টেবিল ম্যানেজমেন্ট')

@section('content')
<div class="content-area">
    <div class="row g-4">
        <!-- Table Floor Plan -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-store me-2 text-primary"></i>ফ্লোর প্ল্যান</span>
                    <div class="d-flex gap-2">
                        <span class="badge bg-success px-3">খালি</span>
                        <span class="badge bg-danger px-3">ব্যস্ত</span>
                        <span class="badge bg-warning px-3">রিজার্ভ</span>
                        <span class="badge bg-info px-3">পরিষ্কার</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($tables as $table)
                        <div class="col-xl-3 col-md-4 col-6">
                            <div class="card text-center border-2 border-{{ $table->status_color }} h-100 table-card"
                                 style="cursor: pointer;"
                                 onclick="window.location='{{ route('pos', ['table_id' => $table->id]) }}'">
                                <div class="card-body py-3">
                                    <div class="mb-2">
                                        <i class="fas fa-chair fa-2x text-{{ $table->status_color }}"></i>
                                    </div>
                                    <h5 class="mb-1">টেবিল {{ $table->table_number }}</h5>
                                    <div class="text-muted small mb-2">{{ $table->capacity }} আসন • {{ $table->location }}</div>
                                    <span class="badge bg-{{ $table->status_color }}">{{ $table->status_label }}</span>

                                    @if($table->activeOrder)
                                    <div class="mt-2 pt-2 border-top">
                                        <div class="small text-muted">{{ $table->activeOrder->order_number }}</div>
                                        <div class="fw-bold text-primary">৳{{ number_format($table->activeOrder->total_amount, 0) }}</div>
                                    </div>
                                    @endif
                                </div>
                                <div class="card-footer py-1">
                                    <button class="btn btn-xs btn-outline-secondary"
                                            onclick="event.stopPropagation(); showStatusModal({{ $table->id }}, '{{ $table->status }}')">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <button class="btn btn-xs btn-outline-primary ms-1"
                                            onclick="event.stopPropagation(); showEditModal({{ $table->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Table & Stats -->
        <div class="col-lg-4">
            <!-- Stats -->
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <div class="card text-center border-success">
                        <div class="card-body py-3">
                            <h3 class="text-success mb-0">{{ $tables->where('status', 'available')->count() }}</h3>
                            <small class="text-muted">খালি টেবিল</small>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card text-center border-danger">
                        <div class="card-body py-3">
                            <h3 class="text-danger mb-0">{{ $tables->where('status', 'occupied')->count() }}</h3>
                            <small class="text-muted">ব্যস্ত টেবিল</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Table Form -->
            <div class="card">
                <div class="card-header"><i class="fas fa-plus me-2 text-primary"></i>নতুন টেবিল যোগ</div>
                <div class="card-body">
                    <form action="{{ route('tables.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">টেবিল নম্বর *</label>
                            <input type="text" name="table_number" class="form-control" placeholder="যেমন: 1, A1, VIP1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">আসন সংখ্যা *</label>
                            <input type="number" name="capacity" class="form-control" value="4" min="1" max="50" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">অবস্থান</label>
                            <select name="location" class="form-select">
                                <option value="Main Hall">Main Hall (প্রধান হল)</option>
                                <option value="VIP">VIP রুম</option>
                                <option value="Outdoor">Outdoor (বাইরে)</option>
                                <option value="Rooftop">Rooftop</option>
                                <option value="Private">Private Room</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>টেবিল যোগ করুন
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- All Tables List -->
            <div class="card mt-3">
                <div class="card-header small">সব টেবিল তালিকা</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr><th>নম্বর</th><th>আসন</th><th>অবস্থান</th><th>অবস্থা</th><th></th></tr>
                            </thead>
                            <tbody>
                                @foreach($tables as $table)
                                <tr>
                                    <td><strong>{{ $table->table_number }}</strong></td>
                                    <td>{{ $table->capacity }}</td>
                                    <td class="text-muted small">{{ $table->location }}</td>
                                    <td><span class="badge bg-{{ $table->status_color }}">{{ $table->status_label }}</span></td>
                                    <td>
                                        <form action="{{ route('tables.destroy', $table->id) }}" method="POST"
                                              onsubmit="return confirm('মুছবেন?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Change Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">অবস্থা পরিবর্তন</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-success" onclick="changeStatus('available')"><i class="fas fa-check me-2"></i>খালি করুন</button>
                    <button class="btn btn-danger" onclick="changeStatus('occupied')"><i class="fas fa-user me-2"></i>ব্যস্ত</button>
                    <button class="btn btn-warning" onclick="changeStatus('reserved')"><i class="fas fa-bookmark me-2"></i>রিজার্ভ</button>
                    <button class="btn btn-info" onclick="changeStatus('cleaning')"><i class="fas fa-broom me-2"></i>পরিষ্কার হচ্ছে</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentTableId = null;

function showStatusModal(id, status) {
    currentTableId = id;
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}

async function changeStatus(status) {
    const res = await fetch(`/tables/${currentTableId}/status`, {
        method: 'POST',
        headers: window.ajaxHeaders,
        body: JSON.stringify({ status })
    });
    const data = await res.json();
    if (data.success) location.reload();
}
</script>
@endpush
