<template>
    <modal @modal-close="handleClose">
        <div
                class="bg-white rounded-lg shadow-lg overflow-hidden"
                style="width: 800px"
        >
            <div class="p-8">
                <heading :level="2" class="mb-6">
                    {{ __('Show Source') }}
                </heading>
                <textarea ref="theTextarea" v-html="htmlSource"></textarea>
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
                        {{ __('Update') }}
                    </button>
                </div>
            </div>
        </div>
    </modal>
</template>

<style src="codemirror/lib/codemirror.css"/>
<style src="codemirror/theme/dracula.css"/>

<script>
  import CodeMirror from 'codemirror'
  import 'codemirror/mode/htmlmixed/htmlmixed'

  export default {

    props: [
      'htmlSource',
    ],

    data: () => ({
      codemirrorEditor: null,
      beautifyHtml: null,
    }),

    /**
     * Mount the component.
     */
    mounted() {
      this.beautifyHtml = require('js-beautify').html;

      const config = {
        tabSize: 4,
        indentWithTabs: true,
        lineWrapping: true,
        lineNumbers: true,
        theme: 'dracula',
        viewportMargin: Infinity,
        mode: 'htmlmixed',
      };

      this.$refs.theTextarea.value = this.beautifyHtml(this.$refs.theTextarea.value, { indent_size: 2 });
      this.codemirrorEditor = CodeMirror.fromTextArea(this.$refs.theTextarea, config);
    },

    computed: {
      doc() {
        return this.codemirrorEditor.getDoc()
      },
    },

    methods: {

      handleClose() {
        this.$emit('close')
      },

      handleConfirm() {
        this.$emit('update', [this.codemirrorEditor.getValue()])
      },
    },
  }
</script>
