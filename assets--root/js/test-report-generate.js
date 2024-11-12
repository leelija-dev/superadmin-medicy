/**
 * Date: 06-11-2024
 * Author: Dipak Majumdar
 * Description: This is script is created to get report status and format acirding to the test id
 **/

import { editors} from './admin-js/ckEditor-module.js';
import { initializeReportTextEditor } from './admin-js/text-report-editor.js';


document.addEventListener("DOMContentLoaded", function() {

    var choice = new Choices(
        "#select-test", {
            allowHTML: true,
            removeItemButton: true,
        }
    );
    let previousValues = choice.getValue(true);

    document.getElementById('select-test').addEventListener('change', function(event) {
        const currentValues = choice.getValue(true);
        // const billId = '<?= $testBillId ?>'; // replace 'your_bill_id_here' with the actual billId
        const billId = this.getAttribute('data-billid')

        var xhr = new XMLHttpRequest();
        xhr.open('POST', "components/TestReportBody.inc.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status === 200) {
                // Handle success
                // console.log(xhr.responseText);

                document.getElementById('testReportBody').innerHTML = xhr.responseText;
                // You can update the DOM or do other actions with the response data

                (async () => {
                    // Select all textarea elements on the page
                    const textareas = document.querySelectorAll('textarea'); // This selects all textareas
                
                    // Loop through each textarea element
                    for (const textarea of textareas) {
                        // Check if there's an existing editor instance for this textarea and destroy it if necessary
                        if (editors[textarea.id]) {  // Using the name attribute as a unique identifier
                            await editors[textarea.id].destroy();
                            delete editors[textarea.id];
                        }
                
                        // Initialize the editor for each textarea
                        initializeReportTextEditor(textarea);
                    }
                })();

            } else {
                // Handle error
                console.error("Error:", xhr.statusText);
                alert("An error occurred: " + xhr.statusText);
            }
        };

        xhr.onerror = function() {
            // Handle error
            console.error("Request failed");
            alert("An error occurred during the transaction");
        };

        // xhr.send("testId=" + encodeURIComponent(currentValues));
        xhr.send("testId=" + encodeURIComponent(currentValues) + "&billId=" + encodeURIComponent(
            billId));
    });


    document.getElementById('select-test').addEventListener('change', function(event) {
        const currentValues = choice.getValue(true);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', "ajax/TestReportConditions.ajax.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status === 200) {
                // Handle success
                if (xhr.responseText) {
                    const response = JSON.parse(xhr.responseText);
                    // console.log(response);

                    const items = choice._store
                        .activeChoices; // Use the correct internal method

                    items.forEach(function(item) {

                        if (!response.includes(Number(item.value))) {
                            // console.log(item)
                            const itemElement = document.querySelector(
                                `#choices--select-test-item-choice-${item.id}`);
                            itemElement.classList.remove('choices__item--selectable');
                            itemElement.classList.remove('is-highlighted');
                            itemElement.classList.add('choices__item--disabled');
                            item.disabled = true
                            // item.active = false
                            itemElement.innerText =
                                `${item.label}  ---  Multiple Department's Report Can not generate at the same time`;
                        }

                    });
                } else {

                    const items = choice._store
                        .activeChoices; // Use the correct internal method

                    items.forEach(function(item) {
                        const itemElement = document.querySelector(
                            `#choices--select-test-item-choice-${item.id}`);

                        if (item.disabled) {
                            item.disabled = false
                        }
                        if (!item.active) {
                            item.active = true
                        }

                        itemElement.innerText = item.label

                        itemElement.classList.add('choices__item--selectable');
                        itemElement.classList.add('is-highlighted');
                        itemElement.classList.remove('choices__item--disabled');
                    });

                }

            } else {
                // Handle error
                console.error("Error:", xhr.statusText);
                alert("An error occurred: " + xhr.statusText);
            }
        };

        xhr.onerror = function() {
            // Handle error
            console.error("Request failed");
            alert("An error occurred during the transaction");
        };

        xhr.send("testId=" + encodeURIComponent(currentValues));
    });



});


const toggleParameter = (element) => {
    const parentElement = element.closest('#parameter');
    if (parentElement) {
        // console.log(parentElement);
        // Add your toggle logic here, for example, hiding the parent element
        parentElement.style.opacity = parentElement.style.opacity === '0.3' ? '' : '0.3';
        // console.log(parentElement.style.opacity);
    }
}


