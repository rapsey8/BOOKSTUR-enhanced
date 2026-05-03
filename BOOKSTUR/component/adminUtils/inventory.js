/* ═══════════════════════════════════════════
   SAMPLE DATA — replace with PHP/DB output
   when orders table is ready
═══════════════════════════════════════════ */
let products = [
    { id:1,  name:'Art Appreciation',           category:'books',    price:320,  stock:15, desc:'Core subject textbook Gr.11', img:'' },
    { id:2,  name:'Mathematics Textbook Gr.11',  category:'books',    price:280,  stock:3,  desc:'SHS Core Mathematics',        img:'' },
    { id:3,  name:'Filipino sa Piling Larang',   category:'books',    price:295,  stock:0,  desc:'SHS Elective subject',        img:'' },
    { id:4,  name:'PE Uniform (Top + Bottom)',   category:'uniforms', price:725,  stock:22, desc:'White/Red, S to XL',          img:'' },
    { id:5,  name:'School Blouse',               category:'uniforms', price:650,  stock:8,  desc:'White, S to XL',              img:'' },
    { id:6,  name:'Polo Shirt (Male)',            category:'uniforms', price:580,  stock:2,  desc:'Light blue, S to XL',         img:'' },
    { id:7,  name:'Academic Toga',               category:'apparel',  price:1200, stock:5,  desc:'Full graduation set',         img:'' },
    { id:8,  name:'College Hood',                category:'apparel',  price:450,  stock:0,  desc:'By college color',            img:'' },
    { id:9,  name:'Ballpen Set (10pcs)',          category:'others',   price:85,   stock:60, desc:'Black & blue ink',            img:'' },
    { id:10, name:'Scientific Calculator',        category:'others',   price:650,  stock:4,  desc:'Casio fx-991EX',              img:'' },
    { id:11, name:'Notebook (3-subject)',          category:'others',   price:120,  stock:0,  desc:'College-ruled, 120 leaves',   img:'' },
    { id:12, name:'Practical Research Book',      category:'books',    price:310,  stock:11, desc:'SHS Core subject',            img:'' },
];

let nextId     = 13;
let editingId  = null;
let deletingId = null;
let currentCat   = 'all';
let currentStock = null;
let currentView  = 'table';

const CAT_ICONS = {
    books:    'auto_stories',
    uniforms: 'checkroom',
    apparel:  'apparel',
    others:   'ink_pen',
};

/* ── Stock helpers ── */
function stockStatus(stock) {
    if (stock === 0) return 'out';
    if (stock <= 5)  return 'low';
    return 'available';
}

function stockBar(stock) {
    const pct   = Math.min(stock / 30 * 100, 100);
    const color = stock === 0 ? '#ef4444' : stock <= 5 ? '#eab308' : '#22c55e';
    return `<div class="stock-bar-wrap">
                <div class="stock-bar" style="width:${pct}%;background:${color};"></div>
            </div>`;
}

function badgeHTML(stock) {
    const s = stockStatus(stock);
    const map = {
        available: ['inv-badge-available', 'Available'],
        low:       ['inv-badge-low',       'Low Stock'],
        out:       ['inv-badge-out',        'Out of Stock'],
    };
    const [cls, label] = map[s];
    return `<span class="inv-badge ${cls}"><span class="inv-badge-dot"></span>${label}</span>`;
}

function catTag(cat) {
    const label = cat.charAt(0).toUpperCase() + cat.slice(1);
    return `<span class="cat-tag cat-tag-${cat}">${label}</span>`;
}

/* ── Filter & sort ── */
function getFiltered() {
    const q    = document.getElementById('invSearch').value.toLowerCase();
    const sort = document.getElementById('sortSelect').value;

    let list = products.filter(p => {
        const matchCat   = currentCat === 'all' || p.category === currentCat;
        const matchStock = !currentStock ||
            (currentStock === 'low' && stockStatus(p.stock) === 'low') ||
            (currentStock === 'out' && p.stock === 0);
        const matchQ = p.name.toLowerCase().includes(q) || p.category.includes(q);
        return matchCat && matchStock && matchQ;
    });

    list.sort((a, b) => {
        switch (sort) {
            case 'name-asc':   return a.name.localeCompare(b.name);
            case 'name-desc':  return b.name.localeCompare(a.name);
            case 'stock-asc':  return a.stock - b.stock;
            case 'stock-desc': return b.stock - a.stock;
            case 'price-asc':  return a.price - b.price;
            case 'price-desc': return b.price - a.price;
            default:           return 0;
        }
    });

    return list;
}

