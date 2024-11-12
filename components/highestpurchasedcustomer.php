<?php
// require_once dirname(__DIR__) . '/config/constant.php';
$includePath = get_include_path();

$today = NOW;

$highestPurchaseCustomerAllTime = $StockOut->overallMostPurchaseCustomer();

$highestPurchaseCustomerByDay = $StockOut->mostPurchaseCustomerByDay();

$highestPurchaseCustomerByWeek = $StockOut->mostPurchaseCustomerByWeek();

$highestPurchaseCustomerByMonth = $StockOut->mostPurchaseCustomerByMonth();

// print_r($highestPurchaseCustomerByMonth);

?>

<div class="card border-left-primary shadow h-100 py-2 pending_border animated--grow-in">
    <div class="d-flex justify-content-between align-items-center">
        <div class="col ml-2 mt-3">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                highest purchse 10 customer</div>
        </div>
        <div class="d-flex justify-content-end px-2">
            <div class="dropdown-menu dropdown-menu-right p-3 mt-n5" id="mostPurchaseCustomerDtPkr" style="display: none;">
                <input type="date" id="mostPurchseCustomerDt">
                <button class="btn btn-sm btn-primary" onclick="mostPurchaseCustomerByDt()" style="height: 2rem;">Find</button>
            </div>
            <div class="dropdown-menu dropdown-menu-right p-3 mt-n5" id="mostPurchseCustomerDtPkrRng" style="display: none;">
                <label>Start Date</label>
                <input type="date" id="mostPurchseCustomerStartDate">
                <label>End Date</label>
                <input type="date" id="mostPurchseCustomerEndDate">
                <button class="btn btn-sm btn-primary" onclick="mostPurchaseCustomerDateRange()" style="height: 2rem;">Find</button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <b>...</b>
                </button>
                <div class="dropdown-menu dropdown-menu-right" style="background-color: rgba(255, 255, 255, 0.8);">
                    <button class="dropdown-item" type="button" id="maxPurchaseCustomerLst24hrs" onclick="maxPurchaseCustomer(this.id)">Last 24 hrs</button>
                    <button class="dropdown-item" type="button" id="maxPurchaseCustomerLst7" onclick="maxPurchaseCustomer(this.id)">Last 7 Days</button>
                    <button class="dropdown-item" type="button" id="maxPurchaseCustomerLst30" onclick="maxPurchaseCustomer(this.id)">Last 30 DAYS</button>
                    <button class="dropdown-item dropdown" type="button" id="maxPurchaseCustomerByDt" onclick="maxPurchaseCustomer(this.id)">By Date</button>
                    <button class="dropdown-item dropdown" type="button" id="maxPurchaseCustomerByDtRng" onclick="maxPurchaseCustomer(this.id)">By Range</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body mt-n2 pb-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div style="width: 100%; margin: 0 auto;" id="highestPurchaseCustomerChartDiv">
                    <canvas id="highestPurchaseCustomerChart"></canvas>
                </div>
                <div style="width: 100%; margin: 0 auto; display:none" id="most-purchase-no-data-found-div">
                <p class="text-warning">Oops!, the requested data isn't in our records.</p>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // =========== most purchase customer chart override function body ==========
    function mostPurchaseCustomerDataFunction(mostPurchaseCustomerData) {

        if (mostPurchaseCustomerData != null) {
            highestPurchaseCustomerBarChart.data.datasets[0].data = mostPurchaseCustomerData.map(item => item.total_purchase);

            var customerId = mostPurchaseCustomerData.map(item => item.customer_id);
            customerId = JSON.stringify(customerId);

            var mostPurchaseCustomerDataUrl = `<?php echo URL ?>ajax/most-visit-and-purchase-customer.ajax.php?customerId=${customerId}`;
            xmlhttp.open("GET", mostPurchaseCustomerDataUrl, false);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(null);
            var mostPurchaseCustomerNameArray = xmlhttp.responseText;

            mostPurchaseCustomerNameArray = JSON.parse(mostPurchaseCustomerNameArray);

            highestPurchaseCustomerBarChart.data.labels = mostPurchaseCustomerNameArray;

            document.getElementById("highestPurchaseCustomerChartDiv").style.display = 'block';
            document.getElementById('most-purchase-no-data-found-div').style.display = 'none';

            highestPurchaseCustomerBarChart.update();

        } else {
            document.getElementById("highestPurchaseCustomerChartDiv").style.display = 'none';
            document.getElementById('most-purchase-no-data-found-div').style.display = 'block';
        }

    }



    // ============= most purchase customer by specific date function body ==============
    function mostPurchaseCustomerByDt() {

        var mostPurchaseCustomerDtPick = document.getElementById('mostPurchseCustomerDt').value;

        var mostPrchsCstmrDtUrl = `<?php echo URL ?>ajax/most-visit-and-purchase-customer.ajax.php?mostPrchsCstmrByDt=${mostPurchaseCustomerDtPick}`;
        xmlhttp.open("GET", mostPrchsCstmrDtUrl, false);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(null);
        var mostPurchaseCustomerDataByDate = xmlhttp.responseText;

        mostPurchaseCustomerDataFunction(JSON.parse(mostPurchaseCustomerDataByDate));

        document.getElementById('mostPurchaseCustomerDtPkr').style.display = 'none';
    }



    // ============= most purchase customer by date range function body ==============
    function mostPurchaseCustomerDateRange() {
        var mostPurchaseCustomerStartDt = document.getElementById('mostPurchseCustomerStartDate').value;
        var mostPurchaseCustomerEndtDt = document.getElementById('mostPurchseCustomerEndDate').value;

        var mostPrchsCstmrDtRngUrl = `<?php echo URL ?>ajax/most-visit-and-purchase-customer.ajax.php?mostPurchaseStartDt=${mostPurchaseCustomerStartDt}&mostPurchaseEndDt=${mostPurchaseCustomerEndtDt}`;
        xmlhttp.open("GET", mostPrchsCstmrDtRngUrl, false);
        xmlhttp.send(null);

        var mostPurchaseCustomerDataByDateRange = xmlhttp.responseText;

        mostPurchaseCustomerDataFunction(JSON.parse(mostPurchaseCustomerDataByDateRange));

        document.getElementById('mostPurchseCustomerDtPkrRng').style.display = 'none';
    }


    // ============ button onclick function call area ===============
    const maxPurchaseCustomer = (id) => {
        if (id == 'maxPurchaseCustomerLst24hrs') {
            document.getElementById('mostPurchaseCustomerDtPkr').style.display = 'none';
            document.getElementById('mostPurchseCustomerDtPkrRng').style.display = 'none';
            mostPurchaseCustomerDataFunction(<?php echo json_encode($highestPurchaseCustomerByDay); ?>);
        }

        if (id == 'maxPurchaseCustomerLst7') {
            document.getElementById('mostPurchaseCustomerDtPkr').style.display = 'none';
            document.getElementById('mostPurchseCustomerDtPkrRng').style.display = 'none';
            mostPurchaseCustomerDataFunction(<?php echo json_encode($highestPurchaseCustomerByWeek); ?>);
        }

        if (id == 'maxPurchaseCustomerLst30') {
            document.getElementById('mostPurchaseCustomerDtPkr').style.display = 'none';
            document.getElementById('mostPurchseCustomerDtPkrRng').style.display = 'none';
            mostPurchaseCustomerDataFunction(<?php echo json_encode($highestPurchaseCustomerByMonth); ?>);
        }

        if (id == 'maxPurchaseCustomerByDt') {
            document.getElementById('mostPurchaseCustomerDtPkr').style.display = 'block';
            document.getElementById('mostPurchseCustomerDtPkrRng').style.display = 'none';

        }

        if (id == 'maxPurchaseCustomerByDtRng') {
            document.getElementById('mostPurchaseCustomerDtPkr').style.display = 'none';
            document.getElementById('mostPurchseCustomerDtPkrRng').style.display = 'block';
        }
    }



    // ============== primary chart data area ==============
    let highestPurchaseCustomerFromStart = <?php echo json_encode($highestPurchaseCustomerAllTime); ?>;

    if (highestPurchaseCustomerFromStart != null) {
        var customerId = highestPurchaseCustomerFromStart.map(item => item.customer_id);
        customerId = JSON.stringify(customerId);

        highestPurchaseCustomerUrl = `<?php echo URL ?>ajax/most-visit-and-purchase-customer.ajax.php?customerId=${customerId}`;
        xmlhttp.open("GET", highestPurchaseCustomerUrl, false);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(null);
        var customerNameArray = xmlhttp.responseText;

        customerNameArray = JSON.parse(customerNameArray);

        var purchaseAmount = highestPurchaseCustomerFromStart.map(item => item.total_purchase);

    } else {
        document.getElementById("highestPurchaseCustomerChartDiv").style.display = 'none';
        document.getElementById('most-purchase-no-data-found-div').style.display = 'block';
    }


    // ========= chart control area ============= \\
    var highestPurchaseCustomerCtx = document.getElementById('highestPurchaseCustomerChart').getContext('2d');
    var highestPurchaseCustomerBarChart = new Chart(highestPurchaseCustomerCtx, {
        type: 'bar',
        data: {
            labels: customerNameArray,
            datasets: [{
                label: 'Purchase Amount',
                data: purchaseAmount,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
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