// const xmlhttp = new XMLHttpRequest();

const selectedDateSpan = document.getElementById('selected-date');
const selectedDateRange = document.getElementById('selected-date-range');
const searchedItem = document.getElementById('search-by');

// data holding labels
const healthCareName = document.getElementById('healthcare-name');
const healthCareGstin = document.getElementById('healthcare-gstin');
const healthCareAddress = document.getElementById('healthcare-address');
const reportGenerationTime = document.getElementById('report-generation-date-time-holder');
const selectedStartDate = document.getElementById('selected-start-date');
const selectedEndDate = document.getElementById('selected-end-date');
const dateRangeType = document.getElementById('selected-date-range-type');

// buttons
const reset1 = document.getElementById('search-reset-1');

// dynamic table 
const itemExpiaryReportTable = document.getElementById('expiry-report-table');

// Function to handle date range type selection
function filterOnDateRangeType(t) {
    dateRangeType.innerHTML = t.value;
    initDatePicker();
}

// Function to initialize date picker based on date range type
function initDatePicker() {
    $(function() {
        function cb(start, end) {
            $('#date-range-select-div span').html(start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY'));
        }

        const commonSettings = {
            autoUpdateInput: false,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY',
                cancelLabel: 'Clear'
            },
            alwaysShowCalendars: false,
            opens: 'right'
        };

        let dateRanges, startDate, endDate;

        if (dateRangeType.innerHTML === 'ED') {
            dateRanges = {
                '30 Days': [moment().subtract(29, 'days'), moment()],
                '45 Days': [moment().subtract(44, 'days'), moment()],
                '60 Days': [moment().subtract(59, 'days'), moment()],
                '90 Days': [moment().subtract(89, 'days'), moment()],
                '120 Days': [moment().subtract(119, 'days'), moment()],
                '180 Days': [moment().subtract(179, 'days'), moment()]
            };
            startDate = moment().subtract(29, 'days');
            endDate = moment();
        } else if (dateRangeType.innerHTML === 'EG') {
            dateRanges = {
                '30 Days': [moment(), moment().add(29, 'days')],
                '45 Days': [moment(), moment().add(44, 'days')],
                '60 Days': [moment(), moment().add(59, 'days')],
                '90 Days': [moment(), moment().add(89, 'days')],
                '120 Days': [moment(), moment().add(119, 'days')],
                '180 Days': [moment(), moment().add(179, 'days')]
            };
            startDate = moment();
            endDate = moment().add(29, 'days');
        } else {
            Swal.fire('Info','Select Range Type first!','info');
            return;
        }

        $('#date-range-select-div').daterangepicker({
            ...commonSettings,
            ranges: dateRanges,
            startDate: startDate,
            endDate: endDate,
            minDate: dateRangeType.innerHTML === 'EG' ? moment() : undefined
        }, cb);

        $('#date-range-select-div').on('apply.daterangepicker', function(ev, picker) {
            cb(picker.startDate, picker.endDate);
        });

        $('#date-range-select-div').on('cancel.daterangepicker', function(ev, picker) {
            $('#date-range-select-div span').html('Select Date');
        });

        $('#date-range-select-div span').html('Select Date');
    });
}

function checkFlag(){
    if(dateRangeType.innerHTML == ''){
        Swal.fire('Error','Select report Type!','error');
    }
}

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

// convert date format
function convertDateFormat3(dateStr) {
    const [day, month, year] = dateStr.split('-');
    const newDateStr = `${day}/${month}/${year}`;

    return newDateStr;
}

// close button contorl
function resteUrl(){
    searchedItem.value = '';
    reset1.style.display = 'none';
    itemExpiryReportSearch();
}

function itemExpiryReportSearch(){
    let searchItem = '';
    let startDate = '';
    let endDate = '';
    
    if($(selectedDateSpan).text() == 'Select Date'){
        Swal.fire('Info','Select Date','info');
        return;
    }else{
        let separatedDates = separateDates($(selectedDateSpan).text());
        startDate = convertDateFormat3(separatedDates.startDate);
        endDate = convertDateFormat3(separatedDates.endDate);
        selectedStartDate.innerHTML = startDate;
        selectedEndDate.innerHTML = endDate;   
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
        searchOn: searchItem
    };

    itemExpiaryDataSearch(dataArray);
}



