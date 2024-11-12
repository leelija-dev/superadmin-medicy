// const xmlhttp = new XMLHttpRequest();

const selectedDateSpan = document.getElementById('selected-date');
const searchedData = document.getElementById('search-by-data');
const itemSearchVal = document.getElementById('search-val');

// data holding labels
const downloadType = document.getElementById('download-file-type');
const healthCareName = document.getElementById('healthcare-name');
const healthCareGstin = document.getElementById('healthcare-gstin');
const healthCareAddress = document.getElementById('healthcare-address');
const reportGenerationTime = document.getElementById('report-generation-date-time-holder');
const selectedStartDate = document.getElementById('selected-date');

// buttons
const reset1 = document.getElementById('search-reset-1');

// dynamic table 
const itemMarginTable = document.getElementById('purchase-analysis-table');

// calender control 
$(function() {
    // Set the current date
    var currentDate = moment().format('DD-MM-YYYY');
    $('#selected-date').html(currentDate);

    // Initialize the date picker
    $('#date-range-select-div').daterangepicker({
        singleDatePicker: true, // Single date picker
        showDropdowns: true, // Year and month controls
        autoUpdateInput: false, // Don't auto update the input
        autoApply: true, // Automatically apply the date selection
        maxDate: moment(), // Disable future dates
        locale: {
            format: 'DD-MM-YYYY'
        }
    });

    // Event handler for applying the date
    $('#date-range-select-div').on('apply.daterangepicker', function(ev, picker) {
        $('#selected-date').html(picker.startDate.format('DD-MM-YYYY'));
    });
});


// date splitter
function separateDates(dateRange) {
    let dates = dateRange.split(' to ');
    let startDate = dates[0];
    let endDate = dates[1];
    return {
        startDate: startDate,
        endDate: endDate
    };
}

// current date time generation function
function getCurrentDateTime() {
    const currentDate = new Date();

    const day = String(currentDate.getDate()).padStart(2, '0');
    const month = String(currentDate.getMonth() + 1).padStart(2, '0'); // January is 0!
    const year = currentDate.getFullYear();

    const hours = String(currentDate.getHours()).padStart(2, '0');
    const minutes = String(currentDate.getMinutes()).padStart(2, '0');
    const seconds = String(currentDate.getSeconds()).padStart(2, '0');

    return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
}

// close button contorl
function resteUrl(){
    searchedData.value = '';
    reset1.style.display = 'none';
    purchaseAnalysisSearch();
}

function purchaseAnalysisSearch(){

    let searchValue = searchedData.value;
    let searchDate = selectedDateSpan.innerHTML;

    if(searchValue != ''){
        reset1.style.display = 'block';
    }

    let convertedDate = convertDateFormat(searchDate, 'YYYY-MM-DD');
    
    let dataArray = {
        searchDate: convertedDate,
        searchOn: searchValue,
    };

    purchaseAnalysisDataSearch(dataArray);
}



