# Coupons

Coupons let customers apply discount codes at checkout.

## Creating a Coupon

Go to **Admin → Shop → Settings → Coupons** (or wherever your CMS routes it):

| Field | Description |
|---|---|
| Code | The code customers enter (e.g., `SUMMER20`) |
| Discount Type | `percentage` or `fixed` |
| Discount Value | Amount (e.g., `20` for 20% or $20 off) |
| Usage Limit | Max times the coupon can be used (0 = unlimited) |
| Expiry Date | Optional expiration |
| Active | Enable/disable without deleting |

## How Coupons Work

1. Customer adds items to cart
2. On the cart page, enters the coupon code
3. If valid: discount is applied to the cart total
4. At checkout: discounted total is charged

## Coupon Types

### Percentage Discount
```
Order total: $100
Coupon: SAVE10 (10% off)
Discount: -$10
Final total: $90
```

### Fixed Amount Discount
```
Order total: $100
Coupon: FLAT15 ($15 off)
Discount: -$15
Final total: $85
```

## Coupon Model

```php
use Acme\CmsDashboard\Models\Coupon;

// Create a coupon
Coupon::create([
    'code'           => 'WELCOME50',
    'discount_type'  => 'percentage',
    'discount_value' => 50,
    'usage_limit'    => 100,
    'is_active'      => true,
    'expires_at'     => now()->addMonths(3),
]);

// Check if coupon is valid
$coupon = Coupon::where('code', 'WELCOME50')
    ->where('is_active', true)
    ->first();

if ($coupon && $coupon->usage_count < $coupon->usage_limit) {
    // Apply discount
}
```
