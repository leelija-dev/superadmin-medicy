// const xmlhttp = new XMLHttpRequest();

// Cache frequently accessed DOM elements
const reportFilterVal = document.getElementById('selected-report-type');
const selectedPurchaseType = document.getElementById('selected-purchse-type');
const stockInStockReturnGstReportTable = document.getElementById('gst-purchase-purchasereturn-table');
const downloadType = document.getElementById('download-file-type');
const healthCareName = document.getElementById('healthcare-name');
const healthCareGstin = document.getElementById('healthcare-gstin');
const healthCareAddress = document.getElementById('healthcare-address');
const selectedStartDate = document.getElementById('selected-start-date');
const selectedEndDate = document.getElementById('selected-end-date');
const reportGenerationTime = document.getElementById('report-generation-date-time-holder');
const totalPurchaseAmountShow = document.getElementById('total-purchase-amount-show-div');
const grossTotalLabel = document.getElementById('total-purchase-amount');
const paginationContainer = document.getElementById('pagination-controls');
totalPurchaseAmountShow.classList.add('d-none');


const dateInput = document.getElementById('date-input');  
const today = new Date();
const year = today.getFullYear();
const month = String(today.getMonth() + 1).padStart(2, '0'); // Add leading zero if needed
dateInput.max = `${year}-${month}`;
    

function filterReportType(t) {
    reportFilterVal.innerHTML = t.value;
}

function filterPurchaseType(t) {
    selectedPurchaseType.innerHTML = t.value;
}

function getCurrentDateTime() {
    const currentDate = new Date();
    const pad = num => String(num).padStart(2, '0');
    return `${pad(currentDate.getDate())}-${pad(currentDate.getMonth() + 1)}-${currentDate.getFullYear()} ${pad(currentDate.getHours())}:${pad(currentDate.getMinutes())}:${pad(currentDate.getSeconds())}`;
}

function getMonthRange(month, year) {
    const startDate = `01-${month}-${year}`;
    const getEndDate = new Date(year, month, 0); 
    const day = getEndDate.getDate();
    const endDate = `${day}-${month}-${year}`

    return {
        startDate: startDate, 
        endDate: endDate,
    };
}

// function convertDateFormat(dateStr, toFormat) {
//     const [day, month, year] = dateStr.split('-');
//     return toFormat === 'YYYY-MM-DD' ? `${year}-${month}-${day}` : `${day}/${month}/${year}`;
// }

function filterSearch() {
    const [year, month] = $('#date-input').val().split("-");
    
    const { startDate, endDate } = getMonthRange(month, year);
   
    const convertedStartDate = convertDateFormat(startDate, 'YYYY-MM-DD');
    const convertedEndDate = convertDateFormat(endDate, 'YYYY-MM-DD');

    selectedStartDate.innerHTML = convertDateFormat(startDate, 'DD/MM/YYYY');
    selectedEndDate.innerHTML = convertDateFormat(endDate, 'DD/MM/YYYY');

    if (!reportFilterVal.innerHTML || !selectedPurchaseType.innerHTML) {
        alert(!reportFilterVal.innerHTML ? 'select report type' : 'select purchase type');
        return;
    }

    const dataArray = {
        searchOn: reportFilterVal.innerHTML,
        startDate: convertedStartDate,
        endDate: convertedEndDate,
        reportOn: selectedPurchaseType.innerHTML === 'WG' ? 1 : 0
    };
    
    gstPurchaseReportSearch(dataArray);
}

function emptyAlert(){
    Swal.fire('Alert','No data found for download!','info');
}

let fullReportData = [];

