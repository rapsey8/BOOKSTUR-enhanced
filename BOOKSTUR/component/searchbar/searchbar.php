<div class="search-box">
    <input type="text" id="searchbar" placeholder="Search..."
           onkeypress="if(event.key === 'Enter') window.location.href='?search=' + this.value">
    
    <span class="material-icons-outlined" 
          onclick="window.location.href='?search=' + document.getElementById('searchbar').value">
        search
    </span>
</div>