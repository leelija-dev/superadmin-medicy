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
const stockInAuditDiv = document.getElementById('stockIn-auditDiv');
const stockInAuditChart = document.getElementById('stockIn-auditChart');
const stockInNoDataFoundDiv = document.getElementById('stockIn-audit-noDataFound');

const stockReturnAuditDiv = document.getElementById('stockReturn-auditDiv');
const stockReturnAuditChart = document.getElementById('stockReturn-auditChart');
const stockReturnNoDataFoundDiv = document.getElementById('stockReturnAudit-noDataFoundDiv');

const stockOutAuditDiv = document.getElementById('stockOut-auditDiv');
const stockOutAuditChart = document.getElementById('stockOut-auditChart');
const stockOutNoDataFoundDiv = document.getElementById('stockOut-noDataFoundDiv');

const salesRtnAuditDiv = document.getElementById('salesReturn-auditDiv');
const salesRtnAuditChart = document.getElementById('salesReturn-auditChart');
const salesRtnNoDataFoundDiv = document.getElementById('salesReturnAudit-noDataFoundDiv');

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

    const reportTypeMapping = {
        1: 'Purchase',
        2: 'Purchase Return',
        3: 'Sales',
        4: 'Sales Return'
    };
    
    checkboxes.forEach((checkbox) => {
        if (checkbox.checked && reportTypeMapping[checkbox.value]) {
            selectedReportTypes.push(reportTypeMapping[checkbox.value]);
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
        startDateFormat1 : selectedStartDate.value,
        endDateFormat1 : selectedEndDate.value,
        startDateFormat2 : convertDateFormat(selectedStartDate.value, 'YYYY-MM-DD'),
        endDateFormat2: convertDateFormat(selectedEndDate.value, 'YYYY-MM-DD'),
        reportOn: selectedReportType.value,
    };

    getOtherAuditReportSearch(dataArray);
}

// Function to fetch audit report data
async function getOtherAuditReportSearch(array) {

    let arryString = JSON.stringify(array);
    let salesDataReport = `ajax/audit-pharmacy-report.ajax.php?dataArray=${arryString}`;
    xmlhttp.open("GET", salesDataReport, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
    
    let report = JSON.parse(xmlhttp.responseText);
    // console.log(report);
    
    if(report.status){
        stockInData = report.stockInData;
        stockReturnData = report.stockReturnData;
        stockOutData = report.stockOutData;
        salesReturnData = report.salesReturnData;
        auditReportDataChart(stockInData, stockReturnData, stockOutData, salesReturnData);
    }else {
        stockInAuditDiv.style.display = 'none';
        stockInNoDataFoundDiv.style.display = 'block';

        stockReturnAuditDiv.style.display = 'none';
        stockReturnNoDataFoundDiv.style.display = 'block';

        stockOutAuditDiv.style.display = 'none';
        stockOutNoDataFoundDiv.style.display = 'block';

        salesRtnAuditDiv.style.display = 'none';
        salesRtnNoDataFoundDiv.style.display = 'block';
        alertFunction();
    }
}

// Function to display audit report data on chart
function auditReportDataChart(stockIn, stockReturn, stockOut, salesReturn) {
    
    if(stockIn.length > 0){
        stockInAuditDiv.style.display = 'block';
        stockInNoDataFoundDiv.style.display = 'none';

        const stockInLabels = stockIn.map(item => item.purchase_date);
        const stockInBillCount = stockIn.map(item => item.stockIn_count);
        const stockInBillAmount = stockIn.map(item => item.purchase_amount);
        loadStockInChart(stockInLabels, stockInBillCount, stockInBillAmount);
    }else{
        stockInAuditDiv.style.display = 'none';
        stockInNoDataFoundDiv.style.display = 'block';
    }

    if(stockReturn.length > 0){
        stockReturnAuditDiv.style.display = 'block';
        stockReturnNoDataFoundDiv.style.display = 'none';

        const stockReturnLabels = stockReturn.map(item => item.rtn_dt);
        const stockReturnBillCount = stockReturn.map(item => item.rtn_count);
        const stockReturnBillAmount = stockReturn.map(item => item.rtn_amount);
        loadStockReturnChart(stockReturnLabels, stockReturnBillCount, stockReturnBillAmount);
    }else{
        stockReturnAuditDiv.style.display = 'none';
        stockReturnNoDataFoundDiv.style.display = 'block';
    }

    if(stockOut.length > 0){
        stockOutAuditDiv.style.display = 'block';
        stockOutNoDataFoundDiv.style.display = 'none';

        const stockOutLabels = stockOut.map(item => item.sales_dt);
        const stockOutBillCount = stockOut.map(item => item.sales_count);
        const stockOutBillAmount = stockOut.map(item => item.sales_amount);
        loadStockOutChart(stockOutLabels, stockOutBillCount, stockOutBillAmount);
    }else{
        stockOutAuditDiv.style.display = 'none';
        stockOutNoDataFoundDiv.style.display = 'block';
    }

    if(salesReturn.length > 0){
        salesRtnAuditDiv.style.display = 'block';
        salesRtnNoDataFoundDiv.style.display = 'none';

        const salesReturnLabels = salesReturn.map(item => item.sls_rtn_dt);
        const salesReturnBillCount = salesReturn.map(item => item.return_count);
        const salesReturnBillAmount = salesReturn.map(item => item.retunr_amount);
        loadSalesReturnChart(salesReturnLabels, salesReturnBillCount, salesReturnBillAmount);
    }else{
        salesRtnAuditDiv.style.display = 'none';
        salesRtnNoDataFoundDiv.style.display = 'block';
    }
}

// Function to load stockIn chart
function loadStockInChart(labels, billCount, billAmount) {
    const stockInAuditDataChart = stockInAuditChart.getContext('2d');

    // Clear previous chart if exists
    if (stockInAuditChart.chart) {
        stockInAuditChart.chart.destroy();
    }

    // Define the chart
    const stockInDataChart = new Chart(stockInAuditDataChart, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: "StockIn By Count",
                data: billCount,
                borderWidth: 0,
                backgroundColor: 'rgba(57, 137, 250, 1)',
                minBarThickness: 2,
                maxBarThickness: 15,
            },
            {
                label: "StockIn By Amount",
                data: billAmount,
                borderWidth: 0,
                backgroundColor: 'rgba(245, 39, 93, 0.8)',
                minBarThickness: 5,
                maxBarThickness: 12,
            }
        ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    stockInAuditChart.chart = stockInDataChart;
}



// Function to load stockReturn chart
function loadStockReturnChart(labels, billCount, billAmount) {
    const stockReturnAuditDataChart = stockReturnAuditChart.getContext('2d');

    // Clear previous chart if exists
    if (stockReturnAuditChart.chart) {
        stockReturnAuditChart.chart.destroy();
    }

    // Define the chart
    const stockReturnDataChart = new Chart(stockReturnAuditDataChart, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: "StockReturn By Count",
                data: billCount,
                borderWidth: 0,
                backgroundColor: 'rgba(57, 137, 250, 1)',
                minBarThickness: 2,
                maxBarThickness: 15,
            },
            {
                label: "StockReturn By Amount",
                data: billAmount,
                borderWidth: 0,
                backgroundColor: 'rgba(245, 39, 93, 0.8)',
                minBarThickness: 5,
                maxBarThickness: 12,
            }
        ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    stockReturnAuditChart.chart = stockReturnDataChart;
}




// Function to load stockOut chart
function loadStockOutChart(labels, billCount, billAmount) {
    const stockOutAuditDataChart = stockOutAuditChart.getContext('2d');

    // Clear previous chart if exists
    if (stockOutAuditChart.chart) {
        stockOutAuditChart.chart.destroy();
    }

    // Define the chart
    const stockOutDataChart = new Chart(stockOutAuditDataChart, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: "StockOut By Count",
                data: billCount,
                borderWidth: 0,
                backgroundColor: 'rgba(57, 137, 250, 1)',
                minBarThickness: 2,
                maxBarThickness: 15,
            },
            {
                label: "StockOut By Amount",
                data: billAmount,
                borderWidth: 0,
                backgroundColor: 'rgba(245, 39, 93, 0.8)',
                minBarThickness: 5,
                maxBarThickness: 12,
            }
        ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    stockOutAuditChart.chart = stockOutDataChart;
}




// Function to load salesReturn chart
function loadSalesReturnChart(labels, billCount, billAmount) {
    const salesReturnAuditDataChart = salesRtnAuditChart.getContext('2d');

    // Clear previous chart if exists
    if (salesRtnAuditChart.chart) {
        salesRtnAuditChart.chart.destroy();
    }

    // Define the chart
    const salesReturnDataChart = new Chart(salesReturnAuditDataChart, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: "Sales Return By Count",
                data: billCount,
                borderWidth: 0,
                backgroundColor: 'rgba(57, 137, 250, 1)',
                minBarThickness: 2,
                maxBarThickness: 15,
            },
            {
                label: "Sales Return By Amount",
                data: billAmount,
                borderWidth: 0,
                backgroundColor: 'rgba(245, 39, 93, 0.8)',
                minBarThickness: 5,
                maxBarThickness: 12,
            }
        ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    salesRtnAuditChart.chart = salesReturnDataChart;
}