@extends('layouts.app')

@section('title', 'মেনু আইটেম')
@section('page-title', 'মেনু আইটেম')

@section('content')
<div class="content-area">
    <!-- Filter Bar -->
    <div class="card mb-4">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="আইটেম খুঁজুন..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">সব ক্যাটাগরি</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm w-100"><i class="fas fa-search me-1"></i>খুঁজুন</button>
                </div>
                <div class="col-md-3 text-end">
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
                        <i class="fas fa-plus me-1"></i>নতুন আইটেম
                    </button>
                    <a href="{{ route('menu.categories') }}" class="btn btn-outline-secondary btn-sm ms-1">
                        <i class="fas fa-tags me-1"></i>ক্যাটাগরি
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Items Grid -->
    <div class="row g-3">
        @forelse($items as $item)
        <div class="col-xl-2 col-lg-3 col-md-4 col-6">
            <div class="card h-100 {{ !$item->is_available ? 'opacity-50' : '' }}">
                <div class="position-relative">
                    @if($item->image)
                        <img src="{{ Storage::url($item->image) }}" class="card-img-top" style="height:130px;object-fit:cover" alt="">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height:100px">
                            <i class="fas fa-image fa-2x text-muted"></i>
                        </div>
                    @endif
                    @if($item->is_featured)
                    <span class="position-absolute top-0 start-0 badge bg-warning m-1">⭐</span>
                    @endif
                    @if(!$item->is_available)
                    <span class="position-absolute top-0 end-0 badge bg-danger m-1">অনুপলব্ধ</span>
                    @endif
                </div>
                <div class="card-body p-2">
                    <div class="small text-muted">{{ $item->category?->name }}</div>
                    <div class="fw-bold" style="font-size:0.85rem">{{ $item->name }}</div>
                    <div class="text-primary fw-bold">৳{{ number_format($item->price, 0) }}</div>
                    <div class="text-muted" style="font-size:0.7rem"><i class="fas fa-clock me-1"></i>{{ $item->preparation_time }} মিনিট</div>
                </div>
                <div class="card-footer p-1 d-flex gap-1 justify-content-center">
                    <button class="btn btn-sm btn-outline-primary flex-fill"
                            data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form action="{{ route('menu.items.toggle', $item->id) }}" method="POST" class="d-inline flex-fill">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-{{ $item->is_available ? 'warning' : 'success' }} w-100" title="{{ $item->is_available ? 'বন্ধ' : 'চালু' }}">
                            <i class="fas fa-{{ $item->is_available ? 'pause' : 'play' }}"></i>
                        </button>
                    </form>
                    <form action="{{ route('menu.items.destroy', $item->id) }}" method="POST" class="d-inline flex-fill"
                          onsubmit="return confirm('মুছবেন?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger w-100"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">আইটেম সম্পাদনা</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('menu.items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">ক্যাটাগরি *</label>
                                        <select name="category_id" class="form-select" required>
                                            @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">নাম *</label>
                                        <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">দাম (৳) *</label>
                                        <input type="number" name="price" class="form-control" value="{{ $item->price }}" step="0.01" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">প্রস্তুতির সময় (মিনিট)</label>
                                        <input type="number" name="preparation_time" class="form-control" value="{{ $item->preparation_time }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">বিবরণ</label>
                                        <textarea name="description" class="form-control" rows="2">{{ $item->description }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">ছবি</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="is_available" class="form-check-input" id="avail{{ $item->id }}" {{ $item->is_available ? 'checked' : '' }}>
                                            <label class="form-check-label" for="avail{{ $item->id }}">পাওয়া যাচ্ছে</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="is_featured" class="form-check-input" id="feat{{ $item->id }}" {{ $item->is_featured ? 'checked' : '' }}>
                                            <label class="form-check-label" for="feat{{ $item->id }}">বিশেষ আইটেম</label>
                                        </div>
                                    </div>
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
        </div>
        @empty
        <div class="col-12 text-center py-5 text-muted">
            <i class="fas fa-utensils fa-3x mb-3 d-block opacity-25"></i>
            কোনো মেনু আইটেম নেই। নতুন আইটেম যোগ করুন।
        </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $items->withQueryString()->links() }}</div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>নতুন মেনু আইটেম</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('menu.items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ক্যাটাগরি *</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- বেছে নিন --</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">আইটেমের নাম *</label>
                            <input type="text" name="name" class="form-control" placeholder="যেমন: চিকেন বার্গার" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">দাম (৳) *</label>
                            <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">প্রস্তুতির সময় (মিনিট)</label>
                            <input type="number" name="preparation_time" class="form-control" value="15" min="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ক্রম (Sort Order)</label>
                            <input type="number" name="sort_order" class="form-control" value="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">বিবরণ</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="আইটেমের বিবরণ..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">ছবি (ঐচ্ছিক)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-6">
                            <div class="form-check">
                                <input type="checkbox" name="is_available" class="form-check-input" id="addAvail" checked>
                                <label class="form-check-label" for="addAvail">পাওয়া যাচ্ছে</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check">
                                <input type="checkbox" name="is_featured" class="form-check-input" id="addFeat">
                                <label class="form-check-label" for="addFeat">বিশেষ আইটেম</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
