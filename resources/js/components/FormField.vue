<template>
    <default-field :field="field" :errors="errors" :show-help-text="showHelpText" :full-width-content="true">
        <template slot="field">
            <editor
                v-model="value"
                :api-key="apiKey"
                cloud-channel="5"
                :init="editorConfig"
                :plugins="editorPlugins"
                :toolbar="editorToolbar"
                :class="errorClasses"
                :placeholder="field.placeholder"
                :id="field.id"
                :name="field.name"
                :height="800"
            />
        </template>
    </default-field>
</template>

<script>
    import { FormField, HandlesValidationErrors } from 'laravel-nova'
    import Editor from '@tinymce/tinymce-vue'

    export default {
        mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],

        components: {
            editor: Editor
        },

        data() {
            return {
                editorConfigInit: this.field.options.init,
                editorPlugins: this.field.options.plugins,
                editorToolbar: this.field.options.toolbar,
                apiKey: this.field.options.apiKey
            }
        },

        computed: {
            editorConfig: function() {
                this.editorConfigInit.images_upload_url = 'true';
                this.editorConfigInit.images_upload_handler = this.uploadImage;

                return this.editorConfigInit;
            }
        },

        methods: {
            setInitialValue() {
                this.value = this.field.value || ''
            },

            fill(formData) {
                formData.append(this.field.attribute, this.value || '')
            },

            uploadImage(blobInfo, success, failure, progress) {

                let xhr, formData;

                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;

                xhr.open('POST', `/nova-vendor/nova-tinymce-field/upload-image/${this.resourceName}/${this.field.attribute}`);

                xhr.upload.onprogress = function (e) {
                    progress(e.loaded / e.total * 100);
                };

                xhr.onload = function() {
                    var json;

                    if (xhr.status === 403) {
                        failure('HTTP Error: ' + xhr.status, { remove: true });
                        return;
                    }

                    if (xhr.status < 200 || xhr.status >= 300) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }

                    json = JSON.parse(xhr.responseText);

                    if (!json || typeof json.url != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }

                    success(json.url);
                };

                xhr.onerror = function () {
                    failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
                };

                const token = document.head.querySelector("[name=csrf-token]").content;

                xhr.setRequestHeader("X-CSRF-Token", token);

                formData = new FormData();
                formData.append('image', blobInfo.blob(), blobInfo.filename());

                xhr.send(formData);
            }
        },
    }

    function uploadImage(blobInfo, success, failure, progress) {

    }
</script>
