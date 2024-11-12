<!-- Products -->
<?php if (in_array(8, $permissionArray)) : ?>
<li class="nav-item  <?= $page ==  "products" || $page ==  "add-new-product" ? "active" : ''; ?>">
    <a   class="nav-link Nmenu-item <?= $page !=  "sales" ? "collapsed" : ''; ?>" href="#" data-toggle="collapse" data-target="#productsManagement" aria-expanded="<?= $page ==  "products" || $page ==  "add-new-product" ? "true" : ''; ?>" aria-controls="productsManagement">
        <i class="fas fa-pills"></i>
        <span>Products</span><i class="fas fa-chevron-right NSarrow"></i>
    </a>
    <div id="pproductsManagement" class="Nsubmenu hidesubmenu <?= $page ==  "products" ||  $page ==  "add-new-product" ? "show" : ''; ?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
      
            <a class=" <?= $page ==  "products" ? "active" : ''; ?>" href="products.php">All Products </a>
            <a class=" mt-n3<?= $page ==  "add-new-product" ? "active" : ''; ?>" href="add-new-product.php ">Add Product</a>
       
    </div>
</li>
<?php endif; ?>
<!--/end Products Menu  -->


<!-- Product Management collapsed Menu  -->
<?php if (in_array(9, $permissionArray)) : ?>
<li class="nav-item mt-md-n3 <?= $page ==  "sales" || $page ==  "sales-returns" || $page == "new-sales" || $page == "sales-returns-items" || $page == 'update-sales' || $page == 'sales-return-edit' ? "active" : ''; ?>">
    <a active  class="nav-link Nmenu-item <?= $page !=  "sales" ? "collapsed" : ''; ?>" href="#" data-toggle="collapse" data-target="#collapseSalesManagement" aria-expanded="<?= $page ==  "sales" || $page ==  "sales-returns" || $page == "new-sales" || $page == "sales-returns-items" || $page == 'update-sales' || $page == 'sales-return-edit' ? "true" : ''; ?>" aria-controls="collapseSalesManagement">
        <i class="fas fa-clinic-medical"></i>
        <span>Sales Management</span><i class="fas fa-chevron-right NSarrow"></i>
    </a>
    <div id="colSalesManagement" class="Nsubmenu hidesubmenu <?= $page ==  "sales" ||  $page ==  "sales-returns" || $page == "new-sales" || $page == "sales-returns-items" || $page == 'update-sales' || $page == 'sales-return-edit' ? "show" : ''; ?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
       
            <a class="<?= $page ==  "sales" || $page == "new-sales" || $page == 'update-sales'  ? "active" : ''; ?>" href="sales.php">Sales</a>
            <a class=" mt-n3<?= $page ==  "sales-returns" || $page == "sales-returns-items" || $page == 'sales-return-edit' ? "active" : ''; ?>" href="sales-returns.php">Sales Returns</a>
       
    </div>
</li>
<?php endif; ?>
<!--/end Purchase Master collapsed Menu  -->


<!-- Purchase Management  -->
<?php if (in_array(10, $permissionArray)) : ?>
<li class="nav-item mt-md-n2 <?= $page ==  "stock-in" || $page ==  "purchase-details" || $page == "stock-in-edit" || $page == "stock-return" || $page == "stock-return-item" ? "active" : ''; ?>">
    <a active  class="nav-link Nmenu-item <?= $page !=  "stock-in" || $page !=  "purchase-details" ? "collapsed" : ''; ?>" href="#" data-toggle="collapse" data-target="#collapsePurchaseManagement" aria-expanded="<?= $page ==  "stock-in" || $page ==  "purchase-details" || $page == "stock-in-edit" || $page == "stock-return" || $page == "stock-return-item" ?  "true" : ''; ?>" aria-controls="collapsePurchaseManagement">
        <i class="fas fa-store-alt"></i>
        <span>Purchase Management</span><i class="fas fa-chevron-right NSarrow"></i>
    </a>
    <div id="colPurchaseManagement" class="Nsubmenu hidesubmenu <?= $page ==  "stock-in" || $page ==  "purchase-details" || $page == "stock-in-edit" || $page == "stock-return" || $page == "stock-return-item" ? "show" : ''; ?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
      
            
            <a class=" <?= $page ==  "stock-in" ? "active" : ''; ?>" href="stock-in.php ">New Purchase</a>
            <a class=" mt-n3 <?= $page ==  "purchase-details" || $page == "stock-in-edit" ? "active" : ''; ?>" href="purchase-details.php ">Purchase </a>
            <a class=" mt-n3 <?= $page ==  "stock-return" || $page == "stock-return-item" ? "active" : ''; ?>" href="stock-return.php">Purchase Return</a>
        
    </div>
</li>
<?php endif; ?>
<!--/end Purchase Master collapsed Menu  -->


<!-- Product Management collapsed Menu  -->
<?php if (in_array(11, $permissionArray)) : ?>
<li class="nav-item mt-md-n2 <?= $page ==  "current-stock" || $page ==  "stock-expiring" || $page ==  "stock-in-details" ?  "active" : ''; ?>">
    <a id="sidebarExp4" class="nav-link Nmenu-item <?= $page !=  "current-stock" ? "collapsed" : ''; ?>" href="#" data-toggle="collapse" data-target="#collapseStock" aria-expanded="<?= $page ==  "current-stock" || $page ==  "stock-expiring" || $page ==  "stock-in-details" ? "true" : ''; ?>" aria-controls="collapseStock">
    <i class="fas fa-warehouse"></i>
        <span>Stock Details</span><i class="fas fa-chevron-right NSarrow"></i>
    </a>
    <div id="colStock" class="Nsubmenu hidesubmenu  <?= $page ==  "current-stock" || $page ==  "stock-expiring" || $page ==  "stock-in-details" ? "show" : ''; ?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
       
           
            <a class=" <?= $page ==  "current-stock" ? "active" : ''; ?>" href="current-stock.php">
                Current Stock </a>
            <a class="  mt-n3<?= $page ==  "stock-expiring" ? "active" : ""; ?>" href="stock-expiring.php">
                Stock Expiring </a>
       
    </div>
</li>
<?php endif; ?>
<!--/end product management collapsed Menu  -->


<!-- Nav Item - Distributo -->
<?php if (in_array(12, $permissionArray)) : ?>
<li class="nav-item mt-md-n3 <?= $page ==  "distributor" ? "active" : ""; ?>">
    <a class="nav-link" href="distributor.php">
    <i class="fas fa-dolly-flatbed"></i>
        <span>Distributor</span></a>
</li>
<?php endif; ?>

<!-- Purchase Master collapsed Menu  -->
<?php if (in_array(13, $permissionArray) ) : ?>
<li class="nav-item  <?= $page ==  "purchase-master" ? "active" : '' ?>">
    <a class="nav-link collapsed" href="purchase-master.php">
        <i class="fas fa-fw fa-cog"></i>
        <span>Purchase Master</span>
    </a>
</li>
<?php endif; ?>

<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    var sidebar = document.getElementById('accordionSidebar');
    var buttons = ['sidebarExp1', 'sidebarExp2', 'sidebarExp3', 'sidebarExp4'];

    function toggleSidebar() {
        sidebar.classList.toggle('sidebar');
        sidebarToggle.classList.toggle('expanded');
    }

    buttons.forEach(function(buttonId) {
        var button = document.getElementById(buttonId);
        button.addEventListener('click', toggleSidebar);
    });
});
</script> -->