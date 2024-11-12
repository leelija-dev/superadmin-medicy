<?php

$strtDt = date('Y-m-d');
$lst7 = date('Y-m-d', strtotime($strtDt . ' - 7 days'));
$lst30 = date('Y-m-d', strtotime($strtDt . ' - 30 days'));




$dailyMostSoldItems = $StockOut->mostSoldStockOutDataGroupByDay($adminId);

$weeklyMostSoldItems = $StockOut->mostSoldStockOutDataGroupByDtRng($lst7, $strtDt, $adminId);

$monthlyMostSoldItems = $StockOut->mostSoldStockOutDataGroupByDtRng($lst30, $strtDt, $adminId);

$mostSoldItemsFromStart = $StockOut->mostSoldStockOutDataFromStart($adminId);


// print_r($mostSoldItemsFromStart);
//================================================================================


$dailyLessSoldItems = $StockOut->leastSoldStockOutDataGroupByDay($adminId);

$weeklyLessSoldItems = $StockOut->leastSoldStockOutDataGroupByWeek($adminId);

$monthlyLessSoldItems = $StockOut->leastSoldStockOutDataGroupByMonth($adminId);

$lessSoldItemsFromStart = $StockOut->leastSoldStockOutDataFromStart($adminId);


// print_r($stoldItemsFromStart);
?>


<div class="card border-left-primary shadow h-100 py-2 pending_border animated--grow-in">
    <div class="row mt-3">
        <div class="col-md-5 ml-3">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1" id="most-sold-header" style="display: block;">
                Most sold 10 items
            </div>
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1" id="less-sold-header" style="display: none;">
                Less sold 10 items
            </div>
        </div>
        <div class="col-md-3">
            <div class="dropdown-menu dropdown-menu-right p-3 mt-n5" id="soldDtPickerDiv" style="display: none; margin-right:1rem;">
                <input type="date" id="mostSoldDateInput">
                <button class="btn btn-sm btn-primary" onclick="soldItemsChkDate()" style="height: 2rem;">Find</button>
            </div>
            <div class="dropdown-menu dropdown-menu-right p-3 mt-n5" id="soldDtRngPickerDiv" style="display: none; margin-right:1rem; ">
                <label>Start Date</label>
                <input type="date" id="mostSoldStarDate">
                <label>End Date</label>
                <input type="date" id="mostSoldEndDate">
                <button class="btn btn-sm btn-primary" onclick="soldItemsChkDateRng()" style="height: 2rem;">Find</button>
            </div>
        </div>
        <div class="col-md-3 d-flex justify-content-end">
            <div class="mr-2">
                <label id='data-sort' class="d-none">asc</label>
                <button type="button" class="btn btn-sm btn-outline-primary card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fas fa-sort"></i> Sort
                </button>

                <div class="dropdown-menu dropdown-menu-right" style="background-color: rgba(200, 200, 200, 0.3);">
                    <button class="dropdown-item  dropdown" type="button" id="asc" value="asc" onclick="dataFilter(this)">Ascending</button>
                    <button class="dropdown-item  dropdown" type="button" id="dsc" value="dsc" onclick="dataFilter(this)">Descending</button>
                </div>
            </div>

            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-primary card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fas fa-filter"></i>
                </button>

                <label id='secondary-filter' class="d-none"></label>
                <div class="dropdown-menu dropdown-menu-right" style="background-color: rgba(255, 255, 255, 0.8);">
                    <button class="dropdown-item" type="button" id="soldLst24hrs" onclick="dataFilter(this.id)">Last 24 hrs</button>
                    <button class="dropdown-item" type="button" id="soldLst7" onclick="dataFilter(this.id)">Last 7 Days</button>
                    <button class="dropdown-item" type="button" id="soldLst30" onclick="dataFilter(this.id)">Last 30 DAYS</button>
                    <button class="dropdown-item  dropdown" type="button" id="soldOnDt" onclick="dataFilter(this.id)">By Date</button>
                    <button class="dropdown-item  dropdown" type="button" id="soldOnDtRng" onclick="dataFilter(this.id)">By Range</button>
                </div>

                <lebel class="d-none" id="current-filter-val">allData</lebel>
            </div>
        </div>
    </div>

    <div class="card-body mt-n2 pb-0">
        <div class="row no-gutters align-items-center">
            <div style="width: 100%; margin: 0 auto;" id='mostsolditemchartDiv'>
                <canvas id="mostsolditemchart"></canvas>
            </div>
            <div style="width: 100%; margin: 0 auto;" id='mostsolditemNDFDiv'>
                <p class="text-gray-500">Oops!, the requested data isn't in our records.</p>
            </div>
        </div>
    </div>
