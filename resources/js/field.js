import ShowWysiwygSourceModal from './components/Modals/ShowWysiwygSourceModal'
import ShowAddRowModal from './components/Modals/ShowAddRowModal'

Nova.booting((Vue, router, store) => {
  Vue.component('show-wysiwyg-source-modal', ShowWysiwygSourceModal)
  Vue.component('show-add-row-modal', ShowAddRowModal)
  Vue.component('index-nova-visual-composer', require('./components/IndexField'))
  Vue.component('detail-nova-visual-composer', require('./components/DetailField'))
  Vue.component('form-nova-visual-composer', require('./components/FormField'))
});
