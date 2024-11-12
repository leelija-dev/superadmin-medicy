<?php

$mostVistedCustomerFromStart = $StockOut->mostVistedCustomerFrmStart($adminId);

$dailyMostVistiCustomerData = $StockOut->mostVisitCustomersByDay($adminId);

$weeklyMostVistiCustomerData = $StockOut->mostVisitCustomersByWeek($adminId);

$monthlyMostVistiCustomerData = $StockOut->mostVisitCustomersByMonth($adminId);

//========================================================================================

$highestPurchaseCustomerAllTime = $StockOut->overallMostPurchaseCustomer($adminId);

$highestPurchaseCustomerByDay = $StockOut->mostPurchaseCustomerByDay($adminId);

$highestPurchaseCustomerByWeek = $StockOut->mostPurchaseCustomerByWeek($adminId);

$highestPurchaseCustomerByMonth = $StockOut->mostPurchaseCustomerByMonth($adminId);
// print_r($mostVistedCustomerFromStart);exit;
?>

<div class="card border-left-primary shadow-sm h-100 py-2 pending_border animated--grow-in">
    <div class="row mt-1">
        <div class="col-md-12">
            <div class="container-fluid box-top" >
                <ul class=" col-11 col-lg-11 col-md-12 nav nav-tabs border-bottom-0" style="size: small;">
                    <li class="nav-item" style="font-size: samll;">
                        <button id="mostVisitedLink" class="nav-link" onclick="changeTab('mostVisited')"
                            style="color: blue; font-size: small; background-color: white;">Most Visited</button>
                    </li>
                    <li class="nav-item">
                        <button id="highestPurchasedLink" class="nav-link" onclick="changeTab('highestPurchased')"
                            style="font-size: small; background-color: white; border-bottom: 1px;">Highest
                            Perchased</button>
                    </li>
                </ul>

                <label id='customer-sort' class="d-none" value='mostVisited'>mostVisited</label>
                <lebel class="d-none" id="chart-label">Visit Count</lebel>

                <div class="col-md-1 btn-group " style="position:relative; height:30px ">
            <button type="button" class="btn btn-sm btn-outline-primary card-btn dropdown font-weight-bold "
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <i class="fas fa-filter"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right border border-secondary">
                <button class="dropdown-item" type="button" id="mostVisitCustomerLst24hrs"
                    onclick="mostvisitCustomer(this.id)">Last 24 hrs</button>
                <button class="dropdown-item" type="button" id="mostVisitCustomerLst7"
                    onclick="mostvisitCustomer(this.id)">Last 7 Days</button>
                <button class="dropdown-item" type="button" id="mostVisitCustomerLst30"
                    onclick="mostvisitCustomer(this.id)">Last 30 DAYS</button>
                <button class="dropdown-item dropdown" type="button" id="mostVisitCustomerOnDt"
                    onclick="mostvisitCustomer(this.id)">By Date</button>
                <button class="dropdown-item dropdown" type="button" id="mostVisitCustomerDtRng"
                    onclick="mostvisitCustomer(this.id)">By Range</button>
            </div>

            <lebel class="d-none" id="customer-purchse-filter-val">allData</lebel>
        </div>
            </div>
        </div>
        <div class="col-md-2 d-flex justify-content-end px-2">
            <div class="dropdown-menu dropdown-menu-right shadow-lg p-3 mt-2" id="mostVistedCustomerDtPkr"
                style="display: none; margin-right:-26rem">
                <div class="d-flex align-items-center p-2">
                    <input type="date" id="mostVisiteCustomerDt">
                    <button class="btn btn-sm btn-primary ml-2" onclick="mostVistedCustomerByDt()"
                        style="height: 2rem;">Find</button>
                </div>
                <i class="fas fa-times text-danger position-absolute mr-1" style="cursor: pointer; top:5px; right:5px"
                    onclick="closePicker('mostVistedCustomerDtPkr')"></i>

            </div>
            <div class="dropdown-menu dropdown-menu-right shadow-lg p-3 mt-2" id="mostVistedCustomerDtPkrRng"
                style="display: none; margin-right:-26rem">
                <div class="d-flex align-items-center">
                    <div class="dtPicker" style="margin-right: 1rem;">
                        <label>Start Date</label>
                        <input type="date" id="mostVisiteCustomerStartDate">
                    </div>
                    <div class="dtPicker" style="margin-right: 1rem;">
                        <label>End Date</label>
                        <input type="date" id="mostVisiteCustomerEndDate">
                    </div>
                    <button class="btn btn-sm btn-primary mt-4" onclick="mostVistedCustomerDateRange()"
                        style="height: 2rem;">Find</button>
                </div>
                <i class="fas fa-times text-danger position-absolute mr-1 mt-1" style="cursor: pointer; top:5px; right:5px"
                    onclick="closePicker('mostVistedCustomerDtPkrRng')"></i>
            </div>
        </div>
       
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <div class="card-body mt-n2 pb-0">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div id="mostVisitCustomerCharDiv">
                        <canvas id="mostVisitCustomerChart"></canvas>
                    </div>
                    <div style="width: 100%; margin: 0 auto; display:none" id="most-visited-no-data-found-div">
                        <p class="text-gray-500">Oops!, the requested data isn't in our records.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <script src="../../../medicy.in/admin/vendor/chartjs-4.4.0/updatedChart.js"></script> -->


