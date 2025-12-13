@extends('layouts.kasir')

@section('title', 'Create New Order')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Create New Order</h4>
                    <form id="orderForm" action="{{ route('kasir.orders.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_name">Customer Name *</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_phone">Customer Phone</label>
                                    <input type="text" class="form-control" id="customer_phone" name="customer_phone">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_type">Order Type *</label>
                                    <select class="form-control" id="order_type" name="order_type" required>
                                        <option value="">Select Order Type</option>
                                        <option value="dine_in">Dine In</option>
                                        <option value="takeaway">Takeaway</option>
                                        <option value="delivery">Delivery</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="table_number_group" style="display: none;">
                                    <label for="table_number">Table Number *</label>
                                    <input type="number" class="form-control" id="table_number" name="table_number" min="1">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Order Items *</label>
                            <div id="items-container">
                                <div class="item-row row mb-3">
                                    <div class="col-md-5">
                                        <select class="form-control menu-select" name="items[0][menu_id]" required>
                                            <option value="">Select Menu</option>
                                            @foreach($menus as $menu)
                                            <option value="{{ $menu->id }}" data-price="{{ $menu->price }}">{{ $menu->name }} - Rp {{ number_format($menu->price) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control quantity-input" name="items[0][quantity]" placeholder="Quantity" min="1" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control price-display" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove-item" style="display: none;">Remove</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" id="add-item">Add Item</button>
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <h5>Total: Rp <span id="total-amount">0</span></h5>
                        </div>
                        <button type="submit" class="btn btn-success">Create Order</button>
                        <a href="{{ route('kasir.orders.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let itemIndex = 1;

    // Show/hide table number based on order type
    $('#order_type').change(function() {
        if ($(this).val() === 'dine_in') {
            $('#table_number_group').show();
            $('#table_number').attr('required', true);
        } else {
            $('#table_number_group').hide();
            $('#table_number').removeAttr('required');
        }
    });

    // Add new item row
    $('#add-item').click(function() {
        const newRow = `
            <div class="item-row row mb-3">
                <div class="col-md-5">
                    <select class="form-control menu-select" name="items[${itemIndex}][menu_id]" required>
                        <option value="">Select Menu</option>
                        @foreach($menus as $menu)
                        <option value="{{ $menu->id }}" data-price="{{ $menu->price }}">{{ $menu->name }} - Rp {{ number_format($menu->price) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control quantity-input" name="items[${itemIndex}][quantity]" placeholder="Quantity" min="1" required>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control price-display" readonly>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-item">Remove</button>
                </div>
            </div>
        `;
        $('#items-container').append(newRow);
        itemIndex++;
        updateRemoveButtons();
    });

    // Remove item row
    $(document).on('click', '.remove-item', function() {
        $(this).closest('.item-row').remove();
        updateRemoveButtons();
        calculateTotal();
    });

    // Update price when menu or quantity changes
    $(document).on('change', '.menu-select, .quantity-input', function() {
        const row = $(this).closest('.item-row');
        const menuSelect = row.find('.menu-select');
        const quantityInput = row.find('.quantity-input');
        const priceDisplay = row.find('.price-display');

        const selectedOption = menuSelect.find('option:selected');
        const price = selectedOption.data('price') || 0;
        const quantity = quantityInput.val() || 0;
        const subtotal = price * quantity;

        priceDisplay.val('Rp ' + subtotal.toLocaleString('id-ID'));
        calculateTotal();
    });

    // Update remove buttons visibility
    function updateRemoveButtons() {
        const itemRows = $('.item-row');
        if (itemRows.length > 1) {
            $('.remove-item').show();
        } else {
            $('.remove-item').hide();
        }
    }

    // Calculate total amount
    function calculateTotal() {
        let total = 0;
        $('.item-row').each(function() {
            const row = $(this);
            const menuSelect = row.find('.menu-select');
            const quantityInput = row.find('.quantity-input');

            const selectedOption = menuSelect.find('option:selected');
            const price = selectedOption.data('price') || 0;
            const quantity = quantityInput.val() || 0;
            total += price * quantity;
        });
        $('#total-amount').text(total.toLocaleString('id-ID'));
    }

    // Initial setup
    updateRemoveButtons();
});
</script>
@endsection
