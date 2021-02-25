Nova.booting((Vue, router, store) => {
  Vue.component('index-nova-tinymce-field', require('./components/IndexField'))
  Vue.component('detail-nova-tinymce-field', require('./components/DetailField'))
  Vue.component('form-nova-tinymce-field', require('./components/FormField'))
})