<script>
    setMaxDateToToday('mostVisiteCustomerDt');
    setMaxDateToToday('mostVisiteCustomerStartDate');
    setMaxDateToToday('mostVisiteCustomerEndDate');


    function changeTab(tab) {

    const ids = ['mostVisitedLink', 'highestPurchasedLink'];

    ids.forEach(id => {
        document.getElementById(id).classList.remove('active');
        document.getElementById(id).style.color = 'black';
        document.getElementById(id).style.border = 'none';
        document.getElementById(id).style.borderBottom = '1px solid #e6e6e6';
    });

    document.getElementById(tab + 'Link').classList.add('active');
    document.getElementById(tab + 'Link').style.color = 'blue';
    document.getElementById(tab + 'Link').style.borderBottom = '1px solid white';
    document.getElementById(tab + 'Link').style.borderTop = '1px solid #e6e6e6';
    document.getElementById(tab + 'Link').style.borderLeft = '1px solid #e6e6e6';
    document.getElementById(tab + 'Link').style.borderRight = '1px solid #e6e6e6';

    if (tab === 'mostVisited') {

        document.getElementById('chart-label').innerHTML = 'Visit Count';

        document.getElementById('customer-sort').innerHTML = tab;

        mostvisitCustomer(document.getElementById('customer-purchse-filter-val').innerHTML);

    } else if (tab === 'highestPurchased') {

        document.getElementById('customer-sort').innerHTML = tab;

        document.getElementById('chart-label').innerHTML = 'Purchase Amount';

        mostvisitCustomer(document.getElementById('customer-purchse-filter-val').innerHTML);

    }
}




