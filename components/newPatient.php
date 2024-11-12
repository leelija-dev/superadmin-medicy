<?php


///initial total count ///
$newPatients = $Patients->newPatientCount();
$totalCount = 0;
if ($newPatients) {
    foreach ($newPatients as $row) {
        $patientCount = $row->patient_count;
        $totalCount += $patientCount;
    }
}

///24 hourse total count //
$newPatientLast24Hours   = $Patients->newPatientCountLast24Hours();
if (isset($newPatientLast24Hours) && is_array($newPatientLast24Hours)) {
    $totalCount24hrs = 0;
    foreach ($newPatientLast24Hours as $row) {
        $patientCount = $row->patient_count;
        $totalCount24hrs += $patientCount;
    }
}
/// 7 days total count //
$newPatientLast7Days     = $Patients->newPatientCountLast7Days();
if (isset($newPatientLast7Days) && is_array($newPatientLast7Days)) {
    $totalCount7days = 0;
    foreach ($newPatientLast7Days as $row) {
        $patientCount = $row->patient_count;
        $totalCount7days += $patientCount;
    }
}
/// 30 days total count //
$newPatientLast30Days    = $Patients->newPatientCountLast30Days();
if (isset($newPatientLast30Days) && is_array($newPatientLast30Days)) {
    $totalCount30days = 0;
    foreach ($newPatientLast30Days as $row) {
        $patientCount = $row->patient_count;
        $totalCount30days += $patientCount;
    }
}

?>


<div class="card border-left-success shadow h-100 py-2">
    <div class="d-flex justify-content-center align-items-start mt-2">
        <div class="card-body ">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mt-n2 mb-2">
                        <i class="fas fa-user-plus"></i> New Patients
                    </div>
                    <div class=" mb-0">
                        <div class="h5 mb-0 font-weight-bold <?= (!$newPatients) ? 'text-warning h6' : 'text-dark' ?>">
                            <p id="newPatient"><?= ($newPatients) ? $totalCount : 'Oops!, the requested data is not in our records.' ?></p>
                        </div>
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
        <div class="d-flex justify-content-end px-1">
            <div class="dropdown-menu dropdown-menu-right p-2 mt-n5 mr-1" id="newPatientDtPkr" style="display: none; background-color: rgba(255, 255, 255, 0.8);">
                <input style="height: 20px;" type="date" id="newPatientDt">
                <button class="btn btn-sm btn-primary" onclick="newPatientByDt()" style="height: 1.5rem; padding:0px;">Find</button>
            </div>
            <div class="dropdown-menu dropdown-menu-right p-2 mt-n5 mr-1" id="newPatientDtPkrRng" style="display: none; background-color: rgba(255, 255, 255, 0.8);">
                <label style="margin-bottom: 0px;"><small>Start Date</small></label> &nbsp; &nbsp; &nbsp; &nbsp; <label style="margin-bottom: 0px;"><small>End Date</small></label><br>
                <input style="width: 80px; height:20px;" type="date" id="newPatientStartDate">
                <input style="width: 80px; height:20px;" type="date" id="newPatientEndDate">
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

</div>
<script src="<?php echo PLUGIN_PATH; ?>chartjs-4.4.0/updatedChart.js"></script>

