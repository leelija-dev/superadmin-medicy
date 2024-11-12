//xml http object for internet explorer
if (window.ActiveXObject) {
    try {
        request = new ActiveXObject("Msxml2.XMLHTTP");
    } //end of try, start catch
    catch (e) {
        try {
            request = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {
            request = false;
        }
    } //end of catch
} else {
    try {
        request = new XMLHttpRequest();
    } catch (e) {
        request = false;
    }

    if (!request) {
        alert("Error Create request");
    }
}