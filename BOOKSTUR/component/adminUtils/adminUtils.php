<?php
$raw_pos = $_SESSION['course'] ?? 'NOT_SET';
echo "";

$current_position = strtoupper(trim($raw_pos)); 
$isAdmin = ($current_position === 'ADMIN' || $current_position === 'SUPER ADMIN');
?>
<?php if ($isAdmin): ?>
<div class="ubuntu-fab-container">
    <div class="ubuntu-menu" id="ubuntuMenu">

        <a href="/BOOKSTUR/component/adminUtils/transaction.php">
            <button class="ubuntu-item">
                <span class="material-symbols-rounded">receipt_long</span>
                Transaction History
            </button>
        </a>
        
        <button class="ubuntu-item" onclick="openAppendModal()">
            <span class="material-symbols-rounded">auto_stories</span>
            Add to Library
        </button>
        
        <button class="ubuntu-item" onclick="openUniformModal()">
            <span class="material-symbols-rounded">apparel</span>
            Add to Uniform
        </button>

        <button class="ubuntu-item" onclick="openApparelModal()">
            <span class="material-symbols-rounded">apparel</span>
            Add to Apparel
        </button>

        <button class="ubuntu-item" onclick="openOtherModal()">
            <span class="material-symbols-rounded">ink_pen</span>
            Add to Others
        </button>
        
        <button class="ubuntu-item" onclick="confirmLogout()">
            <span class="material-symbols-rounded">logout</span>
            Logout
        </button>
    </div>

    <button class="ubuntu-launcher" id="launcherBtn">
        <span class="material-symbols-rounded">grid_view</span>
    </button>
</div>
<?php endif ?>