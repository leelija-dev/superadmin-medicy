<?php
// require_once dirname(__DIR__) . '/config/constant.php';
$includePath = get_include_path();

$today = NOW;

$mostVistedCustomerFromStart = $StockOut->mostVistedCustomerFrmStart();

$dailyMostVistiCustomerData = $StockOut->mostVisitCustomersByDay();

$weeklyMostVistiCustomerData = $StockOut->mostVisitCustomersByWeek();

$monthlyMostVistiCustomerData = $StockOut->mostVisitCustomersByMonth();

// print_r($mostVistedCustomerFromStart);
?>

<div class="card border-left-primary shadow h-100 py-2 pending_border animated--grow-in">
    <div class="d-flex justify-content-between align-items-center">
        <div class="col ml-2 mt-3">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                most visited 10 customer</div>
        </div>
        <div class="d-flex justify-content-end px-2">
            <div class="dropdown-menu dropdown-menu-right p-3 mt-n5" id="mostVistedCustomerDtPkr" style="display: none; ">
                <input type="date" id="mostVisiteCustomerDt">
                <button class="btn btn-sm btn-primary" onclick="mostVistedCustomerByDt()" style="height: 2rem;">Find</button>
            </div>
            <div class="dropdown-menu dropdown-menu-right p-3 mt-n5" id="mostVistedCustomerDtPkrRng" style="display: none;">
                <label>Start Date</label>
                <input type="date" id="mostVisiteCustomerStartDate">
                <label>End Date</label>
                <input type="date" id="mostVisiteCustomerEndDate">
                <button class="btn btn-sm btn-primary" onclick="mostVistedCustomerDateRange()" style="height: 2rem;">Find</button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <b>...</b>
                </button>
                <div class="dropdown-menu dropdown-menu-right" style="background-color: rgba(255, 255, 255, 0.8);">
                    <button class="dropdown-item" type="button" id="mostVisitCustomerLst24hrs" onclick="mostvisitCustomer(this.id)">Last 24 hrs</button>
                    <button class="dropdown-item" type="button" id="mostVisitCustomerLst7" onclick="mostvisitCustomer(this.id)">Last 7 Days</button>
                    <button class="dropdown-item" type="button" id="mostVisitCustomerLst30" onclick="mostvisitCustomer(this.id)">Last 30 DAYS</button>
                    <button class="dropdown-item dropdown" type="button" id="mostVisitCustomerOnDt" onclick="mostvisitCustomer(this.id)">By Date</button>
                    <button class="dropdown-item dropdown" type="button" id="mostVisitCustomerDtRng" onclick="mostvisitCustomer(this.id)">By Range</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body mt-n2 pb-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div style="width: 100%; margin: 0 auto;" id="mostVisitCustomerCharDiv">
                    <canvas id="mostVisitCustomerChart"></canvas>
                </div>
                <div style="width: 100%; margin: 0 auto; display:none" id="most-visited-no-data-found-div">
                    <p class="text-warning">Oops!, the requested data isn't in our records.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <script src="../../../medicy.in/admin/vendor/chartjs-4.4.0/updatedChart.js"></script> -->


