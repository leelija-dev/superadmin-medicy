// Get DOM elements
const selectedReportType = document.getElementById('selected-report-type');
const selectedAuditRange = document.getElementById('selected-range');
const selectedStartDate = document.getElementById('selected-start-date');
const selectedEndDate = document.getElementById('selected-end-date');
const yearRangePickerDiv = document.getElementById('dtPickerDiv');
const filterAddedOn = document.getElementById('added_on');
const custmYearInputFromYear = document.getElementById('from-year');
const custmYearInputToYear = document.getElementById('to-year');
const auditExtraFilter = document.getElementById('groupBy-Year-div');
const auditExtraFilterVal = document.getElementById('audit-extra-filter-val');

// CHART
const opdAuditReportChartDiv = document.getElementById('opd-audit-report-div');
const opdAuditReportChart = document.getElementById('opd-audit-report-chart');
const noOpdChartDataFoundDiv = document.getElementById('opd-audit-report-no-data-found-div');

const labAuditReportChartDiv = document.getElementById('lab-audit-report-div');
const labAuditReportChart = document.getElementById('lab-audit-report-chart');
const noLabChartDataFoundDiv = document.getElementById('lab-audit-report-no-data-found-div');

// Current year
const currentYear = new Date().getFullYear();

// Function to validate year input
function checkYear(ts) {
    const maxYear = ts.id === 'from-year' ? currentYear : currentYear + 1;
    if (ts.value > maxYear) {
        ts.value = maxYear;
    }
}

// Function to set report type
function reportTypeFilter() {
    // selectedReportType.value = ts.value == 1 ? 'OPD' : ts.value == 2 ? 'Diagnostics' : '';

    const checkboxes = document.querySelectorAll('#report-type-group input[type="checkbox"]');
    const selectedReportTypes = [];

    checkboxes.forEach((checkbox) => {
        if (checkbox.checked) {
            if(checkbox.value == 1){
                selectedReportTypes.push('Appointments');
            }

            if(checkbox.value == 2){
                selectedReportTypes.push('Diagnostics');
            }
            
        }
    });
    selectedReportType.value = selectedReportTypes;
}

// Function to create a date range string
function createDateRange(startYear, endYear) {
    return `01-04-${startYear} 31-03-${endYear}`;
}

// Function to handle audit range selection
function auditRangeFilter() {
    const ranges = {
        1: [currentYear - 1, currentYear],
        2: [currentYear, currentYear + 1],
        3: [currentYear - 5, currentYear],
        4: [currentYear - 10, currentYear]
    };

    yearRangePickerDiv.style.display = filterAddedOn.value == 5 ? 'block' : 'none';

    if (filterAddedOn.value >= 1 && filterAddedOn.value <= 4) {
        const [start, end] = ranges[filterAddedOn.value];
        updateSelectedDateRange(createDateRange(start, end));
    }

    if(filterAddedOn.value == 1 || filterAddedOn.value == 2){
        auditExtraFilter.classList.add('d-none');
    }else{
        auditExtraFilter.classList.remove('d-none');
    }
}

// Function to update selected date range
function updateSelectedDateRange(rangeString) {
    const [startRange, endRange] = rangeString.split(' ');
    selectedAuditRange.value = rangeString;
    selectedStartDate.value = startRange;
    selectedEndDate.value = endRange;
}

// Function to handle custom year range input
function customYearRangePicker() {
    const fromYear = parseInt(custmYearInputFromYear.value);
    const toYear = parseInt(custmYearInputToYear.value);

    if (fromYear && toYear) {
        if (toYear > fromYear) {
            updateSelectedDateRange(createDateRange(fromYear, toYear));
            yearRangePickerDiv.style.display = 'none';
            auditExtraFilter.classList.toggle('d-none', toYear - fromYear === 1);
        } else {
            Swal.fire('Error', 'To year must be greater than From year.', 'warning');
            custmYearInputFromYear.value = '';
            custmYearInputToYear.value = '';
        }
    } else {
        Swal.fire('Error', 'Please enter both From and To years.', 'warning');
    }
}

