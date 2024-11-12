// const xmlhttp = new XMLHttpRequest();

const selectedDateSpan = document.getElementById('selected-date');
const selectedDateRange = document.getElementById('selected-date-range');
const selectedReportOn = document.getElementById('report-on-filter');
const searchedItem = document.getElementById('search-by-item');
const itemSearchVal = document.getElementById('item-search-val');

// data holding labels
const downloadType = document.getElementById('download-file-type');
const healthCareName = document.getElementById('healthcare-name');
const healthCareGstin = document.getElementById('healthcare-gstin');
const healthCareAddress = document.getElementById('healthcare-address');
const reportGenerationTime = document.getElementById('report-generation-date-time-holder');
const selectedStartDate = document.getElementById('selected-start-date');
const selectedEndDate = document.getElementById('selected-end-date');

// output labels
const totalSalesAmountLabel = document.getElementById('total-sales-amount');
const totalPurchaseAmountLable = document.getElementById('total-purchase-amount');
const netGstAmountLable = document.getElementById('net-gst-amount');
const totalProfitAmountLable = document.getElementById('total-profit-amount');

// buttons
const reset1 = document.getElementById('search-reset-1');

// divs
const grandTotalShow = document.getElementById('grand-total-div');

// dynamic table 
const itemMarginTable = document.getElementById('item-wise-margin-table');

