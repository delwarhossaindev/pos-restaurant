@php
    $settings = \App\Models\Setting::getValues();
    $isPrint = !isset($embedded);
@endphp
@if($isPrint)
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>রিসিট - {{ $order->order_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Hind Siliguri', monospace; }
        body { width: 300px; margin: 0 auto; padding: 10px; font-size: 12px; }
        .receipt { max-width: 300px; }
    </style>
</head>
<body onload="window.print()">
@endif

<div class="receipt" style="max-width:300px; margin:0 auto; padding:15px; font-family:'Hind Siliguri',monospace; font-size:12px; color:#000;">
    <!-- Header -->
    <div style="text-align:center; margin-bottom:10px; padding-bottom:8px; border-bottom:2px dashed #000;">
        <div style="font-size:16px; font-weight:700;">{{ $settings['restaurant_name'] ?? 'POS Restaurant' }}</div>
        @if(!empty($settings['restaurant_address']))
        <div style="font-size:11px;">{{ $settings['restaurant_address'] }}</div>
        @endif
        @if(!empty($settings['restaurant_phone']))
        <div style="font-size:11px;">📞 {{ $settings['restaurant_phone'] }}</div>
        @endif
    </div>

    <!-- Order Info -->
    <div style="margin-bottom:8px;">
        <div style="display:flex; justify-content:space-between;">
            <span>অর্ডার নং:</span>
            <strong>{{ $order->order_number }}</strong>
        </div>
        <div style="display:flex; justify-content:space-between;">
            <span>টেবিল:</span>
            <span>{{ $order->table ? 'টেবিল ' . $order->table->table_number : 'টেকওয়ে' }}</span>
        </div>
        <div style="display:flex; justify-content:space-between;">
            <span>তারিখ:</span>
            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div style="display:flex; justify-content:space-between;">
            <span>ক্যাশিয়ার:</span>
            <span>{{ $order->user?->name }}</span>
        </div>
    </div>

    <!-- Items -->
    <div style="border-top:1px dashed #000; border-bottom:1px dashed #000; padding:8px 0; margin-bottom:8px;">
        <div style="display:flex; font-weight:700; margin-bottom:5px;">
            <span style="flex:1">আইটেম</span>
            <span style="width:30px; text-align:center">পরি</span>
            <span style="width:60px; text-align:right">মোট</span>
        </div>
        @foreach($order->items as $item)
        <div style="display:flex; margin-bottom:3px;">
            <span style="flex:1; font-size:11px;">{{ $item->menuItem?->name }}</span>
            <span style="width:30px; text-align:center;">{{ $item->quantity }}</span>
            <span style="width:60px; text-align:right;">৳{{ number_format($item->total_price, 0) }}</span>
        </div>
        <div style="font-size:10px; color:#666; padding-left:5px;">
            ৳{{ number_format($item->unit_price, 0) }} × {{ $item->quantity }}
        </div>
        @endforeach
    </div>

    <!-- Totals -->
    <div style="margin-bottom:8px;">
        <div style="display:flex; justify-content:space-between;">
            <span>সাবটোটাল</span>
            <span>৳{{ number_format($order->subtotal, 0) }}</span>
        </div>
        <div style="display:flex; justify-content:space-between;">
            <span>ভ্যাট ({{ $settings['tax_rate'] ?? 5 }}%)</span>
            <span>৳{{ number_format($order->tax_amount, 0) }}</span>
        </div>
        @if($order->discount_amount > 0)
        <div style="display:flex; justify-content:space-between;">
            <span>ছাড়</span>
            <span>-৳{{ number_format($order->discount_amount, 0) }}</span>
        </div>
        @endif
        <div style="display:flex; justify-content:space-between; font-size:14px; font-weight:700; border-top:1px solid #000; padding-top:5px; margin-top:5px;">
            <span>মোট</span>
            <span>৳{{ number_format($order->total_amount, 0) }}</span>
        </div>
    </div>

    @if($order->payment)
    <!-- Payment Info -->
    <div style="border-top:1px dashed #000; padding-top:8px; margin-bottom:8px;">
        <div style="display:flex; justify-content:space-between;">
            <span>পেমেন্ট পদ্ধতি</span>
            <span>{{ $order->payment->method_label }}</span>
        </div>
        <div style="display:flex; justify-content:space-between;">
            <span>প্রদত্ত</span>
            <span>৳{{ number_format($order->payment->paid_amount, 0) }}</span>
        </div>
        @if($order->payment->change_amount > 0)
        <div style="display:flex; justify-content:space-between;">
            <span>ফেরত</span>
            <span>৳{{ number_format($order->payment->change_amount, 0) }}</span>
        </div>
        @endif
    </div>
    @endif

    <!-- Footer -->
    <div style="text-align:center; border-top:2px dashed #000; padding-top:8px; font-size:11px; color:#555;">
        @if(!empty($settings['receipt_footer']))
        <div>{{ $settings['receipt_footer'] }}</div>
        @else
        <div>আমাদের সেবা গ্রহণ করার জন্য ধন্যবাদ!</div>
        <div>আবার আসবেন 🙏</div>
        @endif
        <div style="margin-top:5px; font-size:10px;">Powered by POS Restaurant System</div>
    </div>
</div>

@if($isPrint)
</body>
</html>
@endif
