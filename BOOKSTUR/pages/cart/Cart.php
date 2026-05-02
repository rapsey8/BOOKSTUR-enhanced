<?php
require_once '../../include/config.php';
require_once '../../include/auth_checker.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart | SSCR-C Bookstore</title>

    <!-- Shared styles (your existing stack) -->
    <link rel="stylesheet" href="../../component/navbar/navbar.css">
    <link rel="stylesheet" href="../../component/searchbar/searchbar.css">
    <link rel="stylesheet" href="../../component/adminUtils/adminUtils.css">
    <link rel="stylesheet" href="../../component/addToBooksModal/addToBooksModal.css">
    <link rel="stylesheet" href="../../component/footer/footer.css">
    <link rel="stylesheet" href="../../style.css">

    <!-- Cart-specific styles -->
    <link rel="stylesheet" href="Cart.css">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
</head>
<body>

    <!-- ── Shared navbar ── -->
    <?php include '../../component/navbar/navbar.php'; ?>

    <!-- ═══════════ PAGE HEADER ═══════════ -->
    <header class="page-header cart-page-header">
        <div class="text-container">
            <h1>Shopping Cart</h1>
            <p>Review your items before checkout</p>
        </div>
    </header>

    <!-- ═══════════ CART LAYOUT ═══════════ -->
    <div class="cart-layout">

        <!-- ── LEFT: Cart Items ── -->
        <div class="cart-left-col">

            <!-- Cart Items Card -->
            <div class="cart-card">
                <div class="cart-card-header">
                    <span class="cart-card-title">
                        <span class="material-icons-outlined">shopping_cart</span>
                        Your Items
                        <span class="item-count-badge" id="itemCountBadge">0</span>
                    </span>
                    <button class="clear-cart-btn" onclick="clearCart()">
                        <span class="material-icons-outlined">delete_sweep</span>
                        Clear All
                    </button>
                </div>

                <!-- Items injected by cart.js -->
                <div id="cartItemsList"></div>

                <!-- Empty state -->
                <div class="empty-cart" id="emptyCart">
                    <div class="empty-cart-icon">
                        <span class="material-icons-outlined">shopping_cart</span>
                    </div>
                    <p class="empty-cart-title">Your cart is empty</p>
                    <p class="empty-cart-sub">Browse the bookstore and add items to get started.</p>
                    <a href="../../pages/library/library.php" class="browse-btn">
                        <span class="material-icons-outlined">storefront</span>
                        Browse Products
                    </a>
                </div>
            </div>

            <!-- Notes Card -->
            <div class="cart-card">
                <div class="cart-card-header">
                    <span class="cart-card-title">
                        <span class="material-icons-outlined">edit_note</span>
                        Order Notes
                    </span>
                </div>
                <div class="notes-wrap">
                    <textarea
                        id="orderNotes"
                        placeholder="Any special instructions or requests for your order..."
                        class="notes-textarea"
                        onfocus="this.style.borderColor='var(--primary-red)'; this.style.boxShadow='0 0 0 3px rgba(220,23,23,0.1)';"
                        onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';"
                    ></textarea>
                </div>
            </div>
        </div>

        <!-- ── RIGHT: Order Summary ── -->
        <div class="cart-right-col">

            <!-- Summary Card -->
            <div class="cart-card">
                <div class="cart-card-header">
                    <span class="cart-card-title">
                        <span class="material-icons-outlined">receipt_long</span>
                        Order Summary
                    </span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Subtotal (<span id="summaryItemCount">0</span> items)</span>
                    <span class="summary-value" id="summarySubtotal">₱0.00</span>
                </div>
                <div class="summary-row" id="discountRow" style="display:none;">
                    <span class="summary-label summary-label-green">
                        Discount <span class="discount-badge" id="discountBadge"></span>
                    </span>
                    <span class="summary-value summary-value-green" id="discountValue">−₱0.00</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Pickup</span>
                    <span class="summary-value summary-value-green">FREE</span>
                </div>

                <div class="summary-total-row">
                    <span class="summary-total-label">Total</span>
                    <span class="summary-total-value" id="summaryTotal">₱0.00</span>
                </div>

                <!-- Payment method -->
                <div class="payment-options">
                    <p class="payment-options-label">Payment Method</p>

                    <label class="payment-option selected" onclick="selectPayment(this)">
                        <input type="radio" name="payment" value="GCash" checked>
                        <div>
                            <div class="payment-option-label">GCash</div>
                            <div class="payment-option-sub">Pay via GCash mobile wallet</div>
                        </div>
                        <div class="payment-icon payment-icon-yellow">
                            <span class="material-icons-outlined">phone_iphone</span>
                        </div>
                    </label>

                    <label class="payment-option" onclick="selectPayment(this)">
                        <input type="radio" name="payment" value="Over the Counter">
                        <div>
                            <div class="payment-option-label">Over the Counter</div>
                            <div class="payment-option-sub">Pay at the bookstore window</div>
                        </div>
                        <div class="payment-icon payment-icon-green">
                            <span class="material-icons-outlined">store</span>
                        </div>
                    </label>
                </div>

                <!-- Checkout -->
                <div class="checkout-wrap">
                    <button class="checkout-btn" id="checkoutBtn" onclick="proceedCheckout()" disabled>
                        <span class="material-icons-outlined">lock</span>
                        Place Order
                    </button>
                    <a href="../../pages/dashboard/dashboard.php" class="continue-link">
                        <span class="material-icons-outlined">arrow_back</span>
                        Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Trust badges -->
            <div class="cart-card trust-card">
                <div class="trust-list">
                    <div class="trust-item">
                        <span class="material-icons-outlined trust-icon trust-icon-green">verified</span>
                        Official SSCR Bookstore — authentic items only
                    </div>
                    <div class="trust-item">
                        <span class="material-icons-outlined trust-icon trust-icon-blue">local_shipping</span>
                        Pickup available at the bookstore window
                    </div>
                    <div class="trust-item">
                        <span class="material-icons-outlined trust-icon trust-icon-yellow">support_agent</span>
                        Need help? Visit the bookstore during school hours
                    </div>
                </div>
            </div>

        </div>
    </div><!-- /.cart-layout -->

    <!-- ═══════════ TOAST ═══════════ -->
    <div class="toast" id="toast">
        <span class="material-icons-outlined">check_circle</span>
        <span id="toastMsg">Done!</span>
    </div>

    <!-- ═══════════ PAYMENT PROCESSING OVERLAY ═══════════ -->
    <div id="paymentOverlay" class="payment-overlay hidden">
        <div class="payment-sheet">
            <div class="payment-spinner-wrap" id="paymentSpinnerWrap">
                <div class="payment-spinner" id="paymentSpinner"></div>
            </div>
            <h2 class="payment-title" id="paymentTitle">Processing Payment</h2>
            <p class="payment-sub" id="paymentSub">Please wait, do not close this window...</p>
            <div class="payment-steps">
                <div class="payment-step" id="pstep1">
                    <div class="payment-step-dot" id="pstep1dot">1</div>
                    <span>Validating order details</span>
                    <span class="material-icons-outlined payment-step-icon">fact_check</span>
                </div>
                <div class="payment-step" id="pstep2">
                    <div class="payment-step-dot" id="pstep2dot">2</div>
                    <span id="pstep2Label">Processing payment</span>
                    <span class="material-icons-outlined payment-step-icon">payments</span>
                </div>
                <div class="payment-step" id="pstep3">
                    <div class="payment-step-dot" id="pstep3dot">3</div>
                    <span>Confirming with bookstore</span>
                    <span class="material-icons-outlined payment-step-icon">storefront</span>
                </div>
            </div>
            <div class="payment-amount-chip">
                <span class="material-icons-outlined">payments</span>
                <span id="paymentChipMethod">GCash</span>
                <span class="chip-divider"></span>
                <strong id="paymentChipTotal">₱0.00</strong>
            </div>
        </div>
    </div>

    <!-- ═══════════ ORDER CONFIRMATION OVERLAY ═══════════ -->
    <div id="orderOverlay" class="order-overlay hidden">
        <div class="order-sheet">

            <div class="order-sheet-top">
                <div class="success-ring">
                    <div class="success-ring-inner">
                        <span class="material-icons-outlined success-icon">check</span>
                    </div>
                </div>
                <h2 class="order-success-title">Order Placed!</h2>
                <p class="order-success-sub">We've received your order. Please proceed to pickup.</p>
                <div class="order-id-chip" id="confirmOrderId">#SSCR-0000</div>
            </div>

            <div class="pickup-banner">
                <span class="material-icons-outlined pickup-banner-icon">storefront</span>
                <div>
                    <div class="pickup-banner-title">Pickup Only</div>
                    <div class="pickup-banner-sub">Delivery is not available at the moment. Claim your order at the SSCR Bookstore window during school hours.</div>
                </div>
            </div>

            <div class="order-tracker">
                <div class="tracker-step done" id="step-placed">
                    <div class="tracker-dot">
                        <span class="material-icons-outlined">receipt_long</span>
                    </div>
                    <div class="tracker-info">
                        <div class="tracker-label">Order Placed</div>
                        <div class="tracker-time" id="trackerTime">—</div>
                    </div>
                </div>
                <div class="tracker-line"></div>
                <div class="tracker-step active" id="step-processing">
                    <div class="tracker-dot">
                        <span class="material-icons-outlined">inventory_2</span>
                    </div>
                    <div class="tracker-info">
                        <div class="tracker-label">Being Prepared</div>
                        <div class="tracker-time">Bookstore is processing your order</div>
                    </div>
                </div>
                <div class="tracker-line"></div>
                <div class="tracker-step" id="step-ready">
                    <div class="tracker-dot">
                        <span class="material-icons-outlined">store</span>
                    </div>
                    <div class="tracker-info">
                        <div class="tracker-label">Ready for Pickup</div>
                        <div class="tracker-time">You'll be notified when ready</div>
                    </div>
                </div>
                <div class="tracker-line"></div>
                <div class="tracker-step" id="step-done">
                    <div class="tracker-dot">
                        <span class="material-icons-outlined">check_circle</span>
                    </div>
                    <div class="tracker-info">
                        <div class="tracker-label">Completed</div>
                        <div class="tracker-time">Order claimed at bookstore</div>
                    </div>
                </div>
            </div>

            <div class="confirm-summary">
                <div class="confirm-summary-title">Order Summary</div>
                <div id="confirmItemsList" class="confirm-items-list"></div>
                <div class="confirm-summary-divider"></div>
                <div class="confirm-row">
                    <span>Payment Method</span>
                    <span class="confirm-val" id="confirmPayment">—</span>
                </div>
                <div class="confirm-row">
                    <span>Total Paid</span>
                    <span class="confirm-val confirm-total" id="confirmTotal">₱0.00</span>
                </div>
            </div>

            <div class="order-actions">
                <a href="../../pages/dashboard/dashboard.php" class="btn-view-orders">
                    <span class="material-icons-outlined">receipt_long</span>
                    View My Orders
                </a>
                <a href="../../pages/dashboard/dashboard.php" class="btn-keep-shopping">
                    <span class="material-icons-outlined">storefront</span>
                    Continue Shopping
                </a>
            </div>

        </div>
    </div>

    <!-- ═══════════ ADMIN UTILS + ALL MODALS ═══════════ -->
    <?php include '../../component/adminUtils/adminUtils.php'; ?>
    <?php include '../../component/addToUniformModal/addToUniformModal.php'; ?>
    <?php include '../../component/addToApparelModal/addToApparelModal.php'; ?>
    <?php include '../../component/addToBooksModal/addToBooksModal.php'; ?>
    <?php include '../../component/addToOtherModal/addToOtherModal.php'; ?>

    <!-- ═══════════ FOOTER ═══════════ -->
    <?php include '../../component/footer/footer.php'; ?>

    <!-- ═══════════ SCRIPTS ═══════════ -->
    <script src="../../icons/sweetalert2.all.min.js"></script>
    <script src="../../component/addToBooksModal/addToBooksModal.js"></script>
    <script src="../../component/addToOtherModal/addToOtherModal.js"></script>
    <script src="../../component/addToUniformModal/addToUniformModal.js"></script>
    <script src="../../component/addToApparelModal/addToApparelModal.js"></script>
    <script src="../../component/adminUtils/adminUtils.js"></script>
    <script src="../../component/navbar/nav.js"></script>
    <script src="../../component/searchbar/searchbar.js"></script>

    <!-- Cart logic (must be last) -->
    <script src="Cart.js"></script>

</body>
</html>