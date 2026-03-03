@extends('layouts.app')

@section('title', 'মেনু ক্যাটাগরি')
@section('page-title', 'মেনু ম্যানেজমেন্ট')

@section('content')
<div class="content-area">
    <div class="row g-4">
        <!-- Category List -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-tags me-2 text-primary"></i>ক্যাটাগরি তালিকা</span>
                    <a href="{{ route('menu.items') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-list me-1"></i>সব আইটেম
                    </a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>নাম</th>
                                <th>আইকন</th>
                                <th>আইটেম সংখ্যা</th>
                                <th>অবস্থা</th>
                                <th>একশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $cat)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                             style="width:35px;height:35px;background:{{ $cat->color ?? '#FF6B35' }}20">
                                            <i class="{{ $cat->icon ?? 'fas fa-utensils' }}" style="color:{{ $cat->color ?? '#FF6B35' }}"></i>
                                        </div>
                                        <strong>{{ $cat->name }}</strong>
                                    </div>
                                </td>
                                <td><code>{{ $cat->icon }}</code></td>
                                <td><span class="badge bg-primary">{{ $cat->menu_items_count }}</span></td>
                                <td>
                                    @if($cat->is_active)
                                        <span class="badge bg-success">সক্রিয়</span>
                                    @else
                                        <span class="badge bg-secondary">নিষ্ক্রিয়</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#editCatModal{{ $cat->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('categories.toggle', $cat->id) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-sm btn-outline-{{ $cat->is_active ? 'warning' : 'success' }}" title="{{ $cat->is_active ? 'নিষ্ক্রিয়' : 'সক্রিয়' }}">
                                                <i class="fas fa-{{ $cat->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('মুছে দিতে চান?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editCatModal{{ $cat->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">ক্যাটাগরি সম্পাদনা</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('categories.update', $cat->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">নাম</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">আইকন (Font Awesome)</label>
                                                    <input type="text" name="icon" class="form-control" value="{{ $cat->icon }}" placeholder="fas fa-utensils">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">রঙ</label>
                                                    <input type="color" name="color" class="form-control form-control-color" value="{{ $cat->color ?? '#FF6B35' }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">সর্ট অর্ডার</label>
                                                    <input type="number" name="sort_order" class="form-control" value="{{ $cat->sort_order }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                                <button type="submit" class="btn btn-primary">সংরক্ষণ</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">কোনো ক্যাটাগরি নেই</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Category Form -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><i class="fas fa-plus me-2 text-primary"></i>নতুন ক্যাটাগরি</div>
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">ক্যাটাগরির নাম *</label>
                            <input type="text" name="name" class="form-control" placeholder="যেমন: বার্গার, পিৎজা" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Font Awesome আইকন</label>
                            <input type="text" name="icon" class="form-control" placeholder="fas fa-hamburger" value="fas fa-utensils">
                            <div class="form-text">
                                <a href="https://fontawesome.com/icons" target="_blank">আইকন খুঁজুন</a>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">রঙ</label>
                            <input type="color" name="color" class="form-control form-control-color" value="#FF6B35">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ক্রম (Sort Order)</label>
                            <input type="number" name="sort_order" class="form-control" value="0">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>ক্যাটাগরি যোগ করুন
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Popular Icons -->
            <div class="card mt-3">
                <div class="card-header small">জনপ্রিয় আইকন</div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(['fas fa-hamburger', 'fas fa-pizza-slice', 'fas fa-coffee', 'fas fa-fish', 'fas fa-drumstick-bite', 'fas fa-ice-cream', 'fas fa-bread-slice', 'fas fa-carrot', 'fas fa-cocktail', 'fas fa-soup', 'fas fa-utensils', 'fas fa-fire'] as $icon)
                        <button class="btn btn-sm btn-outline-secondary" onclick="document.querySelector('[name=icon]').value='{{ $icon }}'">
                            <i class="{{ $icon }}"></i>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
