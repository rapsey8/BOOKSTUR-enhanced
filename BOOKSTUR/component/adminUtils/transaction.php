<?php
require_once '../../include/config.php';
require_once '../../include/auth_checker.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History | SSCR - Bookstore</title>

    <!-- Same font stack as every other page -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Shared site-wide styles (has :root vars, .page-header, body, nav, footer, etc.) -->
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="../../component/navbar/navbar.css">
    <link rel="stylesheet" href="../../component/searchbar/searchbar.css">
    <link rel="stylesheet" href="../../component/footer/footer.css">

    <!-- Page-specific styles -->
    <link rel="stylesheet" href="transaction.css">
</head>
<body>

    <!-- ═══════════ NAVBAR — exact same component as every page ═══════════ -->
    <?php include '../../component/navbar/navbar.php'; ?>

    <!-- ═══════════ HERO — same structure as library/uniform/apparel headers ═══════════ -->
    <header class="page-header">
        <div class="text-container" style="margin-bottom: 60px;">
            <h1>Order Ledger</h1>
            <p>Transaction History &amp; Records</p>
        </div>
    </header>

    <!-- ═══════════ MAIN ═══════════ -->
    <main class="main-container">

        <!-- Stat Cards -->
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon stat-icon-orders">
                    <span class="material-icons-outlined">receipt</span>
                </div>
                <div>
                    <div class="stat-label">Total Orders</div>
                    <div class="stat-value" id="stat-total">0</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon-revenue">
                    <span class="material-icons-outlined">payments</span>
                </div>
                <div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value" id="stat-revenue">₱0</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon-completed">
                    <span class="material-icons-outlined">check_circle</span>
                </div>
                <div>
                    <div class="stat-label">Completed</div>
                    <div class="stat-value" id="stat-completed">0</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon-pending">
                    <span class="material-icons-outlined">pending</span>
                </div>
                <div>
                    <div class="stat-label">Pending</div>
                    <div class="stat-value" id="stat-pending">0</div>
                </div>
            </div>
        </div>

        <!-- Section Header + Filters -->
        <div class="section-header">
            <div class="section-title-group">
                <h2 class="section-title">Recent Orders</h2>
                <p class="section-sub">Review and track all past bookstore purchases.</p>
            </div>
            <div class="filter-bar">
                <select id="monthFilter" onchange="filterOrders()" class="filter-select">
                    <option value="">All Months</option>
                    <option value="Jan">January</option>
                    <option value="Feb">February</option>
                    <option value="Mar">March</option>
                    <option value="Apr">April</option>
                    <option value="May">May</option>
                    <option value="Jun">June</option>
                    <option value="Jul">July</option>
                    <option value="Aug">August</option>
                    <option value="Sep">September</option>
                    <option value="Oct">October</option>
                    <option value="Nov">November</option>
                    <option value="Dec">December</option>
                </select>
                <select id="yearFilter" onchange="filterOrders()" class="filter-select">
                    <option value="">All Years</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                </select>
                <select id="statusFilter" onchange="filterOrders()" class="filter-select">
                    <option value="">All Status</option>
                    <option value="Completed">Completed</option>
                    <option value="Pending">Pending</option>
                    <option value="Processing">Processing</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
                <input type="text" id="txSearch" onkeyup="filterOrders()"
                    placeholder="Search Order ID or item..."
                    class="filter-input">
            </div>
        </div>

        <!-- Table -->
        <div class="history-table-wrap">
            <table class="history-table" id="mainTable">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Items Summary</th>
                        <th>Payment Method</th>
                        <th>Total Paid</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Date</th>
                        <th class="text-center">Details</th>
                    </tr>
                </thead>
                <tbody id="historyTable">

                    <!-- SAMPLE ROWS — replace with PHP while loop once orders table exists -->
                    <tr data-order="#SSCR-0021"
                        data-items="PE Uniform (Large)"
                        data-method="GCash"
                        data-total="₱1,450.00"
                        data-status="Completed"
                        data-notes="Paid via GCash. Ready for pickup.">
                        <td><span class="order-id">#SSCR-0021</span></td>
                        <td><strong>2×</strong> PE Uniform (Large)</td>
                        <td class="text-muted text-italic">GCash</td>
                        <td class="text-bold text-dark">₱1,450.00</td>
                        <td class="text-center">
                            <span class="badge badge-completed">
                                <span class="badge-dot"></span>Completed
                            </span>
                        </td>
                        <td class="order-date text-right text-muted">Apr 24, 2026</td>
                        <td class="text-center">
                            <button class="btn-view-details" onclick="openDetail(this)">
                                <span class="material-icons-outlined" style="font-size:17px;">visibility</span>
                            </button>
                        </td>
                    </tr>

                    <tr data-order="#SSCR-0022"
                        data-items="School Blouse (Small)"
                        data-method="Over the Counter"
                        data-total="₱650.00"
                        data-status="Pending"
                        data-notes="Awaiting student pickup at the bookstore window.">
                        <td><span class="order-id">#SSCR-0022</span></td>
                        <td><strong>1×</strong> School Blouse (Small)</td>
                        <td class="text-muted text-italic">Over the Counter</td>
                        <td class="text-bold text-dark">₱650.00</td>
                        <td class="text-center">
                            <span class="badge badge-pending">
                                <span class="badge-dot"></span>Pending
                            </span>
                        </td>
                        <td class="order-date text-right text-muted">May 1, 2026</td>
                        <td class="text-center">
                            <button class="btn-view-details" onclick="openDetail(this)">
                                <span class="material-icons-outlined" style="font-size:17px;">visibility</span>
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>

            <div id="no-history" class="hidden empty-state">
                <div class="empty-icon">
                    <span class="material-icons-outlined">search_off</span>
                </div>
                <p class="empty-title">No records found</p>
                <p class="empty-sub">Try adjusting your filters or search term.</p>
            </div>
        </div>

    </main>

    <!-- ═══════════ DETAIL MODAL ═══════════ -->
    <div id="detailModal" class="modal-container" style="display:none;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-header-left">
                    <div class="modal-header-icon">
                        <span class="material-icons-outlined">receipt_long</span>
                    </div>
                    <div>
                        <h2>Order Details</h2>
                        <p class="modal-order-sub" id="modal-order-id-sub"></p>
                    </div>
                </div>
                <button class="modal-close" onclick="closeDetail()">
                    <span class="material-icons-outlined" style="font-size:18px;">close</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="detail-row">
                    <span class="detail-label">Order ID</span>
                    <span class="detail-value detail-value-id" id="m-order-id"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date</span>
                    <span class="detail-value" id="m-date"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Items</span>
                    <span class="detail-value" id="m-items"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment</span>
                    <span class="detail-value" id="m-method"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Paid</span>
                    <span class="detail-value detail-value-total" id="m-total"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" id="m-status"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Notes</span>
                    <span class="detail-value detail-value-notes" id="m-notes"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-close-modal" onclick="closeDetail()">Close</button>
            </div>
        </div>
    </div>

    <!-- ═══════════ FOOTER — exact same component as every page ═══════════ -->
    <?php include '../../component/footer/footer.php'; ?>

    <!-- nav.js is shared across all pages -->
    <script src="../../component/navbar/nav.js"></script>
    <script src="transaction.js"></script>
</body>
</html>