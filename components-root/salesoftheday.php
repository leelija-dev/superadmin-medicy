<?php

require_once dirname(__DIR__) . '/config/constant.php';
// require_once ROOT_DIR . '_config/sessionCheck.php';
// require_once ROOT_DIR.'_config/user-details.inc.php';

$includePath = get_include_path();

$strtDt = date('Y-m-d');
$lst24hrs = date('Y-m-d', strtotime($strtDt . ' - 1 days'));
$lst7 = date('Y-m-d', strtotime($strtDt . ' - 7 days'));
$lst30 = date('Y-m-d', strtotime($strtDt . ' - 30 days'));

$salesOfTheDayToday = $StockOut->salesOfTheDayRange($strtDt, $strtDt, $adminId);

$sodLst24Hrs = $StockOut->salesOfTheDayRange($lst24hrs, $strtDt, $adminId);

$sodLst7Days = $StockOut->salesOfTheDayRange($lst7, $strtDt, $adminId);

$sodLst30Days = $StockOut->salesOfTheDayRange($lst30, $strtDt, $adminId);


?>

<div class="card border-left-info shadow border-right-info h-100 py-2 pending_border animated--grow-in">
    <div class="px-3 mt-2">
        <div class="row d-flex">
            <div class="col-md-6">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    sales of the day</div>
            </div>
            <div class="col-md-4 d-flex justify-content-end">
                <div class="dropdown-menu dropdown-menu-right p-2 mt-n5" id="sodDatePikDiv" style="display: none; margin-right:1rem;">
                    <input type="date" id="salesOfTheDayDate">
                    <button class="btn btn-sm btn-primary" onclick="sodOnDate()" style="height: 2rem;">Find</button>
                </div>
                <div class="dropdown-menu dropdown-menu-right p-2 mt-n5" id="sodDtPikRngDiv" style="display: none; margin-right:1rem;">
                    <div class="d-flex d-flex justify-content-start">
                        <div>
                            <label>Start Date</label>&nbsp<input type="date" id="sodStartDt"><br>
                            <label>End Date</label>&nbsp&nbsp&nbsp<input type="date" id="sodEndDt">
                        </div>&nbsp
                        <div>
                            <br>
                            <button class="btn btn-sm btn-primary" onclick="sodDtRange()" style="height: 2rem;">Find</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 d-flex justify-content-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-primary card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fas fa-filter"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <button class="dropdown-item" type="button" id="sodCurrentDt" onclick="chkSod(this.id)">Today</button>
                        <button class="dropdown-item" type="button" id="sodLst24hrs" onclick="chkSod(this.id)">Last 24 hrs</button>
                        <button class="dropdown-item" type="button" id="sodLst7" onclick="chkSod(this.id)">Last 7 Days</button>
                        <button class="dropdown-item" type="button" id="sodLst30" onclick="chkSod(this.id)">Last 30 Days</button>
                        <button class="dropdown-item dropdown" type="button" id="sodGvnDt" onclick="chkSod(this.id)">By Date</button>
                        <button class="dropdown-item dropdown" type="button" id="sodDtRng" onclick="chkSod(this.id)">By Date Range</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card-body py-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2" id='sod-Display'>
                <div class="mb-0 font-weight-bold text-gray-800">
                    <span type="symble" id="rupeeSymble" name="rupeeSymble">₹</span>
                    <span type="number" id="salesAmount" name="salesAmount"></span>
                </div>
                <div>
                    <span type="text" id="itemsCount" name="itemsCount"><small></small></span>
                    <span type="text"><small>Items</small></span>
                </div>
            </div>
            <div class="col-auto mt-n2 " style="display: none;" id='sod-no-data'>
                <p class="text-secondary">Nothing sold yet.</p>
            </div>
        </div>
    </div>

</div>

