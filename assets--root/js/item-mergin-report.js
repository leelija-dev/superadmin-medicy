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
        maxDate: moment(), // restrict both start and end dates to today or earlier
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
        $('#date-range-select-div span').html('Select Date'); // reset placeholder
    });

    $('#date-range-select-div span').html('Select Date'); // initial placeholder
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

// // convert date format
// function convertDateFormat(dateStr) {
//     const [day, month, year] = dateStr.split('-');
//     const newDateStr = `${year}-${month}-${day}`;

//     return newDateStr;
// }


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
    let searchFlag = 0;
    
    if($(selectedDateSpan).text() == 'Select Date'){
        // alert('Select Date');
        Swal.fire('Check','Select Date','info');
        return;
    }else{
        let separatedDates = separateDates($(selectedDateSpan).text());
        startDate = convertDateFormat(separatedDates.startDate, 'YYYY-MM-DD');
        endDate = convertDateFormat(separatedDates.endDate, 'YYYY-MM-DD');
        selectedStartDate.innerHTML = startDate;
        selectedEndDate.innerHTML = endDate;   
    }

    if(selectedReportOn.innerHTML == ''){
        Swal.fire('Check','select report type','info');
        // alert('select report type');
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
        searchFlag: 0,
    };

    itemMerginDataSearch(dataArray);
}


