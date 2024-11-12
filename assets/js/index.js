const searchFor = () => {
    let searchForData = document.getElementById("search-all");

    if (searchForData.value.length > 4) {
        let searchAllUrl = `ajax/search-for-all.ajax.php?searchKey=${searchForData.value}`;
        xmlhttp.open("GET", searchAllUrl, false);
        xmlhttp.send(null);
        let response = xmlhttp.responseText;

        console.log(response);
    }
}