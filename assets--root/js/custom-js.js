// 1. Lab Edit Modal Js
function LabCategoryEditModal(cardDetailsId) {
    let labCategoryId = cardDetailsId;
    let url = "ajax/editLabCat-Ajax.php?labCategoryId=" + labCategoryId;
    $(".modal-body").html('<iframe width="99%" height="360px" frameborder="0" allowtransparency="true" src="' + url + '"></iframe>');

} // end of LabCategoryEditModal function

// 2. Delete Function
function deleteConfirmation() {
    return confirm('Are you sure you want to delete this?');
} // end of deleteConfirmation


//3. Pge refresh function
function refreshPage() {
    window.location.reload();
} //end of refreshPage


//4. Appointment Delete Confirmation
function appointmentDelConfirmation(){
    return confirm("Are you sure want to delete this appointment?");
}