<script>
    // =========== most visit customer chart override function body ==========
    function mostVisitCustomerDataFunction(mostVisitCustomerData) {

        if (mostVisitCustomerData != null) {
            mostVistedCustomerChart.data.datasets[0].data = mostVisitCustomerData.map(item => item.visit_count);

            var customerId = mostVisitCustomerData.map(item => item.customer_id);
            customerId = JSON.stringify(customerId);

            mostVisitedCustomerDataUrl = `<?php echo URL ?>ajax/most-visit-and-purchase-customer.ajax.php?customerId=${customerId}`;
            xmlhttp.open("GET", mostVisitedCustomerDataUrl, false);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(null);
            var mostVistiCustomerNameArray = xmlhttp.responseText;

            mostVistiCustomerNameArray = JSON.parse(mostVistiCustomerNameArray);

            mostVistedCustomerChart.data.labels = mostVistiCustomerNameArray;

            document.getElementById("mostVisitCustomerCharDiv").style.display = 'block';
            document.getElementById('most-visited-no-data-found-div').style.display = 'none';

            mostVistedCustomerChart.update();

        } else {
            document.getElementById("mostVisitCustomerCharDiv").style.display = 'none';
            document.getElementById('most-visited-no-data-found-div').style.display = 'block';
        }

    }



    // ============= most visit customer by specific date function body ==============
    function mostVistedCustomerByDt() {
        var mostVistedCustomerDt = document.getElementById('mostVisiteCustomerDt').value;

        mostVstCstmrDtUrl = `<?php echo URL ?>ajax/most-visit-and-purchase-customer.ajax.php?mostVstCstmrByDt=${mostVistedCustomerDt}`;
        xmlhttp.open("GET", mostVstCstmrDtUrl, false);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(null);
        var mostVistiCustomerDataByDate = xmlhttp.responseText;
        console.log(mostVistiCustomerDataByDate);
        // mostVisitCustomerDataFunction(JSON.parse(mostVistiCustomerDataByDate));

        document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
        document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
    }


    // ============= most visit customer by date range function body ==============
    function mostVistedCustomerDateRange() {
        var mostVistedCustomerStartDt = document.getElementById('mostVisiteCustomerStartDate').value;
        var mostVistedCustomerEndtDt = document.getElementById('mostVisiteCustomerEndDate').value;

        mostVstCstmrDtRngUrl = `<?php echo URL ?>ajax/most-visit-and-purchase-customer.ajax.php?mostVisitStartDt=${mostVistedCustomerStartDt}&mostVisitEndDt=${mostVistedCustomerEndtDt}`;
        xmlhttp.open("GET", mostVstCstmrDtRngUrl, false);
        xmlhttp.send(null);

        var mostVistiCustomerDataByDateRange = xmlhttp.responseText;

        mostVisitCustomerDataFunction(JSON.parse(mostVistiCustomerDataByDateRange));


        document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
        document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
    }


    // ============ button onclick function call area ===============
    const mostvisitCustomer = (id) => {
        if (id == 'mostVisitCustomerLst24hrs') {
            document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
            document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
            console.log(<?php echo json_encode($dailyMostVistiCustomerData); ?>);
            mostVisitCustomerDataFunction(<?php echo json_encode($dailyMostVistiCustomerData); ?>);
        }

        if (id == 'mostVisitCustomerLst7') {
            document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
            document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
            mostVisitCustomerDataFunction(<?php echo json_encode($weeklyMostVistiCustomerData); ?>);
        }

        if (id == 'mostVisitCustomerLst30') {
            document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
            document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
            mostVisitCustomerDataFunction(<?php echo json_encode($monthlyMostVistiCustomerData); ?>);
        }

        if (id == 'mostVisitCustomerOnDt') {
            // document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
            document.getElementById('mostVistedCustomerDtPkr').style.display = 'block';

        }

        if (id == 'mostVisitCustomerDtRng') {
            // document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
            document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'block';
        }
    }




    // ============== primary chart data area ==============
    let mostVstCutmrData = <?php echo json_encode($mostVistedCustomerFromStart); ?>;

    if (mostVstCutmrData != null) {
        var customerId = mostVstCutmrData.map(item => item.customer_id);
        customerId = JSON.stringify(customerId);

        mostVisitedCustomerDataUrl = `<?php echo URL ?>ajax/most-visit-and-purchase-customer.ajax.php?customerId=${customerId}`;
        xmlhttp.open("GET", mostVisitedCustomerDataUrl, false);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(null);
        var customerNameArray = xmlhttp.responseText;

        customerNameArray = JSON.parse(customerNameArray);

        var totalVisit = mostVstCutmrData.map(item => item.visit_count);

    } else {
        document.getElementById("mostVisitCustomerCharDiv").style.display = 'none';
        document.getElementById('most-visited-no-data-found-div').style.display = 'block';
    }


    // ========= chart control area ============= \\
    var mstVstCstmrCtx = document.getElementById('mostVisitCustomerChart').getContext('2d');
    var mostVistedCustomerChart = new Chart(mstVstCstmrCtx, {
        type: 'bar',
        data: {
            labels: customerNameArray,
            datasets: [{
                label: 'Visit Count',
                data: totalVisit,
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