/* ── Nav scroll — nav.js handles this already via querySelector('nav')
   This is a no-op safety fallback only ── */
window.addEventListener('scroll', () => {
    const nav = document.querySelector('nav');
    if (nav) nav.classList.toggle('scrolled', window.scrollY > 10);
});

/* ── Dynamic stat counters ── */
function recalcStats() {
    const rows = document.querySelectorAll('#historyTable tr');
    let total = 0, revenue = 0, completed = 0, pending = 0;

    rows.forEach(row => {
        const status   = (row.dataset.status || '').toLowerCase();
        const rawTotal = (row.dataset.total  || '').replace(/[₱,]/g, '');
        const amount   = parseFloat(rawTotal) || 0;

        total++;
        revenue += amount;
        if (status === 'completed')       completed++;
        if (status.includes('pending'))   pending++;
    });

    const fmt = n => '₱' + n.toLocaleString('en-PH', { minimumFractionDigits: 0 });

    document.getElementById('stat-total').textContent     = total;
    document.getElementById('stat-revenue').textContent   = fmt(revenue);
    document.getElementById('stat-completed').textContent = completed;
    document.getElementById('stat-pending').textContent   = pending;
}

/* ── Filter ── */
function filterOrders() {
    const search = document.getElementById('txSearch').value.toLowerCase();
    const month  = document.getElementById('monthFilter').value;
    const year   = document.getElementById('yearFilter').value;
    const status = document.getElementById('statusFilter').value.toLowerCase();

    const rows = document.querySelectorAll('#historyTable tr');
    let visible = 0;

    rows.forEach(row => {
        const text  = row.innerText.toLowerCase();
        const date  = row.querySelector('.order-date')?.innerText || '';
        const rowSt = (row.dataset.status || '').toLowerCase();

        const ok =
            text.includes(search) &&
            (month  === '' || date.includes(month)) &&
            (year   === '' || date.includes(year))  &&
            (status === '' || rowSt.includes(status));

        row.style.display = ok ? '' : 'none';
        if (ok) visible++;
    });

    const empty = document.getElementById('no-history');
    const table = document.getElementById('mainTable');

    if (visible === 0) {
        empty.classList.remove('hidden');
        table.style.display = 'none';
    } else {
        empty.classList.add('hidden');
        table.style.display = '';
    }
}

/* ── Detail Modal ── */
function openDetail(btn) {
    const row = btn.closest('tr');

    document.getElementById('m-order-id').textContent         = row.dataset.order  || '—';
    document.getElementById('modal-order-id-sub').textContent = row.dataset.order  || '';
    document.getElementById('m-date').textContent             = row.querySelector('.order-date')?.innerText || '—';
    document.getElementById('m-items').textContent            = row.dataset.items  || '—';
    document.getElementById('m-method').textContent           = row.dataset.method || '—';
    document.getElementById('m-total').textContent            = row.dataset.total  || '—';
    document.getElementById('m-notes').textContent            = row.dataset.notes  || '—';

    const statusText = row.dataset.status || '';
    const lower      = statusText.toLowerCase();
    let cls = 'badge-pending';
    if (lower === 'completed')       cls = 'badge-completed';
    else if (lower === 'processing') cls = 'badge-processing';
    else if (lower === 'cancelled')  cls = 'badge-cancelled';

    document.getElementById('m-status').innerHTML =
        `<span class="badge ${cls}"><span class="badge-dot"></span>${statusText}</span>`;

    document.getElementById('detailModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeDetail() {
    document.getElementById('detailModal').style.display = 'none';
    document.body.style.overflow = '';
}

/* Close on backdrop click */
document.getElementById('detailModal').addEventListener('click', function (e) {
    if (e.target === this) closeDetail();
});

/* Close on Escape */
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeDetail();
});

/* ── Init ── */
document.addEventListener('DOMContentLoaded', () => {
    recalcStats();
});