// =========== most visit customer chart override function body ==========
function mostVisitCustomerDataFunction(mostVisitCustomerData, flag) {

    if (mostVisitCustomerData != null) {

        if (flag == 0) {
            mostVistedCustomerChart.data.datasets[0].data = mostVisitCustomerData.map(item => item.visit_count);
        } else {
            mostVistedCustomerChart.data.datasets[0].data = mostVisitCustomerData.map(item => item.total_purchase);
        }

        var customerId = mostVisitCustomerData.map(item => item.customer_id);
        customerId = JSON.stringify(customerId);

        mostVisitedCustomerDataUrl =
            `<?php echo URL ?>ajax/most-visit-and-purchase-customer.ajax.php?customerId=${customerId}`;
        request.open("GET", mostVisitedCustomerDataUrl, false);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(null);
        var mostVistiCustomerNameArray = request.responseText;

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

    if (document.getElementById('customer-sort').innerHTML == 'mostVisited') {
        var customerFilterByDate = 'mostVstCstmrByDt';
        var flag = 0;
    } else if (document.getElementById('customer-sort').innerHTML == 'highestPurchased') {
        var flag = 1;
        var customerFilterByDate = 'mostPrchsCstmrByDt';
    }

    mostVstCstmrDtUrl =
        `<?php echo URL ?>ajax/most-visit-and-purchase-customer.ajax.php?${customerFilterByDate}=${mostVistedCustomerDt}`;
    request.open("GET", mostVstCstmrDtUrl, false);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(null);

    mostVisitCustomerDataFunction(JSON.parse(request.responseText), flag);

    document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
    document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
}


// ============= most visit customer by date range function body ==============
function mostVistedCustomerDateRange() {
    var purchaseVisitStartDt = document.getElementById('mostVisiteCustomerStartDate').value;
    var purchaseVisitEndDt = document.getElementById('mostVisiteCustomerEndDate').value;

    if (document.getElementById('customer-sort').innerHTML == 'mostVisited') {
        var flag = 0;
        var customFilterByStartDt = 'mostVisitStartDt';
        var customFilterByEndDt = 'mostVisitEndDt';

    } else if (document.getElementById('customer-sort').innerHTML == 'highestPurchased') {
        var flag = 1;
        var customFilterByStartDt = 'mostPurchaseStartDt';
        var customFilterByEndDt = 'mostPurchaseEndDt';
    }

    mostVstCstmrDtRngUrl =
        `<?php echo URL ?>ajax/most-visit-and-purchase-customer.ajax.php?${customFilterByStartDt}=${purchaseVisitStartDt}&${customFilterByEndDt}=${purchaseVisitEndDt}`;
    request.open("GET", mostVstCstmrDtRngUrl, false);
    request.send(null);

    mostVisitCustomerDataFunction(JSON.parse(request.responseText), flag);

    document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
    document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
}


// ============ button onclick function call area ===============
const mostvisitCustomer = (id) => {
    let filter1 = document.getElementById('customer-sort');
    document.getElementById('customer-purchse-filter-val').innerHTML = filter2 = id;

    if (filter1.innerHTML == 'mostVisited') {
        var flag = 0;

        if (filter2 == 'allData') {
            document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
            document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
            mostVisitCustomerDataFunction(<?php echo json_encode($mostVistedCustomerFromStart); ?>, flag);
        }

        if (filter2 == 'mostVisitCustomerLst24hrs') {
            document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
            document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
            mostVisitCustomerDataFunction(<?php echo json_encode($dailyMostVistiCustomerData); ?>, flag);
        }

        if (filter2 == 'mostVisitCustomerLst7') {
            document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
            document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
            mostVisitCustomerDataFunction(<?php echo json_encode($weeklyMostVistiCustomerData); ?>, flag);
        }

        if (filter2 == 'mostVisitCustomerLst30') {
            document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
            document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
            mostVisitCustomerDataFunction(<?php echo json_encode($monthlyMostVistiCustomerData); ?>, flag);
        }

        if (id == 'mostVisitCustomerOnDt') {
            document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
            document.getElementById('mostVistedCustomerDtPkr').style.display = 'block';

        }

        if (filter2 == 'mostVisitCustomerDtRng') {
            document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
            document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'block';
        }
    }


    if (filter1.innerHTML == 'highestPurchased') {
        var flag = 1;

        if (filter2 == 'allData') {
            document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
            document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
            mostVisitCustomerDataFunction(<?php echo json_encode($highestPurchaseCustomerAllTime); ?>, flag);
        }

        if (filter2 == 'mostVisitCustomerLst24hrs') {
            document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
            document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
            mostVisitCustomerDataFunction(<?php echo json_encode($highestPurchaseCustomerByDay); ?>, flag);
        }

        if (filter2 == 'mostVisitCustomerLst7') {
            document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
            document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
            mostVisitCustomerDataFunction(<?php echo json_encode($highestPurchaseCustomerByWeek); ?>, flag);
        }

        if (filter2 == 'mostVisitCustomerLst30') {
            document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
            document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
            mostVisitCustomerDataFunction(<?php echo json_encode($highestPurchaseCustomerByMonth); ?>, flag);
        }

        if (id == 'mostVisitCustomerOnDt') {
            document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'none';
            document.getElementById('mostVistedCustomerDtPkr').style.display = 'block';

        }

        if (filter2 == 'mostVisitCustomerDtRng') {
            document.getElementById('mostVistedCustomerDtPkr').style.display = 'none';
            document.getElementById('mostVistedCustomerDtPkrRng').style.display = 'block';
        }
    }
}




// ============== primary chart data area ==============
var mostVstCutmrData = <?php echo json_encode($mostVistedCustomerFromStart); ?>;

if (mostVstCutmrData != null) {
    var customerId = mostVstCutmrData.map(item => item.customer_id);
    customerId = JSON.stringify(customerId);

    mostVisitedCustomerDataUrl =
        `<?php echo URL ?>ajax/most-visit-and-purchase-customer.ajax.php?customerId=${customerId}`;
    request.open("GET", mostVisitedCustomerDataUrl, false);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(null);
    var customerNameArray = request.responseText;

    customerNameArray = JSON.parse(customerNameArray);

    var totalVisit = mostVstCutmrData.map(item => item.visit_count);

} else {
    document.getElementById("mostVisitCustomerCharDiv").style.display = 'none';
    document.getElementById('most-visited-no-data-found-div').style.display = 'block';
}


// ========= chart control area ============= \\
var sticker = document.getElementById('chart-label').innerHTML;
var mstVstCstmrCtx = document.getElementById('mostVisitCustomerChart').getContext('2d');

var mostVistedCustomerChart = new Chart(mstVstCstmrCtx, {
    type: 'bar',
    data: {
        labels: customerNameArray,
        datasets: [{
            label: sticker,
            data: totalVisit,
            backgroundColor: 'rgb(179, 179, 255)',
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

changeTab('mostVisited');
</script>