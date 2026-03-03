@extends('layouts.app')

@section('title', 'সেটিং')
@section('page-title', 'সিস্টেম সেটিং')

@section('content')
<div class="content-area">
    <div class="row g-4">
        <!-- Restaurant Settings -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><i class="fas fa-store me-2 text-primary"></i>রেস্টুরেন্টের তথ্য</div>
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-bold">রেস্টুরেন্টের নাম *</label>
                            <input type="text" name="restaurant_name" class="form-control"
                                   value="{{ $settings['restaurant_name'] ?? 'POS Restaurant' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ফোন নম্বর</label>
                            <input type="text" name="restaurant_phone" class="form-control"
                                   value="{{ $settings['restaurant_phone'] ?? '' }}" placeholder="+880...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ইমেইল</label>
                            <input type="email" name="restaurant_email" class="form-control"
                                   value="{{ $settings['restaurant_email'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ঠিকানা</label>
                            <textarea name="restaurant_address" class="form-control" rows="2">{{ $settings['restaurant_address'] ?? '' }}</textarea>
                        </div>
                        <hr>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ভ্যাটের হার (%)</label>
                                <input type="number" name="tax_rate" class="form-control"
                                       value="{{ $settings['tax_rate'] ?? 5 }}" min="0" max="100" step="0.1">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">মুদ্রা</label>
                                <select name="currency" class="form-select">
                                    <option value="৳" {{ ($settings['currency'] ?? '৳') === '৳' ? 'selected' : '' }}>৳ বাংলাদেশি টাকা (BDT)</option>
                                    <option value="$" {{ ($settings['currency'] ?? '') === '$' ? 'selected' : '' }}>$ US Dollar</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label class="form-label">রিসিটের হেডার</label>
                            <textarea name="receipt_header" class="form-control" rows="2"
                                      placeholder="রিসিটের শীর্ষে প্রদর্শিত হবে">{{ $settings['receipt_header'] ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">রিসিটের ফুটার</label>
                            <textarea name="receipt_footer" class="form-control" rows="2"
                                      placeholder="আমাদের সেবা গ্রহণের জন্য ধন্যবাদ!">{{ $settings['receipt_footer'] ?? '' }}</textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>সেটিং সংরক্ষণ করুন
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Staff Management -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-users me-2 text-primary"></i>স্টাফ ম্যানেজমেন্ট</span>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus me-1"></i>নতুন স্টাফ
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr><th>নাম</th><th>ইমেইল</th><th>ভূমিকা</th><th>অবস্থা</th><th>একশন</th></tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td><strong>{{ $user->name }}</strong></td>
                                    <td class="small text-muted">{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'manager' ? 'warning' : 'info') }}">
                                            {{ ['admin' => 'এডমিন', 'manager' => 'ম্যানেজার', 'cashier' => 'ক্যাশিয়ার', 'waiter' => 'ওয়েটার', 'kitchen' => 'কিচেন'][$user->role] ?? $user->role }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                            {{ $user->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if($user->id !== auth()->id())
                                            <form action="{{ route('settings.users.destroy', $user->id) }}" method="POST"
                                                  onsubmit="return confirm('মুছবেন?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit User Modal -->
                                <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">স্টাফ সম্পাদনা</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('settings.users.update', $user->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">নাম</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">ইমেইল</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">ফোন</label>
                                                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">ভূমিকা</label>
                                                        <select name="role" class="form-select">
                                                            @foreach(['admin' => 'এডমিন', 'manager' => 'ম্যানেজার', 'cashier' => 'ক্যাশিয়ার', 'waiter' => 'ওয়েটার', 'kitchen' => 'কিচেন স্টাফ'] as $role => $label)
                                                            <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">নতুন পাসওয়ার্ড (ঐচ্ছিক)</label>
                                                        <input type="password" name="password" class="form-control" placeholder="পরিবর্তন না হলে খালি রাখুন">
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" name="is_active" class="form-check-input" id="active{{ $user->id }}" {{ $user->is_active ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="active{{ $user->id }}">সক্রিয় অ্যাকাউন্ট</label>
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>নতুন স্টাফ যোগ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('settings.users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">নাম *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">ইমেইল *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">ফোন</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ভূমিকা *</label>
                            <select name="role" class="form-select" required>
                                <option value="cashier">ক্যাশিয়ার</option>
                                <option value="waiter">ওয়েটার</option>
                                <option value="kitchen">কিচেন স্টাফ</option>
                                <option value="manager">ম্যানেজার</option>
                                <option value="admin">এডমিন</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">পাসওয়ার্ড *</label>
                            <input type="password" name="password" class="form-control" required minlength="6">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i>স্টাফ যোগ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
