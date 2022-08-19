import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-nova-tinymce-field', IndexField)
  app.component('detail-nova-tinymce-field', DetailField)
  app.component('form-nova-tinymce-field', FormField)
})
