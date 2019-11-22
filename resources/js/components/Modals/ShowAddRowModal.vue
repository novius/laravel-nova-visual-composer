<template>
    <modal @modal-close="handleClose">
        <div
                class="bg-white rounded-lg shadow-lg overflow-hidden"
                style="width: 800px"
        >
            <div class="p-8">
                <heading :level="2" class="mb-6">
                    {{ __('Add row') }}
                </heading>
                <select v-model="templateSelector" class="w-full form-control form-select mb-2">
                    <option value=""
                            v-text=""
                            selected
                            disabled
                    >
                        Choisir un template
                    </option>
                    <option
                            v-for="template in templates"
                            :value="template.classname"
                            v-text="template.name_trans"
                    ></option>
                </select>
            </div>

            <div class="bg-30 px-6 py-3 flex">
                <div class="ml-auto">
                    <button
                            type="button"
                            @click.prevent="handleClose"
                            class="btn text-80 font-normal h-9 px-3 mr-3 btn-link"
                    >
                        {{ __('Cancel') }}
                    </button>

                    <button
                            ref="confirmButton"
                            @click.prevent="handleConfirm"
                            class="btn btn-default btn-primary"
                    >
                        {{ __('Add') }}
                    </button>
                </div>
            </div>
        </div>
    </modal>
</template>
<script>
  export default {

    props: [
      'templates',
    ],

    data: () => ({
      templateSelector: '',
    }),

    methods: {
      handleClose() {
        this.$emit('close')
      },

      handleConfirm() {
        if (!this.templateSelector) {
          this.$toasted.show('Merci de choisir un template.', {type: 'error'});
        } else {
          this.$emit('add-row', [this.templateSelector])
        }
      },
    },
  }
</script>
