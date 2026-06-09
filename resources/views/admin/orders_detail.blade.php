@extends('layouts.admin') {{-- Atau sesuaikan dengan layout utamamu --}}

@section('content')
<style>
    .yomono-btn-black {
        background-color: #000000;
        color: #ffffff;
        border: 1px solid #000000;
        padding: 10px 15px;
        font-size: 11px;
        font-weight: bold;
        letter-spacing: 1px;
        text-transform: uppercase;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .yomono-btn-black:hover {
        background-color: #333333;
        color: #ffffff;
    }
    .yomono-btn-outline {
        background-color: transparent;
        color: #000000;
        border: 1px solid #000000;
        padding: 10px 15px;
        font-size: 11px;
        font-weight: bold;
        letter-spacing: 1px;
        text-transform: uppercase;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .yomono-btn-outline:hover {
        background-color: #000000;
        color: #ffffff;
    }
    .yomono-badge-paid {
        background-color: #e6f7ed;
        color: #1dd1a1;
        padding: 3px 8px;
        font-size: 11px;
        font-weight: bold;
        border-radius: 3px;
    }
    .yomono-badge-status {
        background-color: #f1f2f6;
        color: #2f3542;
        padding: 3px 8px;
        font-size: 11px;
        font-weight: bold;
        border-radius: 3px;
        text-transform: uppercase;
    }
</style>

<div class="container-fluid" style="padding: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h2 style="font-weight: 300; margin-bottom: 5px; font-size: 28px;">Order Details #{{ $order->id }}</h2>
            <p style="color: #888; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Manage Single Transaction Item</p>
        </div>
        <div>
            <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="yomono-btn-black">
                <i class="fa fa-print"></i> PRINT INVOICE
            </a>
        </div>
    </div>

    <div style="display: flex; gap: 30px; align-items: flex-start;">
        
        <div style="flex: 1; display: flex; flex-direction: column; gap: 30px;">
            
            <div style="background: #ffffff; border: 1px solid #f0f0f0; padding: 25px;">
                <h5 style="font-size: 12px; letter-spacing: 1px; color: #999; margin-bottom: 20px; text-transform: uppercase;">Customer & Shipping Information</h5>
                <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; width: 150px; font-weight: bold; text-transform: uppercase; font-size: 11px;">Name:</td>
                        <td style="padding: 8px 0; color: #333;">{{ $order->user->name ?? 'Guest User' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold; text-transform: uppercase; font-size: 11px;">Email:</td>
                        <td style="padding: 8px 0; color: #333;">{{ $order->user->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold; text-transform: uppercase; font-size: 11px;">Phone:</td>
                        <td style="padding: 8px 0; color: #333;">{{ $order->user->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold; text-transform: uppercase; font-size: 11px; vertical-align: top;">Shipping Address:</td>
                        <td style="padding: 8px 0; color: #555; line-height: 1.6; background: #f9f9f9; padding: 10px; border: 1px solid #eee;">
                            {{ $order->shipping_address }}
                        </td>
                    </tr>
                </table>
            </div>

            <div style="background: #ffffff; border: 1px solid #f0f0f0; padding: 25px;">
                <h5 style="font-size: 12px; letter-spacing: 1px; color: #999; margin-bottom: 20px; text-transform: uppercase;">Order Status</h5>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; font-size: 13px;">
                    <span style="color: #777; text-transform: uppercase; font-size: 11px;">Payment Status:</span>
                    <span class="yomono-badge-paid">{{ strtoupper($order->payment_status) }}</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; font-size: 13px;">
                    <span style="color: #777; text-transform: uppercase; font-size: 11px;">Delivery Milestone:</span>
                    <span class="yomono-badge-status">
                        @if($order->status === 'pending') DIPROSES
                        @elif($order->status === 'shipped') DIKIRIM
                        @elif($order->status === 'completed') SELESAI
                        @else {{ $order->status }}
                        @endif
                    </span>
                </div>

                @if($order->tracking_number)
                <div style="margin-bottom: 20px; padding: 10px; background: #f5f6fa; border: 1px dashed #ced6e0; font-family: monospace; font-size: 12px; color: #2f3542;">
                    <i class="fa fa-truck"></i> NO. RESI: {{ strtoupper($order->tracking_number) }}
                </div>
                @endif

                <div style="margin-top: 25px; display: flex; flex-direction: column; gap: 10px;">
                    @if($order->status === 'completed')
                        <div style="background-color: #e6f7ed; color: #1dd1a1; padding: 12px; text-align: center; font-size: 12px; font-weight: bold;">
                            <i class="fa fa-check-circle"></i> PESANAN TELAH SELESAI
                        </div>
                    @else
                        <button type="button" class="yomono-btn-outline" data-bs-toggle="modal" data-bs-target="#updateTrackingModal" style="width: 100%;">
                            <i class="fa fa-exchange"></i> UPDATE TRACKING STATUS
                        </button>
                    @endif
                </div>
            </div>

        </div>

        <div style="width: 450px; background: #ffffff; border: 1px solid #f0f0f0; padding: 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
            <div style="text-align: center; margin-bottom: 30px;">
                <h3 style="letter-spacing: 2px; font-weight: bold; margin-bottom: 5px; font-size: 18px;">YOMONO STORE</h3>
                <p style="font-size: 10px; color: #999; text-transform: uppercase; letter-spacing: 1px;">Minimalist Ready-To-Wear</p>
                <p style="font-size: 11px; color: #555; margin-top: 5px;">Jakarta, Indonesia</p>
            </div>

            <hr style="border: 0; border-top: 1px dashed #ddd; margin: 20px 0;">

            <table style="width: 100%; font-size: 12px; color: #555; margin-bottom: 20px;">
                <tr>
                    <td style="padding: 4px 0; color: #999; text-transform: uppercase;">Invoice No:</td>
                    <td style="padding: 4px 0; text-align: right; font-weight: bold; color: #111;">#INV/{{ \Carbon\Carbon::parse($order->created_at)->format('Ymd') }}/{{ $order->id }}</td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; color: #999; text-transform: uppercase;">Date/Time:</td>
                    <td style="padding: 4px 0; text-align: right; color: #111;">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; color: #999; text-transform: uppercase;">Customer:</td>
                    <td style="padding: 4px 0; text-align: right; font-weight: bold; color: #111; text-transform: uppercase;">{{ $order->user->name ?? 'Guest' }}</td>
                </tr>
            </table>

            <hr style="border: 0; border-top: 1px solid #111; margin: 15px 0;">

            <div style="margin-bottom: 20px;">
                <p style="font-size: 10px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Item Description</p>
                
                @foreach($order->orderItems as $item)
                <div style="margin-bottom: 15px; font-size: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; font-weight: bold; color: #111; line-height: 1.4;">
                        <span style="max-width: 280px;">{{ $item->variant->product->name ?? 'Product Item' }}</span>
                        <span>{{ $item->quantity }}x</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; color: #777; font-size: 11px; margin-top: 3px;">
                        <span>({{ $item->variant->color->name ?? '-' }} / {{ $item->variant->size->name ?? '-' }})</span>
                        <span>IDR {{ number_format($item->price, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <hr style="border: 0; border-top: 1px dashed #ddd; margin: 20px 0;">

            <table style="width: 100%; font-size: 12px; color: #444;">
                <tr>
                    <td style="padding: 5px 0; color: #777;">Subtotal</td>
                    <td style="padding: 5px 0; text-align: right; font-weight: bold; color: #111;">IDR {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0; color: #777;">Shipping Fees</td>
                    <td style="padding: 5px 0; text-align: right; color: #1dd1a1; font-weight: bold; font-size: 11px; text-transform: uppercase;">Free Shipping</td>
                </tr>
                <tr style="font-size: 14px; font-weight: bold; color: #000;">
                    <td style="padding: 15px 0 0 0; border-top: 1px solid #111; text-transform: uppercase; letter-spacing: 1px;">Grand Total</td>
                    <td style="padding: 15px 0 0 0; border-top: 1px solid #111; text-align: right; font-size: 16px;">IDR {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
            </table>

            <div style="text-align: center; margin-top: 40px; font-size: 10px; color: #aaa; letter-spacing: 1px; text-transform: uppercase;">
                Thank you for shopping with us.<br>
                <strong style="color: #666; display: block; margin-top: 5px;">www.yomono.id</strong>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="updateTrackingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 0; border: 1px solid #000;">
            <div class="modal-header" style="background: #000; color: #fff; border-radius: 0;">
                <h5 class="modal-title" style="font-size: 12px; letter-spacing: 1px; font-weight: bold; text-transform: uppercase;">Update Order Tracking</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body" style="padding: 25px;">
                    
                    <div class="mb-4">
                        <label class="form-label" style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: #555; display: block; margin-bottom: 8px;">Select Delivery Milestone</label>
                        <select name="status" class="form-select" style="border-radius: 0; border: 1px solid #ccc; font-size: 12px; padding: 10px;" required>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>DIPROSES (DALAM ANTREAN / DIKEMAS TOKO)</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>DIKIRIM (SEDANG DALAM PERJALANAN KURIR)</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>SELESAI (PAKET SUDAH DITERIMA CUSTOMER)</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label" style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: #555; display: block; margin-bottom: 8px;">Tracking Number / Nomor Resi</label>
                        <input type="text" name="tracking_number" class="form-control" style="border-radius: 0; border: 1px solid #ccc; font-size: 12px; padding: 10px; font-family: monospace;" placeholder="Contoh: JNE98234123" value="{{ $order->tracking_number }}">
                    </div>

                </div>
                <div class="modal-footer" style="background: #f9f9f9; border-top: 1px solid #eee;">
                    <button type="button" class="yomono-btn-outline" data-bs-dismiss="modal" style="padding: 7px 15px;">Cancel</button>
                    <button type="submit" class="yomono-btn-black" style="padding: 7px 20px;">Save Milestone</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection