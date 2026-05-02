/* ══════════════════════════════════════════════
   cart.js  — BOOKSTUR Shopping Cart
   Works with localStorage so addToCart() called
   on any product page (library, uniform, apparel,
   other) persists items here.
══════════════════════════════════════════════ */

const CART_KEY = 'bookstur_cart';

/* ── Storage helpers ── */
function getCart() {
    try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; }
    catch { return []; }
}

function saveCart(cart) {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
}

/* ══════════════════════════════════════════════
   addToCart()
   Called by the "Add to Cart" buttons on every
   product page:
     onclick="addToCart(id, name, price, image)"
   The product pages only pass what they know;
   image is optional (falls back to placeholder).
══════════════════════════════════════════════ */
function addToCart(productId, productName, price, imagePath) {
    const cart = getCart();
    const existing = cart.find(i => i.id == productId);

    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({
            id:    productId,
            name:  productName  || 'Product',
            price: parseFloat(price) || 0,
            image: imagePath    || '',
            qty:   1
        });
    }

    saveCart(cart);

    /* Show a quick SweetAlert toast if available, else native alert */
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Added to cart!',
            showConfirmButton: false,
            timer: 1400,
            timerProgressBar: true,
        });
    } else {
        alert((productName || 'Item') + ' added to cart.');
    }

    updateNavCartCount(); // keep nav badge in sync if present
}

/* ── Update cart count in the nav link ── */
function updateNavCartCount() {
    const cart  = getCart();
    const total = cart.reduce((s, i) => s + i.qty, 0);
    /* Your navbar renders: Cart (0) — try to keep it live */
    document.querySelectorAll('.cart-nav-count').forEach(el => {
        el.textContent = total;
    });
}

/* ════════════════════════════════════════════
   Render cart items from localStorage
════════════════════════════════════════════ */
function renderCart() {
    const cart    = getCart();
    const listEl  = document.getElementById('cartItemsList');
    const emptyEl = document.getElementById('emptyCart');

    if (!listEl) return; // not on cart page

    listEl.innerHTML = '';

    if (cart.length === 0) {
        emptyEl && emptyEl.classList.remove('hidden');
        recalculate();
        return;
    }

    emptyEl && emptyEl.classList.add('hidden');

    cart.forEach(item => {
        const div = document.createElement('div');
        div.className = 'cart-item';
        div.dataset.id    = item.id;
        div.dataset.price = item.price;

        const imgSrc = item.image
            ? '../../src/uploads/products/' + item.image
            : '../../src/placeholder.jpg';

        div.innerHTML = `
            <div class="cart-item-img">
                <img src="${imgSrc}"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                     alt="${escHtml(item.name)}">
                <span class="material-icons-outlined cart-item-img-placeholder" style="display:none;">inventory_2</span>
            </div>
            <div class="cart-item-info">
                <div class="cart-item-name">${escHtml(item.name)}</div>
                <div class="cart-item-price">₱${item.price.toFixed(2)}</div>
            </div>
            <div class="qty-control">
                <button class="qty-btn" onclick="changeQty(this, -1)">−</button>
                <span class="qty-value">${item.qty}</span>
                <button class="qty-btn" onclick="changeQty(this, 1)">+</button>
            </div>
            <div class="cart-item-subtotal">${formatPHP(item.price * item.qty)}</div>
            <button class="cart-remove-btn" onclick="removeItem(this)" title="Remove item">
                <span class="material-icons-outlined">close</span>
            </button>
        `;

        listEl.appendChild(div);
    });

    recalculate();
}

/* ── Escape HTML ── */
function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