<script>
    function updateSod(uploadSodData) {

        if (uploadSodData.total_amount != null && uploadSodData.total_count != null) {
            document.getElementById('salesAmount').innerHTML = uploadSodData.total_amount;
            document.getElementById('itemsCount').innerHTML = uploadSodData.total_count;

            document.getElementById('sod-Display').style.display = 'block';
            document.getElementById('sod-no-data').style.display = 'none';

        } else {
            console.log('null');
            document.getElementById('sod-Display').style.display = 'none';
            document.getElementById('sod-no-data').style.display = 'block';
        }
    }




    // === fixed sod date select from calander ...
    function sodOnDate() {
        let sodDateSelect = document.getElementById('salesOfTheDayDate').value;

        // var xmlhttp = new XMLHttpRequest();
        var sodOnDateUrl = `<?php echo URL ?>ajax/sod-pod-data-search.ajax.php?sodONDate=${sodDateSelect}`;
        xmlhttp.open('GET', sodOnDateUrl, false);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(null);

        updateSod(JSON.parse(xmlhttp.responseText));

        // Hide the date picker divs after clicking "Find"
        document.getElementById('sodDatePikDiv').style.display = 'none';
        document.getElementById('sodDtPikRngDiv').style.display = 'none';
    }





    // === sod date range select from calander ...
    function sodDtRange() {
        let sodStartDate = document.getElementById('sodStartDt').value;
        let sodEndDate = document.getElementById('sodEndDt').value;

        // var xmlhttp = new XMLHttpRequest();
        var sodOnDateRangeUrl = `<?php echo URL ?>ajax/sod-pod-data-search.ajax.php?sodStartDate=${sodStartDate}&sodEndDate=${sodEndDate}`;
        xmlhttp.open('GET', sodOnDateRangeUrl, false);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(null);

        updateSod(JSON.parse(xmlhttp.responseText));

        // Hide the date picker divs after clicking "Find"
        document.getElementById('sodDatePikDiv').style.display = 'none';
        document.getElementById('sodDtPikRngDiv').style.display = 'none';
    }






    /// ===== button on click function for sod date select 
    function chkSod(id) {

        if (id == 'sodCurrentDt') {
            document.getElementById('sodDatePikDiv').style.display = 'none';
            document.getElementById('sodDtPikRngDiv').style.display = 'none';
            updateSod(<?php echo json_encode($salesOfTheDayToday) ?>);

        }

        if (id == 'sodLst24hrs') {
            document.getElementById('sodDatePikDiv').style.display = 'none';
            document.getElementById('sodDtPikRngDiv').style.display = 'none';
            updateSod(<?php echo json_encode($sodLst24Hrs) ?>);
        }

        if (id == 'sodLst7') {
            document.getElementById('sodDatePikDiv').style.display = 'none';
            document.getElementById('sodDtPikRngDiv').style.display = 'none';
            updateSod(<?php echo json_encode($sodLst7Days) ?>);
        }

        if (id == 'sodLst30') {
            document.getElementById('sodDatePikDiv').style.display = 'none';
            document.getElementById('sodDtPikRngDiv').style.display = 'none';
            updateSod(<?php echo json_encode($sodLst30Days) ?>);
        }

        if (id == 'sodGvnDt') {
            document.getElementById('sodDatePikDiv').style.display = 'block';
            document.getElementById('sodDtPikRngDiv').style.display = 'none';
        }

        if (id == 'sodDtRng') {
            document.getElementById('sodDatePikDiv').style.display = 'none';
            document.getElementById('sodDtPikRngDiv').style.display = 'block';
        }
    }



    // initail sod data set set in sod card =====
    function sodDefaluldData(sodInitialData) {
        let sodInitial = JSON.stringify(sodInitialData);

        if (sodInitial.total_amount != null && sodInitial.total_count != null) {
            document.getElementById('salesAmount').innerHTML = uploadSodData.total_amount;
            document.getElementById('itemsCount').innerHTML = uploadSodData.total_count;

            document.getElementById('sod-Display').style.display = 'block';
            document.getElementById('sod-no-data').style.display = 'none';
        } else {
            document.getElementById('sod-Display').style.display = 'none';
            document.getElementById('sod-no-data').style.display = 'block';
        }
    }

    /// window onload function for initail sod data set....
    window.onload = sodDefaluldData(<?php echo json_encode($salesOfTheDayToday); ?>);
</script>