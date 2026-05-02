<main class="product-grid" id="book-grid">
    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $status_class = ($row['status'] === 'Available') ? 'status-available' : 'status-out';
            
            // Siguraduhin na 'product_image' ang column name sa DB
            $image_path = "../../src/uploads/products/" . $row['product_image'];
            ?>
            
            <div class="product-card">
                <div class="img-container">
                    <img src="<?php echo $image_path; ?>" 
                         onerror="this.src='../../src/placeholder.jpg';" 
                         alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                    
                    <span class="status-tag <?php echo $status_class; ?>">
                        <?php echo htmlspecialchars($row['status']); ?>
                    </span>
                </div>
                
                <div class="product-details">
                    <small style="color: #888;"><?php echo htmlspecialchars($row['category_name'] ?? 'No Category'); ?></small>
                    
                    <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                    
                    <p class="price">₱<?php echo number_format($row['price'], 2); ?></p>
                    
                    <button class="add-btn" onclick="addToCart(<?php echo $row['product_id']; ?>)">
                        Add to Cart
                    </button>
                </div>
            </div>

            <?php
        }
    } else {
        echo "<div class='no-products'>No items found.</div>";
    }
    ?>
</main>