// Function to handle audit report grouping
function auditReportGroup(ts) {
    const groupOptions = {
        1: 'date',
        2: 'month',
        3: 'year'
    };
    auditExtraFilterVal.value = groupOptions[ts.value] || '';
}

// Function to validate search filter
function auditReportSearchFilter() {
    if (!selectedReportType.value) {
        Swal.fire('Error', 'Select Report type!', 'error');
        return;
    }

    if (!selectedAuditRange.value) {
        Swal.fire('Error', 'Select audit year range!', 'error');
        return;
    }

    if (!auditExtraFilterVal.value) {
        Swal.fire('Error', 'Select additional filter!', 'error');
        return;
    }

    const dataArray = {
        groupBY: auditExtraFilterVal.value,
        startDate: convertDateFormat(selectedStartDate.value, 'YYYY-MM-DD'),
        endDate: convertDateFormat(selectedEndDate.value, 'YYYY-MM-DD'),
        reportOn: selectedReportType.value,
    };

    getOtherAuditReportSearch(dataArray);
}

// Function to fetch audit report data
async function getOtherAuditReportSearch(array) {

    let arryString = JSON.stringify(array);
    let salesDataReport = `ajax/audit-other-report.ajax.php?dataArray=${arryString}`;
    xmlhttp.open("GET", salesDataReport, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
    let report = JSON.parse(xmlhttp.responseText);
    
    if(report.status){
        opdAppointmentData = report.data1;
        labAppointmentData = report.data2;
        auditReportDataChart(opdAppointmentData, labAppointmentData);
    }else {
        opdAuditReportChartDiv.style.display = 'none';
        noOpdChartDataFoundDiv.style.display = 'block';

        labAuditReportChartDiv.style.display = 'none';
        noLabChartDataFoundDiv.style.display = 'block';
        alertFunction();
    }
}

// Function to display audit report data on chart
function auditReportDataChart(opdData, labData) {
    
    if(opdData.length > 0){
        opdAuditReportChartDiv.style.display = 'block';
        noOpdChartDataFoundDiv.style.display = 'none';

        const opdLabels = opdData.map(item => item.apmnt_dt);
        const opdPatientCounts = opdData.map(item => item.patient_count);
        loadOpdChart(opdLabels, opdPatientCounts);
    }else{
        opdAuditReportChartDiv.style.display = 'none';
        noOpdChartDataFoundDiv.style.display = 'block';
    }

    if(labData.length > 0){
        labAuditReportChartDiv.style.display = 'block';
        noLabChartDataFoundDiv.style.display = 'none';

        const labLabels = labData.map(item => item.apmnt_dt);
        const labPatientCounts = labData.map(item => item.patient_count);
        loadLabChart(labLabels, labPatientCounts);
    }else{
        labAuditReportChartDiv.style.display = 'none';
        noLabChartDataFoundDiv.style.display = 'block';
    }
}

// Function to load opd chart
function loadOpdChart(labels, patientCounts) {
    const opdAuditDataChart = opdAuditReportChart.getContext('2d');

    // Clear previous chart if exists
    if (opdAuditReportChart.chart) {
        opdAuditReportChart.chart.destroy();
    }

    // Define the chart
    const opdAppointmentsDataChart = new Chart(opdAuditDataChart, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Appointments',
                data: patientCounts,
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

    opdAuditReportChart.chart = opdAppointmentsDataChart;
}



// Function to load lab chart
function loadLabChart(labels, patientCounts) {
    const labAuditDataChart = labAuditReportChart.getContext('2d');

    // Clear previous chart if exists
    if (labAuditReportChart.chart) {
        labAuditReportChart.chart.destroy();
    }

    // Define the chart
    const labAppointmentsDataChart = new Chart(labAuditDataChart, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Diagnostics',
                data: patientCounts,
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

    labAuditReportChart.chart = labAppointmentsDataChart;
}
