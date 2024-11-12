
// const xmlhttp = new XMLHttpRequest();

// TABLE CONSTANT
const dataTable = document.getElementById('report-table');

// DIV CONSTANT
const reportTypeFilter = document.getElementById('day-filter');  // primary filter select class
const dateRangeSelect = document.getElementById('date-range'); // date range select class
const categoryFilter = document.getElementById('category-filter'); // primary filter category select
const datePickerDiv = document.getElementById('dtPickerDiv');
const additionalFilterDiv = document.getElementById('extraFilterDiv');
const productCategorySelectDiv = document.getElementById('prod-category-select-div'); // item category select div secondary filter
const paymentModeDiv = document.getElementById('payment-mode-div'); // payment mode select div secondary filter
const staffFilterDiv = document.getElementById('staff-filter-div'); // staff select div secondary filter
const reportFilterDiv = document.getElementById('report-filter-div'); // report generation on primary filter div
const dateRangeSelectDiv = document.getElementById('date-range-select-div');
const inputedDateRangeDiv = document.getElementById('inputed-date-range-div');

// BUTTONS
const productCategoryBtn = document.getElementById('prod-category'); // item category select button
const staffFilter = document.getElementById('staff-filter'); // staff select dropdown button

// FILTERS
const paymentMode = document.getElementById('payment-mode'); // payment mode select class
const salesReportOn = document.getElementById('sales-report-on'); // report generation on primary filter select
const checkSelectAdditionalFilter = document.getElementById('extra-filter-check');
const selectedAdditionalFilter = document.getElementById('selected-additional-fiter');

// FILTER CHECK BOX
const filterCheckbox1 = document.getElementById('bill-date-checked-check-box');

/// constand default data holders .........
const downloadType = document.getElementById('download-file-type');
const dayFilterVal = document.getElementById('day-filter-val');
const dateRangeVal = document.getElementById('dt-rng-val');
const filterByVal = document.getElementById('filter-by-val');
const filterByProdCategoryIdVal = document.getElementById('filter-by-prod-categoty-id-val');
const filterByProdCategoryNameVal = document.getElementById('filter-by-prod-categoty-name');
const filterByPaymentModeVal = document.getElementById('filter-by-payment-mode-val');
const filterByStaffName = document.getElementById('filter-by-staff-name');
const filterByStaffId = document.getElementById('filter-by-staff-id');
const reportFilterVal = document.getElementById('report-filter-val');
const selectedStartDate = document.getElementById('selected-start-date');
const selectedEndDate = document.getElementById('selected-end-date');
const inputedDateRange = document.getElementById('inputed-date-range');
const healthCareName = document.getElementById('healthcare-name');
const healthCareGstin = document.getElementById('healthcare-gstin');
const healthCareAddress = document.getElementById('healthcare-address');
const reportGenerationTime = document.getElementById('report-generation-date-time-holder');

/// dropdown inner html constant
const paymentModeConst = document.getElementById('payment-mode-select-span');

/// all staff data on admin 
const allCurrentStaffNameOnAdmin = document.getElementById('all-stuff-name-data');
const allCurrentStaffIdOnAdmin = document.getElementById('all-stuff-id-data');


// string slice function based on ','.....
function slicedString(string){
    let strToArr = [];
    strToArr = string.split(',');
    return strToArr;
}

//// temp data holder
let tempStartDate = '';
let tempEndDate = '';

// current date time
function getCurrentDateTime() {
    const currentDate = new Date();
    const padZero = (num) => String(num).padStart(2, '0');

    const day = padZero(currentDate.getDate());
    const month = padZero(currentDate.getMonth() + 1); // January is 0!
    const year = currentDate.getFullYear();
    const hours = padZero(currentDate.getHours());
    const minutes = padZero(currentDate.getMinutes());
    const seconds = padZero(currentDate.getSeconds());

    return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
}

// date range modified function 
function formatDate(dateString) {
    const [year, month, day] = dateString.split('-');
    return `${day.padStart(2, '0')}-${month.padStart(2, '0')}-${year}`;
}

// minus date calculation
function calculateDate(days) {
    const currentDate = new Date();
    const newDate = new Date(currentDate);
    newDate.setDate(currentDate.getDate() - parseInt(days, 10));
    
    const day = String(newDate.getDate()).padStart(2, '0');
    const month = String(newDate.getMonth() + 1).padStart(2, '0');
    const year = newDate.getFullYear();
    
    return `${day}/${month}/${year}`;
}

// date format convertion
function convertDateFormat2(dateStr) {
    return dateStr.split('/').join('-');
}

function convertDatePickerDateFormat(dateStr) {
    const [year, month, day] = dateStr.split('-');
    return `${day}/${month}/${year}`;
}

// day filter function
function dayFilter(t){
    dayFilterVal.innerHTML = t.value;
}

