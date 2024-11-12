// const request = new requestRequest();

function getRandomColor() {
    const getRandomNumber = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;

    const r = getRandomNumber(0, 255);
    const g = getRandomNumber(0, 255);
    const b = getRandomNumber(0, 255);

    return `rgba(${r}, ${g}, ${b}, 0.7)`;
}

//============================================================================================

const changeMLV = (t) =>{
    document.getElementById('most-less-sold').value = 'less';
}


// ------------------------------------------------------

function goToStockCheck(id, url) {
    if (typeof url === 'undefined' || url === '') {
        console.error("URL is undefined or empty");
        return;
    }

    let path = '';

    if (id === 'current-stock-data') {
        path = 'current-stock.php';
    } else if (id === 'expiry-stock-data') {
        path = 'stock-expiring.php';
    }

    if (path) {
        window.location.href = url + path;
    } else {
        console.error('Invalid id provided: ' + id);
    }
}
