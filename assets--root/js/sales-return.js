// const xmlhttp = new XMLHttpRequest();

        const viewReturnItem = (invoice, id) => {
            let url = `ajax/viewSalesReturn.ajax.php?invoice=${invoice}&id=${id}`;
            xmlhttp.open("GET", url, false);
            xmlhttp.send(null);
            document.getElementById('viewReturnModalBody').innerHTML = xmlhttp.responseText
        }


        const editSalesReturn = (invoiceId, salesReturnId) => {
            let editUrl = `sales-return-edit.php?invoice=${invoiceId}&salesReturnId=${salesReturnId}`;
            window.location.href = editUrl;
        };


        const cancelSalesReturn = (t) => {

            cancelId = t.id;

            swal({
                    title: "Are you sure?",
                    text: "Do you really cancel theis transaction?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {

                        $.ajax({
                            url: "ajax/salesReturnCancle.ajax.php?",
                            type: "POST",
                            data: {
                                id: cancelId
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.includes('1')) {
                                    swal(
                                        "Canceled",
                                        "Transaction Has Been Canceled",
                                        "success"
                                    ).then(function() {
                                        $(t).closest("tr").css({
                                            "background-color": "red",
                                            "color": "white"
                                        });
                                        window.location.reload();
                                    });

                                } else {
                                    swal("Failed", "Transaction Deletion Failed!",
                                        "error");
                                    $("#error-message").html("Deletion Field !!!")
                                        .slideDown();
                                    $("success-message").slideUp();
                                }
                            }
                        });
                    }
                    return false;
                });
        }

//=======================================================================

