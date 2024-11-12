// date control (date piceker contorl by id - date select cannot exceed current date)

function setMaxDateToToday(id) {
    const today = new Date();
    const dd = String(today.getDate()).padStart(2, '0');
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const yyyy = today.getFullYear();

    const formattedDate = `${yyyy}-${mm}-${dd}`;

    document.getElementById(id).setAttribute('max', formattedDate);
}

window.onload = setMaxDateToToday;