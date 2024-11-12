<?php


///initial total count ///
$newPatients = $Patients->newPatientToday($ADMINID);
// print_r($newPatients);exit;
$totalCount = 0;
if ($newPatients) {
    foreach ($newPatients as $row) {
        $patientCount = $row->patient_count;
        $totalCount += $patientCount;
    }
}

///24 hourse total count //
$newPatientLast24Hours   = $Patients->newPatientCountLast24Hours($adminId);
if (isset($newPatientLast24Hours) && is_array($newPatientLast24Hours)) {
    $totalCount24hrs = 0;
    foreach ($newPatientLast24Hours as $row) {
        $patientCount = $row->patient_count;
        $totalCount24hrs += $patientCount;
    }
}
/// 7 days total count //
$newPatientLast7Days     = $Patients->newPatientCountLast7Days($adminId);
if (isset($newPatientLast7Days) && is_array($newPatientLast7Days)) {
    $totalCount7days = 0;
    foreach ($newPatientLast7Days as $row) {
        $patientCount = $row->patient_count;
        $totalCount7days += $patientCount;
    }
}
/// 30 days total count //
$newPatientLast30Days    = $Patients->newPatientCountLast30Days($adminId);
if (isset($newPatientLast30Days) && is_array($newPatientLast30Days)) {
    $totalCount30days = 0;
    foreach ($newPatientLast30Days as $row) {
        $patientCount = $row->patient_count;
        $totalCount30days += $patientCount;
    }
}

?>


<div class="card shadow-sm h-100 animated--grow-in">
    <div class="px-3 mt-2">
        <div class="d-flex justify-content-between align-items-baseline">
            <p class="text-xs font-weight-bold text-success text-uppercase mb-1">
                <i class="fas fa-user-plus"></i>
                <span id="new-patient-title">New Patients Today</span>
            </p>
            <div class="d-flex justify-content-end px-1">
            <div class="dropdown-menu dropdown-menu-right shadow-lg p-3 pt-4 mt-n5 mr-1" id="newPatientDtPkr" style="display: none; background-color: rgba(255, 255, 255, 0.8);">
                <input style="height: 20px;" type="date" id="newPatientDt">
                <i class="fas fa-times text-danger position-absolute" style="cursor: pointer; top:5px; right:5px" onclick="closePicker('newPatientDtPkr')"></i>
                <button class="btn btn-sm btn-primary" onclick="newPatientByDt()" style="height: 1.5rem; padding:0px;">Find</button>
            </div>
            <div class="dropdown-menu dropdown-menu-right p-3 mt-n5 mr-1" id="newPatientDtPkrRng" style="display: none; background-color: rgba(255, 255, 255, 0.8);">
                <label style="margin-bottom: 0px;"><small>Start Date</small></label> &nbsp; &nbsp; &nbsp; &nbsp; <label style="margin-bottom: 0px;"><small>End Date</small></label><br>
                <input style="width: 80px; height:20px;" type="date" id="newPatientStartDate">
                <input style="width: 80px; height:20px;" type="date" id="newPatientEndDate">
                <i class="fas fa-times text-danger position-absolute" style="cursor: pointer; top:5px; right:5px" onclick="closePicker('newPatientDtPkrRng')"></i>
                <button class="btn btn-sm btn-primary" onclick="newPatientDateRange()" style="height: 1.5rem; padding:0px;">Find</button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <b>...</b>
                </button>
                <div class="dropdown-menu dropdown-menu-right" style="background-color: rgba(255, 255, 255, 0.8);">
                    <button class="dropdown-item" type="button" id="newPatientLst24hrs" onclick="newPatientCount(this.id)">Last 24 hrs</button>
                    <button class="dropdown-item" type="button" id="newPatientLst7" onclick="newPatientCount(this.id)">Last 7 Days</button>
                    <button class="dropdown-item" type="button" id="newPatientLst30" onclick="newPatientCount(this.id)">Last 30 DAYS</button>

                    <button class="dropdown-item dropdown" type="button" id="newPatientOnDt" onclick="newPatientCount(this.id)">By Date</button>
                    <button class="dropdown-item dropdown" type="button" id="newPatientDtRng" onclick="newPatientCount(this.id)">By Range</button>
                </div>
            </div>
        </div>
        </div>

        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class=" mb-0">
                    <div class="h5 mb-0 font-weight-bold <?= (!$newPatients) ? 'text-secondary h6' : 'text-dark' ?>">
                        <p id="newPatient"><?= ($newPatients) ? $totalCount : 'Oops! No Patients yet.' ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if ($newPatients) {
            echo '<div class="d-flex justify-content-end ">
               <button type="button" class=" btn btn-sm btn-outline-light text-dark " id="chartButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" disabled> <i class="fas fa-chart-line"></i></button>
                <div class="dropdown-menu " id="chartMenu" style="margin-top: -128;height: 123px;width: 226px;">
                 <canvas id="myChart"></canvas>
                </div>
              </div>';
        } else {
            echo '';
        }
        ?>
        

    </div>

