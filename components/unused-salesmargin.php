<?php 
$includePath = get_include_path();

$stockOutDetailsData = $StockOut->salesMarginData('admin_id', $adminId);

if($stockOutDetailsData != null){
    foreach($stockOutDetailsData as $salesData){
        $salesItemPack = $salesData->sales_pack;
        $salesItemMrp = $salesData->sales_mrp;
        $salesItemMargin = $salesData->sell_margin;
    }
}

?>

<div class="mb-4">
    <div class="card border-top-primary pending_border animated--grow-in">
        <div class="card-body">
            <a class="text-decoration-none" href="#">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Sales Margin
                        </div>
                        <div class="table-responsive" id="sales-margin-data-table">

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Pack</th>
                                        <th scope="col">MRP</th>
                                        <th scope="col">Margin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">Current</th>
                                        <td><?php echo $salesItemPack ?></td>
                                        <td><?php echo $salesItemMrp ?></td>
                                        <td><?php echo $salesItemMargin ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Expired</th>
                                        <td>00.00</td>
                                        <td>00.00</td>
                                        <td>00.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" id="salesmargin-no-data-found-div">
                            <label>no data found</label>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<script>

</script>