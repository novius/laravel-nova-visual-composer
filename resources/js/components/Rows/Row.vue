<template>
    <div v-bind:id="'list-' + id" class="row-item mb-4">

        <div class="row-item-inputs flex-wrap">
            <div class="text-center w-full mb-4" v-if="addRowBeforeVisible">
                <button @click="addRowBefore" type="button" class="leading-none">
                    <svg width="20" class="svg-icon" viewBox="0 0 20 20">
                        <path fill="currentColor"
                              d="M13.388,9.624h-3.011v-3.01c0-0.208-0.168-0.377-0.376-0.377S9.624,6.405,9.624,6.613v3.01H6.613c-0.208,0-0.376,0.168-0.376,0.376s0.168,0.376,0.376,0.376h3.011v3.01c0,0.208,0.168,0.378,0.376,0.378s0.376-0.17,0.376-0.378v-3.01h3.011c0.207,0,0.377-0.168,0.377-0.376S13.595,9.624,13.388,9.624z M10,1.344c-4.781,0-8.656,3.875-8.656,8.656c0,4.781,3.875,8.656,8.656,8.656c4.781,0,8.656-3.875,8.656-8.656C18.656,5.219,14.781,1.344,10,1.344z M10,17.903c-4.365,0-7.904-3.538-7.904-7.903S5.635,2.096,10,2.096S17.903,5.635,17.903,10S14.365,17.903,10,17.903z"></path>
                    </svg>
                </button>
            </div>

            <div class="row-template w-full relative">
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
                <div class="row-display" ref="rowDynamicContent"></div>
            </div>

            <div class="text-center w-full" v-if="addRowAfterVisible">
                <button @click="addRowAfter" type="button" class="leading-none">
                    <svg width="20" class="svg-icon" viewBox="0 0 20 20">
                        <path fill="currentColor"
                              d="M13.388,9.624h-3.011v-3.01c0-0.208-0.168-0.377-0.376-0.377S9.624,6.405,9.624,6.613v3.01H6.613c-0.208,0-0.376,0.168-0.376,0.376s0.168,0.376,0.376,0.376h3.011v3.01c0,0.208,0.168,0.378,0.376,0.378s0.376-0.17,0.376-0.378v-3.01h3.011c0.207,0,0.377-0.168,0.377-0.376S13.595,9.624,13.388,9.624z M10,1.344c-4.781,0-8.656,3.875-8.656,8.656c0,4.781,3.875,8.656,8.656,8.656c4.781,0,8.656-3.875,8.656-8.656C18.656,5.219,14.781,1.344,10,1.344z M10,17.903c-4.365,0-7.904-3.538-7.904-7.903S5.635,2.096,10,2.096S17.903,5.635,17.903,10S14.365,17.903,10,17.903z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <portal to="modals" v-if="sourceModalOpened">
            <show-wysiwyg-source-modal
                ref="wysiwygModal"
                :htmlSource="htmlSourceModal"
                v-if="sourceModalOpened"
                @update="saveAndCloseSourceModal($event)"
                @close="closeSourceModal"
            />
        </portal>
    </div>
</template>

<style>
    .ql-html:after {
        content: "html";
    }
</style>

