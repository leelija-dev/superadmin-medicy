<div class="card shadow-sm h-100 animated--grow-in">
    <div class="px-3 mt-2">
        <div class="d-flex justify-content-between mt-2">
            <p class="text-xs font-weight-bold text-success text-uppercase mb-1">
                <?= $userRole == 'ADMIN' ? 'Sales' : 'Sold By ' . $username; ?>
            </p>
            <i class="fas fa-user"></i>
        </div>

        <div class="">
            <div class="">
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    <?php
                    if ($userRole == 'ADMIN') {
                        $sold = $StockOut->amountSoldByAll($adminId);
                        // print_r($sold);
                        $amount = 0;
                        $items = 0;
                        if (!empty($sold)) {
                            foreach ($sold as $data) {
                                $amount += $data['amount'];
                                $items += $data['items'];
                            }
                        }
                    } else {
                        $sold = $StockOut->amountSoldByEmployee($employeeId, $adminId);
                        // print_r($sold);
                        $amount = 0;
                        $items = 0;
                        if (!empty($sold)) {
                            foreach ($sold as $data) {
                                $amount += $data['amount'];
                                $items += $data['items'];
                            }
                        }
                    }
                    echo '₹' . $amount;
                    ?>
                </div>
                <p><small class="mb-0 pb-0"><?= $items; ?> Items</small></p>
            </div>
        </div>
    </div>
</div>