// date range select function
function dateRangeFilter(t) {
    dateRangeVal.innerHTML = t.value;

    const date = new Date();
    const year = date.getFullYear();
    let fiscalYr, startDt, endDt;

    const rangeMapping = {
        'T': { daysAgo: 0 },
        'Y': { daysAgo: 1 },
        'LW': { daysAgo: 7 },
        'LM': { daysAgo: 30 },
        'LQ': { daysAgo: 90 },
    };

    const fiscalYearMapping = {
        'CFY': { startYear: year, endYear: year + 1 },
        'PFY': { startYear: year - 1, endYear: year },
    };

    if (t.value === 'SDR') {
        datePickerDiv.style.display = 'block';
    } else {
        datePickerDiv.style.display = 'none';

        if (rangeMapping[t.value]) {
            const { daysAgo } = rangeMapping[t.value];
    
            let startDate = '';
            let endDate = '';
            if(daysAgo == 1){
                startDate = calculateDate(daysAgo);
                endDate = calculateDate(0);
            }else{
                startDate = calculateDate(daysAgo);
                endDate = calculateDate(0);
            }
            

            updateDateRange(startDate, endDate);
        } else if (fiscalYearMapping[t.value]) {
            const { startYear, endYear } = fiscalYearMapping[t.value];
            startDt = `01/04/${startYear}`;
            endDt = `31/03/${endYear}`;

            updateDateRange(startDt, endDt);
        }
    }

    function updateDateRange(startDate, endDate) {
        selectedStartDate.innerHTML = convertDateFormat2(startDate);
        selectedEndDate.innerHTML = convertDateFormat2(endDate);
        inputedDateRange.value = `  ${startDate} - ${endDate}`;
        inputedDateRangeDiv.classList.remove('d-none');
        dateRangeSelectDiv.classList.add('d-none');
    }
}


// Date picker functions
function selectStartDate(t) {
    updateDateRangeDisplay(t.value, 'start');
}

function selectEndDate(t) {
    updateDateRangeDisplay(t.value, 'end');
    finalizeDateRange();
}

function updateDateRangeDisplay(dateValue, type) {
    const formattedDate = convertDateFormat2(convertDatePickerDateFormat(dateValue));

    if (type === 'start') {
        selectedStartDate.innerHTML = formattedDate;
        tempStartDate = convertDatePickerDateFormat(dateValue);
    } else if (type === 'end') {
        selectedEndDate.innerHTML = formattedDate;
        tempEndDate = convertDatePickerDateFormat(dateValue);
    }
}

function finalizeDateRange() {
    inputedDateRange.value = `  ${tempStartDate} - ${tempEndDate}`;
    inputedDateRangeDiv.classList.remove('d-none');
    dateRangeSelectDiv.classList.add('d-none');
    datePickerDiv.style.display = 'none';
}

// Date range select div reset function
function dateRangeReset() {
    inputedDateRangeDiv.classList.add('d-none');
    dateRangeSelectDiv.classList.remove('d-none');
}


// category select filter
function categoryFilterSelect(t) {
    filterByVal.innerHTML = t.value;
    const mappings = {
        'ICAT': [productCategorySelectDiv, reportFilterDiv],
        'PM': [paymentModeDiv, reportFilterDiv],
        'STF': [staffFilterDiv, reportFilterDiv]
    };

    // Hide all divs initially
    [productCategorySelectDiv, paymentModeDiv, staffFilterDiv, reportFilterDiv].forEach(div => div.classList.add('d-none'));

    // Show the relevant divs based on the selected value
    if (mappings[t.value]) {
        mappings[t.value].forEach(div => div.classList.remove('d-none'));
    }
}

// Common function to handle checkbox toggles
function handleCheckboxToggle(source, allCheckboxSelector, btnElement, filterIdElement, filterNameElement, idMap, nameMap) {
    const checkboxes = document.querySelectorAll(allCheckboxSelector);

    if (source.id === idMap.all) {
        checkboxes.forEach(checkbox => {
            if (!checkbox.disabled) {
                checkbox.checked = source.checked;
            }
        });

        if (source.checked) {
            btnElement.innerHTML = nameMap.all;
            filterIdElement.innerHTML = idMap.allIds;
            filterNameElement.innerHTML = nameMap.allNames;
        } else {
            btnElement.innerHTML = nameMap.select;
            filterIdElement.innerHTML = '';
            filterNameElement.innerHTML = '';
        }
    } else {
        const idsArray = filterIdElement.innerHTML.split(',').filter(Boolean);
        const namesArray = filterNameElement.innerHTML.split(',').filter(Boolean);

        if (source.checked) {
            idsArray.push(source.value);
            namesArray.push(source.id);
        } else {
            const idx = idsArray.indexOf(source.value);
            if (idx !== -1) {
                idsArray.splice(idx, 1);
                namesArray.splice(idx, 1);
            }
            document.getElementById(idMap.all).checked = false;
        }

        filterIdElement.innerHTML = idsArray.join(',');
        filterNameElement.innerHTML = namesArray.join(',');

        if (namesArray.length === checkboxes.length - 1) {
            btnElement.innerHTML = nameMap.all;
        } else if (namesArray.length === 0) {
            btnElement.innerHTML = nameMap.select;
        } else {
            btnElement.innerHTML = namesArray.join(',');
        }
    }
}

// item category selection function
function toggleCheckboxes1(source) {
    handleCheckboxToggle(source, '.item-category-select-checkbox-menu input[type="checkbox"]', productCategoryBtn, filterByProdCategoryIdVal, filterByProdCategoryNameVal, {
        all: 'ac-chkBx',
        allIds: '1,2,3,4,5,6,7,8'
    }, {
        all: 'All Category',
        allNames: 'Allopathy,Ayurvedic,Cosmetic,Drug,Generic,Nutraceuticals,OTC,Surgical',
        select: 'Select Item Category'
    });
}

// payment mode selection function
function toggleCheckboxes2(source) {
    handleCheckboxToggle(source, '.payment-mode-checkbox-menu input[type="checkbox"]', paymentModeConst, filterByPaymentModeVal, filterByPaymentModeVal, {
        all: 'apm-chkBx',
        allIds: 'Card,Cash,Credit,UPI'
    }, {
        all: 'All Payment Mode',
        allNames: 'Card,Cash,Credit,UPI',
        select: 'Select Payment Mode'
    });
}

