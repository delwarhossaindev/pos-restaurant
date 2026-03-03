@extends('layouts.app')

@section('title', 'রিসিট')
@section('page-title', 'রিসিট')

@section('content')
<div class="content-area">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body p-0">
                    @include('billing.print', ['order' => $order, 'embedded' => true])
                </div>
                <div class="card-footer d-flex gap-2 justify-content-center">
                    <a href="{{ route('payment.print', $order->id) }}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-print me-2"></i>প্রিন্ট করুন
                    </a>
                    <a href="{{ route('pos') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>নতুন অর্ডার
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-2"></i>হোম
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
