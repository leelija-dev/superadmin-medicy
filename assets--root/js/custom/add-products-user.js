// var xmlhttp = new XMLHttpRequest();

// function displayImage() {
//     let imageType = image.type;
//     // console.log(imageType);

//     let validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];

//     if (validExtensions.includes(imageType)) {
//         let fileReader = new FileReader();

//         // return upload(image);

//         fileReader.onload = () => {
//             let fileUrl = fileReader.result;
//             // console.log(fileUrl);

//             let imgTag = `<img src="${fileUrl}" alt="">`;
//             dragArea.innerHTML = imgTag;
//         };
//         fileReader.readAsDataURL(image);
//     } else {
//         alert("Please Upload JPEG, JPG or an PNG Image.");
//     }
//     // console.log("Image is Droped");
// }


/*##########################################################################################################################################
#                                                                                                                                          #
#                                                 BACK IMAGE                                   #
#                                                                                                                                          #
##########################################################################################################################################*/



////////////////// IMGAE UPLOAD CONTROL AREA \\\\\\\\\\\\\\\\\

let fileInput = document.getElementById('img-file-input');
let imageContainer = document.getElementById('images');
let numOFFiles = document.getElementById('num-of-files');

function updateFilesCount() {
    numOFFiles.textContent = `${imageContainer.childElementCount} Files Selected`;

}

function setPriority(){
    let selectedRadioButton = document.querySelector('input[name="priority-group"]:checked');
    
    if (selectedRadioButton) {
        selectedRadioButton.value = 1;
        console.log(selectedRadioButton);

        return {
            name: selectedRadioButton.name,
            value: selectedRadioButton.value, 
        };
    }
}

function preview() {
    imageContainer.innerHTML = '';
    numOFFiles.textContent = `${fileInput.files.length} Files Selected`;

    for (let i of fileInput.files) {
        let reader = new FileReader();
        let figure = document.createElement("figure");
        figure.className = 'figure-style';
        // console.log(figure);
        let figCap = document.createElement("figcaption");
        // console.log(figCap);
        let radioButton = document.createElement('input');
        radioButton.className = 'radio-button';
        // console.log(radioButton);
        let closeButton = document.createElement('button');
        closeButton.innerHTML = '&times;'; // Close button text (×)
        closeButton.className = 'close-button';

        radioButton.type = 'radio';
        radioButton.name = 'priority-group';
        // radioButton.value = '';
        radioButton.id = 'radio' + (imageContainer.childElementCount + 1);

        closeButton.type = 'button';
        closeButton.id = 'close' + (imageContainer.childElementCount + 1);

        figCap.innerText = i.name;
        console.log(figCap);
        figCap.style.display = 'none';
        figure.appendChild(figCap);
        figure.appendChild(radioButton);
        figure.appendChild(closeButton);

        closeButton.onclick = function () {
            figure.remove();
            updateFilesCount();
        };

        radioButton.onclick = function () {
            setPriority();
            // console.log(`Radio button ${radioButton.id} clicked `);
        };

        reader.onload = () => {
            let img = document.createElement("img");
            img.setAttribute("src", reader.result);
            figure.insertBefore(img, figCap);
            figure.insertBefore(figCap, radioButton);
            figure.insertBefore(figCap, closeButton);
        };

        imageContainer.appendChild(figure);
        reader.readAsDataURL(i);
    }
}
//  image reset //
function resetImg() {
    document.getElementById('img-file-input').value = '';
    document.getElementById('images').innerHTML = '';
    document.getElementById('num-of-files').innerText = 'No files chosen';
}

// fetch image delete //
function closeImage(imageID,imageName, index) {
    // console.log(imageID);
    // console.log("imageName -",imageName);
    if (confirm("Are you sure want to delete ?")) {
    $.ajax({
        url: 'ajax/remove-image.ajax.php', 
        type: 'POST',
        data: { imageID: imageID },
        success: function(response) {
            console.log(response);

            $('#img-' + index).parent().remove();
        },
        error: function(error) {
            console.error('Error removing image:', error);
        }
    });
}
}



//////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////// manufacturur search control \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
//\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\/


// const manufacturerInput = document.getElementById("manufacturer-id");
// const manufDropdown = document.getElementsByClassName("c-dropdown")[0];

// manufacturerInput.addEventListener("focus", () => {
//     manufDropdown.style.display = "block";
// });

// document.addEventListener("click", (event) => {
//     // Check if the clicked element is not the input field or the manufDropdown
//     if (!manufacturerInput.contains(event.target) && !manufDropdown.contains(event.target)) {
//         manufDropdown.style.display = "none";
//     }
// });

// document.addEventListener("blur", (event) => {
//     // Check if the element losing focus is not the manufDropdown or its descendants
//     if (!manufDropdown.contains(event.relatedTarget)) {
//         // Delay the hiding to allow the click event to be processed
//         setTimeout(() => {
//             manufDropdown.style.display = "none";
//         }, 100);
//     }
// });






// manufacturerInput.addEventListener("keydown", () => {

//     // Delay the hiding to allow the click event to be processed
//     let list = document.getElementsByClassName('lists')[0];
//     let searchVal = document.getElementById("manufacturer-id").value;
//     // console.log(searchVal);

//     if (searchVal.length > 2) {

//         let manufURL = `ajax/manufacturer.list-view.ajax.php?match=${searchVal}`;
//         xmlhttp.open("GET", manufURL, false);
//         xmlhttp.send(null);

//         list.innerHTML = xmlhttp.responseText;


//     } else if (searchVal == '') {

//         searchVal = 'all';

//         let manufURL = `ajax/manufacturer.list-view.ajax.php?match=${searchVal}`;
//         xmlhttp.open("GET", manufURL, false);
//         xmlhttp.send(null);
//         // console.log();
//         list.innerHTML = xmlhttp.responseText;

//     } else {

//         list.innerHTML = '';
//     }
// });




// const setManufacturer = (t) => {
//     let manufId = t.id.trim();
//     let manufName = t.innerHTML.trim();

//     document.getElementById("manufacturer-id").value = manufName;
//     document.getElementById("manufacturer").value = manufId;
//     // document.getElementById("manufacturer").innerHTML = manufName;manufacturer-id

//     document.getElementsByClassName("c-dropdown")[0].style.display = "none";
// }


// const addManufacturer = () => {
//     $.ajax({
//         url: "components/manufacturer-add.php",
//         type: "POST",
//         success: function (response) {
//             let body = document.querySelector('.add-manufacturer');
//             body.innerHTML = response;
//         },
//         error: function (error) {
//             console.error("Error: ", error);
//         }
//     });
// }

