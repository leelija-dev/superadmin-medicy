<div class="card border-left-danger shadow h-100 py-2 animated--grow-in">
    <div class="card-body pb-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Expiring in 3 Months </div>
                    <div class="col-auto text-danger mr-n4">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                </div>
                <div class="mb-0 font-weight-bold text-gray-800">
                    <?php
                    $expStok = $CurrentStock->showStockExpiry(NOW, $adminId);
                    echo count($expStok);
                    ?> Stocks</div>
            </div>

        </div>
    </div>
</div>