<script>
    const xmlhttp = new XMLHttpRequest();


    ///find new patient by selected date ///
    function newPatientDataOverride(patientOverrideData) {
        // console.log(patientOverrideData);
        if (patientOverrideData) {
            newPatientchart.data.datasets[0].data = patientOverrideData.map(item => item.patient_count);
            newPatientchart.data.labels = patientOverrideData.map(item => item.added_on);
            newPatientchart.update();
        }
    }

    function newPatientByDt() {
        var newPatientDt = document.getElementById('newPatientDt').value;
        var newPatientElement = document.getElementById('newPatient');

        newPatientDtUrl = `<?php echo LOCAL_DIR ?>ajax/new-patient-count.ajax.php?newPatientDt=${newPatientDt}`;
        xmlhttp.open("GET", newPatientDtUrl, false);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(null);
        var newPatientDataByDate = xmlhttp.responseText;
        // console.log(typeof(newPatientDataByDate));
        if (newPatientDataByDate) {
            var newPatientData = JSON.parse(newPatientDataByDate);
            if (newPatientData.length > 0) {
                newPatientElement.textContent = newPatientData[0].patient_count;
            } else {
                newPatientElement.textContent = 'No Data Found';
            }
            newPatientDataOverride(JSON.parse(newPatientDataByDate));
            document.getElementById('newPatientDtPkr').style.display = 'none';
        } else {
            newPatientElement.textContent = 'No Data Found';
        }
    }

    /// find new patient by selected range ///
    function newPatientDateRange() {
        var newPatientStartDate = document.getElementById('newPatientStartDate').value;
        var newPatientEndDate = document.getElementById('newPatientEndDate').value;

        newPatientDtRngUrl = `<?php echo LOCAL_DIR ?>ajax/new-patient-count.ajax.php?newPatientStartDate=${newPatientStartDate}&newPatientEndDate=${newPatientEndDate}`;
        xmlhttp.open("GET", newPatientDtRngUrl, false);
        xmlhttp.send(null);

        var newPatientDataByDateRange = xmlhttp.responseText;
        // console.log(newPatientDataByDateRange);
        var newPatientElement = document.getElementById('newPatient');

        if (newPatientDataByDateRange && newPatientDataByDateRange !== 'No Data Found') {
            var parsedData = JSON.parse(newPatientDataByDateRange);
            var totalCount = parsedData.reduce(function(total, current) {
                return total + parseInt(current.patient_count);
            }, 0);
            newPatientElement.innerText = totalCount;
            newPatientDataOverride(JSON.parse(newPatientDataByDateRange));
            document.getElementById('newPatientDtPkrRng').style.display = 'none';
        } else {
            newPatientElement.innerText = 'No Data Found';
        }

    }



    /// selection button ////
    function newPatientCount(buttonId) {
        document.getElementById('newPatientDtPkr').style.display = 'none';
        document.getElementById('newPatientDtPkrRng').style.display = 'none';

        if (buttonId === 'newPatientLst24hrs') {
            document.getElementById('newPatient').textContent = <?= $totalCount24hrs ?>;
            newPatientDataOverride(<?= json_encode($newPatientLast24Hours) ?>);
        }
        if (buttonId === 'newPatientLst7') {
            document.getElementById('newPatient').textContent = <?= $totalCount7days ?>;
            newPatientDataOverride(<?= json_encode($newPatientLast7Days) ?>);
        }
        if (buttonId === 'newPatientLst30') {
            document.getElementById('newPatient').textContent = <?= $totalCount30days ?>;
            newPatientDataOverride(<?= json_encode($newPatientLast30Days) ?>);
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
    if (newPatients != null) {
        /// for line chart hover ////
        let myChart = document.getElementById('myChart');
        document.getElementById('chartButton').addEventListener('mouseenter', function() {
            document.getElementById('chartMenu').style.display = 'block';
        });
        document.getElementById('chartButton').addEventListener('mouseleave', function() {
            if (!myChart.matches(':hover')) {
                chartMenu.style.display = 'none';
            }
        });
        myChart.addEventListener('mouseleave', function() {
            chartMenu.style.display = 'none';
        });
        //end....///
    }


    if (newPatients) {
        var newPatientprimaryLabel = newPatients.map(item => item.added_on);
        var newPatientparimaryData = newPatients.map(item => item.patient_count);
    }

    // /// for line chart ///
    const newPatientCtx = document.getElementById('myChart');
    var newPatientchart = new Chart(newPatientCtx, {
        type: 'line',
        data: {
            labels: newPatientprimaryLabel,
            datasets: [{
                label: 'New Patients Count',
                data: newPatientparimaryData,
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