<div class="mb-4">
    <div class="card border-left-info shadow py-2 pending_border animated--grow-in">
        <div class="card-body">
            <div class="text-decoration-none">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-sm font-weight-bold text-info text-uppercase mb-1">
                            Needs to PAY
                            <i class="text-danger fas fa-arrow-up"></i>
                        </div>
                        <?php
                        $pays = $StockIn->needsToPay($adminId);
                        $payee = 0;
                        foreach ($pays as $data) {
                            $payee += $data['amount'];
                        }
                        $noOfPayees = count($pays);
                        ?>

                        <?php if ($noOfPayees > 0): ?>

                            <div class="row">

                                <div class="col-12 d-flex align-items-center justify-content-around w-100">
                                    <div class="w-25 h5 mb-0 font-weight-bold text-gray-800">
                                        <img class="w-50" src="<?= ASSETS_PATH ?>img/need-to-pay.svg" alt="...">
                                    </div>

                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= '₹' . $payee; ?>
                                    </div>

                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $noOfPayees; ?> bills
                                    </div>
                                </div>

                                <div class="col-12 table-responsive table-hover mt-2" id="sales-margin-data-table">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">Distributor</th>
                                                <th scope="col">Due Date</th>
                                                <th scope="col">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($pays as $data) {
                                                $stockInId = $data['id'];
                                                $distBillNo = $data['distributor_bill'];
                                                $distributorName = $Distributor->distributorName($data['distributor_id']);
                                            ?>
                                                <tr data-toggle="modal" data-target="#exampleModal" onclick="stockDetails(event,'<?= $distBillNo ?>','<?= $stockInId ?>')" style="cursor: pointer;">
                                                    <td><?= $distributorName ?></td>
                                                    <td><?= $data['due_date'] ?></td>
                                                    <td><?= $data['amount'] ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        <?php else: ?>
                            <div class="col-12">
                                <p class="text-center">
                                    <i class="far fa-laugh-beam display-4"></i>
                                    <br>
                                    <span>Wohoo!</span>
                                </p>
                                <p class="text-center font-weight-light">You don't have to pay to any distributor!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <small class="text-muted">Try to pay the due as soon as posibble</small>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Purchase Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body stockDetails">
            </div>
        </div>
    </div>
</div>


<script>
    const stockDetails = async (event,distBill, id) => {
        event.preventDefault();
        const url = `ajax/stockInDetails.view.ajax.php?distBill=${encodeURIComponent(distBill)}&id=${encodeURIComponent(id)}`;

        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const content = await response.text();

            const stockDetailsElement = document.querySelector('.stockDetails');
            if (stockDetailsElement) {
                stockDetailsElement.innerHTML = content;
            }
        } catch (error) {
            Swal.fire({
                title: "Failed",
                text: error,
                icon: "error"
            });
        }
    };
</script>