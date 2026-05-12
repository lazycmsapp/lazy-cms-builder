@if($type === 'product')
<div class="wp-metabox mt-6 mb-6">
    <div class="wp-metabox-header"><span>Product Data</span></div>
    <div class="wp-metabox-content" style="padding: 16px;">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Pricing -->
            <div class="space-y-4">
                <h3 class="text-[14px] font-bold text-[#1d2327] border-b border-[#f0f0f1] pb-2">Pricing</h3>
                <div class="field-row">
                    <label class="block text-[13px] font-medium text-[#1d2327] mb-1">Regular Price ($)</label>
                    <input type="number" id="regular_price" name="price" step="0.01" value="{{ old('price', $post->shopData->price ?? '') }}" class="wp-input w-full @error('price') border-[#d63638] @enderror" placeholder="0.00">
                    @error('price')
                        <p class="text-[#d63638] text-[12px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="field-row">
                    <label class="block text-[13px] font-medium text-[#1d2327] mb-1">Sale Price ($)</label>
                    <input type="number" id="sale_price" name="sale_price" step="0.01" value="{{ old('sale_price', $post->shopData->sale_price ?? '') }}" class="wp-input w-full @error('sale_price') border-[#d63638] @enderror" placeholder="0.00">
                    @error('sale_price')
                        <p class="text-[#d63638] text-[12px] mt-1">{{ $message }}</p>
                    @enderror
                    <div id="price-error" class="hidden text-[#d63638] text-[12px] mt-1 italic font-medium">Sale price must be less than the regular price.</div>
                </div>
            </div>

            <!-- Inventory -->
            <div class="space-y-4">
                <h3 class="text-[14px] font-bold text-[#1d2327] border-b border-[#f0f0f1] pb-2">Inventory</h3>
                <div class="field-row">
                    <label class="block text-[13px] font-medium text-[#1d2327] mb-1">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $post->shopData->sku ?? '') }}" class="wp-input w-full @error('sku') border-[#d63638] @enderror" placeholder="Unique identifier">
                    @error('sku')
                        <p class="text-[#d63638] text-[12px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="field-row">
                    <label class="block text-[13px] font-medium text-[#1d2327] mb-1">Stock Status</label>
                    <select name="stock_status" class="wp-input w-full h-8 py-0 @error('stock_status') border-[#d63638] @enderror">
                        <option value="instock" {{ old('stock_status', $post->shopData->stock_status ?? '') === 'instock' ? 'selected' : '' }}>In Stock</option>
                        <option value="outofstock" {{ old('stock_status', $post->shopData->stock_status ?? '') === 'outofstock' ? 'selected' : '' }}>Out of Stock</option>
                        <option value="onbackorder" {{ old('stock_status', $post->shopData->stock_status ?? '') === 'onbackorder' ? 'selected' : '' }}>On Backorder</option>
                    </select>
                    @error('stock_status')
                        <p class="text-[#d63638] text-[12px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-[#f0f0f1]">
            <div class="flex items-center">
                <input type="checkbox" name="manage_stock" id="manage_stock" value="1" {{ old('manage_stock', $post->shopData->manage_stock ?? false) ? 'checked' : '' }} class="mr-2 rounded-sm border-[#8c8f94] text-[#2271b1]">
                <label for="manage_stock" class="text-[13px] font-medium text-[#1d2327]">Manage stock?</label>
            </div>
            
            <div id="stock-quantity-container" class="{{ old('manage_stock', $post->shopData->manage_stock ?? false) ? '' : 'hidden' }} mt-3 pl-6">
                <label class="block text-[13px] font-medium text-[#1d2327] mb-1">Stock Quantity</label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $post->shopData->stock_quantity ?? 0) }}" class="wp-input w-32 @error('stock_quantity') border-[#d63638] @enderror" min="0">
                @error('stock_quantity')
                    <p class="text-[#d63638] text-[12px] mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>

<!-- Short Description Card -->
<div class="wp-metabox mt-6 mb-6">
    <div class="wp-metabox-header"><span>Product Short Description</span></div>
    <div class="wp-metabox-content" style="padding: 16px;">
        <textarea name="short_description" rows="3" class="wp-input w-full p-2 @error('short_description') border-[#d63638] @enderror" placeholder="Brief summary of the product...">{{ old('short_description', $post->shopData->short_description ?? '') }}</textarea>
        @error('short_description')
            <p class="text-[#d63638] text-[12px] mt-1">{{ $message }}</p>
        @enderror
        <p class="text-[#646970] text-[12px] mt-1 italic">This concise summary will appear next to the product image on the single product page and in product catalogs.</p>
    </div>
</div>

<script>
    document.getElementById('manage_stock')?.addEventListener('change', function() {
        document.getElementById('stock-quantity-container')?.classList.toggle('hidden', !this.checked);
    });

    // Real-time Price Validation
    const regPriceInput = document.getElementById('regular_price');
    const salePriceInput = document.getElementById('sale_price');
    const priceError = document.getElementById('price-error');

    function validatePrices() {
        const regPrice = parseFloat(regPriceInput.value) || 0;
        const salePrice = parseFloat(salePriceInput.value) || 0;

        if (salePrice > 0 && regPrice > 0 && salePrice >= regPrice) {
            priceError.classList.remove('hidden');
            salePriceInput.classList.add('border-[#d63638]');
            salePriceInput.classList.add('focus:border-[#d63638]');
            salePriceInput.classList.add('focus:ring-[#d63638]');
        } else {
            priceError.classList.add('hidden');
            salePriceInput.classList.remove('border-[#d63638]');
            salePriceInput.classList.remove('focus:border-[#d63638]');
            salePriceInput.classList.remove('focus:ring-[#d63638]');
        }
    }

    regPriceInput?.addEventListener('input', validatePrices);
    salePriceInput?.addEventListener('input', validatePrices);

    // Run once on load to handle existing or old() values
    validatePrices();
</script>
@endif
