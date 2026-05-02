<div id="appendModal" class="modal-container" style="display:none; ">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Book</h2>
        </div>
        
        <form  action="../../include/addToBooksModalFunction.php" method="POST" enctype="multipart/form-data" id="addToBooksModalForm">
            <div class="modal-body">
                <div class="img-upload-section">
                <div class="upload-wrapper">
                    <label for="product_image" class="upload-label">
                        <div id="preview_container" style="margin-top:20px;">
                            <div class="placeholder-content">
                                <div class="icon-circle">
                                    <span class="material-icons-outlined">cloud_upload</span>
                                </div>
                                <div class="text-content">
                                    <span class="main-text">Click to upload photo</span>
                                    <span class="sub-text">PNG, JPG up to 5MB</span>
                                </div>
                            </div>
                        </div>
                        <div class="upload-overlay">
                            <span class="material-icons-outlined">edit</span>
                            <p>Change Photo</p>
                        </div>
                    </label>
                    <input type="file" name="product_image" id="product_image" accept="image/*" onchange="previewProductImage(this)" hidden>
                </div>
            </div>

                <div class="input-group">
                    <label>Product Name</label>
                    <input type="text" name="product_name"  placeholder="e.g. Art Appreciation">
                </div>

               <div class="input-group">
                <label>Category</label>
                <select name="category_id">
                    <option value="" disabled selected>Select a category</option>
                    <?php
                   
                    $cat_query = "SELECT * FROM categories ORDER BY category_name ASC";
                    $cat_result = mysqli_query($conn, $cat_query);
                    
                    while($cat = mysqli_fetch_assoc($cat_result)) {
                        echo "<option value='".$cat['category_id']."'>".$cat['category_name']."</option>";
                    }
                    ?>
                </select>
            </div>

                <div style="display: flex; gap: 10px;">
                    <div class="input-group" style="flex: 1;">
                        <label>Price (₱)</label>
                        <input type="number" name="price" step="0.01"  placeholder="0.00">
                    </div>
                    <div class="input-group" style="flex: 1;">
                        <label>Stock</label>
                        <input type="number" name="stock"  placeholder="0">
                    </div>
                </div>

                <div class="input-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" placeholder="Optional details..."></textarea>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeAppendModal()">Cancel</button>
                <button type="submit" class="btn-save">Append Product</button>
            </div>
        </form>
    </div>
</div>