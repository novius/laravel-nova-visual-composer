<template>
    <panel-item :field="field">
        <div slot="value" class="summary" v-html="template"></div>
    </panel-item>
</template>

<script>
  export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data: () => ({
      template: 'Loading ...',
    }),

    mounted() {
      if (!this.field.value) {
        this.template = '-';
        return;
      }

      let rowsParam = JSON.parse(this.field.value);
      rowsParam.forEach((row, index) => {
        rowsParam[index] = {
          template: row['template'],
          content: '', // Prevent "entity too large" HTTP error
        };
      });

      Nova.request({
        url: '/nova-vendor/nova-visual-composer/rows-summary',
        method: 'GET',
        params: {
          rows: JSON.stringify(rowsParam),
        },
      }).then(({data}) => {
        if (data.error) {
          this.$toasted.show(data.message, {type: 'error'});
        } else {
          if (!data.summaryHTML) {
            this.template = '-';
          } else {
            this.template = data.summaryHTML;
          }
        }
      });
    },
  }
</script>
