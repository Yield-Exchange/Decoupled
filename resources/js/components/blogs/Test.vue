<template>
    <div>

        <ckeditor :editor="editor" v-model="editorData" :config="editorConfig">
        </ckeditor>

        {{editorData}}
    </div>
</template>
<style scoped>

</style>
<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import UploadAdapter from './UploadAdapter';


export default {
    components: {

    },
    data() {
        return {
            data: '',
            editor: ClassicEditor,
            editorData: '',
            editorConfig: {
                extraPlugins: [this.uploader],
            }

        };
    },
    methods: {

        uploader(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                // console.log(document.querySelector('meta[name="csrf-token"]').content);
                console.log(loader);
                return new UploadAdapter(loader);
            };
        },
    }
};
</script>
