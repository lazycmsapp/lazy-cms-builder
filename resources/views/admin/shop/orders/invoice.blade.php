<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number ?: $order->id }} | {{ get_cms_option('site_title', 'Lazy CMS') }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            color: #1a1a1a;
            line-height: 1.5;
            margin: 0;
            padding: 40px;
            background: #fff;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 30px;
            margin-bottom: 40px;
        }

        .logo-section h1 {
            margin: 0;
            font-size: 28px;
            color: #1363df;
            letter-spacing: -0.5px;
        }

        .invoice-details {
            text-align: right;
        }

        .invoice-details h2 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            color: #4b5563;
        }

        .invoice-details p {
            margin: 5px 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        .address-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .address-box h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: #9ca3af;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .address-box p {
            margin: 0;
            font-size: 15px;
            color: #374151;
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .order-table th {
            background: #f9fafb;
            text-align: left;
            padding: 12px 15px;
            font-size: 13px;
            text-transform: uppercase;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-table td {
            padding: 15px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
        }

        .totals-section {
            display: flex;
            justify-content: flex-end;
        }

        .totals-table {
            width: 250px;
        }

        .totals-table tr td {
            padding: 8px 0;
            font-size: 14px;
        }

        .totals-table tr td:last-child {
            text-align: right;
            font-weight: 600;
        }

        .total-row td {
            border-top: 2px solid #1363df;
            padding-top: 15px !important;
            font-size: 18px !important;
            color: #1363df;
        }

        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #f3f4f6;
            padding-top: 20px;
        }

        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }

        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #1363df;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(19, 99, 223, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">
        <span>Print Invoice</span>
    </button>

    <div class="invoice-container">
        <header>
            <div class="logo-section">
                <h1>{{ get_cms_option('site_name', 'Lazy Panda') }}</h1>
                <p style="font-size: 13px; color: #6b7280; margin-top: 5px;">
                    {{ get_shop_option('shop_address_line_1') }}<br>
                    {{ get_shop_option('shop_city') }}, {{ get_shop_option('shop_postcode') }}
                </p>
            </div>
            <div class="invoice-details">
                <h2>Invoice</h2>
                <p>#{{ $order->order_number ?: $order->id }}</p>
                <p>Date: {{ $order->created_at->format('M d, Y') }}</p>
            </div>
        </header>

        <div class="address-section">
            <div class="address-box">
                <h3>Billing To</h3>
                <p><strong>{{ $order->first_name }} {{ $order->last_name }}</strong></p>
                <p>{{ $order->address_line_1 }}</p>
                @if($order->address_line_2) <p>{{ $order->address_line_2 }}</p> @endif
                <p>{{ $order->city }}, {{ $order->state }} {{ $order->postcode }}</p>
                <p>{{ $order->country }}</p>
                <p>Email: {{ $order->customer_email }}</p>
                <p>Phone: {{ $order->customer_phone }}</p>
            </div>
            @if($order->shipping_address_line_1)
            <div class="address-box">
                <h3>Shipping To</h3>
                <p><strong>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</strong></p>
                <p>{{ $order->shipping_address_line_1 }}</p>
                @if($order->shipping_address_line_2) <p>{{ $order->shipping_address_line_2 }}</p> @endif
                <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postcode }}</p>
                <p>{{ $order->shipping_country }}</p>
            </div>
            @endif
        </div>

        <table class="order-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: center;">Price</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <div style="font-weight: 600;">{{ $item->product_name }}</div>
                        @if($item->variation_details)
                            <div style="font-size: 11px; color: #6b7280;">{{ $item->variation_details }}</div>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ lazy_price_format($item->price, $order) }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ lazy_price_format($item->subtotal, $order) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Subtotal</td>
                    <td>{{ lazy_price_format($order->subtotal, $order) }}</td>
                </tr>
                <tr>
                    <td>Shipping ({{ $order->shipping_method ?: 'Standard' }})</td>
                    <td>{{ $order->shipping_total > 0 ? lazy_price_format($order->shipping_total, $order) : 'Free' }}</td>
                </tr>
                @if($order->tax_total > 0)
                <tr>
                    <td>Tax</td>
                    <td>{{ lazy_price_format($order->tax_total, $order) }}</td>
                </tr>
                @endif
                @if($order->discount_total > 0)
                <tr style="color: #059669;">
                    <td>Discount</td>
                    <td>-{{ lazy_price_format($order->discount_total, $order) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>Total</td>
                    <td>{{ lazy_price_format($order->total, $order) }}</td>
                </tr>
            </table>
        </div>

        @if($order->customer_note)
        <div style="margin-top: 40px; padding: 20px; background: #f9fafb; border-radius: 8px;">
            <h3 style="font-size: 12px; text-transform: uppercase; color: #9ca3af; margin-top: 0;">Customer Note</h3>
            <p style="font-size: 14px; margin: 0; font-style: italic;">"{{ $order->customer_note }}"</p>
        </div>
        @endif

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>&copy; {{ date('Y') }} {{ get_cms_option('site_name', 'Lazy Panda') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
