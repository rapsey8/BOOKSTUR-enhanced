<div id="addToApparelModal" class="modal-container" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Apparel</h2>
        </div>
        
        <form action="../../include/addToApparelModalFunciton.php" method="POST" enctype="multipart/form-data" id="addToApparelModalForm">
            <div class="modal-body">
                <div class="img-upload-section">
                    <div class="upload-wrapper">
                        <label for="product_image" class="upload-label">
                            <div id="preview_container" style="margin-top:20px;">
                                <div class="placeholder-content">
                                    <div class="icon-circle">
                                        <span class="material-icons-outlined">checkroom</span> </div>
                                    <div class="text-content">
                                        <span class="main-text">Upload Uniform Photo</span>
                                        <span class="sub-text">PNG, JPG up to 5MB</span>
                                    </div>
                                </div>
                            </div>
                            <div class="upload-overlay">
                                <span class="material-icons-outlined">edit</span>
                                <p>Change Photo</p>
                            </div>
                        </label>
                        <input type="file" name="product_image" id="image_url" accept="image/*" onchange="previewProductImage(this)" hidden>
                    </div>
                </div>

                <div class="input-group">
                    <label>Uniform Name</label>
                    <input type="text" name="product_name" placeholder="e.g. Senior High School Male Polo">
                </div>

                <div style="display: flex; gap: 10px;">
                    <div class="input-group" style="flex: 1;">
                        <label>Category</label>
                        <select name="category_id">
                            <option value="" disabled selected>Select category</option>
                            <?php
                            $cat_query = "SELECT * FROM categories ORDER BY category_name ASC";
                            $cat_result = mysqli_query($conn, $cat_query);
                            while($cat = mysqli_fetch_assoc($cat_result)) {
                                echo "<option value='".$cat['category_id']."'>".$cat['category_name']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="input-group" style="flex: 1;">
                        <label>Size</label>
                        <select name="size">
                            <option value="" disabled selected>Select size</option>
                            <option value="XS">Extra Small</option>
                            <option value="S">Small</option>
                            <option value="M">Medium</option>
                            <option value="L">Large</option>
                            <option value="XL">XL</option>
                            <option value="2XL">2XL</option>
                            <option value="N/A">Not Applicable</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <div class="input-group" style="flex: 1;">
                        <label>Price (₱)</label>
                        <input type="number" name="price" step="0.01" placeholder="0.00">
                    </div>
                    <div class="input-group" style="flex: 1;">
                        <label>Stock</label>
                        <input type="number" name="stock" placeholder="0">
                    </div>
                </div>

                <div class="input-group">
                    <label>Description/Material</label>
                    <textarea name="description" rows="3" placeholder="e.g. Cotton fabric, tailored fit..."></textarea>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeApparelModal()">Cancel</button>
                <button type="submit" class="btn-save">Add Uniform</button>
            </div>
        </form>
    </div>
</div>