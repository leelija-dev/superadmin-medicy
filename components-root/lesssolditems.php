<?php
// require_once dirname(__DIR__) . '/config/constant.php';
$includePath = get_include_path();

$today = NOW;

$leastStoldItemsFromStart = $StockOut->leastSoldStockOutDataFromStart($adminId);

$dailyLeastStoldItems = $StockOut->leastSoldStockOutDataGroupByDay($adminId);

$weeklyLeastStoldItems = $StockOut->leastSoldStockOutDataGroupByWeek($adminId);

$monthlyLeastStoldItems = $StockOut->leastSoldStockOutDataGroupByMonth($adminId);
// print_r($leastStoldItemsFromStart);
?>

<div class="card border-left-primary shadow h-100 py-2 pending_border animated--grow-in">
    <div class="d-flex justify-content-between align-items-center">
        <div class="col ml-2 mt-3">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                less sold 10 items</div>
        </div>
        <div class="d-flex justify-content-end px-2">
            <div class="dropdown-menu dropdown-menu-right p-3 mt-n5" id="lessSoldDtPickerDiv" style="display: none;">
                <input type="date" id="lessSoldDateInput">
                <button class="btn btn-sm btn-primary" onclick="lessSoldItemsChkDate()" style="height: 2rem;">Find</button>
            </div>
            <div class="dropdown-menu dropdown-menu-right p-3 mt-n5" id="lessSoldDtRangePickerDiv" style="display: none;">
                <label>Start Date</label>
                <input type="date" id="lessSoldStartDate">
                <label>End Date</label>
                <input type="date" id="lessSoldEndtDate">
                <button class="btn btn-sm btn-primary" onclick="lessSoldItemsChkDateRange()" style="height: 2rem;">Find</button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <!-- <img src=" IMG_PATH./arrow-down-sign-to-navigate.jpg" alt=""> -->

                    <b>...</b>
                </button>
                <div class="dropdown-menu dropdown-menu-right" style="background-color: rgba(255, 255, 255, 0.8);">
                    <button class="dropdown-item" type="button" id="lessSoldLst24hrs" onclick="lessSoldItemChk(this.id)">Last 24 hrs</button>
                    <button class="dropdown-item" type="button" id="lessSoldLst7" onclick="lessSoldItemChk(this.id)">Last 7 Days</button>
                    <button class="dropdown-item" type="button" id="lessSoldLst30" onclick="lessSoldItemChk(this.id)">Last 30 DAYS</button>
                    <button class="dropdown-item dropdown" type="button" id="lessSoldLstDt" onclick="lessSoldItemChk(this.id)">By Date</button>
                    <button class="dropdown-item dropdown" type="button" id="lessSoldLstDtRng" onclick="lessSoldItemChk(this.id)">By Range</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body mt-n2 pb-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div style="width: 100%; margin: 0 auto;" id='lesssolditemChartDiv'>
                    <canvas id="lesssolditemchart"></canvas>
                </div>
                <div style="width: 100%; margin: 0 auto;" id='lesssolditemNDFDiv'>
                    <p class="text-warning">Oops!, the requested data isn't in our records.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo PLUGIN_PATH; ?>chartjs-4.4.0/updatedChart.js"></script>

