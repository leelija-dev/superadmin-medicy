/**
 * Date: 24-10-2024
 * Author: Dipak Majumdar
 * Description: This is script is created to initilize texteditor to the Report Text Format
 **/

import { editors } from './ckEditor-module.js';
import {initializeReportTextEditor } from './text-report-editor.js';


document.addEventListener('DOMContentLoaded', () => {
    const reportFormatSelector = document.getElementById('change-report-format');
    if (reportFormatSelector) {
        reportFormatSelector.addEventListener('change', (event) => changeReportFormat(event.target, reportFormatSelector.value));
    }
});

// Function to change report format and initialize CKEditor for the new textarea
const changeReportFormat = (e, reportFormatId) => {
    const testId = e.getAttribute('data-test');

    const dynamicRowContainer = document.getElementById('dynamic-row-container');

    if (reportFormatId == '2') {


        if (dynamicRowContainer) {
            (async () => {
                try {
                    const response = await fetch(`./components/ReportFormat/text-format.php?test-id=${testId}`);
                    const data = await response.text();
                    
                    // Set the innerHTML with the fetched data
                    dynamicRowContainer.innerHTML = data;
                    
                    // Select the new textarea element
                    const newTextarea = document.getElementById('report-text-format-field');
        
                    // Destroy the existing editor instance if it exists
                    if (newTextarea && editors[newTextarea.id]) {
                        await editors[newTextarea.id].destroy();
                        delete editors[newTextarea.id];
                    }
        
                    // Initialize the editor
                    initializeReportTextEditor(newTextarea)
                } catch (error) {
                    console.error('Error loading PHP content or initializing the editor:', error);
                }
            })();
        }
        

    } else {
        fetch(`./components/ReportFormat/New-SingleFieldFormat.php`)
            .then(response => response.text())
            .then(data => {
                dynamicRowContainer.innerHTML = data;
            })
            .catch(error => console.error('Error loading PHP content:', error));

    }
};