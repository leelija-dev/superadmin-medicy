const searchKey = document.getElementById('data-search'); // // search by input value
const resetFilterButton1 = document.getElementById('filter-reset-1');

const datePickerDiv = document.getElementById('dtPickerDiv');
const filter1 = document.getElementById('added_on');
const dateFilterSelect = document.getElementById('selected-date-filter');
const selectedStartDate = document.getElementById('select-start-date');
const selectedEndDate   = document.getElementById('select-end-date');
const resetFilterButton2 = document.getElementById('filter-reset-2');

// const newUrlControlFlag = document.getElementById('url-control-flag');
// const customDatePickerRange = document.getElementById('date-range-control-flag');

const filter2 = document.getElementById('added_by');
const addedByVal = document.getElementById('selected-addedBy');
const resetFilterButton3 = document.getElementById('filter-reset-3');

const filter3 = document.getElementById('payment_mode');
const selectedPaymentMode = document.getElementById('selected-payment-mode');
const resetFilterButton4 = document.getElementById('filter-reset-4');




// ============= date format controler ===============
function formatDate(dateString) {
    let dateParts = dateString.split('-');
    let year = dateParts[0];
    let month = dateParts[1];
    let day = dateParts[2];

    day = day.padStart(2, '0');
    month = month.padStart(2, '0');

    return `${day}-${month}-${year}`;
}

// minus date calculation
function calculateDate(days) {
    var currentDate = new Date();
    var newDate = new Date(currentDate);
    newDate.setDate(currentDate.getDate() - parseInt(days));
    var formattedDate = ("0" + newDate.getDate()).slice(-2) + "-" + ("0" + (newDate.getMonth() + 1)).slice(-2) + "-" + newDate.getFullYear();
    
    return formattedDate;
}