<script>
    function updateLessSoldData(lessSoldData) {

        if (lessSoldData != null) {
            lessSoldChart.data.datasets[0].data = lessSoldData.map(item => item.total_sold);

            var productIds = lessSoldData.map(item => item.product_id);
            productIds = JSON.stringify(productIds);

            lessSoldProdNameUrl = `<?php echo URL ?>ajax/components-most-sold-items.ajax.php?lessSoldProdId=${productIds}`;
            request.open("GET", lessSoldProdNameUrl, false);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(null);
            var prodNameArray = request.responseText;
            prodNameArray = JSON.parse(prodNameArray);

            lessSoldChart.data.labels = prodNameArray;

            document.getElementById('lesssolditemChartDiv').style.display = 'block';
            document.getElementById('lesssolditemNDFDiv').style.display = 'none';

            lessSoldChart.update();

        } else {

            document.getElementById('lesssolditemChartDiv').style.display = 'none';
            document.getElementById('lesssolditemNDFDiv').style.display = 'block';

        }

    }




    function lessSoldItemsChkDate() {
        var lessSolddatePicker = document.getElementById('lessSoldDateInput').value;

        lessSoldDtPkrUrl = `<?php echo URL ?>ajax/components-most-sold-items.ajax.php?lessSoldChkDt=${lessSolddatePicker}`;
        request.open("GET", lessSoldDtPkrUrl, false);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(null);
        var lessSoldDataByDate = request.responseText;
        updateLessSoldData(JSON.parse(lessSoldDataByDate));

        document.getElementById('lessSoldDtPickerDiv').style.display = 'none';
        document.getElementById('lessSoldDtRangePickerDiv').style.display = 'none';
    }




    function lessSoldItemsChkDateRange() {
        var lessSoldStarDt = document.getElementById('lessSoldStartDate').value;
        var lessSoldEndDt = document.getElementById('lessSoldEndtDate').value;

        lessSoldDtPkrUrl = `<?php echo URL ?>ajax/components-most-sold-items.ajax.php?lessSoldStartDt=${lessSoldStarDt}&lessSoldEndDt=${lessSoldEndDt}`;
        request.open("GET", lessSoldDtPkrUrl, false);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(null);
        var lessSoldDataInDtRange = request.responseText;

        updateLessSoldData(JSON.parse(lessSoldDataInDtRange));

        document.getElementById('lessSoldDtPickerDiv').style.display = 'none';
        document.getElementById('lessSoldDtRangePickerDiv').style.display = 'none';
    }





    function lessSoldItemChk(id) {
        if (id == 'lessSoldLst24hrs') {
            document.getElementById('lessSoldDtPickerDiv').style.display = 'none';
            document.getElementById('lessSoldDtRangePickerDiv').style.display = 'none';
            updateLessSoldData(<?php echo json_encode($dailyLeastStoldItems); ?>);
        }

        if (id == 'lessSoldLst7') {
            document.getElementById('lessSoldDtPickerDiv').style.display = 'none';
            document.getElementById('lessSoldDtRangePickerDiv').style.display = 'none';
            updateLessSoldData(<?php echo json_encode($weeklyLeastStoldItems); ?>);
        }

        if (id == 'lessSoldLst30') {
            document.getElementById('lessSoldDtPickerDiv').style.display = 'none';
            document.getElementById('lessSoldDtRangePickerDiv').style.display = 'none';
            updateLessSoldData(<?php echo json_encode($monthlyLeastStoldItems); ?>);
        }

        if (id == 'lessSoldLstDt') {
            document.getElementById('lessSoldDtPickerDiv').style.display = 'block';
            // document.getElementById('lessSoldDtRangePickerDiv').style.display = 'none';
        }

        if (id == 'lessSoldLstDtRng') {
            // document.getElementById('lessSoldDtPickerDiv').style.display = 'none';
            document.getElementById('lessSoldDtRangePickerDiv').style.display = 'block';
        }
    }


    // ========= less sold primacy chart area ============= \\
    let lessSoldPrimaryData = <?php echo json_encode($leastStoldItemsFromStart); ?>;

    if (lessSoldPrimaryData != null) {

        var productIds = lessSoldPrimaryData.map(item => item.product_id);
        productIds = JSON.stringify(productIds);

        lessSoldProdNameUrl = `<?php echo URL ?>ajax/components-most-sold-items.ajax.php?lessSoldProdId=${productIds}`;
        request.open("GET", lessSoldProdNameUrl, false);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(null);
        var prodNameArray = request.responseText;
        prodNameArray = JSON.parse(prodNameArray);

        var totalSold = lessSoldPrimaryData.map(item => item.total_sold);

        document.getElementById('lesssolditemChartDiv').style.display = 'block';
        document.getElementById('lesssolditemNDFDiv').style.display = 'none';

    } else {
        document.getElementById('lesssolditemChartDiv').style.display = 'none';
        document.getElementById('lesssolditemNDFDiv').style.display = 'block';
    }



    // ========== less sold bar chart area ================
    var lessSoldCtx = document.getElementById('lesssolditemchart').getContext('2d');
    var lessSoldChart = new Chart(lessSoldCtx, {
        type: 'bar',
        data: {
            labels: prodNameArray,
            datasets: [{
                label: 'Total Sold',
                data: totalSold,
                backgroundColor: 'rgba(75, 192, 192, 0.8)',
                // borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>