/*
// ============= date format controler ===============
function formatDate(dateString) {
    let dateParts = dateString.split('-');
    let year = dateParts[0];
    let month = dateParts[1];
    let day = dateParts[2];

    // Format day and month with leading zeros if necessary
    day = day.padStart(2, '0');
    month = month.padStart(2, '0');

    return `${day}-${month}-${year}`;
}

// minus date calculation
function calculateDate(days) {
    var currentDate = new Date();
    
    // Subtract days from current date
    var newDate = new Date(currentDate);
    newDate.setDate(currentDate.getDate() - parseInt(days));
    
    // Format the date as dd-mm-yyyy
    var formattedDate = ("0" + newDate.getDate()).slice(-2) + "-" + ("0" + (newDate.getMonth() + 1)).slice(-2) + "-" + newDate.getFullYear();
    
    return formattedDate;
}


function pharmacySearchFilter3(){
    // current url path
    let currentUrl = newUrl = window.location.origin + window.location.pathname; // holding current location

    // custom range control
    document.getElementById('dtPickerDiv').style.display = 'none'; // date picker div control
    let customDateRangeFlag = document.getElementById('date-range-control-flag');
    let urlControlFlag = document.getElementById('url-control-flag');

    let parameters = ''
    
    // filter data fetch area
    let searchKey = document.getElementById('data-search');  // search by input value

    let filter = document.getElementById('sales-return-on'); // sales or purchase date filter2
    
    let filter2 = document.getElementById('sales-return-processed-by');  // payment mode filter
    
    let itemReturnStartDt = document.getElementById('select-sales-return-start-date');
    let itemReturnEndDt = document.getElementById('select-sales-return-end-date');

    let salesReturnBy = document.getElementById('return-processed-by');

    // date contorl area
    let date = new Date();
    let day = date.getDate();
    let month = (date.getMonth() + 1); // Adding 1 because January is 0
    let year = date.getFullYear();
    let currentDate = `${day}-${month}-${year}`;

    let startDate = '';
    let endDate = '';

    // search key check
    if(searchKey.value != ''){
        parameters +=  `&search=${searchKey.value}`;
    }

    if(filter.value != ''){

        if(filter.value == 'T'){            // 'T' -> (date filter today)   
            startDate = currentDate;
            endDate = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter.value == 'Y'){            // 'Y' -> (date filter yeesterday)  
            startDate = calculateDate('1');
            endDate = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter.value == 'LW'){            // 'LW' -> (date filter last 7 days)  
            startDate = calculateDate('7');
            endDate = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter.value == 'LM'){            // 'LM' -> (date filter last 30 days)  
            startDate = calculateDate('30');
            endDate = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter.value == 'LQ'){            // 'LQ' -> (date filter last 90 days)  
            startDate = calculateDate('90');
            endDate = currentDate;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter.value == 'CFY'){           // 'CFY' -> (date filter curremt fiscal year)  
            let currentYr =  year;
            let fiscalYr = parseInt(year) + 1;
                     
            startDate = '01-04-'+currentYr;
            endDate = '31-03-'+fiscalYr;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter.value == 'PFY'){            // 'PFY' -> (date filter previous fiscal year)  
            let fiscalYr = parseInt(year) - 1;

            startDate = '01-04-'+fiscalYr;
            endDate = '31-03-'+year;
            
            customDateRangeFlag.innerHTML = '0';
            document.getElementById('dtPickerDiv').style.display = 'none';
        }

        if(filter.value == 'CR'){              // 'CR' -> (custom range)
            document.getElementById('dtPickerDiv').style.display = 'block';
            if(customDateRangeFlag.innerHTML == '1'){
                startDate = formatDate(document.getElementById('from-date').value);
                endDate = formatDate(document.getElementById('to-date').value);
                urlControlFlag.innerHTML = '1';
                console.log(urlControlFlag);
            }
            customDateRangeFlag.innerHTML = '1';
        }

        itemReturnStartDt.innerHTML = startDate;
        itemReturnEndDt.innerHTML = endDate;
    }
    if(itemReturnStartDt.innerHTML != ''){
        parameters +=  `&itemReturnStartDt=${itemReturnStartDt.innerHTML}&itemReturnEndDt=${itemReturnEndDt.innerHTML}`;
    }
   
    
    // returned by filter set
    if(filter2.value != ''){
        salesReturnBy.innerHTML = filter2.value;
    }
    if(salesReturnBy.innerHTML != ''){
        parameters +=  `&addedBy=${salesReturnBy.innerHTML}`;
    }
  
    console.log(parameters);

    if(urlControlFlag.innerHTML == '1'){
        customDateRangeFlag.innerHTML = '0';
        urlControlFlag.innerHTML = '0';
        document.getElementById('dtPickerDiv').style.display = 'none';
    }

    // update url
    if(customDateRangeFlag.innerHTML == '0'){
        var newUrl = `${currentUrl}?${parameters}`;
        window.location.replace(newUrl);
    }
}



const checkResetFilter = ()=>{
    if(document.getElementById('data-search').value != ''){
        document.getElementById('filter-reset-1').classList.remove('d-none');
    }else{
        document.getElementById('filter-reset-1').classList.add('d-none');
    }
    if(document.getElementById('select-sales-return-start-date').innerHTML != ''){
        document.getElementById('filter-reset-2').classList.remove('d-none');
    }else{
        document.getElementById('filter-reset-2').classList.add('d-none');
    }
    if(document.getElementById('return-processed-by').innerHTML != ''){
        document.getElementById('filter-reset-3').classList.remove('d-none');
    }else{
        document.getElementById('filter-reset-3').classList.add('d-none');
    }
}


// reset url function
const resteUrl = (thisId)=>{
    if(thisId == 'filter-reset-1'){
        document.getElementById('data-search').value = ''; 
    }

    if(thisId == 'filter-reset-2'){
        document.getElementById('select-sales-return-start-date').innerHTML = '';
        document.getElementById('select-sales-return-end-date').innerHTML = '';
    }

    if(thisId == 'filter-reset-3'){
        document.getElementById('return-processed-by').innerHTML = '';
    }

    pharmacySearchFilter3();
    checkResetFilter();
}


checkResetFilter();*/