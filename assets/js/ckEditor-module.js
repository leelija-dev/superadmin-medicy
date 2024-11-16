// from '@ckeditor/ckeditor5-build-classic'; // Adjust based on your CKEditor build
import {ClassicEditor, Essentials, Bold, Italic, Font, Paragraph, Table} from 'ckeditor5';

// Object to hold CKEditor instances
const editors = {};

// Function to initialize CKEditor for all textareas
export function initializeEditorsForTextareas() {
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        if (!editors[textarea.id]) {
            ClassicEditor
                .create(textarea, {
                    plugins: [Essentials, Bold, Italic, Font, Paragraph, Table],
                    toolbar: {
                        items: [
                            'undo', 'redo', '|', 'bold', 'italic', '|',
                            'insertTable', '|', 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                        ]
                    }
                })
                .then(editor => {
                    editors[textarea.id] = editor;
                })
                .catch(error => {
                    console.error('There was a problem initializing the editor:', error);
                });
        }
    });
}

// Export the editors object
export { editors };