// staff selection function
function toggleCheckboxes3(source) {
    handleCheckboxToggle(source, '.staff-list-checkbox-menu input[type="checkbox"]', staffFilter, filterByStaffId, filterByStaffName, {
        all: 'stf-chkBx',
        allIds: allCurrentStaffIdOnAdmin.innerHTML
    }, {
        all: 'All Staff',
        allNames: allCurrentStaffNameOnAdmin.innerHTML,
        select: 'Select Staff'
    });
}


// filter report on function
function filterReportOn(t){
    if(t.value == 'TS'){
        reportFilterVal.innerHTML = 'Total Sell';
    }

    if(t.value == 'TM'){
        reportFilterVal.innerHTML = 'Total Margin';
    }

    if(t.value == 'TD'){
        reportFilterVal.innerHTML = 'Total Discount';
    }
}

function additionalCheckBoxFunction(){
    if(filterCheckbox1.checked == true){
        return 1;
    }else{
        return 0;
    }
}


function emptyAlert(num){
    if(num == 0){
        Swal.fire('Alert','No data found','info');
    }else{
        Swal.fire('Alert','Generate Proper report first!','info');
    }
}


// sales data search call (funning ajax query)
function salesSummerySearch() {
    const dtFilter = dayFilterVal.innerHTML;
    const startDate = selectedStartDate.innerHTML;
    const endDate = selectedEndDate.innerHTML;
    let searchString = '';
    let additionalFilter1 = dtFilter != '0' ? 0 : additionalCheckBoxFunction();

    // Validate date range
    if (!dateRangeVal.innerHTML) {
        Seal.fire('Check','Select date range','info');
        return;
    }

    // Validate primary filter
    const primaryFilter = filterByVal.innerHTML;
    if (!primaryFilter) {
        // alert('Select filter value');
        Seal.fire('Check','Select filter value','info');
        return;
    }

    // Handle secondary filters based on primary filter
    switch (primaryFilter) {
        case 'ICAT':
            if (!filterByProdCategoryIdVal.innerHTML && !filterByProdCategoryNameVal.innerHTML) {
                // alert('Select item category value');
                Seal.fire('Check','Select item category value','info');
                return;
            }
            searchString = filterByProdCategoryNameVal.innerHTML;
            break;
        case 'PM':
            if (!filterByPaymentModeVal.innerHTML) {
                // alert('Select payment mode');
                Seal.fire('Check','Select payment mode','info');
                return;
            }
            searchString = filterByPaymentModeVal.innerHTML;
            break;
        case 'STF':
            if (!filterByStaffId.innerHTML) {
                // alert('Select staff');
                Seal.fire('Check','Select staff','info');
                return;
            }
            searchString = filterByStaffId.innerHTML;
            break;
    }

    // Validate report filter
    if (!reportFilterVal.innerHTML) {
        Seal.fire('Check','Select report filter','info');
        // alert('Select report filter');
        return;
    }

    const dataArray = {
        datefilter: dtFilter,
        searchOn: searchString,
        startDt: convertDateFormat(startDate, 'YYYY-MM-DD'),
        endDt: convertDateFormat(endDate, 'YYYY-MM-DD'),
        filterBy: primaryFilter,
        additionalFilter1: additionalFilter1
    };

    // console.log(dataArray);
    
    salesDataSearchFunction(dataArray);
}

// salse data search function (ajax call)
let reportData = '';
function salesDataSearchFunction(array) {
    const arryString = encodeURIComponent(JSON.stringify(array));
    const salesDataReportUrl = `ajax/salesSummeryReport.ajax.php?dataArray=${arryString}`;
    
    // const xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", salesDataReportUrl, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);
    
    const report = JSON.parse(xmlhttp.responseText);
        
    if (report.status) {
        salesReportShow(report.data);
        reportData = report.data;
    } else {
        reportData = [];
        dataTable.innerHTML = '';
        emptyAlert(0);
    }

}