// item mergin data search function (ajax call)
let fullReportData = [];
function itemExpiaryDataSearch(array){

    let arryString = JSON.stringify(array);
    let salesDataReport = `ajax/itemExpiryReport.ajax.php?dataArray=${arryString}`;
    xmlhttp.open("GET", salesDataReport, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
    let report = xmlhttp.responseText;

    report = JSON.parse(report);
    if(report.status){
        itemExpiryReportShow(report.data);
        fullReportData = report.data;
    }else{
        // grandTotalShow.classList.add('d-none');
        itemExpiaryReportTable.innerHTML = '';
        fullReportData = [];
        alertFunction();
    }
}




function itemExpiryReportShow(reportData) {
    if(reportData.length != 0){
        // Pagination constants
        const rowsPerPage = 25;
        let currentPage = 1;

        function renderTable(data, page) {
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedData = data.slice(start, end);

            itemExpiaryReportTable.innerHTML = '';

            document.getElementById('download-checking').innerHTML = '1';
            // grandTotalShow.classList.remove('d-none');

            let currentDateTime = getCurrentDateTime();
            reportGenerationTime.innerHTML = currentDateTime;

            // Define headers
            const header = ['Distributor', 'GSTIN', 'Total Items', 'Total Quantity', 'Stock by PTR'];

            // Create table headers
            const thead = document.createElement('thead');
            const tr = document.createElement('tr');

            header.forEach((headerText, index) => {
                const th = document.createElement('th');
                th.textContent = headerText;
                th.style.fontWeight = 'bold'; 
            
                if (index >= 2) {
                    th.style.textAlign = 'right'; 
                }
            
                tr.appendChild(th);
            });
            thead.appendChild(tr);
            // Append the header row to the table head
            itemExpiaryReportTable.appendChild(thead);

            // Create table body
            const tbody = document.createElement('tbody');

            let distNamesList = [];
            paginatedData.forEach(data => {
                distNamesList.push(data.dist_name);
            });

            const distNames = [...new Set(distNamesList)].sort();

            let totalQantity = 0;
            let stockByPtr = 0;

            distNames.forEach(distributor => {
                let totalItems = 0;
                let distGstin = '';
                let distributorTotalQuantity = 0;
                let distributorStockByPtr = 0;

                paginatedData.forEach(data => {
                    if (distributor == data.dist_name) {
                        totalItems++;
                        totalQantity += data.current_qty;
                        stockByPtr += data.current_qty * data.item_ptr;

                        if(data.dist_gstin != ''){
                            distGstin = data.dist_gstin;
                        }else{
                            distGstin = 'No GST ID found!';
                        }

                        distributorTotalQuantity += data.current_qty;
                        distributorStockByPtr += data.current_qty * data.item_ptr;
                    }
                });

                const row = document.createElement('tr');

                const distNameCell = document.createElement('td');
                distNameCell.textContent = distributor;
                row.appendChild(distNameCell);

                const distGstinCell = document.createElement('td');
                distGstinCell.textContent = distGstin;
                row.appendChild(distGstinCell);

                const totalItemCell = document.createElement('td');
                totalItemCell.textContent = totalItems;
                totalItemCell.style.textAlign = 'right';
                row.appendChild(totalItemCell);

                const totalQantityCell = document.createElement('td');
                totalQantityCell.textContent = distributorTotalQuantity;
                totalQantityCell.style.textAlign = 'right';
                row.appendChild(totalQantityCell);

                const stockByPtrCell = document.createElement('td');
                stockByPtrCell.textContent = distributorStockByPtr.toFixed(2);
                stockByPtrCell.style.textAlign = 'right';
                row.appendChild(stockByPtrCell);

                tbody.appendChild(row);
            });

            // Append the table body to the table
            itemExpiaryReportTable.appendChild(tbody);

            // Create pagination controls
            createPaginationControls(reportData.length, page);
        }

        function createPaginationControls(totalRows, page) {
            const paginationControls = document.getElementById('pagination-controls');
            paginationControls.innerHTML = ''; // Clear previous controls

            const totalPages = Math.ceil(totalRows / rowsPerPage);

            if (totalPages > 1) {
                const paginationWrapper = document.createElement('div');
                paginationWrapper.className = 'd-flex align-items-center justify-content-center';

                // Previous button
                const prevButton = document.createElement('button');
                prevButton.innerHTML = '&#8592;'; // Left arrow
                prevButton.disabled = page === 1;
                prevButton.className = 'btn btn-link text-decoration-none text-skyblue';
                prevButton.addEventListener('click', () => {
                    currentPage--;
                    renderTable(reportData, currentPage);
                });
                paginationWrapper.appendChild(prevButton);

                if (totalPages > 3) {
                    // Show "1 of n" format after the third page
                    const pageNumber = document.createElement('span');
                    pageNumber.textContent = `${page} of ${totalPages}`;
                    pageNumber.className = `mx-1 ${'fw-bold text-primary'}`;
                    paginationWrapper.appendChild(pageNumber);
                } else {
                    // Page numbers
                    for (let i = 1; i <= totalPages; i++) {
                        const pageNumber = document.createElement('span');
                        pageNumber.textContent = i;
                        pageNumber.className = `mx-1 ${i === page ? 'fw-bold text-primary' : 'text-skyblue'}`;
                        pageNumber.style.cursor = 'pointer';
                        pageNumber.addEventListener('click', () => {
                            currentPage = i;
                            renderTable(reportData, currentPage);
                        });
                        paginationWrapper.appendChild(pageNumber);
                    }
                }

                // Next button
                const nextButton = document.createElement('button');
                nextButton.innerHTML = '&#8594;'; // Right arrow
                nextButton.disabled = page === totalPages;
                nextButton.className = 'btn btn-link text-decoration-none text-skyblue';
                nextButton.addEventListener('click', () => {
                    currentPage++;
                    renderTable(reportData, currentPage);
                });
                paginationWrapper.appendChild(nextButton);

                paginationControls.appendChild(paginationWrapper);
            }
        }

        // Initial render
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
            // downloadType.selectedIndex = 0;
        }
        if(ts.value == 'csv'){
            exportToCSV();
            // downloadType.selectedIndex = 0;
        }
        if(ts.value == 'pdf'){
            exportToPDF();
        }
    }else{
        Swal.fire('Error','generate report first!','error');
        // downloadType.selectedIndex = 0;
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
            ["Sales Summary Report : " + selectedStartDate.innerHTML + " To " + selectedEndDate.innerHTML],
            ["Report generated at : " + reportGenerationTime.innerHTML],
        ];
    
        // Define headers based on table columns
        const headers = ['Distributor', 'GSTIN', 'Total Items', 'Total Quantity', 'Stock by PTR'];
    
        // Use fullReportData for rows
        const rows = fullReportData.map(data => {
            const distName = data.dist_name || '';
            const distGstin = data.dist_gstin || 'No GST ID found!';
            const totalItems = fullReportData.filter(d => d.dist_name === distName).length;
            const totalQuantity = fullReportData.filter(d => d.dist_name === distName).reduce((sum, d) => sum + d.current_qty, 0);
            const stockByPtr = fullReportData.filter(d => d.dist_name === distName).reduce((sum, d) => sum + (d.current_qty * d.item_ptr), 0);
    
            return [distName, distGstin, totalItems, totalQuantity, stockByPtr.toFixed(2)];
        });
    
        // Remove duplicate rows based on distributor name
        const uniqueRows = Array.from(new Set(rows.map(row => row[0]))).map(distName => {
            const row = rows.find(r => r[0] === distName);
            return row;
        });
    
        // Calculate grand total for the last column
        const grandTotals = new Array(headers.length).fill('');
        grandTotals[0] = 'Total'; // Set the label for the first cell in the grand total row
        grandTotals[headers.length - 1] = uniqueRows.reduce((sum, row) => {
            const value = parseFloat(row[headers.length - 1]) || 0;
            return sum + value;
        }, 0).toFixed(2);
    
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
        uniqueRows.forEach(row => {
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
                    fgColor: { argb: 'FF0000' } // Green background color
                };
            }
        });
    
        // Generate Excel file
        workbook.xlsx.writeBuffer().then(buffer => {
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            saveAs(blob, 'expired-items-report.xlsx');
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
    
        // Define headers based on table columns
        const headers = ['Distributor', 'GSTIN', 'Total Items', 'Total Quantity', 'Stock by PTR'];
    
        // Use fullReportData for rows
        const rows = fullReportData.map(data => {
            const distName = data.dist_name || '';
            const distGstin = data.dist_gstin || 'No GST ID found!';
            const totalItems = fullReportData.filter(d => d.dist_name === distName).length;
            const totalQuantity = fullReportData.filter(d => d.dist_name === distName).reduce((sum, d) => sum + d.current_qty, 0);
            const stockByPtr = fullReportData.filter(d => d.dist_name === distName).reduce((sum, d) => sum + (d.current_qty * d.item_ptr), 0);
    
            return [distName, distGstin, totalItems, totalQuantity, stockByPtr.toFixed(2)];
        });
    
        // Remove duplicate rows based on distributor name
        const uniqueRows = Array.from(new Set(rows.map(row => row[0]))).map(distName => {
            const row = rows.find(r => r[0] === distName);
            return row;
        });
    
        // Calculate grand total for the last column
        const grandTotals = new Array(headers.length).fill('');
        grandTotals[0] = 'Total'; // Set the label for the first cell in the grand total row
        grandTotals[headers.length - 1] = uniqueRows.reduce((sum, row) => {
            const value = parseFloat(row[headers.length - 1]) || 0;
            return sum + value;
        }, 0).toFixed(2);
    
        // Convert data to CSV format
        let csvContent = '';
    
        // Add header data1 to CSV
        headerData1.forEach(rowData => {
            const mergeToColumn = headers.length > 0 ? headers.length : 1; // Adjust for header length
            csvContent += rowData[0] + ','.repeat(mergeToColumn - 1) + '\n';
        });
    
        // Add header data2 to CSV
        headerData2.forEach(rowData => {
            const mergeToColumn = headers.length > 0 ? headers.length : 1; // Adjust for header length
            csvContent += rowData[0] + ','.repeat(mergeToColumn - 1) + '\n';
        });
    
        // Add an empty row for spacing
        csvContent += '\n';
    
        // Add headers row to CSV
        csvContent += headers.join(',') + ',Total\n';
    
        // Add rows to CSV
        uniqueRows.forEach(row => {
            csvContent += row.join(',') + '\n';
        });
    
        // Add the grand totals row
        csvContent += grandTotals.join(',') + '\n';
    
        // Generate CSV file
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8' });
        saveAs(blob, 'expired-items-report.csv');
    }else{
        alertFunction();
    }
}