/* ── Render table ── */
function renderTable() {
    const list  = getFiltered();
    const tbody = document.getElementById('invTableBody');
    const empty = document.getElementById('tableEmpty');

    if (list.length === 0) {
        tbody.innerHTML = '';
        empty.classList.remove('hidden');
    } else {
        empty.classList.add('hidden');
        tbody.innerHTML = list.map(p => `
            <tr>
                <td>
                    <div class="prod-cell">
                        <div class="prod-thumb">
                            ${p.img
                                ? `<img src="${p.img}" alt="${p.name}">`
                                : `<span class="material-icons-outlined">${CAT_ICONS[p.category] || 'inventory_2'}</span>`
                            }
                        </div>
                        <div>
                            <div class="prod-name">${p.name}</div>
                            ${p.desc ? `<div class="prod-desc">${p.desc}</div>` : ''}
                        </div>
                    </div>
                </td>
                <td>${catTag(p.category)}</td>
                <td class="text-center">
                    <div class="stock-cell">
                        ${stockBar(p.stock)}
                        <span class="stock-val" style="color:${p.stock === 0 ? '#ef4444' : p.stock <= 5 ? '#eab308' : '#16a34a'}">${p.stock}</span>
                    </div>
                </td>
                <td class="text-right">
                    <span class="price-val">₱${p.price.toLocaleString('en-PH', { minimumFractionDigits: 2 })}</span>
                </td>
                <td class="text-center">${badgeHTML(p.stock)}</td>
                <td class="text-center">
                    <div class="action-btns">
                        <button class="act-btn act-btn-edit" onclick="openEdit(${p.id})" title="Edit">
                            <span class="material-icons-outlined">edit</span>
                        </button>
                        <button class="act-btn act-btn-delete" onclick="openDelete(${p.id})" title="Delete">
                            <span class="material-icons-outlined">delete</span>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    renderGrid(list);
    updatePills();
}

/* ── Render grid ── */
function renderGrid(list) {
    const grid = document.getElementById('gridView');

    if (list.length === 0) {
        grid.innerHTML = `<div class="inv-empty" style="grid-column:1/-1">
            <span class="material-icons-outlined">search_off</span>
            <p>No products match your search.</p>
        </div>`;
        return;
    }

    grid.innerHTML = list.map(p => `
        <div class="grid-card">
            <div class="grid-card-img">
                ${p.img
                    ? `<img src="${p.img}" alt="${p.name}">`
                    : `<span class="material-icons-outlined">${CAT_ICONS[p.category] || 'inventory_2'}</span>`
                }
                <div class="grid-card-badge">${badgeHTML(p.stock)}</div>
            </div>
            <div class="grid-card-body">
                <div class="grid-card-name">${p.name}</div>
                ${catTag(p.category)}
                <div class="grid-card-meta">
                    <span class="grid-card-price">₱${p.price.toLocaleString('en-PH', { minimumFractionDigits: 2 })}</span>
                    <span class="grid-card-stock">${p.stock} in stock</span>
                </div>
            </div>
            <div class="grid-card-footer">
                <button class="grid-card-btn grid-card-btn-edit" onclick="openEdit(${p.id})">
                    <span class="material-icons-outlined">edit</span> Edit
                </button>
                <button class="grid-card-btn grid-card-btn-delete" onclick="openDelete(${p.id})">
                    <span class="material-icons-outlined">delete</span> Delete
                </button>
            </div>
        </div>
    `).join('');
}

/* ── Update hero pills ── */
function updatePills() {
    document.getElementById('pill-total').textContent = products.length;
    document.getElementById('pill-low').textContent   = products.filter(p => stockStatus(p.stock) === 'low').length;
    document.getElementById('pill-out').textContent   = products.filter(p => p.stock === 0).length;
    document.getElementById('pill-ok').textContent    = products.filter(p => stockStatus(p.stock) === 'available').length;
}

/* ── Sidebar tabs ── */
function switchCat(btn) {
    document.querySelectorAll('.sidebar-tab[data-cat]').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.sidebar-tab[data-stock]').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    currentCat   = btn.dataset.cat;
    currentStock = null;
    renderTable();
}

function switchStock(btn) {
    document.querySelectorAll('.sidebar-tab[data-cat]').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.sidebar-tab[data-stock]').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    currentStock = btn.dataset.stock;
    currentCat   = 'all';
    renderTable();
}

/* ── View toggle ── */
function setView(v) {
    currentView = v;
    const tableWrap = document.getElementById('tableView');
    const gridWrap  = document.getElementById('gridView');
    const btnT = document.getElementById('btnTable');
    const btnG = document.getElementById('btnGrid');

    if (v === 'table') {
        tableWrap.style.display = '';
        gridWrap.style.display  = 'none';
        btnT.classList.add('active');
        btnG.classList.remove('active');
    } else {
        tableWrap.style.display = 'none';
        gridWrap.style.display  = 'grid';
        btnT.classList.remove('active');
        btnG.classList.add('active');
    }
}

/* ── Edit modal ── */
function openEdit(id) {
    const p = products.find(x => x.id === id);
    if (!p) return;
    editingId = id;
    document.getElementById('edit-product-name').textContent = p.name;
    document.getElementById('edit-stock').value  = p.stock;
    document.getElementById('edit-price').value  = p.price;
    document.getElementById('edit-status').value = p.stock > 0 ? 'Available' : 'Out of Stock';
    document.getElementById('editModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEdit() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = '';
    editingId = null;
}

function saveEdit() {
    const p = products.find(x => x.id === editingId);
    if (!p) return;
    p.stock = parseInt(document.getElementById('edit-stock').value) || 0;
    p.price = parseFloat(document.getElementById('edit-price').value) || 0;
    closeEdit();
    renderTable();
    showToast('Product updated successfully!');
}

/* ── Add modal ── */
function openAddModal() {
    document.getElementById('add-name').value     = '';
    document.getElementById('add-category').value = '';
    document.getElementById('add-price').value    = '';
    document.getElementById('add-stock').value    = '';
    document.getElementById('add-desc').value     = '';
    document.getElementById('add-preview').innerHTML = `
        <span class="material-icons-outlined inv-upload-icon">cloud_upload</span>
        <span class="inv-upload-main-text">Click to upload photo</span>
        <span class="inv-upload-sub-text">PNG, JPG up to 5MB</span>
    `;
    document.getElementById('addUploadLabel').classList.remove('has-image');
    document.getElementById('addModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAdd() {
    document.getElementById('addModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function previewAddImage(input) {
    const preview = document.getElementById('add-preview');
    const label   = document.getElementById('addUploadLabel');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML = `<img src="${e.target.result}"
                style="width:100%;height:100%;object-fit:cover;border-radius:12px;">`;
            label.classList.add('has-image');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function saveAdd() {
    const name  = document.getElementById('add-name').value.trim();
    const cat   = document.getElementById('add-category').value;
    const price = parseFloat(document.getElementById('add-price').value) || 0;
    const stock = parseInt(document.getElementById('add-stock').value)   || 0;
    const desc  = document.getElementById('add-desc').value.trim();

    if (!name)     { showToast('Product name is required.', true); return; }
    if (!cat)      { showToast('Please select a category.', true); return; }
    if (price < 0) { showToast('Enter a valid price.', true); return; }

    products.push({ id: nextId++, name, category: cat, price, stock, desc, img: '' });
    closeAdd();
    renderTable();
    showToast('Product added successfully!');
}

/* ── Delete modal ── */
function openDelete(id) {
    deletingId = id;
    const p = products.find(x => x.id === id);
    document.getElementById('delete-product-name').textContent = p?.name || '';
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDelete() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = '';
    deletingId = null;
}

function confirmDelete() {
    products = products.filter(p => p.id !== deletingId);
    closeDelete();
    renderTable();
    showToast('Product deleted.');
}

/* ── Close overlays on backdrop click ── */
['editModal', 'addModal', 'deleteModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function (e) {
        if (e.target === this) {
            this.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
});

/* ── Escape key ── */
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeEdit(); closeAdd(); closeDelete(); }
});

/* ── Toast ── */
function showToast(msg, isError = false) {
    const toast = document.getElementById('inv-toast');
    const icon  = document.getElementById('inv-toast-icon');
    const msgEl = document.getElementById('inv-toast-msg');
    icon.textContent = isError ? 'error' : 'check_circle';
    icon.className   = 'material-icons-outlined' + (isError ? ' err' : '');
    msgEl.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2800);
}

/* ── Init ── */
document.addEventListener('DOMContentLoaded', () => {
    renderTable();
});