// purchase analysis data fetch ajax call
let fullReportData = [];
function purchaseAnalysisDataSearch(array){
    let arryString = JSON.stringify(array);
    let salesDataReport = `ajax/purchaseAnalysis.ajax.php?dataArray=${arryString}`;
    xmlhttp.open("GET", salesDataReport, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
    let report = xmlhttp.responseText; 
    
    report = JSON.parse(report);
    // console.log(report);
    if(report.status){
        fullReportData = report.data;
        purchaseAnalysisReportShow(report.data);
    }else{
        itemMarginTable.innerHTML = '';
        fullReportData = [];
        alertFunction();
    }
}





function purchaseAnalysisReportShow(reportData) {
    if(reportData.length != 0){
        const rowsPerPage = 25; 
        let currentPage = 1;

        itemMarginTable.innerHTML = '';
        document.getElementById('download-checking').innerHTML = '1';

        let currentDateTime = getCurrentDateTime();
        reportGenerationTime.innerHTML = currentDateTime;

        const header = ['Bill Date', 'Bill No', 'Distributor Name', 'Item Name', 'Qty', 'MRP', 'PTR', 'Margin %',   'Margin Difference %', 'Margin Difference ₹', 'Avg. Margin%'];

        const thead = document.createElement('thead');
        const tr = document.createElement('tr');
        header.forEach((headerText, index) => {
            const th = document.createElement('th');
            th.textContent = headerText;
            th.style.fontWeight = 'bold'; 
            if (index >= 4 && index <= 10) {
                th.style.textAlign = 'right'; 
            }
            tr.appendChild(th);
        });
        thead.appendChild(tr);
        itemMarginTable.appendChild(thead);

        function renderTable(data, page) {
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedData = data.slice(start, end);

            itemMarginTable.innerHTML = '';
            itemMarginTable.appendChild(thead.cloneNode(true)); 

            const tbody = document.createElement('tbody');
            paginatedData.forEach(data => {
                const row = document.createElement('tr');
                ['bill_date', 'bill_no', 'dist_name', 'item_name', 'qty', 'mrp', 'ptr', 'margin', 'margin_diff',    'margin_diff_amount', 'avg_margin'].forEach((key, index) => {
                    const cell = document.createElement('td');
                    const value = data[key] || '';
                    cell.textContent = (['mrp', 'ptr', 'margin', 'margin_diff', 'margin_diff_amount',   'avg_margin'].includes(key) ? parseFloat(value).toFixed(2) : value) || '';
                    cell.style.textAlign = (index >= 4 && index <= 10) ? 'right' : ''; 
                    if (key === 'margin_diff' || key === 'margin_diff_amount') {
                        if (parseFloat(value) < 0) {
                            cell.style.color = 'red';
                        }
                    }
                    row.appendChild(cell);
                });
                tbody.appendChild(row);
            });

            itemMarginTable.appendChild(tbody);

            const paginationControls = createPaginationControls(reportData.length, currentPage, rowsPerPage,    (pageNumber) => {
                currentPage = pageNumber;
                renderTable(reportData, currentPage);
            });
            document.getElementById('pagination-controls').innerHTML = ''; 
            document.getElementById('pagination-controls').appendChild(paginationControls);
        }
        renderTable(reportData, currentPage);
    }else{
        alertFunction();
    }
}





// download file format selection function
function selectDownloadType(ts){
    if(document.getElementById('download-checking').innerHTML == '1'){
        if(ts.value == 'exl'){
            exportToExcel();
            downloadType.selectedIndex = 0;
        }
        if(ts.value == 'csv'){
            exportToCSV();
            downloadType.selectedIndex = 0;
        }
        if(ts.value == 'pdf'){
            exportToPDF();
        }
    }else{
        Swal.fire('Error','generate report first!','error');
        downloadType.selectedIndex = 0;
    }
}




// exporting function gose down there
// Function for export the table data to Excel
function exportToExcel() {
    if(fullReportData.length != 0){
        const headerData1 = [
            [healthCareName.innerHTML],
        ];
        const headerData2 = [
            [healthCareAddress.innerHTML],
            ["GSTIN : " + healthCareGstin.innerHTML],
            [],
            ["Sales Summary Report : " + selectedStartDate.innerHTML],
            ["Report generated at : " + reportGenerationTime.innerHTML],
        ];
    
        const headers = ['Bill Date', 'Bill No', 'Distributo Name', 'Item Name', 'Qty', 'MRP', 'PTR', 'Margin %', 'Margin Difference %', 'Margin Difference ₹', 'Avg. Margin%'];
        
        // Use fullReportData for rows
        const rows = fullReportData.map(data => [
            data.bill_date || '',
            data.bill_no || '',
            data.dist_name || '',
            data.item_name || '',
            data.qty || '',
            parseFloat(data.mrp).toFixed(2),
            parseFloat(data.ptr).toFixed(2),
            parseFloat(data.margin).toFixed(2),
            parseFloat(data.margin_diff).toFixed(2) + ' %',
            parseFloat(data.margin_diff_amount).toFixed(2),
            parseFloat(data.avg_margin).toFixed(2)
        ]);
    
        // Create a new Excel workbook
        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet('Report');
    
        // Add header data1 to the worksheet with merged cells, center alignment, and specified font
        let currentRow = 1; // Start at row 1
    
        headerData1.forEach(rowData => {
            const mergeToColumn = headers.length > 0 ? headers.length : 1; // Merge across all columns if headers exist
            worksheet.mergeCells(`A${currentRow}:${String.fromCharCode(65 + mergeToColumn - 1)}${currentRow}`);
            const mergedCell = worksheet.getCell(`A${currentRow}`);
            mergedCell.value = rowData[0];
            mergedCell.alignment = { horizontal: 'center' }; // Center align the content
            mergedCell.font = { size: 14, bold: true }; // Set font size to 14 and bold
            currentRow++;
        });
    
        // Add header data2 to the worksheet with merged cells and center alignment
        headerData2.forEach(rowData => {
            const mergeToColumn = headers.length > 0 ? headers.length : 1; // Merge across all columns if headers exist
            worksheet.mergeCells(`A${currentRow}:${String.fromCharCode(65 + mergeToColumn - 1)}${currentRow}`);
            const mergedCell = worksheet.getCell(`A${currentRow}`);
            mergedCell.value = rowData[0];
            mergedCell.alignment = { horizontal: 'center' }; // Center align the content
            currentRow++;
        });
    
        // Add an empty row for spacing
        worksheet.addRow([]);
        currentRow++; // Increment row index for spacing
    
        // Add headers row to the worksheet and apply bold font
        const headersRow = worksheet.addRow(headers);
        headersRow.eachCell((cell, colNumber) => {
            cell.font = { bold: true };
            // Style the header cells with a yellow background
            cell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: { argb: 'FFFF00' } // Yellow background color
            };
        });
    
        // Add rows to the worksheet
        rows.forEach(row => {
            worksheet.addRow(row);
        });
    
        // Generate Excel file
        workbook.xlsx.writeBuffer().then(buffer => {
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            saveAs(blob, 'purchase-analysis-report.xlsx');
        });
    }else{
        alertFunction();
    }
}






