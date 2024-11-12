const billViewandEdit = async (billId) => {
    try {
        let url = "ajax/labBill.view.ajax.php?billId=" + billId;

        // Fetch the content from the server
        let response = await fetch(url);

        // Check if the response is OK (status code 200)
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }

        // Get the content as text
        // console.log(response.text());
        let data = await response.text();
        // console.log(data);
        
        // Inject the fetched content into the .billview element
        document.querySelector(".billview").innerHTML = data;

    } catch (error) {
        console.error("There was a problem with the fetch operation:", error);
    }
}; // end of billViewandEdit function


function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
}


cancelBill = (billId) => {
    swal({
            title: "Are you sure?",
            text: "Once Cancelled, You Will Not Be Able to Modify This Bill.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {

                $.ajax({
                    url: "ajax/labBill.delete.ajax.php",
                    type: "POST",
                    data: {
                        billId: billId,
                        status: "Cancelled",
                    },
                    success: function(data) {
                        // alert (data);
                        if (data == 1) {
                            swal("Done! Your Bill Has Been Cancelled.", {
                                icon: "success",
                            });
                            row = document.getElementById(billId);
                            row.closest('tr').style.background = '#b51212';
                            row.closest('tr').style.color = '#FFFFFF';
                        } else {
                            $("#error-message").html("Cancellation Field !!!").slideDown();
                        }

                    }
                });

            }
        });
}


// ==========================================================
const billCount = document.getElementById("bill-count");

if(billCount != null){
    for (let i = 1; i <= billCount.value; i++) {
        const billStatus = document.getElementById("bill-status-" + i);
        const printInvoiceClick = document.getElementById("print-invoice-click-" + i);
        const reportGenerateClick = document.getElementById("generate-report-click-"+i);
        const cancelBillClick = document.getElementById("cancel-bill-click-"+i);
        
        if (billStatus.value === 'Cancelled') {
            printInvoiceClick.removeAttribute('onclick');
            reportGenerateClick.removeAttribute('onclick');
            reportGenerateClick.removeAttribute('href');
            printInvoiceClick.removeAttribute('href');
            cancelBillClick.removeAttribute('onclick');
            cancelBillClick.removeAttribute('onclick');
        }
    }
}