<script>
    import Quill from 'quill';
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
            'id',
            'type',
            'initialValue',
            'totalRows',
            'positions',
        ],

        data: () => ({
            sourceModalOpened: false,
            htmlSourceModal: '',
            quillEditorConcernedByModal: null,
            value: '',
            template: '',
        }),

        mounted() {
            Nova.request({
                url: '/nova-vendor/nova-visual-composer/template-content',
                method: 'GET',
                params: {
                    template: this.type
                },
            }).then(({ data }) => {
                if (data.error) {
                    this.deleteRow();
                    this.$toasted.show(data.message, { type: 'error' });
                } else {
                    this.template = data.templateHTML;
                    this.$refs.rowDynamicContent.innerHTML = this.template;

                    this.$nextTick(() => {
                        this.fillInputs();
                        this.initWysiwygFields();
                        this.initImageUploadFields();
                        this.bindEvents();
                        this.value = this.getValue();
                    });
                }
            });
        },

        updated() {
            this.$nextTick(() => {
                if (this.$refs.rowDynamicContent.innerHTML === '') {
                    // Sometimes, rowDynamicContent is emptied by Vue.js (bug of vuedraggable ?) => we have to reset HTML content and re-bind events
                    this.$refs.rowDynamicContent.innerHTML = this.template;

                    this.fillInputsFromValue();
                    this.initWysiwygFields();
                    this.initImageUploadFields();
                    this.bindEvents();
                }
            });
        },

        methods: {
            deleteRow() {
                this.$emit('delete-row', [this.id]);
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
                this.value = this.getValue();

                this.$emit('update-row', [this.id, this.value]);

                this.$forceUpdate();
            },

            fillInputs() {
                if (!this.$el || !this.initialValue || typeof this.initialValue !== 'string') {
                    return;
                }

                const content = JSON.parse(this.initialValue);
                const fields = this.$el.querySelectorAll('.js-visual-field');

                if (!fields.length) {
                    return;
                }

                fields.forEach((field, index) => {
                    const fieldName = field.dataset.fieldName;
                    if (! fieldName) {
                        console.error('Row field error : data-field-name attribute is required.', field);
                        return;
                    }

                    const contentIndex = _.findIndex(content, function(o) { return o.name === fieldName; });
                    if (contentIndex === -1) {
                        return;
                    }

                    const fieldContent = content[contentIndex]['content'];
                    if (field.classList.contains('js-image-uploader')) {
                        field.setAttribute('data-value', fieldContent.join('|'));
                    } else {
                        field.value = fieldContent;
                    }
                });
            },

            fillInputsFromValue() {
                if (!this.$el || !this.value || typeof this.value !== 'string') {
                    return;
                }

                const content = JSON.parse(this.value);
                const fields = this.$el.querySelectorAll('.js-visual-field');

                if (!fields.length) {
                    return;
                }

                fields.forEach((field, index) => {
                    const fieldName = field.dataset.fieldName;
                    if (! fieldName) {
                        console.error('Row field error : data-field-name attribute is required.', field);
                        return;
                    }

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

                    const pond = FilePond.create(uploadElement, filePondConfig);
                    const domElement = pond.element;
                    if (uploadElement.dataset.fieldName) {
                        domElement.dataset.fieldName = uploadElement.dataset.fieldName;
                    }
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

                const component = this;

                wysiwygElements.forEach((wysiwyg) => {
                    const parentNode = wysiwyg.parentNode;

                    // Create Editor container
                    const node = document.createElement('DIV');
                    node.classList.add('js-quill-editor');
                    parentNode.appendChild(node);

                    // Hide textarea
                    wysiwyg.style.display = 'none';

                    const quillEditor = parentNode.querySelector('.js-quill-editor');
                    quillEditor.innerHTML = wysiwyg.value;

                    var quill = new Quill(quillEditor, {
                        modules: {
                            toolbar: {
                                container: window.laraNovaVisualComposerConfig.quill.toolbarOptions,
                                handlers: {
                                    'html': () => {
                                        component.showHtmlModal(quillEditor, wysiwyg)
                                    },
                                }
                            }
                        },
                        theme: 'snow'
                    });

                    quill.on('text-change', function (delta, oldDelta, source) {
                        wysiwyg.value = quill.root.innerHTML;
                        component.refreshValue();
                    });
                });
            },

            showHtmlModal(quillEditor, wysiwyg) {
                this.openSourceModal(quillEditor, wysiwyg);
            },

            cleanSourceModal() {
                this.htmlSourceModal = '';
                this.quillEditorConcernedByModal = null;
            },

            openSourceModal(quillEditor, wysiwyg) {
                this.quillEditorConcernedByModal = quillEditor.__quill;
                this.htmlSourceModal = wysiwyg.value;
                this.sourceModalOpened = true;
            },

            closeSourceModal() {
                this.cleanSourceModal();
                this.sourceModalOpened = '';
                this.sourceModalOpened = false;
            },

            saveAndCloseSourceModal($event) {
                this.quillEditorConcernedByModal.setContents([])
                this.quillEditorConcernedByModal.clipboard.dangerouslyPasteHTML(0, $event[0]);
                this.cleanSourceModal();
                this.sourceModalOpened = '';
            },

            addRowBefore() {
                this.$emit('add-row-before', [this.position]);
            },

            addRowAfter() {
                this.$emit('add-row-after', [this.position]);
            },

            getValue() {
                let contents = [];
                const row = this.$el;

                if (!row) {
                    return JSON.stringify(contents);
                }

                const fields = row.querySelectorAll('.js-visual-field');

                if (!fields.length) {
                    return JSON.stringify(contents);
                }

                fields.forEach((field) => {
                    const fieldName = field.dataset.fieldName;
                    if (! fieldName) {
                        console.error('Row field error : data-field-name attribute is required.', field);
                        return;
                    }

                    const fieldData = {
                        name: fieldName,
                        content: '',
                    };

                    if (field.classList.contains('js-image-uploader')) {
                        let images = [];
                        const inputFields = field.querySelectorAll('input[name=filepond]');
                        if (inputFields.length) {
                            inputFields.forEach((input) => {
                                if (input.value !== '') {
                                    images.push(input.value);
                                }
                            });
                            fieldData.content = images;
                        } else {
                            fieldData.content = [];
                        }
                    } else {
                        fieldData.content = field.value;
                    }

                    contents.push(fieldData);
                });

                return JSON.stringify(contents);
            },
        },

        computed: {
            addRowBeforeVisible() {
                return parseInt(this.position, 10) === 0;
            },

            addRowAfterVisible() {
                return (this.position + 1) < this.totalRows;
            },

            position() {
                const component = this;

                const index = this.positions.findIndex(function (value) {
                    return component.id === value;
                });

                if (index === -1) {
                    return this.totalRows;
                }

                return index;
            },
        },
    }
</script>
