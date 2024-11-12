// var xmlhttp = new XMLHttpRequest();

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
        selectedRadioButton.value = figCap.innerHTML;
        // console.log(selectedRadioButton);

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
        // console.log("figCap "+figCap);
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
        // console.log(figCap);
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

