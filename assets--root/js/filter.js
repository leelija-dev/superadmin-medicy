var xmlhttp = new XMLHttpRequest();
const filterAppointment = (t) => {

    let fieldID = t.id;
    let data = t.value;

    if (data != '') {
        // console.log(fieldID);
        // console.log(data);
        // alert(fieldID);
        // alert(data);

        $.ajax({
            url: "ajax/filter.ajax.php",
            type: "POST",
            data: {
                searchFor: fieldID,
                search: data
            },
            success: function (response) {

                var responseObject = JSON.parse(response);
                console.log(responseObject);

                var tableData = responseObject.data;
                var paginationData = responseObject.pagination;

                console.log(paginationData);

                $('#appointments-dataTable').empty();

                var table = document.getElementById('appointments-dataTable');

                // Create table headers
                var headerRow = table.insertRow(0);
                headerRow.insertCell(0).innerHTML = 'Appointment ID';
                headerRow.insertCell(1).innerHTML = 'Patient ID';
                headerRow.insertCell(2).innerHTML = 'Patient Name';
                headerRow.insertCell(3).innerHTML = 'Assigned Doctor';
                headerRow.insertCell(4).innerHTML = 'Appointment Date';
                headerRow.insertCell(5).innerHTML = 'Action';

                for (let i = 0; i < tableData.length; i++) {
                    var row = table.insertRow(i + 1);
                    row.insertCell(0).innerHTML = tableData[i].appointment_id;
                    row.insertCell(1).innerHTML = tableData[i].patient_id;
                    row.insertCell(2).innerHTML = tableData[i].patient_name;
                    row.insertCell(3).innerHTML = tableData[i].doc_name;
                    row.insertCell(4).innerHTML = tableData[i].appointment_date;

                    // Action cell
                    var actionCell = row.insertCell(5);

                    // Edit link
                    var editLink = document.createElement("a");
                    editLink.className = "text-primary";
                    editLink.setAttribute("data-toggle", "modal");
                    editLink.setAttribute("data-target", ".AppointmntViewAndEdit");
                    editLink.setAttribute("onclick", "appointmentViewAndEditModal(" + tableData[i].appointment_id + ")");
                    editLink.setAttribute("title", "View and Edit");
                    editLink.innerHTML = '<i class="far fa-edit"></i>';
                    actionCell.appendChild(editLink);

                    // Print link
                    var printLink = document.createElement("a");
                    printLink.className = "text-primary";
                    printLink.href = "prescription.php?prescription=" + tableData[i].appointment_id;
                    printLink.setAttribute("title", "View and Print");
                    printLink.innerHTML = '<i class="fas fa-print"></i>';
                    actionCell.appendChild(printLink);

                    // Delete link
                    var deleteLink = document.createElement("a");
                    deleteLink.className = "delete-btn";
                    deleteLink.setAttribute("data-id", tableData[i].appointment_id);
                    deleteLink.setAttribute("title", "Delete");
                    deleteLink.innerHTML = '<i class="far fa-trash-alt"></i>';
                    actionCell.appendChild(deleteLink);
                }

                renderPaginationControls(paginationData);

            }
        });
} else {
    window.location.reload();
    }



function renderPaginationControls(paginationData) {
    $('#pagination-control').html(paginationData);
}







    // if (table == 'added_on' && data == 'CR') {
    //     // window.alert(table);
    //     // window.alert(data);
    //     showHiddenDiv1();
    // }

    // if (table == 'added_on' && data != 'CR') {
    //     showHiddenDiv2();
    //     let frmDate = 'fdate';
    //     let toDate = 'tdate';
    //     filterUrl = `ajax/return.filter.ajax.php?table=${table}&value=${data}&fromDate=${frmDate}&toDate=${toDate}`;
    //     xmlhttp.open("GET", filterUrl, false);
    //     xmlhttp.send(null);
    //     document.getElementById("filter-table").innerHTML = xmlhttp.responseText;
    // }

    // if (table != 'added_on') {
    //     let frmDate = 'fdate';
    //     let toDate = 'tdate';
    //     filterUrl2 = `ajax/return.filter.ajax.php?table=${table}&value=${data}&fromDate=${frmDate}&toDate=${toDate}`;
    //     xmlhttp.open("GET", filterUrl2, false);
    //     xmlhttp.send(null);
    //     document.getElementById("filter-table").innerHTML = xmlhttp.responseText;
    // }
}

const filterPatients = (t) =>{
    let fieldID = t.id;
    let data = t.value;
    // console.log(fieldID);
    console.log(data);


    $.ajax({
        url: "ajax/filter.ajax.php",
        type: "POST",
        data: {
            searchFor: fieldID,
            search: data
        },
        success: function(response) {
            // alert(data);
            // console.log(response);
            $('#dataTable').html(response);
            
        }
    });
}
