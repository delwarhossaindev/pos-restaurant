@extends('layouts.app')

@section('title', 'POS অর্ডার')
@section('page-title', 'নতুন অর্ডার')

@push('styles')
<style>
.pos-container { display: flex; height: calc(100vh - 65px); overflow: hidden; }
.menu-panel { flex: 1; overflow-y: auto; padding: 15px; background: #f0f2f5; }
.cart-panel { width: 380px; background: #fff; display: flex; flex-direction: column; border-left: 1px solid #eee; }
.cart-header { padding: 15px; border-bottom: 1px solid #eee; background: #1a1a2e; color: #fff; }
.cart-items { flex: 1; overflow-y: auto; padding: 10px; }
.cart-footer { padding: 15px; border-top: 1px solid #eee; background: #fff; }

/* Category tabs */
.category-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 15px; }
.cat-btn {
    padding: 8px 18px; border-radius: 25px; border: 2px solid #ddd;
    cursor: pointer; background: #fff; font-size: 0.85rem;
    transition: all 0.2s; white-space: nowrap;
    font-family: 'Hind Siliguri', sans-serif;
}
.cat-btn.active { background: #FF6B35; border-color: #FF6B35; color: #fff; }
.cat-btn:hover { border-color: #FF6B35; color: #FF6B35; }

/* Menu items grid */
.menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px; }
.menu-item {
    background: #fff; border-radius: 12px; padding: 15px;
    cursor: pointer; transition: all 0.2s; border: 2px solid transparent;
    text-align: center; position: relative;
}
.menu-item:hover { border-color: #FF6B35; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(255,107,53,0.15); }
.menu-item.unavailable { opacity: 0.5; cursor: not-allowed; }
.menu-item .item-name { font-weight: 600; font-size: 0.85rem; color: #333; margin-bottom: 5px; }
.menu-item .item-price { color: #FF6B35; font-weight: 700; font-size: 1rem; }
.menu-item .item-cat { font-size: 0.7rem; color: #999; }
.menu-item .add-btn {
    position: absolute; bottom: -10px; right: 10px;
    background: #FF6B35; color: #fff; border: none;
    border-radius: 50%; width: 28px; height: 28px;
    font-size: 1rem; display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: all 0.2s;
}
.menu-item:hover .add-btn { opacity: 1; }

/* Cart items */
.cart-item {
    background: #f8f9fa; border-radius: 10px; padding: 10px 12px;
    margin-bottom: 8px; display: flex; align-items: center; gap: 10px;
}
.cart-item .item-info { flex: 1; }
.cart-item .item-name { font-weight: 600; font-size: 0.85rem; }
.cart-item .item-price { font-size: 0.8rem; color: #FF6B35; }
.cart-item .qty-controls { display: flex; align-items: center; gap: 5px; }
.qty-btn {
    width: 26px; height: 26px; border-radius: 50%;
    border: 1px solid #ddd; background: #fff;
    font-size: 0.9rem; cursor: pointer; display: flex;
    align-items: center; justify-content: center;
    transition: all 0.2s;
}
.qty-btn:hover { background: #FF6B35; color: #fff; border-color: #FF6B35; }
.qty-display { width: 30px; text-align: center; font-weight: 600; }
.remove-btn { color: #dc3545; cursor: pointer; background: none; border: none; padding: 2px 5px; }

/* Table selector */
.table-selector { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 5px; }
.table-btn {
    flex-shrink: 0; padding: 6px 12px; border-radius: 8px; border: 2px solid #ddd;
    cursor: pointer; background: #fff; font-size: 0.8rem;
    font-family: 'Hind Siliguri', sans-serif;
}
.table-btn.active { background: #FF6B35; border-color: #FF6B35; color: #fff; }
.table-btn.occupied { border-color: #e74c3c; color: #e74c3c; }

/* Summary */
.summary-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 0.9rem; }
.summary-row.total { font-weight: 700; font-size: 1.1rem; color: #FF6B35; border-top: 2px solid #eee; padding-top: 8px; margin-top: 5px; }
</style>
@endpush

@section('content')
<div class="pos-container">
    <!-- Left: Menu Panel -->
    <div class="menu-panel">
        <!-- Table & Order Type selector -->
        <div class="card mb-3 p-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold mb-1">অর্ডারের ধরন</label>
                    <select id="orderType" class="form-select form-select-sm">
                        <option value="dine_in">🍽️ ডাইন ইন</option>
                        <option value="takeaway">🥡 টেকওয়ে</option>
                        <option value="delivery">🛵 ডেলিভারি</option>
                    </select>
                </div>
                <div class="col-md-4" id="tableSection">
                    <label class="form-label small fw-bold mb-1">টেবিল বেছে নিন</label>
                    <select id="tableSelect" class="form-select form-select-sm">
                        <option value="">-- টেবিল নেই --</option>
                        @foreach($tables as $table)
                        <option value="{{ $table->id }}"
                            {{ $selectedTableId == $table->id ? 'selected' : '' }}
                            data-status="{{ $table->status }}">
                            টেবিল {{ $table->table_number }}
                            ({{ $table->status === 'available' ? 'খালি' : 'ব্যস্ত' }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">অতিথি সংখ্যা</label>
                    <input type="number" id="guestCount" class="form-control form-control-sm" value="1" min="1" max="20">
                </div>
                <div class="col-md-2">
                    @if($activeOrder)
                    <span class="badge bg-warning text-dark p-2">সক্রিয় অর্ডার: {{ $activeOrder->order_number }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="mb-3">
            <input type="text" id="menuSearch" class="form-control" placeholder="🔍 আইটেম খুঁজুন...">
        </div>

        <!-- Category Tabs -->
        <div class="category-tabs">
            <button class="cat-btn active" data-category="all">সব আইটেম</button>
            @foreach($categories as $cat)
            <button class="cat-btn" data-category="{{ $cat->id }}">
                <i class="{{ $cat->icon ?? 'fas fa-utensils' }} me-1"></i>{{ $cat->name }}
            </button>
            @endforeach
        </div>

        <!-- Menu Items Grid -->
        <div class="menu-grid" id="menuGrid">
            @foreach($categories as $cat)
                @foreach($cat->activeMenuItems as $item)
                <div class="menu-item" data-category="{{ $cat->id }}" data-id="{{ $item->id }}"
                     data-name="{{ $item->name }}" data-price="{{ $item->price }}"
                     onclick="addToCart({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }})">
                    <div class="item-cat">{{ $cat->name }}</div>
                    <div class="item-name">{{ $item->name }}</div>
                    <div class="item-price">৳{{ number_format($item->price, 0) }}</div>
                    <div class="text-muted" style="font-size:0.65rem"><i class="fas fa-clock"></i> {{ $item->preparation_time }} মিনিট</div>
                    <button class="add-btn"><i class="fas fa-plus"></i></button>
                </div>
                @endforeach
            @endforeach
        </div>
    </div>

    <!-- Right: Cart Panel -->
    <div class="cart-panel">
        <div class="cart-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>অর্ডার কার্ট</h6>
                <button class="btn btn-sm btn-outline-light" onclick="clearCart()">
                    <i class="fas fa-trash"></i> মুছুন
                </button>
            </div>
            <div class="mt-2 small" id="cartTableInfo">কোনো টেবিল নির্বাচিত নয়</div>
        </div>

        <!-- Customer Info (for takeaway/delivery) -->
        <div id="customerSection" class="p-3 border-bottom d-none">
            <div class="mb-2">
                <input type="text" id="customerName" class="form-control form-control-sm" placeholder="কাস্টমারের নাম">
            </div>
            <div>
                <input type="text" id="customerPhone" class="form-control form-control-sm" placeholder="ফোন নম্বর">
            </div>
        </div>

        <!-- Cart Items -->
        <div class="cart-items" id="cartItems">
            <div class="text-center text-muted py-5" id="emptyCartMsg">
                <i class="fas fa-shopping-cart fa-2x mb-2 d-block opacity-25"></i>
                কার্টে কিছু নেই<br>
                <small>বাম পাশ থেকে আইটেম যোগ করুন</small>
            </div>
        </div>

        <!-- Notes -->
        <div class="px-3 pb-2">
            <textarea id="orderNotes" class="form-control form-control-sm" rows="2" placeholder="বিশেষ নোট (ঐচ্ছিক)..."></textarea>
        </div>

        <!-- Cart Footer / Summary -->
        <div class="cart-footer">
            <div class="summary-row">
                <span class="text-muted">সাবটোটাল</span>
                <span id="subtotal">৳0</span>
            </div>
            <div class="summary-row">
                <span class="text-muted">ভ্যাট ({{ config('pos.tax_rate', 5) }}%)</span>
                <span id="taxAmount">৳0</span>
            </div>
            <div class="summary-row">
                <span class="text-muted">ছাড়</span>
                <div class="d-flex align-items-center gap-1">
                    <span>-৳</span>
                    <input type="number" id="discountInput" class="form-control form-control-sm"
                           style="width:70px" value="0" min="0" onchange="updateSummary()">
                </div>
            </div>
            <div class="summary-row total">
                <span>মোট</span>
                <span id="totalAmount">৳0</span>
            </div>

            <div class="d-grid mt-3">
                <button class="btn btn-success btn-lg fw-bold" onclick="placeOrder()" id="placeOrderBtn" disabled>
                    <i class="fas fa-check-circle me-2"></i>অর্ডার দিন
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-money-bill me-2"></i>পেমেন্ট</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h4 class="text-success">মোট: ৳<span id="modalTotal">0</span></h4>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">পেমেন্ট পদ্ধতি</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="payment-method-btn active" data-method="cash">
                                <i class="fas fa-money-bill-wave fa-lg d-block mb-1"></i>নগদ
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="payment-method-btn" data-method="card">
                                <i class="fas fa-credit-card fa-lg d-block mb-1"></i>কার্ড
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="payment-method-btn" data-method="bkash">
                                <i class="fas fa-mobile-alt fa-lg d-block mb-1"></i>বিকাশ
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="payment-method-btn" data-method="nagad">
                                <i class="fas fa-wallet fa-lg d-block mb-1"></i>নগদ (অ্যাপ)
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3" id="paidAmountSection">
                    <label class="form-label fw-bold">প্রদত্ত টাকা</label>
                    <input type="number" id="paidAmount" class="form-control form-control-lg text-center"
                           placeholder="0" oninput="calcChange()">
                </div>
                <div class="alert alert-info d-none" id="changeSection">
                    <strong>ফেরত: ৳<span id="changeAmount">0</span></strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-success btn-lg px-4" onclick="processPayment()" id="confirmPayBtn">
                    <i class="fas fa-check me-2"></i>পেমেন্ট নিশ্চিত করুন
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.payment-method-btn {
    border: 2px solid #ddd; border-radius: 10px; padding: 12px;
    text-align: center; cursor: pointer; transition: all 0.2s; font-size: 0.85rem;
}
.payment-method-btn.active { border-color: #FF6B35; background: #fff8f5; color: #FF6B35; font-weight: 600; }
.payment-method-btn:hover { border-color: #FF6B35; }
</style>
@endpush

@push('scripts')
<script>
const TAX_RATE = {{ \App\Models\Setting::getValue('tax_rate', 5) }};
let cart = [];
let currentOrderId = null;
let selectedPaymentMethod = 'cash';

// Add to cart
function addToCart(id, name, price) {
    const existing = cart.find(i => i.id === id);
    if (existing) {
        existing.qty++;
    } else {
        cart.push({ id, name, price: parseFloat(price), qty: 1 });
    }
    renderCart();

    // Animate
    const item = document.querySelector(`.menu-item[data-id="${id}"]`);
    if (item) {
        item.style.borderColor = '#FF6B35';
        setTimeout(() => item.style.borderColor = '', 500);
    }
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
}

function updateQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) removeFromCart(id);
    else renderCart();
}

function clearCart() {
    if (cart.length === 0) return;
    if (!confirm('কার্ট মুছে দিতে চান?')) return;
    cart = [];
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cartItems');
    const emptyMsg = document.getElementById('emptyCartMsg');
    const placeBtn = document.getElementById('placeOrderBtn');

    if (cart.length === 0) {
        container.innerHTML = '';
        emptyMsg.classList.remove('d-none');
        placeBtn.disabled = true;
        updateSummary();
        return;
    }

    emptyMsg.classList.add('d-none');
    placeBtn.disabled = false;

    container.innerHTML = cart.map(item => `
        <div class="cart-item">
            <div class="item-info">
                <div class="item-name">${item.name}</div>
                <div class="item-price">৳${(item.price * item.qty).toFixed(0)} (৳${item.price.toFixed(0)} × ${item.qty})</div>
            </div>
            <div class="qty-controls">
                <button class="qty-btn" onclick="updateQty(${item.id}, -1)">−</button>
                <span class="qty-display">${item.qty}</span>
                <button class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
                <button class="remove-btn" onclick="removeFromCart(${item.id})"><i class="fas fa-times"></i></button>
            </div>
        </div>
    `).join('');

    updateSummary();
}

function updateSummary() {
    const subtotal = cart.reduce((sum, i) => sum + i.price * i.qty, 0);
    const tax = subtotal * TAX_RATE / 100;
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const total = subtotal + tax - discount;

    document.getElementById('subtotal').textContent = '৳' + subtotal.toFixed(0);
    document.getElementById('taxAmount').textContent = '৳' + tax.toFixed(0);
    document.getElementById('totalAmount').textContent = '৳' + Math.max(0, total).toFixed(0);
}

// Category filter
document.querySelectorAll('.cat-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const cat = this.dataset.category;
        document.querySelectorAll('.menu-item').forEach(item => {
            item.style.display = (cat === 'all' || item.dataset.category === cat) ? '' : 'none';
        });
    });
});

// Search
document.getElementById('menuSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.menu-item').forEach(item => {
        const name = item.dataset.name.toLowerCase();
        item.style.display = name.includes(q) ? '' : 'none';
    });
});

// Order type
document.getElementById('orderType').addEventListener('change', function() {
    const isDineIn = this.value === 'dine_in';
    document.getElementById('tableSection').style.display = isDineIn ? '' : 'none';
    document.getElementById('customerSection').classList.toggle('d-none', isDineIn);
});

// Table select
document.getElementById('tableSelect').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    document.getElementById('cartTableInfo').textContent = opt.value ? 'টেবিল: ' + opt.text : 'কোনো টেবিল নির্বাচিত নয়';
});

// Set initial table info
const initTable = document.getElementById('tableSelect');
if (initTable.value) {
    document.getElementById('cartTableInfo').textContent = 'টেবিল: ' + initTable.options[initTable.selectedIndex].text;
}

// Place order
async function placeOrder() {
    if (cart.length === 0) { alert('কার্টে কিছু নেই!'); return; }

    const orderType = document.getElementById('orderType').value;
    const tableId = document.getElementById('tableSelect').value;
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const subtotal = cart.reduce((sum, i) => sum + i.price * i.qty, 0);
    const tax = subtotal * TAX_RATE / 100;
    const total = Math.max(0, subtotal + tax - discount);

    document.getElementById('modalTotal').textContent = total.toFixed(0);
    document.getElementById('paidAmount').value = total.toFixed(0);

    const payModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    payModal.show();
}

// Payment method selection
document.querySelectorAll('.payment-method-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.payment-method-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        selectedPaymentMethod = this.dataset.method;
        const isCash = selectedPaymentMethod === 'cash';
        document.getElementById('paidAmountSection').style.display = isCash ? '' : 'none';
        if (!isCash) document.getElementById('changeSection').classList.add('d-none');
    });
});

function calcChange() {
    const paid = parseFloat(document.getElementById('paidAmount').value) || 0;
    const total = parseFloat(document.getElementById('modalTotal').textContent) || 0;
    const change = paid - total;
    const changeSection = document.getElementById('changeSection');
    if (change >= 0) {
        document.getElementById('changeAmount').textContent = change.toFixed(0);
        changeSection.classList.remove('d-none');
    } else {
        changeSection.classList.add('d-none');
    }
}

async function processPayment() {
    const btn = document.getElementById('confirmPayBtn');
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>প্রক্রিয়া হচ্ছে...';

    // First create the order
    const orderData = {
        order_type: document.getElementById('orderType').value,
        restaurant_table_id: document.getElementById('tableSelect').value || null,
        guests: parseInt(document.getElementById('guestCount').value),
        notes: document.getElementById('orderNotes').value,
        customer_name: document.getElementById('customerName')?.value || null,
        customer_phone: document.getElementById('customerPhone')?.value || null,
        items: cart.map(i => ({ menu_item_id: i.id, quantity: i.qty })),
    };

    try {
        const orderRes = await fetch('{{ route("orders.store") }}', {
            method: 'POST',
            headers: window.ajaxHeaders,
            body: JSON.stringify(orderData)
        });
        const orderJson = await orderRes.json();

        if (!orderJson.success) throw new Error(orderJson.message || 'অর্ডার তৈরি হয়নি');

        const orderId = orderJson.order_id;
        const discount = parseFloat(document.getElementById('discountInput').value) || 0;

        // Apply discount if any
        if (discount > 0) {
            await fetch(`/orders/${orderId}/discount`, {
                method: 'POST',
                headers: window.ajaxHeaders,
                body: JSON.stringify({ discount })
            });
        }

        // Process payment
        const paidAmount = selectedPaymentMethod === 'cash'
            ? parseFloat(document.getElementById('paidAmount').value)
            : parseFloat(document.getElementById('modalTotal').textContent);

        const payRes = await fetch(`/billing/${orderId}/pay`, {
            method: 'POST',
            headers: window.ajaxHeaders,
            body: JSON.stringify({
                method: selectedPaymentMethod,
                paid_amount: paidAmount,
                transaction_id: null,
            })
        });
        const payJson = await payRes.json();

        if (!payJson.success) throw new Error(payJson.message || 'পেমেন্ট ব্যর্থ');

        // Success - redirect to receipt
        window.location.href = payJson.receipt_url;

    } catch(e) {
        alert('ত্রুটি: ' + e.message);
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check me-2"></i>পেমেন্ট নিশ্চিত করুন';
    }
}

// Initial render
renderCart();
updateSummary();
</script>
@endpush