function gstPurchaseReportSearch(array) {
    const salesDataReport = `ajax/gstPurchaseReport.ajax.php?dataArray=${JSON.stringify(array)}`;
    xmlhttp.open("GET", salesDataReport, false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(null);

    const report = JSON.parse(xmlhttp.responseText);
    if (report.status == '1') {
        fullReportData = report.data;
        purchaseReportShow(fullReportData);
    } else {
        stockInStockReturnGstReportTable.innerHTML = '';
        paginationContainer.innerHTML = '';
        totalPurchaseAmountShow.classList.add('d-none');
        stockInStockReturnGstReportTable.innerHTML = `<tr class="text-center text-danger font-weight-bold">
                                                    <td colspan="5">No Data Found!</td>
                                            </tr>`;
        fullReportData = [];
        // alert('no data found');
    }
}

function purchaseReportShow(reportData) {
    const rowsPerPage = 25;
    let currentPage = 1;

    document.getElementById('download-checking').innerHTML = '1';
    reportGenerationTime.innerHTML = getCurrentDateTime();
    totalPurchaseAmountShow.classList.remove('d-none');

    function renderTable(data, page) {
        const paginatedData = data.slice((page - 1) * rowsPerPage, page * rowsPerPage);
        stockInStockReturnGstReportTable.innerHTML = generateTableHeader();
        const tbody = document.createElement('tbody');
        let grossTotal = 0;

        paginatedData.forEach((rowData, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${rowData.bill_no || ''}</td>
                <td>${rowData.added_on || ''}</td>
                <td>${rowData.bill_date || ''}</td>
                <td>${rowData.dist_name || ''}</td>
                <td style="text-align:right;">${calculateTaxable(rowData).toFixed(2)}</td>
                <td style="text-align:right;">0</td>
                <td style="text-align:right;">${formatGst(rowData.total_gst_amount, rowData.total_gst_percent)}</td>
                <td style="text-align:right;">${formatGst(rowData.total_gst_amount, rowData.total_gst_percent)}</td>
                <td style="text-align:right;">0</td>
                <td style="text-align:right;">${rowData.total_paid_on_item}</td>
            `;
            tbody.appendChild(row);
            grossTotal += parseFloat(rowData.total_paid_on_item);
        });

        stockInStockReturnGstReportTable.appendChild(tbody);
        grossTotalLabel.innerHTML = grossTotal.toFixed(2);

        paginationContainer.innerHTML = '';
        paginationContainer.appendChild(createPaginationControls(data.length, page, rowsPerPage, renderTable));
    }
    renderTable(reportData, currentPage);
}


function generateTableHeader() {
    const header = ['Sl No', 'Bill No', 'Entry Date', 'Bill Date', 'Distributor', 'Taxable', 'CESS', 'SGST', 'CGST', 'IGST', 'Total Amount'];
    return `
        <thead>
            <tr>
                ${header.map(text => `<th style="${['Taxable', 'CESS', 'SGST', 'CGST', 'IGST', 'Total Amount'].includes(text) ? 'text-align:right;' : ''}font-weight:bold;">${text}</th>`).join('')}
            </tr>
        </thead>
    `;
}

function calculateTaxable(data) {
    return parseFloat(data.total_paid_on_item) - parseFloat(data.total_gst_amount);
}

function formatGst(amount, percent) {
    const halfAmount = (parseFloat(amount) / 2).toFixed(2);
    const halfPercent = (parseFloat(percent) / 2).toFixed(2);
    return `${halfAmount} (${halfPercent}%)`;
}



/// report download function call
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
        // if(ts.value == 'pdf'){
        //     exportToPDF();
        // }
    }else{
        // alert('generate report first!');
        Swal.fire('Alert','Generate report first!','info');
        downloadType.selectedIndex = 0;
    }
}


function exportToExcel() {
    if(fullReportData.length != 0){
        const headerData1 = [
            [healthCareName.innerHTML],
        ];
        const headerData2 = [
            [healthCareAddress.innerHTML],
            ["GSTIN : " + healthCareGstin.innerHTML],
            [],
            ["Purchase Report : " + selectedStartDate.innerHTML + " To " + selectedEndDate.innerHTML],
            ["Report generated at : " + reportGenerationTime.innerHTML],
            []
        ];
    
        const headers = ['Sl No', 'Bill No', 'Entry Date', 'Bill Date', 'Distributor', 'Taxable', 'CESS', 'SGST ', 'CGST', 'IGST', 'Total Amount'];
    
        // Create a new Excel workbook
        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet('Report');
    
        // Add header data1 to the worksheet with merged cells, center alignment, and specified font
        let currentRow = 1; 
    
        headerData1.forEach(rowData => {
            const mergeToColumn = headers.length;
            worksheet.mergeCells(`A${currentRow}:${String.fromCharCode(65 + mergeToColumn - 1)}${currentRow}`);
            const mergedCell = worksheet.getCell(`A${currentRow}`);
            mergedCell.value = rowData[0];
            mergedCell.alignment = { horizontal: 'center' }; // Center align the content
            mergedCell.font = { size: 14, bold: true }; // Set font size to 14 and bold
            currentRow++;
        });
    
        // Add header data2 to the worksheet with merged cells and center alignment
        headerData2.forEach(rowData => {
            const mergeToColumn = headers.length;
            worksheet.mergeCells(`A${currentRow}:${String.fromCharCode(65 + mergeToColumn - 1)}${currentRow}`);
            const mergedCell = worksheet.getCell(`A${currentRow}`);
            mergedCell.value = rowData[0];
            mergedCell.alignment = { horizontal: 'center' }; // Center align the content
            currentRow++;
        });
    
        // Add an empty row for spacing
        worksheet.addRow([]);
    
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
    
        // Initialize total variables for the 6th and 11th columns
        let totalSixthColumn = 0;
        let totalEleventhColumn = 0;
    
        // Add rows to the worksheet using fullReportData
        fullReportData.forEach((data, index) => {
            const row = [
                index + 1, // Serial number
                data.bill_no || '',
                data.added_on || '',
                data.bill_date || '',
                data.dist_name || '',
                (parseFloat(data.total_paid_on_item) - parseFloat(data.total_gst_amount)).toFixed(2),
                '0',
                (parseFloat(data.total_gst_amount) / 2).toFixed(2) + ' (' + (parseFloat(data.total_gst_percent) / 2).toFixed(2) + '%)',
                (parseFloat(data.total_gst_amount) / 2).toFixed(2) + ' (' + (parseFloat(data.total_gst_percent) / 2).toFixed(2) + '%)',
                '0',
                data.total_paid_on_item
            ];
            const excelRow = worksheet.addRow(row);
            excelRow.eachCell((cell, colNumber) => {
                if (colNumber > 1) {
                    const isNumeric = !isNaN(parseFloat(cell.value)) && isFinite(cell.value);
                    if (isNumeric) {
                        cell.alignment = { horizontal: 'left' };
    
                        if (colNumber === 6) {
                            totalSixthColumn += parseFloat(cell.value);
                        }
                        if (colNumber === 11) {
                            totalEleventhColumn += parseFloat(cell.value);
                        }
                    }
                }
            });
        });
    
        // Add an empty row before the totals
        worksheet.addRow([]);
    
        // Add grand totals row
        const totalsRow = worksheet.addRow([]);
        totalsRow.getCell(1).value = 'Grand Total';
        totalsRow.getCell(6).value = totalSixthColumn.toFixed(2); // 6th column total
        totalsRow.getCell(11).value = totalEleventhColumn.toFixed(2); // 11th column total
    
        // Style the grand totals row
        totalsRow.eachCell((cell, colNumber) => {
            cell.font = { bold: true };
            cell.alignment = { horizontal: colNumber > 1 ? 'left' : 'center' };
        });
    
        // Generate Excel file
        workbook.xlsx.writeBuffer().then(buffer => {
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            saveAs(blob, 'purchase-report.xlsx');
        });
    }else{
        emptyAlert();
        // alert('no data');
    }
}




function exportToCSV() {
    // Use the fullReportData stored from the AJAX call
    if(fullReportData.length != 0){
        const data = fullReportData;

        const headerData1 = [
            [healthCareName.innerHTML],
        ];
        const headerData2 = [
            [healthCareAddress.innerHTML],
            ["GSTIN : " + healthCareGstin.innerHTML],
            [],
            ["Purchase Report : " + selectedStartDate.innerHTML + " To " + selectedEndDate.innerHTML],
            ["Report generated at : " + reportGenerationTime.innerHTML],
            []
        ];

        const headers = ['Sl No', 'Bill No', 'Entry Date', 'Bill Date', 'Distributor', 'Taxable', 'CESS', 'SGST     ', 'CGST', 'IGST', 'Total Amount'];

        // Initialize total variables for the 6th and 11th columns
        let totalSixthColumn = 0;
        let totalEleventhColumn = 0;

        // Prepare CSV content
        let csvContent = '';

        // Add header data1
        headerData1.forEach(rowData => {
            csvContent += rowData.join(',') + '\n';
        });

        // Add header data2
        headerData2.forEach(rowData => {
            csvContent += rowData.join(',') + '\n';
        });

        // Add an empty row for spacing
        csvContent += '\n';

        // Add headers row
        csvContent += headers.join(',') + '\n';

        // Add data rows and calculate totals
        data.forEach((data, index) => {
            const slNo = index + 1;
            const taxable = (parseFloat(data.total_paid_on_item) - parseFloat(data.total_gst_amount)).toFixed(2);
            const cess = '0';
            const sgst = (parseFloat(data.total_gst_amount) / 2).toFixed(2) + ' (' + (parseFloat(data.  total_gst_percent) / 2).toFixed(2) + '%)';
            const cgst = (parseFloat(data.total_gst_amount) / 2).toFixed(2) + ' (' + (parseFloat(data.  total_gst_percent) / 2).toFixed(2) + '%)';
            const igst = '0';
            const totalAmount = data.total_paid_on_item;

            const row = [
                slNo,
                data.bill_no || '',
                data.added_on || '',
                data.bill_date || '',
                data.dist_name || '',
                taxable,
                cess,
                sgst,
                cgst,
                igst,
                totalAmount
            ];

            csvContent += row.join(',') + '\n';

            // Calculate totals
            totalSixthColumn += parseFloat(taxable);
            totalEleventhColumn += parseFloat(totalAmount);
        });

        // Add an empty row before the totals
        csvContent += '\n';

        // Add grand totals row
        const grandTotalRow = Array(headers.length).fill('');
        grandTotalRow[0] = 'Grand Total';
        grandTotalRow[5] = totalSixthColumn.toFixed(2); // 6th column total
        grandTotalRow[10] = totalEleventhColumn.toFixed(2); // 11th column total
        csvContent += grandTotalRow.join(',') + '\n';

        // Create a Blob from the CSV content
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);

        // Create a link to download the CSV file
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'purchase-report.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }else{
        emptyAlert();
    }
}

