Nova.booting((Vue, router, store) => {
    Vue.component('index-nova-visual-composer', require('./components/IndexField'))
    Vue.component('detail-nova-visual-composer', require('./components/DetailField'))
    Vue.component('form-nova-visual-composer', require('./components/FormField'))
});
