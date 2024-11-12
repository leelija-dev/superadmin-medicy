const searchKey = document.getElementById('data-search');
const addedOnFilter = document.getElementById('added_on');
const calenderPick = document.getElementById('month-year');
const selectedAddedOn = document.getElementById('selected-month-year');
const datePickerDiv = document.getElementById('dtPickerDiv');

const currentUrl = window.location.origin + window.location.pathname; // current URL

const resetFilterButton1 = document.getElementById('filter-reset-1');
const resetFilterButton2 = document.getElementById('filter-reset-2');


const date = new Date();
const currentMonthYear = `${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getFullYear()}`; // Formatted current month and year

// Function to get next month in mm/yyyy format
function getNextMonth(date = new Date()) {
    const nextMonthDate = new Date(date);
    nextMonthDate.setMonth(nextMonthDate.getMonth() + 1);
    const month = (nextMonthDate.getMonth() + 1).toString().padStart(2, '0');
    const year = nextMonthDate.getFullYear();

    return `${month}/${year}`;
}


function convertToMMYYYY(dateString) {
    const [year, month] = dateString.split('-');
    return `${month}/${year}`;
}


function expiryItemFilter() {
    let urlUpdateParameter = '';

    datePickerDiv.style.display = 'none';

    if (searchKey.value.trim() !== '') {
        urlUpdateParameter = `&search=${searchKey.value.trim()}`;
        datePickerDiv.style.display = 'none';
    }

    if(addedOnFilter.value != ''){
        if(addedOnFilter.value == 1){
            selectedAddedOn.value = currentMonthYear;
            datePickerDiv.style.display = 'none';
            customDateRangeFlag.value = '0';
        }

        if(addedOnFilter.value == 2){
            selectedAddedOn.value = getNextMonth();
            datePickerDiv.style.display = 'none';
            customDateRangeFlag.value = '0';
        }

        if(addedOnFilter.value == 3){
            datePickerDiv.style.display = 'block';
            if(customDateRangeFlag.value == '1'){
                let calenderPickValeu = calenderPick.value;
                selectedAddedOn.value = convertToMMYYYY(calenderPickValeu);
                console.log(selectedAddedOn.value);
                urlControlFlag.value = '1';
            }
            customDateRangeFlag.value = '1';
        }
    }
    if(selectedAddedOn.value != ''){
        urlUpdateParameter +=  `&dateFilterSelect=${addedOnFilter.value}&dateFilterValue=${selectedAddedOn.value}`;
    }

    if(urlControlFlag.value == '1'){
        customDateRangeFlag.value = '0';
        urlControlFlag.value = '0';
        datePickerDiv.style.display = 'none';
    }
    
    if(customDateRangeFlag.value == '0'){
        var newUrl = `${currentUrl}?${urlUpdateParameter}`;
        window.location.replace(newUrl);
    }
}



const checkResetFilter = ()=>{
    if(searchKey.value != ''){
        resetFilterButton1.classList.remove('d-none');
    }else{
        resetFilterButton1.classList.add('d-none');
    }

    if(selectedAddedOn.value != ''){
        resetFilterButton2.classList.remove('d-none');
    }else{
        resetFilterButton2.classList.add('d-none');
    }
}


// reset url function
const resteUrl = (thisId)=>{
    if(thisId == 'filter-reset-1'){
        searchKey.value = ''; 
    }

    if(thisId == 'filter-reset-2'){
        calenderPick.value = '';
        selectedAddedOn.value = '';
    }
    expiryItemFilter();
    checkResetFilter();
}

checkResetFilter();