// date picker div range control script
$(function() {

    function cb(start, end) {
        $('#date-range-select-div span').html(start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY'));
    }

    $('#date-range-select-div').daterangepicker({
        autoUpdateInput: false, // initial value
        showDropdowns: true, // year and month controls
        locale: {
            format: 'DD-MM-YYYY',
            cancelLabel: 'Clear'
        },
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    $('#date-range-select-div').on('apply.daterangepicker', function(ev, picker) {
        cb(picker.startDate, picker.endDate);
    });

    $('#date-range-select-div').on('cancel.daterangepicker', function(ev, picker) {
        $('#date-range-select-div span').html('Select Date'); // reset place holder
    });

    $('#date-range-select-div span').html('Select Date'); // initial place holder
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


// control functions
function reportOnFilter(t){
    if(t.value == 'S'){
        selectedReportOn.innerHTML = 'sales-table';
    }
    if(t.value == 'SR'){
        selectedReportOn.innerHTML = 'sales-return-table';
    }
}

// close button contorl
function resteUrl(){
    searchedItem.value = '';
    reset1.style.display = 'none';
    itemMerginSearch();
}


function itemMerginSearch(){
    let searchItem = '';
    let startDate = '';
    let endDate = '';
    
    if($(selectedDateSpan).text() == 'Select Date'){
        Swal.fire('Date missing','Select Date!','info');
        return;
    }else{
        let separatedDates = separateDates($(selectedDateSpan).text());
        startDate = convertDateFormat(separatedDates.startDate, 'YYYY-MM-DD'); // function is in sb-admin2.js
        endDate = convertDateFormat(separatedDates.endDate, 'YYYY-MM-DD');     // function is in sb-admin2.js
        selectedStartDate.innerHTML = startDate;
        selectedEndDate.innerHTML = endDate;   
    }

    if(selectedReportOn.innerHTML == ''){
        Swal.fire('Info','Select report type','info');
        return;
    }

    if(searchedItem.value == ''){
        searchItem = '';
    }else{
        searchItem = searchedItem.value;
        reset1.style.display = 'block';
    }

    // console.log(startDate, endDate);
    let dataArray = {
        startDt: startDate,
        endDt: endDate,
        filterBy: selectedReportOn.innerHTML,
        searchOn: searchItem,
        searchFlag: 1,
    };

    itemMerginDataSearch(dataArray);
}




// item mergin data search function (ajax call)
let fullReportData = [];
function itemMerginDataSearch(array){

    let arryString = JSON.stringify(array);
    let salesDataReport = `ajax/itemMerginReport.ajax.php?dataArray=${arryString}`;
    xmlhttp.open("GET", salesDataReport, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
    let report = xmlhttp.responseText;

    // console.log(report);
    report = JSON.parse(report);
    if(report.status == '1'){
        fullReportData = report.data;
        billItemMerginReportShow(report.data);
    }else{
        grandTotalShow.classList.add('d-none');
        itemMarginTable.innerHTML = '';
        // alert('no data found');
        fullReportData = [];
        alertFunction();
    }
}




function billItemMerginReportShow(reportData) {
    if(reportData.length != 0){
        const rowsPerPage = 25;
        let currentPage = 1;

        itemMarginTable.innerHTML = '';
        document.getElementById('download-checking').innerHTML = '1';
        grandTotalShow.classList.remove('d-none');

        let currentDateTime = getCurrentDateTime();
        reportGenerationTime.innerHTML = currentDateTime;

        const header = ['Added by', 'Bill No', 'Bill Date', 'Patient Name', 'Item Name', 'Category', 'Unit',    'MANUF.', 'Sale', 'Stock', 'MRP', 'Sales Amt.', 'Purchase', 'Net GST', 'Profit (%)'];

        function renderTable(data, page) {
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedData = data.slice(start, end);

            itemMarginTable.innerHTML = '';

            const thead = document.createElement('thead');
            const tr = document.createElement('tr');
            header.forEach((headerText, index) => {
                const th = document.createElement('th');
                th.textContent = headerText;
                th.style.fontWeight = 'bold'; 
                if (index >= header.length - 5) {
                    th.style.textAlign = 'right'; 
                }
                tr.appendChild(th);
            });
            thead.appendChild(tr);

            itemMarginTable.appendChild(thead);

            const tbody = document.createElement('tbody');

            let totalSalesAmount = 0;
            let totalPurchaseAmount = 0;
            let totalNetGst = 0;
            let totalProfit = 0;

            paginatedData.forEach(data => {
                const row = document.createElement('tr');

                const addedByCell = document.createElement('td');
                addedByCell.textContent = data.added_by_name || ''; 
                row.appendChild(addedByCell);

                const billNoCell = document.createElement('td');
                billNoCell.textContent = data.bill_no || ''; 
                row.appendChild(billNoCell);

                const billDateCell = document.createElement('td');
                billDateCell.textContent = data.bill_date || ''; 
                row.appendChild(billDateCell);

                const patientNameCell = document.createElement('td');
                patientNameCell.textContent = data.patient_name || ''; 
                row.appendChild(patientNameCell);

                const itemNameCell = document.createElement('td');
                itemNameCell.textContent = data.item || ''; 
                row.appendChild(itemNameCell);

                const itemCategoryCell = document.createElement('td');
                itemCategoryCell.textContent = data.category || '';
                row.appendChild(itemCategoryCell);

                const itemUnitCell = document.createElement('td');
                itemUnitCell.textContent = data.unit || ''; 
                row.appendChild(itemUnitCell);

                const itemManufCell = document.createElement('td');
                itemManufCell.textContent = data.manuf_short_name || ''; 
                row.appendChild(itemManufCell);

                const itemSalesQtyCell = document.createElement('td');
                itemSalesQtyCell.textContent = data.stock_out_qty || '';
                row.appendChild(itemSalesQtyCell);

                const itemCurrentQtyCell = document.createElement('td');
                itemCurrentQtyCell.textContent = data.current_qty || ''; 
                row.appendChild(itemCurrentQtyCell);

                const itemMrpCell = document.createElement('td');
                itemMrpCell.textContent = parseFloat(data.mrp).toFixed(2); 
                itemMrpCell.style.textAlign = 'right'; 
                row.appendChild(itemMrpCell);

                const itemSalesAmountCell = document.createElement('td');
                itemSalesAmountCell.textContent = parseFloat(data.sales_amount).toFixed(2); 
                itemSalesAmountCell.style.textAlign = 'right'; 
                totalSalesAmount += parseFloat(data.sales_amount);
                row.appendChild(itemSalesAmountCell);

                const itemPurchaseAmountCell = document.createElement('td');
                itemPurchaseAmountCell.textContent = parseFloat(data.p_amount).toFixed(2); 
                itemPurchaseAmountCell.style.textAlign = 'right';
                totalPurchaseAmount += parseFloat(data.p_amount);
                row.appendChild(itemPurchaseAmountCell);

                const itemNetGstCell = document.createElement('td');
                itemNetGstCell.textContent = parseFloat(data.gst_amount).toFixed(2); 
                itemNetGstCell.style.textAlign = 'right'; 
                totalNetGst += parseFloat(data.gst_amount);
                row.appendChild(itemNetGstCell);

                const itemProfitAmountPercentageCell = document.createElement('td');
                let profit = ((parseFloat(data.sales_amount) - parseFloat(data.p_amount)) - parseFloat(data.    gst_amount));
                let profitPercent = (parseFloat(profit) * 100) / parseFloat(data.p_amount);
                itemProfitAmountPercentageCell.textContent = profit.toFixed(2) + ' (' + profitPercent.toFixed(2)    + '%)';
                itemProfitAmountPercentageCell.style.textAlign = 'right'; 
                row.appendChild(itemProfitAmountPercentageCell);

                tbody.appendChild(row);
            });

            totalSalesAmountLabel.innerHTML = totalSalesAmount.toFixed(2);
            totalPurchaseAmountLable.innerHTML = totalPurchaseAmount.toFixed(2);
            netGstAmountLable.innerHTML = totalNetGst.toFixed(2);
            let profitAmount = parseFloat(totalSalesAmount) - parseFloat(totalPurchaseAmount);
            totalProfitAmountLable.innerHTML = profitAmount.toFixed(2);

            itemMarginTable.appendChild(tbody);

            const paginationControls = createPaginationControls(reportData.length, currentPage, rowsPerPage,    (page) => {
                currentPage = page;
                renderTable(reportData, currentPage);
            });

            const paginationWrapper = document.getElementById('pagination-controls');
            paginationWrapper.innerHTML = ''; 
            paginationWrapper.appendChild(paginationControls);
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
        alertFunction();
        downloadType.selectedIndex = 0;
    }
}




// Function for export the table data to Excel
function exportToExcel() {
    if(fullReportData.length != 0){
        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet('Report');

        // Merge rows for header data
        worksheet.mergeCells('A1:O1');
        worksheet.getCell('A1').value = healthCareName.innerHTML;
        worksheet.getCell('A1').font = { size: 16, bold: true };
        worksheet.getCell('A1').alignment = { vertical: 'middle', horizontal: 'center' };

        worksheet.mergeCells('A2:O2');
        worksheet.getCell('A2').value = healthCareAddress.innerHTML;
        worksheet.getCell('A2').alignment = { vertical: 'middle', horizontal: 'center' };

        worksheet.mergeCells('A3:O3');
        worksheet.getCell('A3').value = healthCareGstin.innerHTML;
        worksheet.getCell('A3').alignment = { vertical: 'middle', horizontal: 'center' };

        worksheet.mergeCells('A4:O4');
        worksheet.getCell('A4').value = selectedStartDate.innerHTML;
        worksheet.getCell('A4').alignment = { vertical: 'middle', horizontal: 'center' };

        worksheet.mergeCells('A5:O5');
        worksheet.getCell('A5').value = 'Report generated time: ' + reportGenerationTime.innerHTML;
        worksheet.getCell('A5').alignment = { vertical: 'middle', horizontal: 'center' };

    // Add the header row
        const headerRow = worksheet.addRow(['Added by', 'Bill No', 'Bill Date', 'Patient Name', 'Item Name', 'Category', 'Unit', 'MANUF.', 'Sale', 'Stock', 'MRP', 'Sales Amt.', 'Purchase', 'Net GST', 'Profit (%)']);
        headerRow.font = { bold: true };

    // Add data rows
        fullReportData.forEach(data => {
            const profit = ((parseFloat(data.sales_amount) - parseFloat(data.p_amount)) - parseFloat(data.  gst_amount));
            const profitPercent = (parseFloat(profit) * 100) / parseFloat(data.p_amount);
            const rowData = [
                data.added_by_name,
                data.bill_no,
                data.bill_date,
                data.patient_name,
                data.item,
                data.category,
                data.unit,
                data.manuf_short_name,
                data.stock_out_qty,
                data.current_qty,
                parseFloat(data.mrp).toFixed(2),
                parseFloat(data.sales_amount).toFixed(2),
                parseFloat(data.p_amount).toFixed(2),
                parseFloat(data.gst_amount).toFixed(2),
                `${profit.toFixed(2)} (${profitPercent.toFixed(2)}%)`
            ];
            worksheet.addRow(rowData);
        });

        // Save the workbook
        workbook.xlsx.writeBuffer().then(data => {
            const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml. sheet' });
            saveAs(blob, 'bill-item-wise-mergin.xlsx');
        });
    }else{
        alertFunction();
    }
}




// export to csv
function exportToCSV() {
    if(fullReportData.length != 0){
        const headerData1 = [
            [healthCareName.innerHTML],
        ];
        const headerData2 = [
            [healthCareAddress.innerHTML],
            ["GSTIN : " + healthCareGstin.innerHTML],
            [],
            ["Sales Summary Report : " + selectedStartDate.innerHTML + " To " + selectedEndDate.innerHTML],
            ["Report generated at : " + reportGenerationTime.innerHTML],
        ];
    
        const headers = ['Added by', 'Bill No', 'Bill Date', 'Patient Name', 'Item Name', 'Category', 'Unit', 'MANUF.', 'Sale', 'Stock', 'MRP', 'Sales Amt.', 'Purchase', 'Net GST', 'Profit (%)'];
        const rows = fullReportData.map(data => {
            const profit = ((parseFloat(data.sales_amount) - parseFloat(data.p_amount)) - parseFloat(data.gst_amount));
            const profitPercent = (parseFloat(profit) * 100) / parseFloat(data.p_amount);
            return [
                data.added_by_name,
                data.bill_no,
                data.bill_date,
                data.patient_name,
                data.item,
                data.category,
                data.unit,
                data.manuf_short_name,
                data.stock_out_qty,
                data.current_qty,
                parseFloat(data.mrp).toFixed(2),
                parseFloat(data.sales_amount).toFixed(2),
                parseFloat(data.p_amount).toFixed(2),
                parseFloat(data.gst_amount).toFixed(2),
                `${profit.toFixed(2)} (${profitPercent.toFixed(2)}%)`
            ];
        });
    
        // Calculate grand totals for each column (excluding the first column which is the date column)
        const numColumns = headers.length;
        const grandTotals = new Array(numColumns).fill('');
        const startColumn = numColumns - 4; // Start from the last four columns
    
        for (let colIndex = startColumn; colIndex < numColumns; colIndex++) {
            grandTotals[colIndex] = rows.reduce((sum, row) => {
                // Extract numeric value outside parentheses
                const match = row[colIndex].match(/([0-9.-]+)(?:\s*\([^)]*\))?/);
                const value = match ? match[1].replace(/[^0-9.-]+/g, "") : "0";
                return sum + (parseFloat(value) || 0);
            }, 0).toFixed(2); // Sum and format as needed
        }
    
        // Function to escape CSV values
        function escapeCSVValue(value) {
            if (typeof value === 'string' && (value.includes(',') || value.includes('"') || value.includes('\n'))) {
                value = '"' + value.replace(/"/g, '""') + '"';
            }
            return value;
        }
    
        // Create CSV content
        let csvContent = '';
    
        // Add header data1 to the CSV content
        headerData1.forEach(rowData => {
            csvContent += rowData.map(escapeCSVValue).join(',') + '\n';
        });
    
        // Add header data2 to the CSV content
        headerData2.forEach(rowData => {
            csvContent += rowData.map(escapeCSVValue).join(',') + '\n';
        });
    
        // Add an empty row for spacing
        csvContent += '\n';
    
        // Add headers row to the CSV content
        csvContent += headers.map(escapeCSVValue).join(',') + '\n';
    
        // Add rows to the CSV content
        rows.forEach(row => {
            csvContent += row.map(escapeCSVValue).join(',') + '\n';
        });
    
        // Add the grand totals row to the CSV content
        csvContent += grandTotals.map(escapeCSVValue).join(',') + '\n';
    
        // Create a blob from the CSV content
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
    
        // Create a link to download the CSV file
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', 'billItem-report.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }else{
        alertFunction();
    }
}
