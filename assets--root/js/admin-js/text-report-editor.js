
import { ClassicEditor, Essentials, Bold, Italic, Font, Paragraph, Table, Alignment, Autoformat, BlockQuote, CloudServices, EasyImage, Heading, Image, ImageCaption, ImageStyle, ImageToolbar, ImageUpload, Indent, Link, List, MediaEmbed, PasteFromOffice, TableToolbar, TextTransformation, Underline, Strikethrough, Highlight, HorizontalLine, HtmlEmbed } from 'ckeditor5';
import { editors } from './ckEditor-module.js';

// Export the function so it can be imported elsewhere
export const initializeReportTextEditor = (textarea) => {
    if (textarea && !editors[textarea.id]) {
        ClassicEditor
            .create(textarea, {
                plugins: [
                    Essentials, Bold, Italic, Font, Paragraph, Table, Alignment, Autoformat, BlockQuote, CloudServices,
                    EasyImage, Heading, Image, ImageCaption, ImageStyle, ImageToolbar, ImageUpload, Indent, Link, List, MediaEmbed,
                    PasteFromOffice, TableToolbar, TextTransformation, Underline, Strikethrough, Highlight, HorizontalLine, HtmlEmbed
                ],
                toolbar: {
                    items: [
                        'undo', 'redo', '|', 'heading', '|', 'bold', 'italic', 'underline', 'strikethrough', 'highlight', '|',
                        'link', 'blockquote', 'insertTable', '|', 'bulletedList', 'numberedList', '|', 'indent', 'outdent', '|',
                        'imageUpload', 'mediaEmbed', 'horizontalLine', '|', 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                        'alignment', '|', 'htmlEmbed'
                    ]
                },
                image: {
                    toolbar: [
                        'imageStyle:inline', 'imageStyle:block', 'imageStyle:side', '|',
                        'toggleImageCaption', 'imageTextAlternative'
                    ]
                },
                table: {
                    contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                },
                mediaEmbed: {
                    previewsInData: true
                }
            })
            .then(editor => {
                editors[textarea.id] = editor;
            })
            .catch(error => {
                console.error('There was a problem initializing the editor:', error);
            });
    }
};