</div>
<script>
    // const xmlhttp = new XMLHttpRequest();

    ///find new patient by selected date ///
    // function newPatientDataOverride(patientOverrideData) {
    //     // console.log(patientOverrideData);
    //     if (patientOverrideData) {
    //         newPatientchart.data.datasets[0].data = patientOverrideData.map(item => item.patient_count);
    //         newPatientchart.data.labels = patientOverrideData.map(item => item.added_on);
    //         newPatientchart.update();
    //     }
    // }

    // setMaxDateToToday('newPatientDt');
    // setMaxDateToToday('newPatientEndDate');
    // date range control
    function setMaxDateToToday1() {
        const today = new Date();
        const dd = String(today.getDate()).padStart(2, '0'); 
        const mm = String(today.getMonth() + 1).padStart(2, '0'); 
        const yyyy = today.getFullYear(); 

        const formattedDate = `${yyyy}-${mm}-${dd}`; 

        document.getElementById('newPatientDt').setAttribute('max', formattedDate);
        document.getElementById('newPatientStartDate').setAttribute('max', formattedDate);
        document.getElementById('newPatientEndDate').setAttribute('max', formattedDate);
    }

    window.onload = setMaxDateToToday1;
    // eof date range control

    function newPatientByDt() {
        document.getElementById('newPatientDtPkr').style.display= 'none'; // for fadeout the div

        var newPatientDt = document.getElementById('newPatientDt').value;
        var newPatientElement = document.getElementById('newPatient');
        let title = document.getElementById('new-patient-title');

        newPatientDtUrl = `<?php echo LOCAL_DIR ?>ajax/new-patient-count.ajax.php?newPatientDt=${newPatientDt}`;
        request.open("GET", newPatientDtUrl, false);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(null);
        var newPatientDataByDate = request.responseText;
        title.innerText = `New Patients of ${newPatientDt}`;
        // console.log(newPatientDataByDate);
        if (newPatientDataByDate) {
            var newPatientData = JSON.parse(newPatientDataByDate);
            if (newPatientData.length > 0) {
                newPatientElement.textContent = newPatientData[0].patient_count;
            } else {
                newPatientElement.textContent = 'No Data Found';
            }
            // newPatientDataOverride(JSON.parse(newPatientDataByDate));
            document.getElementById('newPatientDtPkr').style.display = 'none';
        } else {
            newPatientElement.textContent = 'No Data Found';
        }
    }

    /// find new patient by selected range ///
    function newPatientDateRange() {
        document.getElementById('newPatientDtPkrRng').style.display = 'none'; //for fadeout the div

        var newPatientStartDate = document.getElementById('newPatientStartDate').value;
        var newPatientEndDate = document.getElementById('newPatientEndDate').value;
        let title = document.getElementById('new-patient-title');

        newPatientDtRngUrl = `<?php echo LOCAL_DIR ?>ajax/new-patient-count.ajax.php?newPatientStartDate=${newPatientStartDate}&newPatientEndDate=${newPatientEndDate}`;
        request.open("GET", newPatientDtRngUrl, false);
        request.send(null);

        var newPatientDataByDateRange = request.responseText;
        // console.log(newPatientDataByDateRange);
        var newPatientElement = document.getElementById('newPatient');
        title.innerText = `New Patients From ${newPatientStartDate} to ${newPatientEndDate}`;

        if (newPatientDataByDateRange && newPatientDataByDateRange !== 'No Data Found') {
            var parsedData = JSON.parse(newPatientDataByDateRange);
            var totalCount = parsedData.reduce(function(total, current) {
                return total + parseInt(current.patient_count);
            }, 0);
            newPatientElement.innerText = totalCount;
            // newPatientDataOverride(JSON.parse(newPatientDataByDateRange));
            document.getElementById('newPatientDtPkrRng').style.display = 'none';
        } else {
            newPatientElement.innerText = 'No Data Found';
        }

    }



    /// selection button ////
    function newPatientCount(buttonId) {
        document.getElementById('newPatientDtPkr').style.display = 'none';
        document.getElementById('newPatientDtPkrRng').style.display = 'none';
        var title = document.getElementById('new-patient-title');

        if (buttonId === 'newPatientLst24hrs') {
            document.getElementById('newPatient').textContent = <?= $totalCount24hrs ?>;
            // newPatientDataOverride(<?= json_encode($newPatientLast24Hours) ?>);
            title.innerText = "New Patients Today";
        }
        if (buttonId === 'newPatientLst7') {
            document.getElementById('newPatient').textContent = <?= $totalCount7days ?>;
            // newPatientDataOverride(<?= json_encode($newPatientLast7Days) ?>);
            title.innerText = "New Patients of Last Week";
        }
        if (buttonId === 'newPatientLst30') {
            document.getElementById('newPatient').textContent = <?= $totalCount30days ?>;
            // newPatientDataOverride(<?= json_encode($newPatientLast30Days) ?>);
            title.innerText = "New Patients of Last Month";
        }
        if (buttonId === 'newPatientOnDt') {
            document.getElementById('newPatientDtPkr').style.display = 'block';
        }
        if (buttonId === 'newPatientDtRng') {
            document.getElementById('newPatientDtPkrRng').style.display = 'block';
        }
    }



    // primary chart data =====

    const newPatients = <?php echo json_encode($newPatients); ?>;
    // console.log(newPatients);
    // if (newPatients != null) {
    //     /// for line chart hover ////
    //     let myChart = document.getElementById('myChart');
    //     document.getElementById('chartButton').addEventListener('mouseenter', function() {
    //         document.getElementById('chartMenu').style.display = 'block';
    //     });
    //     document.getElementById('chartButton').addEventListener('mouseleave', function() {
    //         if (!myChart.matches(':hover')) {
    //             chartMenu.style.display = 'none';
    //         }
    //     });
    //     myChart.addEventListener('mouseleave', function() {
    //         chartMenu.style.display = 'none';
    //     });
    //     //end....///
    // }


    if (newPatients) {
        var newPatientprimaryLabel = newPatients.map(item => item.added_on);
        var newPatientparimaryData = newPatients.map(item => item.patient_count);
    }

    // /// for line chart ///
    // const newPatientCtx = document.getElementById('myChart');
    // var newPatientchart = new Chart(newPatientCtx, {
    //     type: 'line',
    //     data: {
    //         labels: newPatientprimaryLabel,
    //         datasets: [{
    //             label: 'New Patients Count',
    //             data: newPatientparimaryData,
    //             borderWidth: 1
    //         }]
    //     },
    //     options: {
    //         scales: {
    //             y: {
    //                 beginAtZero: true
    //             }
    //         }
    //     }
    // });
</script>