// sales report show
function salesReportShow(parsedData) {
    document.getElementById('download-checking').innerHTML = '1';
    dataTable.innerHTML = '';

    let currentDateTime = getCurrentDateTime();
    reportGenerationTime.innerHTML = currentDateTime;

    // Define Headers
    const headerStart1a = ['Date'];
    const headerStart1b = ['Date', 'Bill Date'];
    const headerStart2 = ['Start Date', 'End Date'];
    const headerEnd1 = ['Total Sell'];
    const headerEnd2 = ['Total Margin'];
    const headerEnd3 = ['Total Discount'];
    
    let headerStart;
    let headerStartFlag = 0;
    if (parsedData.some(item => item.hasOwnProperty('bil_dt'))) {
        headerStart = headerStart1b;
        headerStartFlag = 1;
    } else {
        headerStart = headerStart1a;
    }
    
    if (dayFilterVal.innerHTML != 0) {
        headerStart = headerStart2;
    }

    let headerMid = [];
    if (filterByVal.innerHTML === 'ICAT') {
        headerMid = slicedString(filterByProdCategoryNameVal.innerHTML);
    } else if (filterByVal.innerHTML === 'PM') {
        headerMid = slicedString(filterByPaymentModeVal.innerHTML);
    } else if (filterByVal.innerHTML === 'STF') {
        headerMid = slicedString(filterByStaffName.innerHTML);
    }
    headerMid = [...new Set(headerMid)].sort();

    let headerEnd;
    switch (reportFilterVal.innerHTML) {
        case 'Total Sell':
            headerEnd = headerEnd1;
            break;
        case 'Total Margin':
            headerEnd = headerEnd2;
            break;
        case 'Total Discount':
            headerEnd = headerEnd3;
            break;
    }

    const headers = [...headerStart, ...headerMid, ...headerEnd];

    function renderTable(data, page, rowsPerPage) {
        dataTable.innerHTML = '';

        const startIndex = (page - 1) * rowsPerPage;
        const endIndex = Math.min(startIndex + rowsPerPage, data.length);
        const paginatedData = data.slice(startIndex, endIndex);

        // Create the table
        const thead = document.createElement('thead');
        const tr = document.createElement('tr');
        headers.forEach(headerText => {
            const th = document.createElement('th');
            th.textContent = headerText;
            th.style.fontWeight = 'bold';
            tr.appendChild(th);
        });
        thead.appendChild(tr);

        const grandTotalRow = document.createElement('tr');
        grandTotalRow.classList.add('grand-total');
        grandTotalRow.style.fontWeight = 'bold';

        const grandTotals = headers.reduce((acc, header) => {
            acc[header] = 0;
            return acc;
        }, {});

        paginatedData.forEach(data => {
            headers.forEach(header => {
                if (headerMid.includes(header)) {
                    if (filterByVal.innerHTML === 'ICAT' && data.category_name === header) {
                        updateGrandTotals(grandTotals, header, data);
                    } else if (filterByVal.innerHTML === 'PM' && data.payment_mode === header) {
                        updateGrandTotals(grandTotals, header, data);
                    } else if (filterByVal.innerHTML === 'STF' && data.added_by_name === header) {
                        updateGrandTotals(grandTotals, header, data);
                    }
                } else if (header === 'Total Sell') {
                    grandTotals[header] += parseFloat(data.total_stock_out_amount || 0);
                } else if (header === 'Total Margin') {
                    grandTotals[header] += parseFloat(data.total_sales_margin || 0);
                } else if (header === 'Total Discount') {
                    grandTotals[header] += parseFloat(data.total_discount || 0);
                }
            });
        });

        headers.forEach(header => {
            const cell = document.createElement('td');
            cell.textContent = ['Date', 'Bill Date', 'Start Date', 'End Date'].includes(header)
                ? ''
                : `₹${grandTotals[header].toFixed(2)}`;
            cell.className = 'total-cell';
            grandTotalRow.appendChild(cell);
        });

        thead.appendChild(grandTotalRow);
        dataTable.appendChild(thead);

        const tbody = document.createElement('tbody');
        const groupedData = groupDataByKey(paginatedData);

        for (let groupKey in groupedData) {
            const group = groupedData[groupKey];
            const row = createRows(group, groupKey, headers);
            tbody.appendChild(row);
        }
        dataTable.appendChild(tbody);
    }

    function updateGrandTotals(grandTotals, header, data) {
        if (reportFilterVal.innerHTML === 'Total Sell') {
            grandTotals[header] += parseFloat(data.total_stock_out_amount || 0);
        } else if (reportFilterVal.innerHTML === 'Total Margin') {
            grandTotals[header] += parseFloat(data.total_sales_margin || 0);
        } else if (reportFilterVal.innerHTML === 'Total Discount') {
            grandTotals[header] += parseFloat(data.total_discount || 0);
        }
    }

    function groupDataByKey(data) {
        const keyFn = dayFilterVal.innerHTML == 0
            ? item => item.added_on
            : dayFilterVal.innerHTML == 1
                ? item => item.start_date + ' to ' + item.end_date
                : item => `${item.year}-${item.month}`;

        return data.reduce((acc, item) => {
            const key = keyFn(item);
            if (!acc[key]) {
                acc[key] = [];
            }
            acc[key].push(item);
            return acc;
        }, {});
    }

    function createRows(groupData, groupKey, headers) {
        const tr = document.createElement('tr');
        let rowData = {};

        if (dayFilterVal.innerHTML == 0) {
            if(headerStartFlag == 0){
                rowData['Date'] = formatDate(groupKey);
            }
            if(headerStartFlag == 1){
                rowData['Date'] = formatDate(groupKey);
                rowData['Bill Date'] = formatDate(groupData[0].bil_dt);
            }
            
        } else if (dayFilterVal.innerHTML == 1) {
            const [startDate, endDate] = groupKey.split(' to ');
            rowData['Start Date'] = formatDate(startDate);
            rowData['End Date'] = formatDate(endDate);
        } else if (dayFilterVal.innerHTML == 2) {
            rowData['Start Date'] = formatDate(groupData[0].start_date);
            rowData['End Date'] = formatDate(groupData[0].end_date);
        }

        let totalSellAmount = 0, totalMargin = 0, totalDiscount = 0;
        headerMid.forEach(header => {
            rowData[header] = 0.00;
        });

        groupData.forEach(data => {
            if (filterByVal.innerHTML === 'ICAT' && headerMid.includes(data.category_name)) {
                updateRowData(rowData, data.category_name, data);
                totalSellAmount += parseFloat(data.total_stock_out_amount || 0);
                totalMargin += parseFloat(data.total_sales_margin || 0);
                totalDiscount += parseFloat(data.total_discount || 0);
            } else if (filterByVal.innerHTML === 'PM' && headerMid.includes(data.payment_mode)) {
                updateRowData(rowData, data.payment_mode, data);
                totalSellAmount += parseFloat(data.total_stock_out_amount || 0);
                totalMargin += parseFloat(data.total_sales_margin || 0);
                totalDiscount += parseFloat(data.total_discount || 0);
            } else if (filterByVal.innerHTML === 'STF' && headerMid.includes(data.added_by_name)) {
                updateRowData(rowData, data.added_by_name, data);
                totalSellAmount += parseFloat(data.total_stock_out_amount || 0);
                totalMargin += parseFloat(data.total_sales_margin || 0);
                totalDiscount += parseFloat(data.total_discount || 0);
            } else {
                if (reportFilterVal.innerHTML === 'Total Sell') {
                    totalSellAmount += parseFloat(data.total_stock_out_amount || 0);
                } else if (reportFilterVal.innerHTML === 'Total Margin') {
                    totalMargin += parseFloat(data.total_sales_margin || 0);
                } else if (reportFilterVal.innerHTML === 'Total Discount') {
                    totalDiscount += parseFloat(data.total_discount || 0);
                }
            }
        });

        rowData['Total Sell'] = totalSellAmount.toFixed(2);
        rowData['Total Margin'] = totalMargin.toFixed(2);
        rowData['Total Discount'] = totalDiscount.toFixed(2);

        headers.forEach(header => {
            const td = document.createElement('td');
            td.textContent = rowData[header] !== undefined ? `₹${rowData[header]}` : '';
            tr.appendChild(td);
        });

        return tr;
    }

    function updateRowData(rowData, key, data) {
        if (reportFilterVal.innerHTML === 'Total Sell') {
            rowData[key] += parseFloat(data.total_stock_out_amount || 0);
        } else if (reportFilterVal.innerHTML === 'Total Margin') {
            rowData[key] += parseFloat(data.total_sales_margin || 0);
        } else if (reportFilterVal.innerHTML === 'Total Discount') {
            rowData[key] += parseFloat(data.total_discount || 0);
        }
    }

    function formatDate(date) {
        const [year, month, day] = date.split('-');
        return `${day}-${month}-${year}`;
    }

    // Pagination setup
    const rowsPerPage = 25; // Number of rows per page
    const totalRows = parsedData.length;
    let currentPage = 1;

    function onPageChange(page) {
        currentPage = page;
        renderTable(parsedData, currentPage, rowsPerPage);
        document.getElementById('pagination-controls').innerHTML = '';
        document.getElementById('pagination-controls').appendChild(createPaginationControls(totalRows, currentPage, rowsPerPage, onPageChange));
    }
    onPageChange(currentPage);
}





