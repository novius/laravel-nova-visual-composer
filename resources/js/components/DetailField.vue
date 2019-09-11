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

      Nova.request({
        url: '/nova-vendor/nova-visual-composer/rows-summary',
        method: 'GET',
        params: {
          rows: this.field.value
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