// export to csv
function exportToCSV() {
    if(fullReportData.length){
        const headerData1 = [
            [healthCareName.innerHTML],
        ];
        const headerData2 = [
            [healthCareAddress.innerHTML],
            ["GSTIN : " + healthCareGstin.innerHTML],
            [],
            ["Sales Summary Report : " + selectedStartDate.innerHTML],
            ["Report generated at : " + reportGenerationTime.innerHTML],
        ];
    
        const headers = ['Bill Date', 'Bill No', 'Distributo Name', 'Item Name', 'Qty', 'MRP', 'PTR', 'Margin %', 'Margin Difference %', 'Margin Difference ₹', 'Avg. Margin%'];
        
        // Use fullReportData for rows
        const rows = fullReportData.map(data => [
            data.bill_date || '',
            data.bill_no || '',
            data.dist_name || '',
            data.item_name || '',
            data.qty || '',
            parseFloat(data.mrp).toFixed(2),
            parseFloat(data.ptr).toFixed(2),
            parseFloat(data.margin).toFixed(2),
            parseFloat(data.margin_diff).toFixed(2) + ' %',
            parseFloat(data.margin_diff_amount).toFixed(2),
            parseFloat(data.avg_margin).toFixed(2)
        ]);
    
        // Convert data to CSV format
        function convertToCSV(data) {
            return data.map(row => row.map(cell => `"${cell}"`).join(',')).join('\n');
        }
    
        // Prepare CSV content
        let csvContent = '';
    
        // Add headerData1 to CSV
        headerData1.forEach(rowData => {
            csvContent += rowData[0] + '\n';
        });
    
        // Add headerData2 to CSV
        headerData2.forEach(rowData => {
            csvContent += rowData[0] + '\n';
        });
    
        // Add empty row for spacing
        csvContent += '\n';
    
        // Add headers row to CSV
        csvContent += headers.join(',') + '\n';
    
        // Add rows to CSV
        csvContent += convertToCSV(rows);
    
        // Create a Blob and trigger download
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        if (link.download !== undefined) { // feature detection
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', 'purchase-analysis-report.csv');
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }else{
        alertFunction();
    }
}