</div>

<script>
    let flag = 0;

    // ====== most sold chart data override function =========
    function updateMostSoldData(mostSold) {
        let soldData = mostSold;

        if (mostSold != null) {

            mostSoldChart.data.datasets[0].data = mostSold.map(item => item.total_sold);

            var productIds = mostSold.map(item => item.product_id);
            productIds = JSON.stringify(productIds);

            mostSoldProdNameUrl = `<?php echo URL ?>ajax/components-most-sold-items.ajax.php?mostSoldProdId=${productIds}   `;
            request.open("GET", mostSoldProdNameUrl, false);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(null);
            var prodNameArray = request.responseText;
            prodNameArray = JSON.parse(prodNameArray);

            mostSoldChart.data.labels = prodNameArray;

            document.getElementById('mostsolditemchartDiv').style.display = 'block'
            document.getElementById('mostsolditemNDFDiv').style.display = 'none'

            mostSoldChart.update();

        } else {
            document.getElementById('mostsolditemchartDiv').style.display = 'none'
            document.getElementById('mostsolditemNDFDiv').style.display = 'block'
        }
    }



    function soldItemsChkDate() {
        var mostSolddatePicker = document.getElementById('mostSoldDateInput').value;

        mostSoldDtPkrUrl = `<?php echo URL ?>ajax/components-most-sold-items.ajax.php?mostSoldByDt=${mostSolddatePicker}`;
        request.open("GET", mostSoldDtPkrUrl, false);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(null);
        var mostSoldDataByDate = request.responseText;

        updateMostSoldData(JSON.parse(mostSoldDataByDate));

        document.getElementById('soldDtPickerDiv').style.display = 'none';
        document.getElementById('soldDtRngPickerDiv').style.display = 'none';
    }


    function soldItemsChkDateRng() {
        var mostSoldStarDate = document.getElementById('mostSoldStarDate').value;
        var mostSoldEndDate = document.getElementById('mostSoldEndDate').value;

        mostSoldDtPkrUrl = `<?php echo URL ?>ajax/components-most-sold-items.ajax.php?mostSoldStarDate=${mostSoldStarDate}&mostSoldEndDate=${mostSoldEndDate}`;
        request.open("GET", mostSoldDtPkrUrl, false);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(null);

        var mostSoldDataByDate = request.responseText;

        updateMostSoldData(JSON.parse(mostSoldDataByDate));

        document.getElementById('soldDtPickerDiv').style.display = 'none';
        document.getElementById('soldDtRngPickerDiv').style.display = 'none';
    }


    /// ================== filter area =====================

    function dataFilter(id) {

        console.log(id);
        var currentFilterVal = document.getElementById("current-filter-val");
        var dataSort = document.getElementById("data-sort").innerHTML;

        // Update data sort value based on id
        if (id.id === 'asc' || id.id === 'dsc') {
            dataSort.innerHTML = id.id;
        }

        if (id == '[object HTMLButtonElement]') {
            currentFilterVal.innerHTML = document.getElementById("current-filter-val").innerHTML;
        } else {
            currentFilterVal.innerHTML = id;
        }


        if (document.getElementById("data-sort").innerHTML == 'asc') {
            console.log(document.getElementById("data-sort").innerHTML);
            document.getElementById('most-sold-header').style.display = 'block';
            document.getElementById('less-sold-header').style.display = 'none';

            if (document.getElementById("current-filter-val").innerHTML == 'allData') {
                document.getElementById('secondary-filter').innerHTML = id;
                document.getElementById('soldDtPickerDiv').style.display = 'none';
                document.getElementById('soldDtRngPickerDiv').style.display = 'none';
                updateMostSoldData(<?php echo json_encode($mostSoldItemsFromStart); ?>);
            }

            if (document.getElementById("current-filter-val").innerHTML == 'soldLst24hrs') {
                document.getElementById('secondary-filter').innerHTML = id;
                document.getElementById('soldDtPickerDiv').style.display = 'none';
                document.getElementById('soldDtRngPickerDiv').style.display = 'none';
                updateMostSoldData(<?php echo json_encode($dailyMostSoldItems); ?>);
            }

            if (document.getElementById("current-filter-val").innerHTML == 'soldLst7') {
                document.getElementById('soldDtPickerDiv').style.display = 'none';
                document.getElementById('soldDtRngPickerDiv').style.display = 'none';
                updateMostSoldData(<?php echo json_encode($weeklyMostSoldItems); ?>);
            }

            if (document.getElementById("current-filter-val").innerHTML == 'soldLst30') {
                document.getElementById('soldDtPickerDiv').style.display = 'none';
                document.getElementById('soldDtRngPickerDiv').style.display = 'none';
                updateMostSoldData(<?php echo json_encode($monthlyMostSoldItems); ?>);
            }

            if (document.getElementById("current-filter-val").innerHTML == 'soldOnDt') {
                document.getElementById('soldDtPickerDiv').style.display = 'block';
                document.getElementById('soldDtRngPickerDiv').style.display = 'none';
            }

            if (document.getElementById("current-filter-val").innerHTML == 'soldOnDtRng') {
                document.getElementById('soldDtPickerDiv').style.display = 'none';
                document.getElementById('soldDtRngPickerDiv').style.display = 'block';
            }

        } else if (document.getElementById("data-sort").innerHTML == 'dsc') {
            console.log(document.getElementById("data-sort").innerHTML);
            document.getElementById('most-sold-header').style.display = 'none';
            document.getElementById('less-sold-header').style.display = 'block';

            if (document.getElementById("current-filter-val").innerHTML == 'allData') {
                document.getElementById('soldDtPickerDiv').style.display = 'none';
                document.getElementById('soldDtRngPickerDiv').style.display = 'none';
                updateMostSoldData(<?php echo json_encode($lessSoldItemsFromStart); ?>);
            }

            if (document.getElementById("current-filter-val").innerHTML == 'soldLst24hrs') {
                document.getElementById('soldDtPickerDiv').style.display = 'none';
                document.getElementById('soldDtRngPickerDiv').style.display = 'none';
                updateMostSoldData(<?php echo json_encode($dailyLessSoldItems); ?>);
            }


            if (document.getElementById("current-filter-val").innerHTML == 'soldLst7') {
                document.getElementById('soldDtPickerDiv').style.display = 'none';
                document.getElementById('soldDtRngPickerDiv').style.display = 'none';
                updateMostSoldData(<?php echo json_encode($weeklyLessSoldItems); ?>);
            }

            if (document.getElementById("current-filter-val").innerHTML == 'soldLst30') {
                document.getElementById('soldDtPickerDiv').style.display = 'none';
                document.getElementById('soldDtRngPickerDiv').style.display = 'none';
                updateMostSoldData(<?php echo json_encode($monthlyLessSoldItems); ?>);
            }

            if (document.getElementById("current-filter-val").innerHTML == 'soldOnDt') {
                document.getElementById('soldDtPickerDiv').style.display = 'block';
                document.getElementById('soldDtRngPickerDiv').style.display = 'none';
            }

            if (document.getElementById("current-filter-val").innerHTML == 'soldOnDtRng') {
                document.getElementById('soldDtPickerDiv').style.display = 'none';
                document.getElementById('soldDtRngPickerDiv').style.display = 'block';
            }
        }
    }





    // ========= most sold item primary data area ============= \\

    var mostSoldDataFromStart = <?php echo json_encode($mostSoldItemsFromStart); ?>;

    if (mostSoldDataFromStart != null) {

        var productIds = mostSoldDataFromStart.map(item => item.product_id);
        productIds = JSON.stringify(productIds);
        var dataToSend = `mostSoldProdId=${productIds}`;

        mostSoldProdNameUrl = `<?php echo URL ?>ajax/components-most-sold-items.ajax.php?mostSoldProdId=${productIds}`;
        request.open("GET", mostSoldProdNameUrl, false);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(null);
        var prodNameArray = request.responseText;

        prodNameArray = JSON.parse(prodNameArray);

        var totalSold = mostSoldDataFromStart.map(item => item.total_sold);

        document.getElementById('mostsolditemchartDiv').style.display = 'block'
        document.getElementById('mostsolditemNDFDiv').style.display = 'none'
    } else {
        document.getElementById('mostsolditemchartDiv').style.display = 'none'
        document.getElementById('mostsolditemNDFDiv').style.display = 'block'
    }


    // =============  most sold item bar chart area =============
    var soldCharCtx = document.getElementById('mostsolditemchart').getContext('2d');
    var mostSoldChart = new Chart(soldCharCtx, {
        type: 'bar',
        data: {
            labels: prodNameArray,
            datasets: [{
                label: 'Total Sold',
                data: totalSold,
                backgroundColor: 'rgb(179, 217, 255)',
                borderWidth: 0,
                barThickness: 10,
                maxBarThickness: 10,
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