// download file format selection function
function selectDownloadType(ts){
    if(document.getElementById('download-checking').innerHTML == '1'){
        if(ts.value == 'exl'){
            exportToExcel(reportData);
            downloadType.selectedIndex = 0;
        }
        if(ts.value == 'csv'){
            exportToCSV(reportData);
            downloadType.selectedIndex = 0;
        }
        
    }else{
        emptyAlert(0);
        downloadType.selectedIndex = 0;
    }
}

// Function for export the table data to Excel
function exportToExcel(parsedData) {
    if(parsedData.length != 0){
        const masterHeaderData1 = [
            [healthCareName.innerHTML],];
        
        const masterHeaderData2 = [
            [healthCareAddress.innerHTML],
            ["GSTIN : " + healthCareGstin.innerHTML],
            [],
            ["Sales Summary Report : " + selectedStartDate.innerHTML + " To " + selectedEndDate.innerHTML],
            ["Report generated at : " + reportGenerationTime.innerHTML],
            []
        ];
    
       
        // Create a new workbook and a worksheet
        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet('Sales Report');
    
        let currentDateTime = getCurrentDateTime();
    
        // Define Headers
        const headerStart1a = ['Date'];
        const headerStart1b = ['Date', 'Bill Date'];
        let headerStart1;
        parsedData.forEach(item => {
            if (item.hasOwnProperty('bil_dt')) {
                headerStart1 = headerStart1b;
            } else {
                headerStart1 = headerStart1a;
            }
        });
        const headerStart2 = ['Start Date', 'End Date'];
        const headerEnd1 = ['Total Sell'];
        const headerEnd2 = ['Total Margin'];
        const headerEnd3 = ['Total Discount'];
    
        let headerStart = [];
        let headerMid = [];
        let headerEnd = [];
    
        if (dayFilterVal.innerHTML == 0) {
            headerStart = headerStart1;
        } else {
            headerStart = headerStart2;
        }
    
        if (filterByVal.innerHTML === 'ICAT') {
            headerMid = slicedString(filterByProdCategoryNameVal.innerHTML);
        } else if (filterByVal.innerHTML === 'PM') {
            headerMid = slicedString(filterByPaymentModeVal.innerHTML);
        } else if (filterByVal.innerHTML === 'STF') {
            headerMid = slicedString(filterByStaffName.innerHTML);
        }
    
        if (reportFilterVal.innerHTML === 'Total Sell') {
            headerEnd = headerEnd1;
        } else if (reportFilterVal.innerHTML === 'Total Margin') {
            headerEnd = headerEnd2;
        } else if (reportFilterVal.innerHTML === 'Total Discount') {
            headerEnd = headerEnd3;
        }
    
        headerMid = [...new Set(headerMid)].sort();
        const headers = headerStart.concat(headerMid).concat(headerEnd);
    
        // Add header data1 to the worksheet with merged cells, center alignment, and specified font
        let currentRow = 1; 
    
        masterHeaderData1.forEach(rowData => {
            const mergeToColumn = headers.length;
            worksheet.mergeCells(`A${currentRow}:${String.fromCharCode(65 + mergeToColumn - 1)}${currentRow}`);
            const mergedCell = worksheet.getCell(`A${currentRow}`);
            mergedCell.value = rowData[0];
            mergedCell.alignment = { horizontal: 'center' }; // Center align the content
            mergedCell.font = { size: 14, bold: true }; // Set font size to 14 and bold
            currentRow++;
        });
    
        // Add header data2 to the worksheet with merged cells and center alignment
        masterHeaderData2.forEach(rowData => {
            const mergeToColumn = headers.length;
            worksheet.mergeCells(`A${currentRow}:${String.fromCharCode(65 + mergeToColumn - 1)}${currentRow}`);
            const mergedCell = worksheet.getCell(`A${currentRow}`);
            mergedCell.value = rowData[0];
            mergedCell.alignment = { horizontal: 'center' }; // Center align the content
            currentRow++;
        });
    
    
        // Add headers to the worksheet with color
        const headerRow = worksheet.addRow(headers);
        headerRow.eachCell(cell => {
            cell.font = { bold: true };
            cell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: { argb: 'FFFF00' } // Yellow
            };
        });
    
        // Group data and add to worksheet
        const groupDataByKey = (data, keyFn) => {
            return data.reduce((acc, item) => {
                const key = keyFn(item);
                if (!acc[key]) {
                    acc[key] = [];
                }
                acc[key].push(item);
                return acc;
            }, {});
        };
    
        let groupedData;
        if (dayFilterVal.innerHTML == 0) {
            groupedData = groupDataByKey(parsedData, item => item.added_on);
        } else if (dayFilterVal.innerHTML == 1) {
            groupedData = groupDataByKey(parsedData, item => item.start_date + ' to ' + item.end_date);
        } else if (dayFilterVal.innerHTML == 2) {
            groupedData = groupDataByKey(parsedData, item => `${item.year}-${item.month}`);
        }
    
        const createRows = (groupData, groupKey, headers) => {
            let rowData = {};
    
            if (dayFilterVal.innerHTML == 0) {
                rowData['Date'] = formatDate(groupKey);
            } else if (dayFilterVal.innerHTML == 1) {
                const [startDate, endDate] = groupKey.split(' to ');
                rowData['Start Date'] = formatDate(startDate);
                rowData['End Date'] = formatDate(endDate);
            } else if (dayFilterVal.innerHTML == 2) {
                const [year, month] = groupKey.split('-');
                rowData['Start Date'] = formatDate(groupData[0].start_date);
                rowData['End Date'] = formatDate(groupData[0].end_date);
            }
    
            let totalSellAmount = 0, totalMargin = 0, totalDiscount = 0;
    
            headerMid.forEach(header => {
                rowData[header] = 0.00;
            });
    
            groupData.forEach(data => {
                if (filterByVal.innerHTML === 'ICAT' && headerMid.includes(data.category_name)) {
                    let amount = 0;
                    if (reportFilterVal.innerHTML === 'Total Sell') {
                        amount = parseFloat(data.total_stock_out_amount || 0);
                    } else if (reportFilterVal.innerHTML === 'Total Margin') {
                        amount = parseFloat(data.total_sales_margin || 0);
                    } else if (reportFilterVal.innerHTML === 'Total Discount') {
                        amount = parseFloat(data.total_discount || 0);
                    }
    
                    rowData[data.category_name] += amount;
                    totalSellAmount += amount;
                    totalMargin += parseFloat(data.total_sales_margin || 0);
                    totalDiscount += parseFloat(data.total_discount || 0);
                } else if (filterByVal.innerHTML === 'PM' && headerMid.includes(data.payment_mode)) {
                    let amount = 0;
                    if (reportFilterVal.innerHTML === 'Total Sell') {
                        amount = parseFloat(data.total_stock_out_amount || 0);
                    } else if (reportFilterVal.innerHTML === 'Total Margin') {
                        amount = parseFloat(data.total_sales_margin || 0);
                    } else if (reportFilterVal.innerHTML === 'Total Discount') {
                        amount = parseFloat(data.total_discount || 0);
                    }
    
                    rowData[data.payment_mode] += amount;
                    totalSellAmount += amount;
                    totalMargin += parseFloat(data.total_sales_margin || 0);
                    totalDiscount += parseFloat(data.total_discount || 0);
                } else if (filterByVal.innerHTML === 'STF' && headerMid.includes(data.added_by_name)) {
                    let amount = 0;
                    if (reportFilterVal.innerHTML === 'Total Sell') {
                        amount = parseFloat(data.total_stock_out_amount || 0);
                    } else if (reportFilterVal.innerHTML === 'Total Margin') {
                        amount = parseFloat(data.total_sales_margin || 0);
                    } else if (reportFilterVal.innerHTML === 'Total Discount') {
                        amount = parseFloat(data.total_discount || 0);
                    }
    
                    rowData[data.added_by_name] += amount;
                    totalSellAmount += amount;
                    totalMargin += parseFloat(data.total_sales_margin || 0);
                    totalDiscount += parseFloat(data.total_discount || 0);
                }
            });
    
            if (reportFilterVal.innerHTML === 'Total Sell') {
                rowData['Total Sell'] = totalSellAmount;
            } else if (reportFilterVal.innerHTML === 'Total Margin') {
                rowData['Total Margin'] = totalMargin;
            } else if (reportFilterVal.innerHTML === 'Total Discount') {
                rowData['Total Discount'] = totalDiscount;
            }
    
            return rowData;
        };
    
        Object.entries(groupedData).forEach(([groupKey, groupData]) => {
            const rowData = createRows(groupData, groupKey, headers);
            const dataRow = [];
            headers.forEach(header => {
                if (typeof rowData[header] === 'number') {
                    dataRow.push(`₹${rowData[header].toFixed(2)}`);
                } else {
                    dataRow.push(rowData[header] || '');
                }
            });
            worksheet.addRow(dataRow);
        });
    
        // Calculate and append the grand total row
        const grandTotals = {};
        headers.forEach(header => {
            grandTotals[header] = 0;
        });
    
        headers.forEach(header => {
            if (header !== 'Date' && header !== 'Bill Date' && header !== 'Start Date' && header !== 'End Date') {
                parsedData.forEach(data => {
                    if (headerMid.includes(header)) {
                        if (filterByVal.innerHTML === 'ICAT' && data.category_name === header) {
                            if (reportFilterVal.innerHTML === 'Total Sell') {
                                grandTotals[header] += parseFloat(data.total_stock_out_amount || 0);
                            } else if (reportFilterVal.innerHTML === 'Total Margin') {
                                grandTotals[header] += parseFloat(data.total_sales_margin || 0);
                            } else if (reportFilterVal.innerHTML === 'Total Discount') {
                                grandTotals[header] += parseFloat(data.total_discount || 0);
                            }
                        } else if (filterByVal.innerHTML === 'PM' && data.payment_mode === header) {
                            if (reportFilterVal.innerHTML === 'Total Sell') {
                                grandTotals[header] += parseFloat(data.total_stock_out_amount || 0);
                            } else if (reportFilterVal.innerHTML === 'Total Margin') {
                                grandTotals[header] += parseFloat(data.total_sales_margin || 0);
                            } else if (reportFilterVal.innerHTML === 'Total Discount') {
                                grandTotals[header] += parseFloat(data.total_discount || 0);
                            }
                        } else if (filterByVal.innerHTML === 'STF' && data.added_by_name === header) {
                            if (reportFilterVal.innerHTML === 'Total Sell') {
                                grandTotals[header] += parseFloat(data.total_stock_out_amount || 0);
                            } else if (reportFilterVal.innerHTML === 'Total Margin') {
                                grandTotals[header] += parseFloat(data.total_sales_margin || 0);
                            } else if (reportFilterVal.innerHTML === 'Total Discount') {
                                grandTotals[header] += parseFloat(data.total_discount || 0);
                            }
                        }
                    } else if (header === 'Total Sell') {
                        grandTotals[header] += parseFloat(data.total_stock_out_amount || 0);
                    } else if (header === 'Total Margin') {
                        grandTotals[header] += parseFloat(data.total_sales_margin || 0);
                    } else if (header === 'Total Discount') {
                        grandTotals[header] += parseFloat(data.total_discount || 0);
                    }
                });
            }
        });
    
        const grandTotalRow = [];
        headers.forEach(header => {
            if (header !== 'Date' && header !== 'Bill Date' && header !== 'Start Date' && header !== 'End Date') {
                grandTotalRow.push(`₹${grandTotals[header].toFixed(2)}`);
            }
        });
        const grandTotalRowExcel = worksheet.addRow(['Grand Total', ...grandTotalRow]);
    
        // Style the grand total row
        grandTotalRowExcel.font = { bold: true };
        grandTotalRowExcel.eachCell({ includeEmpty: true }, (cell, colNumber) => {
            const header = headers[colNumber - 1];
            if (header.includes(header)) {
                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: '90EE90' } // Light Green
                };
            }
        });
    
        // Save the Excel file
        workbook.xlsx.writeBuffer().then(buffer => {
            saveAs(new Blob([buffer]), `SalesReport_${currentDateTime}.xlsx`);
        });
    }else{
        emptyAlert(1);
    }
}

