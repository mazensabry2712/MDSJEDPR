/**
 * Drag and Drop File Upload Component
 * Author: Corporate Sites Management System
 * Version: 1.0.0
 */

class DragDropUpload {
    constructor(options = {}) {
        this.options = {
            container: options.container || '#dragDropArea',
            fileInput: options.fileInput || '#logoInput',
            allowedTypes: options.allowedTypes || ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'],
            maxSize: options.maxSize || 2 * 1024 * 1024, // 2MB
            maxSizeText: options.maxSizeText || '2MB',
            multiple: options.multiple || false,
            ...options
        };

        this.init();
    }

    init() {
        this.container = $(this.options.container);
        this.fileInput = $(this.options.fileInput);
        this.filePreview = this.container.find('.file-preview');
        this.dragDropContent = this.container.find('.drag-drop-content');

        this.bindEvents();
    }

    bindEvents() {
        // Click to browse functionality
        this.container.on('click', (e) => {
            if (!$(e.target).hasClass('remove-file')) {
                this.fileInput.click();
            }
        });

        // Specific click handler for browse link
        $(document).on('click', '.browse-link', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.fileInput.click();
        });

        // Drag and drop events
        this.container.on('dragover dragenter', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.container.addClass('dragover');
        });

        this.container.on('dragleave dragend', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.container.removeClass('dragover');
        });

        this.container.on('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.container.removeClass('dragover');

            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                this.handleFiles(files);
            }
        });

        // File input change event
        this.fileInput.on('change', () => {
            if (this.fileInput[0].files.length > 0) {
                this.handleFiles(this.fileInput[0].files);
            }
            $('.previous-file-info').fadeOut();
        });

        // Remove file button
        $(document).on('click', '.remove-file', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.removeFile();
        });
    }

    handleFiles(files) {
        const file = files[0];

        // Validate file type
        if (!this.options.allowedTypes.includes(file.type)) {
            this.showError(`Please select a valid file type. Allowed: ${this.getAllowedTypesText()}`);
            return;
        }

        // Validate file size
        if (file.size > this.options.maxSize) {
            this.showError(`File size must be less than ${this.options.maxSizeText}`);
            return;
        }

        // Set file to input
        const dt = new DataTransfer();
        dt.items.add(file);
        this.fileInput[0].files = dt.files;

        // Show preview
        this.showPreview(file);

        // Trigger callback if provided
        if (this.options.onFileSelected) {
            this.options.onFileSelected(file);
        }
    }

    showPreview(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            this.filePreview.find('.preview-image').attr('src', e.target.result);
            this.filePreview.find('.file-name').text(file.name);
            this.filePreview.find('.file-size').text(this.formatFileSize(file.size));

            this.dragDropContent.addClass('d-none');
            this.filePreview.removeClass('d-none');
        };
        reader.readAsDataURL(file);
    }

    removeFile() {
        this.fileInput.val('');
        this.filePreview.addClass('d-none');
        this.dragDropContent.removeClass('d-none');

        // Trigger callback if provided
        if (this.options.onFileRemoved) {
            this.options.onFileRemoved();
        }
    }

    showError(message) {
        // Create or update error message
        let errorDiv = this.container.siblings('.drag-drop-error');
        if (errorDiv.length === 0) {
            errorDiv = $(`<div class="drag-drop-error alert alert-danger mt-2">${message}</div>`);
            this.container.after(errorDiv);
        } else {
            errorDiv.text(message);
        }

        // Hide error after 5 seconds
        setTimeout(() => {
            errorDiv.fadeOut(() => errorDiv.remove());
        }, 5000);
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    getAllowedTypesText() {
        return this.options.allowedTypes.map(type => type.split('/')[1].toUpperCase()).join(', ');
    }

    // Public methods
    reset() {
        this.removeFile();
    }

    getFile() {
        return this.fileInput[0].files[0] || null;
    }

    setFile(file) {
        const dt = new DataTransfer();
        dt.items.add(file);
        this.fileInput[0].files = dt.files;
        this.showPreview(file);
    }
}

// Auto-initialize if elements exist
$(document).ready(function() {
    if ($('#dragDropArea').length > 0) {
        window.dragDropUpload = new DragDropUpload({
            onFileSelected: function(file) {
                console.log('File selected:', file.name);
            },
            onFileRemoved: function() {
                console.log('File removed');
            }
        });
    }
});
