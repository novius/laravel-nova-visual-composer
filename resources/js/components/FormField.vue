<template>
    <default-field :field="field" :errors="errors" :full-width-content="true">
        <template slot="field">
            <draggable
                    v-model="rows"
                    :options="{ handle: '.js-row-item-move' }"
                    @end="endDrag"
            >
                <row
                        v-for="(row, index) in rows"
                        v-model="rows[index]"
                        :key="row.index"
                        :index="row.index"
                        :type="row.type"
                        :position="row.position"
                        :initialValue="row.initialValue"
                        :totalRows="totalRows"
                        @delete-row="deleteRow($event)"
                        @update-row="updateRow($event)"
                        @add-row-before="showModalAddRow($event, 'before')"
                        @add-row-after="showModalAddRow($event, 'after')"
                ></row>
            </draggable>

            <div class="actions">
                <select v-model="templateSelector" class="w-full form-control form-select mb-2">
                    <option
                            value=""
                            v-text=""
                            selected
                            disabled
                    >
                        Choisir un template
                    </option>
                    <option
                            v-for="template in field.templates"
                            :value="template.classname"
                            v-text="template.name_trans"
                    ></option>
                </select>

                <button
                        class="btn btn-default btn-primary"
                        @click.prevent="addNewRow(templateSelector)"
                        v-text="field.addRowButtonLabel"
                ></button>
            </div>

            <portal to="modals" v-if="rowModalOpened">
                <show-add-row-modal
                        :templates="field.templates"
                        v-if="rowModalOpened"
                        @add-row="addRowAtSpecificIndex($event)"
                        @close="closeAddRowModal"
                />
            </portal>
        </template>
    </default-field>
</template>

<script>
    import draggable from 'vuedraggable'
    import Row from './Rows/Row.vue'
    import Vue from 'vue';
    import { FormField, HandlesValidationErrors } from 'laravel-nova'

    export default {
        mixins: [FormField, HandlesValidationErrors],

        components: {
            draggable,
            Row
        },

        props: ['resourceName', 'resourceId', 'field'],

        data: () => ({
            value: '',
            rows: [],
            counter: 0,
            rowModalOpened: false,
            addRowDesiredIndex: null,
        }),

        computed: {
            addButtonText() {
                return (this.field.add_button_text)
                        ? this.field.add_button_text
                        : 'Add row'
            },

            templateLabel(template) {
                return (template.name !== template.name_trans) ? template.name_trans : template.name;
            },

            totalRows() {
                return this.rows.length;
            },
        },

        methods: {
            /**
             * Set the initial, internal value for the field.
             */
            setInitialValue() {
                this.value = this.field.value || '';
                this.$nextTick(() => {
                    if (this.value !== '') {
                        this.resetRowsFromValue();
                    }
                });
            },
            /**
             * Reset rows and create them from value
             */
            resetRowsFromValue() {
                this.rows = [];

                const rows = JSON.parse(this.value);
                rows.forEach((row) => {
                    this.addNewRow(row.template, row.content);
                });
            },
            /**
             * Fill the given FormData object with the field's internal value.
             */
            fill(formData) {
                formData.append(this.field.attribute, this.value || '')
            },
            /**
             * Update the field's internal value.
             */
            handleChange(value) {
                this.value = value
            },
            /**
             * Create a new new
             * @param rowType
             * @param content
             */
            addNewRow(rowType, content) {
                this.addNewRowAtIndex(rowType, content, this.rows.length);
            },

            addNewRowAtIndex(rowType, content, index) {
                if (!rowType) {
                    return;
                }

                const tmpIndex = this.totalRows;
                const newRow = new Vue({
                    ...Row,
                    propsData: {
                        type: rowType,
                        initialValue: (content ? content : ''),
                        index: tmpIndex,
                        totalRows: this.totalRows,
                        position: tmpIndex,
                    }
                });

                this.rows.push(newRow);

                if (index !== (this.rows.length - 1)) {
                    this.rows.splice(index, 0, this.rows.splice(tmpIndex, 1)[0]);
                    this.refreshRowsPosition();
                }

                this.refreshValue();
            },

            deleteRow(event) {

                const index = this.rows.findIndex(function (row) {
                    return row.index === event[0];
                });

                if (index !== -1) {
                    // Delete selected row
                    this.$delete(this.rows, index);
                    // Refresh all positions
                    this.refreshRowsPosition();
                    // Refresh field value
                    this.refreshValue();
                }
            },

            updateRow(event) {
                const index = this.rows.findIndex(function (row) {
                    return row.index === event[0];
                });

                if (index !== -1) {
                    // Update value of current row updated
                    this.rows[index]['initialValue'] = event[1];
                    // Update field value
                    this.refreshValue();
                }
            },

            endDrag() {
                this.refreshRowsPosition();
                this.$forceUpdate(); // required because Vue doesn't render components after @end event of draggable
                this.refreshValue();
            },

            refreshRowsPosition() {
                this.rows.forEach((row, rowIndex) => {
                    this.$set(row, 'position', rowIndex);
                });
            },

            refreshValue() {
                let contents = [];
                this.rows.forEach((row) => {

                    contents.push({
                        template: row.type,
                        content: row.initialValue,
                    });
                });

                this.value = JSON.stringify(contents);
            },

            showModalAddRow($event, where) {
                let index = parseInt($event[0]);
                if (where === 'after') {
                    index++;
                }

                this.addRowDesiredIndex = index;
                this.rowModalOpened = true;
            },

            closeAddRowModal() {
                this.addRowDesiredIndex = null;
                this.rowModalOpened = false;
            },

            addRowAtSpecificIndex($event) {
                this.addNewRowAtIndex($event[0], '', this.addRowDesiredIndex);

                this.addRowDesiredIndex = null;
                this.rowModalOpened = false;
            },
        },
    }
</script>
