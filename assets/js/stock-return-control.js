//================ CALENDER TABLE DATA CONTROL =======================
const returnFilter = (t) => {

    let table = t.id;
    let data = t.value;

    // alert(table);
    // alert(data);

    var xmlhttp = new XMLHttpRequest();

    if (table == 'added_on' && data == 'CR') {
        // window.alert(table);
        // window.alert(data);
        showHiddenDiv1();
    }

    if (table == 'added_on' && data != 'CR') {
        showHiddenDiv2();
        let frmDate = 'fdate';
        let toDate = 'tdate';
        filterUrl = `ajax/return.filter.ajax.php?table=${table}&value=${data}&fromDate=${frmDate}&toDate=${toDate}`;
        xmlhttp.open("GET", filterUrl, false);
        xmlhttp.send(null);
        document.getElementById("filter-table").innerHTML = xmlhttp.responseText;
    }

    if (table != 'added_on') {
        let frmDate = 'fdate';
        let toDate = 'tdate';
        filterUrl2 = `ajax/return.filter.ajax.php?table=${table}&value=${data}&fromDate=${frmDate}&toDate=${toDate}`;
        xmlhttp.open("GET", filterUrl2, false);
        xmlhttp.send(null);
        document.getElementById("filter-table").innerHTML = xmlhttp.responseText;
    }
}

//========================= DATE PICKER DIV CONTROL =======================
const showHiddenDiv1 = () => {
    var div = document.getElementById('hiddenDiv');
    div.style.display = 'block';
}

const showHiddenDiv2 = () => {
    var div = document.getElementById('hiddenDiv');
    div.style.display = 'none';
}
// =============== EOF DATE PICKER DIV CONTROL =====================


const getDates = (id, val) => {
    let frmDate = document.getElementById("from-date").value;
    let toDate = document.getElementById("to-date").value;
    let table = id;
    let data = val;
    // window.alert(table);
    // window.alert(data);

    if (frmDate < toDate) {
        var xmlhttp = new XMLHttpRequest();
        // ============== Date Range ==============
        dateRangeUrl = `ajax/return.filter.ajax.php?table=${table}&value=${data}&fromDate=${frmDate}&toDate=${toDate}`;
        // alert(dateRangeUrl);
        xmlhttp.open("GET", dateRangeUrl, false);
        xmlhttp.send(null);
        document.getElementById("filter-table").innerHTML = xmlhttp.responseText;
    } else {
        // Swal.fire(
        //     'Check From Date?',
        //     'From Date must be smaller than To Date!',
        //     'info'
        // )
        window.alert("Check From Date. From Date must be smaller than To Date!")
    }
}


//====================================================================
const viewReturnItems = (returnId) => {

    var xmlhttp = new XMLHttpRequest();

    // ============== View Return Item in Detail ==============
    idUrl = `ajax/purchaseReturnItemList.ajax.php?return-id=${returnId}`;
    // alert(url);
    xmlhttp.open("GET", idUrl, false);
    xmlhttp.send(null);
    document.getElementById("viewReturnModalBody").innerHTML = xmlhttp.responseText;
    // alert(xmlhttp.responseText);
}


// ============================== edit return item contol ==================
const editReturnItem = (editId) => {
    // console.log("hello");
    // alert("edit id : " + editId);
    $.ajax({
        url: "ajax/edit-request-check.ajax.php",
        type: "POST",
        data: {
            Id: editId
        },
        success: function(data){
            // alert(data);
            if (data == 1) {
                window.location.href = `stock-return-edit.php?returnId=${editId}`;
            } else {
                swal("Oops", "Can't edit this data.", "error");
            }
        }
    });
}

//=============================== CANCEL STOCK RETURN FUNCTION CALL =============================
const cancelPurchaseReturn = (cancelId, t) => {
    
    let btn = document.getElementById('cancel-btn-'+cancelId);
    // alert(cancelId);
    if (confirm("Are You Sure?")) {
        $.ajax({
            url: "ajax/return.Cancel.ajax.php",
            type: "POST",
            data: {
                id: cancelId
            },
            success: function (data) {
                if (data) {
                    $(t).closest("tr").css("background-color", "#ff0000");
                    $(t).closest("tr").css("color", "#fff");
                    $(btn).off('click');
                } else {
                    // $("#error-message").html("Deletion Field !!!").slideDown();
                    // $("success-message").slideUp();
                    alert("Cancelation Failed !");
                }
            }
        });
    }
    return false;
}