const pharmacySearchFilter1 = () =>{
    
    // current url path
    let currentUrl = newUrl = window.location.origin + window.location.pathname; // holding current location
    let parameters = ''

    // custom range control
    datePickerDiv.style.display = 'none'; // date picker div control

    // date contorl area
    let date = new Date();
    let day = date.getDate();
    let month = (date.getMonth() + 1); // Adding 1 because January is 0
    let year = date.getFullYear();
    let currentDate = `${day}-${month}-${year}`;

    let startDate = '';
    let endDate = '';


    if(searchKey.value != ''){
        parameters +=  `&search=${searchKey.value}`;
    }

    // date filter check and data control area 
    if(filter1.value != ''){

        if(filter1.value == 'T'){            // 'T' -> (date filter today)   
            startDate = currentDate;
            endDate = currentDate;
            
            customDateRangeFlag.value = '0';
            datePickerDiv.style.display = 'none';
        }

        if(filter1.value == 'Y'){            // 'Y' -> (date filter yeesterday)  
            startDate = calculateDate('1');
            endDate = calculateDate('1');
            
            customDateRangeFlag.value = '0';
            datePickerDiv.style.display = 'none';
        }

        if(filter1.value == 'LW'){            // 'LW' -> (date filter last 7 days)  
            startDate = calculateDate('7');
            endDate = currentDate;
            
            customDateRangeFlag.value = '0';
            datePickerDiv.style.display = 'none';
        }

        if(filter1.value == 'LM'){            // 'LM' -> (date filter last 30 days)  
            startDate = calculateDate('30');
            endDate = currentDate;
            
            customDateRangeFlag.value = '0';
            datePickerDiv.style.display = 'none';
        }

        if(filter1.value == 'LQ'){            // 'LQ' -> (date filter last 90 days)  
            startDate = calculateDate('90');
            endDate = currentDate;
            
            customDateRangeFlag.value = '0';
            datePickerDiv.style.display = 'none';
        }

        if(filter1.value == 'CFY'){           // 'CFY' -> (date filter curremt fiscal year)  
            let currentYr =  year;
            let fiscalYr = parseInt(year) + 1;
                     
            startDate = '01-04-'+currentYr;
            endDate = '31-03-'+fiscalYr;
            
            customDateRangeFlag.value = '0';
            datePickerDiv.style.display = 'none';
        }

        if(filter1.value == 'PFY'){            // 'PFY' -> (date filter previous fiscal year)  
            let fiscalYr = parseInt(year) - 1;

            startDate = '01-04-'+fiscalYr;
            endDate = '31-03-'+year;
            
            customDateRangeFlag.value = '0';
            datePickerDiv.style.display = 'none';
        }

        if(filter1.value == 'CR'){            // 'CR' -> (custom range)
            datePickerDiv.style.display = 'block';
            if(customDateRangeFlag.value == '1'){
                startDate = formatDate(document.getElementById('from-date').value);
                endDate = formatDate(document.getElementById('to-date').value);
                urlControlFlag.value = '1';
            }
            customDateRangeFlag.value = '1';
        }

        const dateFilterMap = {
            'T': 'Today',
            'Y': 'Yesterday',
            'LW': 'Last 7 Days',
            'LM': 'Last 30 Days',
            'LQ': 'Last 90 Days',
            'CFY': 'Current Fiscal Year',
            'PFY': 'Previous Fiscal Year',
            'CR': 'Custom Range'
        };
        dateFilterSelect.value = dateFilterMap[filter1.value] || '';

        selectedStartDate.value = startDate;
        selectedEndDate.value = endDate;
    }

    if(selectedStartDate.value != ''){
        parameters +=  `&dateFilterSelect=${dateFilterSelect.value}&dateFilterStart=${selectedStartDate.value}&dateFilterEnd=${selectedEndDate.value}`;
    }


    // doctor filter set
    if(filter2 != null){
        if(filter2.value != ''){
            addedByVal.value = filter2.value;
        }
        if(addedByVal.value != ''){
            parameters +=  `&addedBy=${addedByVal.value}`;
        }
    }
    
    if(filter3 != null){
        if(filter3.value != ''){
            selectedPaymentMode.value = filter3.value
        }
        if(selectedPaymentMode.value != ''){
            parameters +=  `&paymentMode=${selectedPaymentMode.value}`;
        }
    }
    
    if(urlControlFlag.value == '1'){
        customDateRangeFlag.value = '0';
        urlControlFlag.value = '0';
        datePickerDiv.style.display = 'none';
    }
    
    
    // update url
    if(customDateRangeFlag.value == '0'){
        var newUrl = `${currentUrl}?${parameters}`;
        window.location.replace(newUrl);
    }
}




const checkResetFilter = ()=>{
    if(searchKey != null){
        if(searchKey.value != '' ){
            resetFilterButton1.classList.remove('d-none');
        }else{
            resetFilterButton1.classList.add('d-none');
        }
    }
    // else{
    //     return;
    // }
    
    if(selectedStartDate != null){
        if(selectedStartDate.value != ''){
            resetFilterButton2.classList.remove('d-none');
        }else{
            resetFilterButton2.classList.add('d-none');
        }
    }
    // else{
    //     return;
    // }
    

    if(addedByVal && resetFilterButton3 != null){
        if(addedByVal.value != ''){
            resetFilterButton3.classList.remove('d-none');
        }else{
            resetFilterButton3.classList.add('d-none');
        }
    }
    
    if(selectedPaymentMode && resetFilterButton4 != null){
        if(selectedPaymentMode.value != ''){
            resetFilterButton4.classList.remove('d-none');
        }else{
            resetFilterButton4.classList.add('d-none');
        }
    }
}


// reset url function
const resteUrl = (thisId)=>{
    if(thisId == 'filter-reset-1'){
        searchKey.value = ''; 
    }

    if(thisId == 'filter-reset-2'){
        selectedStartDate.value = '';
        selectedEndDate.value = '';
    }

    if(thisId == 'filter-reset-3'){
        addedByVal.value = '';
    }

    if(thisId == 'filter-reset-4'){
        selectedPaymentMode.value = '';
    }

    pharmacySearchFilter1();
    checkResetFilter();
}

checkResetFilter();