// item mergin data search function (ajax call)
let reportData = [];
function itemMerginDataSearch(array){

    let arryString = JSON.stringify(array);
    let salesDataReport = `ajax/itemMerginReport.ajax.php?dataArray=${arryString}`;
    xmlhttp.open("GET", salesDataReport, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
    let report = xmlhttp.responseText;

    // console.log(report);
    report = JSON.parse(report);
    if(report.status){
        reportData = report.data;
        itemMerginReportShow(report.data);
    }else{
        grandTotalShow.classList.add('d-none');
        itemMarginTable.innerHTML = '';
        // alert('no data found');
        reportData = [];
        alertFunction();
    }
}




function itemMerginReportShow(reportData) {
    if(reportData.length != 0){
        const rowsPerPage = 25;
        let currentPage = 1;

        itemMarginTable.innerHTML = '';
        document.getElementById('download-checking').innerHTML = '1';
        grandTotalShow.classList.remove('d-none');

        let currentDateTime = getCurrentDateTime();
        reportGenerationTime.innerHTML = currentDateTime;

        const header = ['Item Name', 'Category', 'Unit', 'MANUF.', 'Sale', 'Stock', 'MRP', 'Sales Amt.',    'Purchase', 'Net GST', 'Profit (%)'];

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
                if (index >= 5) {
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
            let totalProfitAmount = 0;
            let totalProfitParcent = 0;

            paginatedData.forEach(data => {
                const row = document.createElement('tr');

                const itemNameCell = document.createElement('td');
                itemNameCell.textContent = data.item;
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
                itemSalesQtyCell.textContent = data.stock_out_qty;
                row.appendChild(itemSalesQtyCell);

                const itemCurrentQtyCell = document.createElement('td');
                itemCurrentQtyCell.textContent = data.current_qty;
                itemCurrentQtyCell.style.textAlign = 'right'; 
                row.appendChild(itemCurrentQtyCell);

                const itemMrpCell = document.createElement('td');
                itemMrpCell.textContent = parseFloat(data.mrp).toFixed(2); 
                itemMrpCell.style.textAlign = 'right'; 
                row.appendChild(itemMrpCell);

                const itemSalesAmountCell = document.createElement('td');
                itemSalesAmountCell.textContent = parseFloat(data.sales_amount).toFixed(2); 
                itemSalesAmountCell.style.textAlign = 'right'; 
                totalSalesAmount = parseFloat(totalSalesAmount) + parseFloat(data.sales_amount);
                row.appendChild(itemSalesAmountCell);

                const itemPurchaseAmountCell = document.createElement('td');
                itemPurchaseAmountCell.textContent = parseFloat(data.p_amount).toFixed(2); 
                itemPurchaseAmountCell.style.textAlign = 'right'; 
                totalPurchaseAmount = parseFloat(totalPurchaseAmount) + parseFloat(data.p_amount);
                row.appendChild(itemPurchaseAmountCell);

                const itemNetGstCell = document.createElement('td');
                itemNetGstCell.textContent = parseFloat(data.gst_amount).toFixed(2); 
                itemNetGstCell.style.textAlign = 'right'; 
                totalNetGst = parseFloat(totalNetGst) + parseFloat(data.gst_amount);
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
            totalProfitAmount = parseFloat(totalSalesAmount) - parseFloat(totalPurchaseAmount) - parseFloat (totalNetGst);
            totalProfitParcent = (parseFloat(totalProfitAmount) * 100) / parseFloat(totalPurchaseAmount);
            totalProfitAmountLable.innerHTML = totalProfitAmount.toFixed(2) + ' (' + totalProfitParcent.toFixed (2) + '%)';

            itemMarginTable.appendChild(tbody);

            const paginationControls = createPaginationControls(reportData.length, currentPage, rowsPerPage,    (newPage) => {
                currentPage = newPage;
                renderTable(reportData, currentPage);
            });
            const paginationContainer = document.getElementById('pagination-controls');
            paginationContainer.innerHTML = '';
            paginationContainer.appendChild(paginationControls);
        }

        renderTable(reportData, currentPage);
    }else{
        alertFunction()
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



// exporting function gose down there
// Function for export the table data to Excel
function exportToExcel() {
    if(reportData.length != 0){
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
    
        // Use the full report data instead of the paginated data
        const headers = ['Item Name', 'Category', 'Unit', 'MANUF.', 'Sale', 'Stock', 'MRP', 'Sales Amt.', 'Purchase', 'Net GST', 'Profit (%)'];
        const rows = reportData.map(data => {
            let profit = ((parseFloat(data.sales_amount) - parseFloat(data.p_amount)) - parseFloat(data.gst_amount));
            let profitPercent = (parseFloat(profit) * 100) / parseFloat(data.p_amount);
            return [
                data.item,
                data.category || '',
                data.unit || '',
                data.manuf_short_name || '',
                data.stock_out_qty,
                data.current_qty,
                parseFloat(data.mrp).toFixed(2),
                parseFloat(data.sales_amount).toFixed(2),
                parseFloat(data.p_amount).toFixed(2),
                parseFloat(data.gst_amount).toFixed(2),
                profit.toFixed(2) + ' (' + profitPercent.toFixed(2) + '%)'
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
            const excelRow = worksheet.addRow(row);
            excelRow.eachCell((cell, colNumber) => {
                // Skip styling for the first cell (date column)
                if (colNumber > 1) {
                    // Check if cell value is numeric
                    const isNumeric = !isNaN(parseFloat(cell.value)) && isFinite(cell.value);
                    if (isNumeric) {
                        // Left align numeric cells
                        cell.alignment = { horizontal: 'left' };
                    }
                }
            });
        });
    
        // Add the grand totals row and style its cells with green background and bold font
        const grandTotalRow = worksheet.addRow(grandTotals);
        grandTotalRow.eachCell((cell, colNumber) => {
            // Apply bold font to all cells in the grand totals row
            cell.font = { bold: true };
            // Skip styling for the first cell (Grand Total label)
            if (colNumber > 0) {
                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: '00FF00' } // Green background color
                };
            }
        });
    
        // Generate Excel file
        workbook.xlsx.writeBuffer().then(buffer => {
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            saveAs(blob, 'item-wise-mergin-report.xlsx');
        });
    }else{
        alertFunction();
    }
}





// export to csv
function exportToCSV() {
    if(reportData.length != 0){
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
    
        // Use the full report data instead of the paginated data
        const headers = ['Item Name', 'Category', 'Unit', 'MANUF.', 'Sale', 'Stock', 'MRP', 'Sales Amt.', 'Purchase', 'Net GST', 'Profit (%)'];
        const rows = reportData.map(data => {
            let profit = ((parseFloat(data.sales_amount) - parseFloat(data.p_amount)) - parseFloat(data.gst_amount));
            let profitPercent = (parseFloat(profit) * 100) / parseFloat(data.p_amount);
            return [
                data.item,
                data.category || '',
                data.unit || '',
                data.manuf_short_name || '',
                data.stock_out_qty,
                data.current_qty,
                parseFloat(data.mrp).toFixed(2),
                parseFloat(data.sales_amount).toFixed(2),
                parseFloat(data.p_amount).toFixed(2),
                parseFloat(data.gst_amount).toFixed(2),
                profit.toFixed(2) + ' (' + profitPercent.toFixed(2) + '%)'
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
    
        // Create CSV content
        let csvContent = '';
    
        // Add header data1 to the CSV
        headerData1.forEach(rowData => {
            csvContent += rowData.join(',') + '\n';
        });
    
        // Add header data2 to the CSV
        headerData2.forEach(rowData => {
            csvContent += rowData.join(',') + '\n';
        });
    
        // Add an empty line for spacing
        csvContent += '\n';
    
        // Add headers row to the CSV
        csvContent += headers.join(',') + '\n';
    
        // Add rows to the CSV
        rows.forEach(row => {
            csvContent += row.join(',') + '\n';
        });
    
        // Add the grand totals row to the CSV
        csvContent += grandTotals.join(',') + '\n';
    
        // Download the CSV file
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        if (link.download !== undefined) { // feature detection
            // Browsers that support HTML5 download attribute
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', 'item-wise-mergin-report.csv');
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }else{
        alertFunction();
    }
}