// Function for export the table data to CSV
function exportToCSV(parsedData) {
    // Define header data
    if(parsedData.length != 0){
        const headerData1 = [healthCareName.innerHTML];
        const headerData2 = [
            healthCareAddress.innerHTML,
            "GSTIN : " + healthCareGstin.innerHTML,
            "",
            "Sales Summary Report : " + selectedStartDate.innerHTML + " To " + selectedEndDate.innerHTML,
            "Report generated at : " + reportGenerationTime.innerHTML,
            ""
        ];

        // Define headers from the data table
        const headerStart1a = ['Date'];
        const headerStart1b = ['Date', 'Bill Date'];
        let headerStart1;
        parsedData.forEach(item => {
            if (item.hasOwnProperty('bil_dt')) {
                headerStart1 = headerStart1b;
            } else {
                headerStart1 = headerStart1a;
            }
        });
        const headerStart2 = ['Start Date', 'End Date'];
        const headerEnd1 = ['Total Sell'];
        const headerEnd2 = ['Total Margin'];
        const headerEnd3 = ['Total Discount'];

        let headerStart = [];
        let headerMid = [];
        let headerEnd = [];

        if (dayFilterVal.innerHTML == 0) {
            headerStart = headerStart1;
        } else {
            headerStart = headerStart2;
        }

        if (filterByVal.innerHTML === 'ICAT') {
            headerMid = slicedString(filterByProdCategoryNameVal.innerHTML);
        } else if (filterByVal.innerHTML === 'PM') {
            headerMid = slicedString(filterByPaymentModeVal.innerHTML);
        } else if (filterByVal.innerHTML === 'STF') {
            headerMid = slicedString(filterByStaffName.innerHTML);
        }

        if (reportFilterVal.innerHTML === 'Total Sell') {
            headerEnd = headerEnd1;
        } else if (reportFilterVal.innerHTML === 'Total Margin') {
            headerEnd = headerEnd2;
        } else if (reportFilterVal.innerHTML === 'Total Discount') {
            headerEnd = headerEnd3;
        }

        headerMid = [...new Set(headerMid)].sort();
        const headers = headerStart.concat(headerMid).concat(headerEnd);

        // Initialize grand totals
        const grandTotals = {};
        headers.forEach(header => {
            grandTotals[header] = 0;
        });

        // Group data based on the filter values
        const groupDataByKey = (data, keyFn) => {
            return data.reduce((acc, item) => {
                const key = keyFn(item);
                if (!acc[key]) {
                    acc[key] = [];
                }
                acc[key].push(item);
                return acc;
            }, {});
        };

        let groupedData;
        if (dayFilterVal.innerHTML == 0) {
            groupedData = groupDataByKey(parsedData, item => item.added_on);
        } else if (dayFilterVal.innerHTML == 1) {
            groupedData = groupDataByKey(parsedData, item => item.start_date + ' to ' + item.end_date);
        } else if (dayFilterVal.innerHTML == 2) {
            groupedData = groupDataByKey(parsedData, item => `${item.year}-${item.month}`);
        }

        const createRows = (groupData, groupKey) => {
            let rowData = {};

            if (dayFilterVal.innerHTML == 0) {
                rowData['Date'] = formatDate(groupKey);
            } else if (dayFilterVal.innerHTML == 1) {
                const [startDate, endDate] = groupKey.split(' to ');
                rowData['Start Date'] = formatDate(startDate);
                rowData['End Date'] = formatDate(endDate);
            } else if (dayFilterVal.innerHTML == 2) {
                const [year, month] = groupKey.split('-');
                rowData['Start Date'] = formatDate(groupData[0].start_date);
                rowData['End Date'] = formatDate(groupData[0].end_date);
            }

            let totalSellAmount = 0, totalMargin = 0, totalDiscount = 0;

            headerMid.forEach(header => {
                rowData[header] = 0.00;
            });

            groupData.forEach(data => {
                let amount = 0;
                if (filterByVal.innerHTML === 'ICAT' && headerMid.includes(data.category_name)) {
                    amount = reportFilterVal.innerHTML === 'Total Sell' ? parseFloat(data.  total_stock_out_amount || 0) :
                            reportFilterVal.innerHTML === 'Total Margin' ? parseFloat(data.total_sales_margin ||    0) :
                            reportFilterVal.innerHTML === 'Total Discount' ? parseFloat(data.total_discount ||  0) : 0;

                    rowData[data.category_name] += amount;
                    grandTotals[data.category_name] += amount; // Update grand totals
                    totalSellAmount += amount;
                    totalMargin += parseFloat(data.total_sales_margin || 0);
                    totalDiscount += parseFloat(data.total_discount || 0);
                } else if (filterByVal.innerHTML === 'PM' && headerMid.includes(data.payment_mode)) {
                    amount = reportFilterVal.innerHTML === 'Total Sell' ? parseFloat(data.  total_stock_out_amount || 0) :
                            reportFilterVal.innerHTML === 'Total Margin' ? parseFloat(data.total_sales_margin ||    0) :
                            reportFilterVal.innerHTML === 'Total Discount' ? parseFloat(data.total_discount ||  0) : 0;

                    rowData[data.payment_mode] += amount;
                    grandTotals[data.payment_mode] += amount; // Update grand totals
                    totalSellAmount += amount;
                    totalMargin += parseFloat(data.total_sales_margin || 0);
                    totalDiscount += parseFloat(data.total_discount || 0);
                } else if (filterByVal.innerHTML === 'STF' && headerMid.includes(data.added_by_name)) {
                    amount = reportFilterVal.innerHTML === 'Total Sell' ? parseFloat(data.  total_stock_out_amount || 0) :
                            reportFilterVal.innerHTML === 'Total Margin' ? parseFloat(data.total_sales_margin ||    0) :
                            reportFilterVal.innerHTML === 'Total Discount' ? parseFloat(data.total_discount ||  0) : 0;

                    rowData[data.added_by_name] += amount;
                    grandTotals[data.added_by_name] += amount; // Update grand totals
                    totalSellAmount += amount;
                    totalMargin += parseFloat(data.total_sales_margin || 0);
                    totalDiscount += parseFloat(data.total_discount || 0);
                }
            });

            if (reportFilterVal.innerHTML === 'Total Sell') {
                rowData['Total Sell'] = totalSellAmount;
                grandTotals['Total Sell'] += totalSellAmount; // Update grand totals
            } else if (reportFilterVal.innerHTML === 'Total Margin') {
                rowData['Total Margin'] = totalMargin;
                grandTotals['Total Margin'] += totalMargin; // Update grand totals
            } else if (reportFilterVal.innerHTML === 'Total Discount') {
                rowData['Total Discount'] = totalDiscount;
                grandTotals['Total Discount'] += totalDiscount; // Update grand totals
            }

            return rowData;
        };

        const csvRows = [];

        // Add header data to CSV rows
        csvRows.push(headerData1[0]);
        headerData2.forEach(row => csvRows.push(row));
        csvRows.push(headers.join(','));

        // Process grouped data and add to CSV rows
        Object.entries(groupedData).forEach(([groupKey, groupData]) => {
            const rowData = createRows(groupData, groupKey);
            const dataRow = headers.map(header => {
                if (typeof rowData[header] === 'number') {
                    return `${rowData[header].toFixed(2)}`;
                } else {
                    return rowData[header] || '';
                }
            });
            csvRows.push(dataRow.join(','));
        });

        // Calculate grand totals for the CSV
        const grandTotalRow = ['Grand Total'];
        headers.forEach(header => {
            if (header !== 'Date' && header !== 'Bill Date' && header !== 'Start Date' && header !== 'End Date')    {
                grandTotalRow.push(`${grandTotals[header] || 0.00}`);
            }
        });

        // Add grand total row to the CSV rows
        csvRows.push(grandTotalRow.join(','));

        // Convert to CSV string
        const csvData = csvRows.join('\n');

        // Create blob for CSV string
        const blob = new Blob([csvData], { type: 'text/csv' });

        // Create download link
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `SalesReport_${getCurrentDateTime()}.csv`;

        // Append link to DOM, simulate click, and remove link
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }else{
        emptyAlert(1);
    }
}
