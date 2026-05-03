<?php
require_once '../../include/config.php';
require_once '../../include/auth_checker.php';

/* ── Admin gate ── */
$current_position = strtoupper(trim($_SESSION['course'] ?? ''));
$isAdmin = ($current_position === 'ADMIN' || $current_position === 'SUPER ADMIN');
if (!$isAdmin) {
    header("Location: /BOOKSTUR/pages/dashboard/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory | SSCR Bookstore</title>

    <!-- Same font/icon stack as every other page -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Shared site styles -->
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="../../component/navbar/navbar.css">
    <link rel="stylesheet" href="../../component/searchbar/searchbar.css">
    <link rel="stylesheet" href="../../component/footer/footer.css">

    <!-- Page-specific -->
    <link rel="stylesheet" href="inventory.css">

    <!-- Guarantee modals start hidden regardless of CSS load order -->
    <style>
        .inv-overlay.hidden { display: none !important; }
    </style>
</head>
<body>

    <!-- ═══════════ NAVBAR — shared component ═══════════ -->
    <?php include '../../component/navbar/navbar.php'; ?>

    <!-- ═══════════ HERO ═══════════ -->
    <header class="page-header inv-hero">
        <div class="inv-hero-content">
            <div class="text-container" style="margin-bottom: 0;">
                <h1>Inventory</h1>
                <p>Manage stock across all product categories</p>
            </div>
            <button class="btn-add-product" onclick="openAddModal()">
                <span class="material-icons-outlined">add</span>
                Add Product
            </button>
        </div>


        <!-- Quick stat pills inside hero -->
        <div class="hero-pills">
            <div class="hero-pill">
                <span class="material-icons-outlined">inventory_2</span>
                <span id="pill-total">0</span> Total Items
            </div>
            <div class="hero-pill hero-pill-warn">
                <span class="material-icons-outlined">warning_amber</span>
                <span id="pill-low">0</span> Low Stock
            </div>
            <div class="hero-pill hero-pill-danger">
                <span class="material-icons-outlined">block</span>
                <span id="pill-out">0</span> Out of Stock
            </div>
            <div class="hero-pill hero-pill-green">
                <span class="material-icons-outlined">check_circle</span>
                <span id="pill-ok">0</span> Available
            </div>
        </div>
    </header>

    <!-- ═══════════ MAIN ═══════════ -->
    <main class="inv-main">

        <!-- ── Sidebar tabs ── -->
        <aside class="inv-sidebar">
            <p class="sidebar-label">Categories</p>
            <button class="sidebar-tab active" data-cat="all" onclick="switchCat(this)">
                <span class="material-icons-outlined">apps</span> All Products
            </button>
            <button class="sidebar-tab" data-cat="books" onclick="switchCat(this)">
                <span class="material-icons-outlined">auto_stories</span> Books
            </button>
            <button class="sidebar-tab" data-cat="uniforms" onclick="switchCat(this)">
                <span class="material-icons-outlined">checkroom</span> Uniforms
            </button>
            <button class="sidebar-tab" data-cat="apparel" onclick="switchCat(this)">
                <span class="material-icons-outlined">dry_cleaning</span> Apparel
            </button>
            <button class="sidebar-tab" data-cat="others" onclick="switchCat(this)">
                <span class="material-icons-outlined">edit</span> Others
            </button>

            <div class="sidebar-divider"></div>
            <p class="sidebar-label">Stock Filter</p>
            <button class="sidebar-tab" data-stock="low" onclick="switchStock(this)">
                <span class="material-icons-outlined">warning_amber</span> Low Stock
            </button>
            <button class="sidebar-tab" data-stock="out" onclick="switchStock(this)">
                <span class="material-icons-outlined">block</span> Out of Stock
            </button>
        </aside>

        <!-- ── Content area ── -->
        <div class="inv-content">

            <!-- Search + sort bar -->
            <div class="inv-toolbar">
                <div class="inv-search-wrap">
                    <span class="material-icons-outlined inv-search-icon">search</span>
                    <input type="text" id="invSearch" class="inv-search"
                        placeholder="Search products..." oninput="renderTable()">
                </div>
                <div class="inv-toolbar-right">
                    <select id="sortSelect" class="inv-select" onchange="renderTable()">
                        <option value="name-asc">Name A–Z</option>
                        <option value="name-desc">Name Z–A</option>
                        <option value="stock-asc">Stock: Low first</option>
                        <option value="stock-desc">Stock: High first</option>
                        <option value="price-asc">Price: Low first</option>
                        <option value="price-desc">Price: High first</option>
                    </select>
                    <div class="view-toggle">
                        <button class="view-btn active" id="btnTable" onclick="setView('table')">
                            <span class="material-icons-outlined">table_rows</span>
                        </button>
                        <button class="view-btn" id="btnGrid" onclick="setView('grid')">
                            <span class="material-icons-outlined">grid_view</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- TABLE VIEW -->
            <div id="tableView" class="inv-table-wrap">
                <table class="inv-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th class="text-center">Stock</th>
                            <th class="text-right">Price</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="invTableBody"></tbody>
                </table>
                <div id="tableEmpty" class="inv-empty hidden">
                    <span class="material-icons-outlined">search_off</span>
                    <p>No products match your search.</p>
                </div>
            </div>

            <!-- GRID VIEW (hidden by default, shown when grid btn clicked) -->
            <div id="gridView" class="inv-grid" style="display:none;"></div>

        </div>
    </main>

    <!-- ═══════════ EDIT STOCK MODAL ═══════════ -->
    <div id="editModal" class="inv-overlay hidden">
        <div class="inv-modal-sheet">
            <div class="inv-modal-head">
                <div class="inv-modal-head-icon">
                    <span class="material-icons-outlined">edit</span>
                </div>
                <div>
                    <h3 class="inv-modal-title">Edit Stock</h3>
                    <p class="inv-modal-sub" id="edit-product-name">—</p>
                </div>
                <button class="inv-modal-x" onclick="closeEdit()">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <div class="inv-modal-body">
                <div class="inv-form-group">
                    <label>Current Stock</label>
                    <input type="number" id="edit-stock" class="inv-form-input" min="0">
                </div>
                <div class="inv-form-group">
                    <label>Price (₱)</label>
                    <input type="number" id="edit-price" class="inv-form-input" min="0" step="0.01">
                </div>
                <div class="inv-form-group">
                    <label>Status</label>
                    <select id="edit-status" class="inv-form-input">
                        <option value="Available">Available</option>
                        <option value="Out of Stock">Out of Stock</option>
                    </select>
                </div>
            </div>
            <div class="inv-modal-foot">
                <button class="inv-btn-cancel" onclick="closeEdit()">Cancel</button>
                <button class="inv-btn-save" onclick="saveEdit()">
                    <span class="material-icons-outlined">save</span> Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- ═══════════ ADD PRODUCT MODAL ═══════════ -->
    <div id="addModal" class="inv-overlay hidden">
        <div class="inv-modal-sheet inv-modal-sheet-wide">
            <div class="inv-modal-head">
                <div class="inv-modal-head-icon inv-modal-head-icon-green">
                    <span class="material-icons-outlined">add_box</span>
                </div>
                <div>
                    <h3 class="inv-modal-title">Add New Product</h3>
                    <p class="inv-modal-sub">Fill in the product details below</p>
                </div>
                <button class="inv-modal-x" onclick="closeAdd()">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <div class="inv-modal-body">

                <!-- Image upload -->
                <div class="inv-upload-wrapper">
                    <label for="add-image" class="inv-upload-label" id="addUploadLabel">
                        <div id="add-preview" class="inv-upload-preview">
                            <span class="material-icons-outlined inv-upload-icon">cloud_upload</span>
                            <span class="inv-upload-main-text">Click to upload photo</span>
                            <span class="inv-upload-sub-text">PNG, JPG up to 5MB</span>
                        </div>
                        <div class="inv-upload-overlay-hover">
                            <span class="material-icons-outlined">edit</span>
                            <span>Change Photo</span>
                        </div>
                    </label>
                    <input type="file" id="add-image" accept="image/*" hidden onchange="previewAddImage(this)">
                </div>

                <div class="inv-form-row">
                    <div class="inv-form-group">
                        <label>Product Name <span class="inv-req">*</span></label>
                        <input type="text" id="add-name" class="inv-form-input" placeholder="e.g. Art Appreciation">
                    </div>
                    <div class="inv-form-group">
                        <label>Category <span class="inv-req">*</span></label>
                        <select id="add-category" class="inv-form-input">
                            <option value="" disabled selected>Select category</option>
                            <option value="books">Books</option>
                            <option value="uniforms">Uniforms</option>
                            <option value="apparel">Apparel</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                </div>
                <div class="inv-form-row">
                    <div class="inv-form-group">
                        <label>Price (₱) <span class="inv-req">*</span></label>
                        <input type="number" id="add-price" class="inv-form-input" min="0" step="0.01" placeholder="0.00">
                    </div>
                    <div class="inv-form-group">
                        <label>Stock <span class="inv-req">*</span></label>
                        <input type="number" id="add-stock" class="inv-form-input" min="0" placeholder="0">
                    </div>
                </div>
                <div class="inv-form-group">
                    <label>Description</label>
                    <textarea id="add-desc" class="inv-form-input inv-form-textarea" placeholder="Optional details..."></textarea>
                </div>
            </div>
            <div class="inv-modal-foot">
                <button class="inv-btn-cancel" onclick="closeAdd()">Cancel</button>
                <button class="inv-btn-save" onclick="saveAdd()">
                    <span class="material-icons-outlined">add</span> Add Product
                </button>
            </div>
        </div>
    </div>

    <!-- ═══════════ DELETE CONFIRM ═══════════ -->
    <div id="deleteModal" class="inv-overlay hidden">
        <div class="inv-modal-sheet inv-modal-sheet-sm">
            <div class="inv-modal-head">
                <div class="inv-modal-head-icon inv-modal-head-icon-red">
                    <span class="material-icons-outlined">delete_forever</span>
                </div>
                <div>
                    <h3 class="inv-modal-title">Delete Product</h3>
                    <p class="inv-modal-sub">This action cannot be undone.</p>
                </div>
                <button class="inv-modal-x" onclick="closeDelete()">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <div class="inv-modal-body">
                <p class="inv-delete-confirm-text">
                    Are you sure you want to delete <strong id="delete-product-name"></strong>?
                </p>
            </div>
            <div class="inv-modal-foot">
                <button class="inv-btn-cancel" onclick="closeDelete()">Cancel</button>
                <button class="inv-btn-delete" onclick="confirmDelete()">
                    <span class="material-icons-outlined">delete</span> Delete
                </button>
            </div>
        </div>
    </div>

    <!-- ═══════════ TOAST ═══════════ -->
    <div id="inv-toast" class="inv-toast">
        <span class="material-icons-outlined" id="inv-toast-icon">check_circle</span>
        <span id="inv-toast-msg">Done!</span>
    </div>

    <!-- ═══════════ FOOTER — shared component ═══════════ -->
    <?php include '../../component/footer/footer.php'; ?>

    <!-- nav.js shared across all pages -->
    <script src="../../component/navbar/nav.js"></script>
    <script src="inventory.js"></script>
</body>
</html>