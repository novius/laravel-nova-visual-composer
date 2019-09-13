<template>
    <div v-bind:id="'list-' + id" class="row-item mb-4 py-3">
        <div class="js-row-item-move row-item-move">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="12" height="28" viewBox="0 0 12 28">
                <title>arrows-v</title>
                <path
                    d="M11 5c0 0.547-0.453 1-1 1h-2v16h2c0.547 0 1 0.453 1 1 0 0.266-0.109 0.516-0.297 0.703l-4 4c-0.187 0.187-0.438 0.297-0.703 0.297s-0.516-0.109-0.703-0.297l-4-4c-0.187-0.187-0.297-0.438-0.297-0.703 0-0.547 0.453-1 1-1h2v-16h-2c-0.547 0-1-0.453-1-1 0-0.266 0.109-0.516 0.297-0.703l4-4c0.187-0.187 0.438-0.297 0.703-0.297s0.516 0.109 0.703 0.297l4 4c0.187 0.187 0.297 0.438 0.297 0.703z"></path>
            </svg>
        </div>
        <button
            class="row-item-delete"
            type="button"
            @click="deleteRow">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="22" height="28" viewBox="0 0 22 28">
                <title>close</title>
                <path
                    d="M20.281 20.656c0 0.391-0.156 0.781-0.438 1.062l-2.125 2.125c-0.281 0.281-0.672 0.438-1.062 0.438s-0.781-0.156-1.062-0.438l-4.594-4.594-4.594 4.594c-0.281 0.281-0.672 0.438-1.062 0.438s-0.781-0.156-1.062-0.438l-2.125-2.125c-0.281-0.281-0.438-0.672-0.438-1.062s0.156-0.781 0.438-1.062l4.594-4.594-4.594-4.594c-0.281-0.281-0.438-0.672-0.438-1.062s0.156-0.781 0.438-1.062l2.125-2.125c0.281-0.281 0.672-0.438 1.062-0.438s0.781 0.156 1.062 0.438l4.594 4.594 4.594-4.594c0.281-0.281 0.672-0.438 1.062-0.438s0.781 0.156 1.062 0.438l2.125 2.125c0.281 0.281 0.438 0.672 0.438 1.062s-0.156 0.781-0.438 1.062l-4.594 4.594 4.594 4.594c0.281 0.281 0.438 0.672 0.438 1.062z"></path>
            </svg>
        </button>
        <div class="row-item-inputs flex-wrap">
            <div class="row-template w-full" v-html="template"></div>
        </div>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import * as FilePond from 'filepond';

    import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
    import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
    import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
    import FilePondPluginFileEncode from 'filepond-plugin-file-encode';

    FilePond.registerPlugin(
        FilePondPluginFileEncode,
        FilePondPluginFileValidateSize,
        FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
    );

    export default {

        props: [
            'type',
            'index',
            'initialValue'
        ],

        data: () => ({
            id: null,
            template: 'Loading ...',
            value: '',
        }),

        created: function () {

        },

        mounted() {

            this.id = this._uid;

            Nova.request({
                url: '/nova-vendor/nova-visual-composer/template-content',
                method: 'GET',
                params: {
                    template: this.type
                },
            }).then(({data}) => {
                if (data.error) {
                    this.deleteRow();
                    this.$toasted.show(data.message, {type: 'error'});
                } else {
                    this.template = data.templateHTML;
                }
            });

        },

        methods: {
            deleteRow() {
                this.$emit('delete-row', [this.index, this.id]);
            },

            bindEvents() {
                const row = this.$el;
                const fields = row.querySelectorAll('.js-visual-field');

                fields.forEach((field) => {
                    if (field.classList.contains('js-image-uploader')) {
                        return;
                    }

                    field.addEventListener('keyup', () => {
                        this.refreshValue();
                    });

                    field.addEventListener('change', () => {
                        this.refreshValue();
                    });
                });
            },

            refreshValue() {
                const row = this.$el;
                let contents = [];
                const fields = row.querySelectorAll('.js-visual-field');
                fields.forEach((field) => {
                    if (field.classList.contains('js-image-uploader')) {
                        let images = [];
                        const inputFields = field.querySelectorAll('input[name=filepond]');
                        if (inputFields.length) {
                            inputFields.forEach((input) => {
                                images.push(input.value);
                            });
                            contents.push(images);
                        } else {
                            contents.push([]);
                        }
                    } else {
                        contents.push(field.value);
                    }
                });

                this.value = JSON.stringify(contents);

                this.$emit('update-row', [this.index, this.value]);
            },

            fillInputs() {
                if (!this.initialValue || typeof this.initialValue !== 'string') {
                    return;
                }

                const content = JSON.parse(this.initialValue);
                const fields = this.$el.querySelectorAll('.js-visual-field');
                fields.forEach((field, index) => {
                    if (content[index]) {
                        if (field.classList.contains('js-image-uploader')) {
                            field.setAttribute('data-value', content[index].join('|'));
                        } else {
                            field.value = content[index];
                        }
                    }
                });
            },

            initImageUploadFields() {
                const uploadElements = this.$el.querySelectorAll('input.js-image-uploader');
                if (!uploadElements.length) {
                    return;
                }

                uploadElements.forEach((uploadElement) => {

                    const serverFiles = uploadElement.getAttribute('data-value') ? uploadElement.getAttribute('data-value').split('|') : [];

                    let filePondConfig = {
                        maxFileSize: '3MB',
                        acceptedFileTypes: ['image/png', 'image/jpeg'],
                        server: {
                            url: '/nova-vendor/nova-visual-composer/image-upload',
                            process: {
                                headers: {
                                    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                                }
                            },
                            revert: {
                                headers: {
                                    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                                }
                            },
                            load: {
                                headers: {
                                    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                                }
                            },
                        },
                    };

                    if (serverFiles.length) {

                        let files = [];
                        serverFiles.forEach((serverFile) => {
                            files.push({
                                source: serverFile,
                                options: {
                                    type: 'local'
                                }
                            });
                        });

                        filePondConfig.files = files;
                    }

                    FilePond.create(uploadElement, filePondConfig);
                });

                document.addEventListener('FilePond:processfile', e => {
                    if (!e.detail.error) {
                        this.refreshValue();
                    }
                });

                document.addEventListener('FilePond:updatefiles', e => {
                    if (!e.detail.error) {
                        this.refreshValue();
                    }
                });
            },

            initWysiwygFields() {
                const wysiwygElements = this.$el.querySelectorAll('textarea.js-wysiwyg');
                if (!wysiwygElements) {
                    return;
                }

                wysiwygElements.forEach((wysiwyg) => {
                    ClassicEditor
                    .create(wysiwyg, {
                        toolbar: [
                            'heading',
                            '|',
                            'bold',
                            'italic',
                            'link',
                            'bulletedList',
                            'numberedList',
                            'blockQuote',
                            'undo',
                            'redo'
                        ],
                        heading: {
                            options: [
                                {model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph'},
                                {model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2'},
                                {model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3'},
                                {model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4'},
                                {model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5'},
                            ]
                        }
                    })
                    .then(editor => {
                        editor.model.document.on('change:data', () => {
                            editor.updateSourceElement();
                            this.refreshValue();
                        });
                    });
                });
            }
        },

        watch: {
            template: function (val, oldVal) {
                const component = this;

                this.$nextTick(() => {
                    component.fillInputs();
                    component.initWysiwygFields();
                    component.initImageUploadFields();
                    component.bindEvents();
                });
            },
        }

    }
</script>
