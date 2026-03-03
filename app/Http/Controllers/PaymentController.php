<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show(Order $order)
    {
        $order->load(['items.menuItem', 'table', 'payment']);
        return view('billing.show', compact('order'));
    }

    public function process(Request $request, Order $order)
    {
        $request->validate([
            'method' => 'required|in:cash,card,mobile_banking,bkash,nagad',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        if ($order->payment) {
            return response()->json(['success' => false, 'message' => 'ইতিমধ্যে পেমেন্ট হয়েছে।'], 400);
        }

        $paidAmount = (float) $request->paid_amount;
        $change = $paidAmount - $order->total_amount;

        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'amount' => $order->total_amount,
            'paid_amount' => $paidAmount,
            'change_amount' => max(0, $change),
            'method' => $request->method,
            'transaction_id' => $request->transaction_id,
            'status' => 'completed',
        ]);

        $order->update(['status' => 'completed']);

        if ($order->restaurant_table_id) {
            RestaurantTable::where('id', $order->restaurant_table_id)->update(['status' => 'available']);
        }

        return response()->json([
            'success' => true,
            'change' => max(0, $change),
            'receipt_url' => route('payment.receipt', $order->id),
        ]);
    }

    public function receipt(Order $order)
    {
        $order->load(['items.menuItem', 'table', 'payment', 'user']);
        return view('billing.receipt', compact('order'));
    }

    public function printReceipt(Order $order)
    {
        $order->load(['items.menuItem', 'table', 'payment', 'user']);
        return view('billing.print', compact('order'));
    }
}