/* ── Format currency ── */
function formatPHP(amount) {
    return '₱' + amount.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

/* ── Generate order ID ── */
function generateOrderId() {
    return '#SSCR-' + Math.floor(1000 + Math.random() * 9000);
}

/* ════════════════════════════════════════════
   Recalculate totals
════════════════════════════════════════════ */
let promoDiscount = 0;
let promoCode     = '';

function recalculate() {
    const items    = document.querySelectorAll('#cartItemsList .cart-item');
    const emptyEl  = document.getElementById('emptyCart');
    const checkBtn = document.getElementById('checkoutBtn');
    let subtotal = 0;

    items.forEach(item => {
        const price = parseFloat(item.dataset.price);
        const qty   = parseInt(item.querySelector('.qty-value').textContent);
        const sub   = price * qty;
        item.querySelector('.cart-item-subtotal').textContent = formatPHP(sub);
        subtotal += sub;
    });

    const discount = promoDiscount > 0 ? Math.round(subtotal * promoDiscount) : 0;
    const total    = subtotal - discount;

    document.getElementById('summaryItemCount').textContent = items.length;
    document.getElementById('itemCountBadge').textContent   = items.length;
    document.getElementById('summarySubtotal').textContent  = formatPHP(subtotal);
    document.getElementById('summaryTotal').textContent     = formatPHP(total);

    const discRow = document.getElementById('discountRow');
    if (discount > 0) {
        discRow.style.display = 'flex';
        document.getElementById('discountValue').textContent = '−' + formatPHP(discount);
        document.getElementById('discountBadge').textContent = promoCode;
    } else {
        discRow.style.display = 'none';
    }

    if (items.length === 0) {
        emptyEl  && emptyEl.classList.remove('hidden');
        if (checkBtn) checkBtn.disabled = true;
    } else {
        emptyEl  && emptyEl.classList.add('hidden');
        if (checkBtn) checkBtn.disabled = false;
    }

    persistQtyChanges(); // keep localStorage in sync with qty edits
}

/* ── Persist qty changes back to localStorage ── */
function persistQtyChanges() {
    const cart  = getCart();
    const items = document.querySelectorAll('#cartItemsList .cart-item');

    items.forEach(itemEl => {
        const id  = itemEl.dataset.id;
        const qty = parseInt(itemEl.querySelector('.qty-value').textContent);
        const entry = cart.find(i => String(i.id) === String(id));
        if (entry) entry.qty = qty;
    });

    saveCart(cart);
}

/* ── Change quantity ── */
function changeQty(btn, delta) {
    const qtyEl = btn.closest('.qty-control').querySelector('.qty-value');
    let qty = parseInt(qtyEl.textContent) + delta;
    if (qty < 1) qty = 1;
    qtyEl.textContent = qty;
    recalculate();
}

/* ── Remove item ── */
function removeItem(btn) {
    const itemEl = btn.closest('.cart-item');
    const id     = itemEl.dataset.id;
    const name   = itemEl.querySelector('.cart-item-name').textContent;

    itemEl.style.transition = 'opacity 0.25s, transform 0.25s';
    itemEl.style.opacity    = '0';
    itemEl.style.transform  = 'translateX(20px)';

    setTimeout(() => {
        itemEl.remove();

        /* Remove from localStorage */
        const cart = getCart().filter(i => String(i.id) !== String(id));
        saveCart(cart);

        recalculate();
        showToast('"' + name + '" removed from cart');
    }, 250);
}

/* ── Clear cart ── */
function clearCart() {
    document.querySelectorAll('#cartItemsList .cart-item').forEach(el => el.remove());
    saveCart([]);
    recalculate();
    showToast('Cart cleared');
}

/* ── Toast ── */
function showToast(msg) {
    const t = document.getElementById('toast');
    if (!t) return;
    document.getElementById('toastMsg').textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
}

/* ── Payment method selection ── */
function selectPayment(label) {
    document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
    label.classList.add('selected');
}

/* ════════════════════════════════════════════
   PAYMENT PROCESSING OVERLAY
════════════════════════════════════════════ */
function resetPaymentSteps() {
    [['pstep1','pstep1dot','1'],['pstep2','pstep2dot','2'],['pstep3','pstep3dot','3']].forEach(([s,d,n]) => {
        document.getElementById(s).className = 'payment-step';
        document.getElementById(d).textContent = n;
    });
    document.getElementById('paymentTitle').textContent = 'Processing Payment';
    document.getElementById('paymentSub').textContent   = 'Please wait, do not close this window...';
    document.getElementById('paymentSpinner').className    = 'payment-spinner';
    document.getElementById('paymentSpinnerWrap').className = 'payment-spinner-wrap';
}

function setStep(stepId, dotId, state) {
    const el  = document.getElementById(stepId);
    const dot = document.getElementById(dotId);
    el.className = 'payment-step ' + state;
    if (state === 'done') dot.textContent = '✓';
}

/* ── Main checkout ── */
function proceedCheckout() {
    const items = document.querySelectorAll('#cartItemsList .cart-item');
    if (!items.length) return;

    const method = document.querySelector('input[name="payment"]:checked')?.value || 'GCash';
    const total  = document.getElementById('summaryTotal').textContent;

    document.getElementById('paymentChipMethod').textContent = method;
    document.getElementById('paymentChipTotal').textContent  = total;
    document.getElementById('pstep2Label').textContent       = 'Processing payment via ' + method;

    resetPaymentSteps();

    const btn = document.getElementById('checkoutBtn');
    btn.disabled = true;

    document.getElementById('paymentOverlay').classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    setTimeout(() => setStep('pstep1','pstep1dot','active'), 200);
    setTimeout(() => { setStep('pstep1','pstep1dot','done'); setStep('pstep2','pstep2dot','active'); }, 1300);
    setTimeout(() => { setStep('pstep2','pstep2dot','done'); setStep('pstep3','pstep3dot','active'); }, 2700);
    setTimeout(() => {
        setStep('pstep3','pstep3dot','done');
        document.getElementById('paymentSpinner').className    = 'payment-spinner done';
        document.getElementById('paymentSpinnerWrap').className = 'payment-spinner-wrap done';
        document.getElementById('paymentTitle').textContent    = 'Order Confirmed!';
        document.getElementById('paymentSub').textContent      = 'Redirecting to your order summary...';
    }, 4000);

    setTimeout(() => {
        document.getElementById('paymentOverlay').classList.add('hidden');
        btn.disabled = false;
        openOrderConfirmation();

        /* Clear cart after successful "order" */
        saveCart([]);
    }, 4900);
}

/* ── Order confirmation overlay ── */
function openOrderConfirmation() {
    const payment = document.querySelector('input[name="payment"]:checked')?.value || 'Over the Counter';
    const orderId = generateOrderId();
    document.getElementById('confirmOrderId').textContent = orderId;

    const now = new Date();
    document.getElementById('trackerTime').textContent =
        now.toLocaleDateString('en-PH', { month:'short', day:'numeric', year:'numeric' })
        + ' · ' + now.toLocaleTimeString('en-PH', { hour:'2-digit', minute:'2-digit' });

    const items  = document.querySelectorAll('#cartItemsList .cart-item');
    const listEl = document.getElementById('confirmItemsList');
    listEl.innerHTML = '';
    items.forEach(item => {
        const name  = item.querySelector('.cart-item-name').textContent;
        const qty   = parseInt(item.querySelector('.qty-value').textContent);
        const price = parseFloat(item.dataset.price);
        const row   = document.createElement('div');
        row.className = 'confirm-item';
        row.innerHTML = `
            <span class="confirm-item-name">${escHtml(name)}</span>
            <span class="confirm-item-qty">×${qty}</span>
            <span class="confirm-item-price">${formatPHP(price * qty)}</span>
        `;
        listEl.appendChild(row);
    });

    document.getElementById('confirmPayment').textContent = payment;
    document.getElementById('confirmTotal').textContent   = document.getElementById('summaryTotal').textContent;

    document.getElementById('orderOverlay').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

/* ── Nav scroll (reuse your existing pattern) ── */
window.addEventListener('scroll', () => {
    const nav = document.querySelector('nav');
    if (nav) nav.classList.toggle('scrolled', window.scrollY > 50);
});

/* ── Init ── */
document.addEventListener('DOMContentLoaded', () => {
    if (getCart().length === 0) {
    saveCart([
        { id: 9001, name: 'PE Uniform (Top + Bottom)', price: 725, image: '', qty: 2 },
        { id: 9002, name: 'Mathematics Textbook Gr. 11', price: 320, image: '', qty: 1 }
    ]);
      }
    renderCart();
    updateNavCartCount();
});