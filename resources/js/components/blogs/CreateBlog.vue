<template>
    <!--https://github.com/davidroyer/vue2-editor-->
    <div class="row" style="width:100%">

        <b-col sm="9">
            <h4 class="text-center color-black" style="color: black;"> Create a blog</h4>
            <br />
            <div class="card">
                <div class="card-body" style="padding-top:0">
                    <div class="row">
                        <div class="form-group row">
                            <div class="col-lg-8">
                                <div class="row" style="margin-top: 10px" v-if="this.formErrors">
                                    <b-alert show :variant="'info'">{{  this.formErrors  }}</b-alert>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <b-col sm="12">
                            <label for="title">Title *</label>
                            <b-form-input placeholder="Title" class="font-13" v-model="title" id="title">
                            </b-form-input>
                        </b-col>
                    </div>

                    <div class="row mb-2">
                        <b-col sm="12">
                            <label for="description">Description *</label>

                            <b-form-textarea id="description" v-model="description" placeholder="Short Description"
                                rows="5" max-rows="6">
                            </b-form-textarea>
                        </b-col>
                    </div>

                    <br />

                    <b-row>
                        <b-col sm="12" style="margin-bottom: 10px">
                            <label for="editor">Content *</label>

                            <ckeditor id="editor" :editor="editor" v-model="content" :config="editorConfig">
                            </ckeditor>

                        </b-col>
                    </b-row>

                </div>
            </div>
            <div class="card">
                <div class="card-header" style="border-bottom: 1px solid #ced4da;padding-top: 10px;padding-bottom: 10px;">
                    Search Engine Optimize
                </div>

                <div class="card-body" style="padding-top:0">
                    <div class="row mb-2">
                        <b-col sm="12">
                            <label for="seo_title">SEO Title</label>
                            <b-form-input placeholder="SEO Title" class="font-13" v-model="seo_title" id="seo_title">
                            </b-form-input>
                        </b-col>
                    </div>

                    <div class="row mb-2">
                        <b-col sm="12">
                            <label for="seo_description">SEO Description</label>

                            <b-form-textarea id="seo_description" v-model="seo_description"
                                placeholder="Short Description" rows="5" max-rows="6">
                            </b-form-textarea>
                        </b-col>
                    </div>
                </div>
            </div>
        </b-col>

        <b-col sm="3">
            <div class="row">
                <div style="height: 55px"></div>
                <div class="card">
                    <div class="card-header"
                        style="border-bottom: 1px solid #ced4da;padding-top: 10px;padding-bottom: 10px;">
                        Publish
                    </div>
                    <div class="card-body" style="padding-top: 10px">
                        <b-row>
                            <b-col cols="4">
                                <b-button :variant="'primary'" :disabled="submitButtonSpinner" :size="'md'"
                                    style="font-size:15px;" @click="doSubmit()">
                                    <b-spinner small variant="light" label="Loading" style="margin-right:5px"
                                        v-if="submitButtonSpinner">
                                    </b-spinner>
                                    {{  submitButtonText  }}
                                </b-button>
                            </b-col>
                            <b-col cols="8">
                                <b-button :variant="'success'" :disabled="submitEditButtonSpinner" :size="'md'"
                                    style="font-size:15px;" @click="doSubmit(true)">
                                    <b-spinner small variant="light" label="Loading" style="margin-right:5px"
                                        v-if="submitEditButtonSpinner">
                                    </b-spinner>
                                    {{  submitEditButtonText  }}
                                </b-button>
                            </b-col>
                        </b-row>
                    </div>
                </div>
            </div>

            <div class="row">
                <div style="height: 5px"></div>
                <div class="card" style="height:120px">
                    <div class="card-header"
                        style="border-bottom: 1px solid #ced4da;padding-top: 10px;padding-bottom: 10px;">
                        Status *
                    </div>
                    <div class="card-body" style="padding-top: 10px">
                        <b-row>
                            <v-select label="status" :options="this.getStatuses" class="font-13"
                                placeholder="Select Status*" style="color: #212529;font-weight: 400;" id="status"
                                v-model="status">
                            </v-select>
                        </b-row>
                    </div>
                </div>
            </div>

            <div class="row">
                <div style="height: 5px"></div>
                <div class="card" style="height:120px">
                    <div class="card-header"
                        style="border-bottom: 1px solid #ced4da;padding-top: 10px;padding-bottom: 10px;">
                        Category *
                    </div>
                    <div class="card-body" style="padding-top: 10px">
                        <b-row>
                            <v-select label="name" :options="categories_d" class="font-13"
                                placeholder="Select category*" style="color: #212529;font-weight: 400;" id="category"
                                v-model="category">
                            </v-select>
                        </b-row>
                    </div>
                </div>
            </div>

            <div class="row">
                <div style="height: 5px"></div>
                <div class="card" style="height:150px">
                    <div class="card-header"
                        style="border-bottom: 1px solid #ced4da;padding-top: 10px;padding-bottom: 10px;">
                        Tags
                    </div>
                    <div class="card-body" style="padding-top: 10px">
                        <b-row>

                            <v-select multiple label="name" :options="tags_d" class="font-13" placeholder="Select tag*"
                                style="color: #212529;font-weight: 400;" id="tags" v-model="tag">
                            </v-select>
                        </b-row>
                    </div>
                </div>
            </div>

            <div class="row">
                <div style="height: 5px"></div>
                <div class="card" style="min-height:150px">
                    <div class="card-header"
                        style="border-bottom: 1px solid #ced4da;padding-top: 10px;padding-bottom: 10px;">
                        Image*
                    </div>
                    <div class="card-body" style="padding-top: 10px">
                        <b-row>
                            <b-col cols="12">
                                <b-form-file accept="image/*" @change="loadImage($event)"
                                    placeholder="Choose an image..." drop-placeholder="Drop image here...">
                                </b-form-file>
                            </b-col>
                            <b-row>
                                <b-col cols="11">
                                    <cropper ref="cropper" class="cropper" :src="image.src" :stencil-props="{
                                        handlers: {},
                                        movable: false,
                                        scalable: false,
                                        aspectRatio: 7 / 4,
                                    }" :stencil-size="{
                                        width: 540,
                                        height: 360
                                    }" :resize-image="{
                                        adjustStencil: false
                                    }" image-restriction="stencil" />
                                    <b-button variant="danger" size="sm" v-if="image.src" @click="this.reset"
                                        style="margin-top:10px">
                                        <b-icon icon="x" variant="light"></b-icon>
                                        Remove
                                    </b-button>

                                </b-col>
                            </b-row>
                            <b-col cols="12" v-if="main_image">
                                <br />
                                <b-img :src="'/blog/images/' + main_image" fluid :alt="title"></b-img>
                            </b-col>
                        </b-row>
                    </div>
                </div>
            </div>

        </b-col>

    </div>
</template>
<style>
.ck.ck-content.ck-editor__editable.ck-rounded-corners {
    min-height: 300px !important;
}

.ql-editor p,
.ql-editor li {
    font-weight: normal;
    color: black;
}

.ql-snow .ql-tooltip {
    left: 100px !important;
}

.ql-clipboard {
    left: 4px !important;
}

.cropper {
    max-height: 500px;
    max-width: 500px;
    background: #DDD;
}
</style>
<script>
// This function is used to detect the actual image type,
function getMimeType(file, fallback = null) {
    const byteArray = (new Uint8Array(file)).subarray(0, 4);
    let header = '';
    for (let i = 0; i < byteArray.length; i++) {
        header += byteArray[i].toString(16);
    }
    switch (header) {
        case "89504e47":
            return "image/png";
        case "47494638":
            return "image/gif";
        case "ffd8ffe0":
        case "ffd8ffe1":
        case "ffd8ffe2":
        case "ffd8ffe3":
        case "ffd8ffe8":
            return "image/jpeg";
        default:
            return fallback;
    }
}

import axios from 'axios'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import UploadAdapter from './UploadAdapter';
export default {
    components: {

    },
    mounted() {
    },
    created() {
        // console.log(this.blog);
    },
    props: ['blog', 'categories', 'tags', 'statuses'],
    data() {
        let blog_ = this.blog ? JSON.parse(this.blog) : null;
        let categories_ = JSON.parse(this.categories);
        let tags_ = JSON.parse(this.tags);
        let statuses_ = JSON.parse(this.statuses);
        // let tag_decoded = null;
        // try{
        //     tag_decoded = JSON.parse(blog_.tags);
        // }catch(e){}
        return {
            submitEditButtonText: 'Save & Edit',
            submitEditButtonSpinner: false,
            submitButtonSpinner: false,
            submitButtonText: 'Save',
            description: blog_ ? blog_.description : '',
            content: blog_ ? blog_.body : '',
            formErrors: '',
            seo_title: blog_ ? blog_.seo_title : '',
            seo_description: blog_ ? blog_.seo_description : '',
            status: blog_ ? blog_.status : '',
            getStatuses: statuses_,
            image: {
                src: null,
                type: null
            },
            image_uploaded: null,
            main_image: blog_ ? blog_.main_image : '',
            // tags: blog_ ? blog_.tags : '',
            title: blog_ ? blog_.title : '',
            categories_d: categories_,
            tags_d: tags_,
            category: blog_ ? blog_.category : null,
            tag: blog_ ? blog_.slug_objects : null,
            editor: ClassicEditor,
            editorConfig: {
                extraPlugins: [this.handleImageAdded],
            }
        }
    },
    watch: {

    },
    computed: {
        allowSubmit() {
            return this.canSubmit();
        },
    },
    methods: {
        canSubmit() {
            return /*this.image &&*/ this.title && this.status && this.description && this.content;
        },
        async doSubmit(continue_edit = false) {
            let this_ = this;

            if (!this_.canSubmit()) {
                this_.$swal({
                    title: 'Submit failed',
                    text: "All fields are required.",
                    confirmButtonText: 'Close'
                });
                return false;
            }

            const formData = new FormData();
            formData.append("title", this_.title);
            formData.append("description", this_.description);
            formData.append("content", this_.content);
            formData.append("status", this_.status);
            formData.append("seo_title", this_.seo_title);
            formData.append("seo_description", this_.seo_description);

            if (this_.tag) {
                this_.tag.map((obj, index) => {
                    // console.log(obj)
                    formData.append("tags[" + index + "]", obj.id);
                });
            } else {
                this_.$swal({
                    title: 'Saving Post failed',
                    text: "Tag is required",
                    confirmButtonText: 'Close'
                });
                return;
            }

            if (this_.category) {
                formData.append("category_id", this_.category?.id);
            } else {
                this_.$swal({
                    title: 'Saving Post failed',
                    text: "Category is required",
                    confirmButtonText: 'Close'
                });
                return;
            }

            if (this.blog) {
                let blog_ = this.blog ? JSON.parse(this.blog) : null;
                formData.append("action", 'edit');
                formData.append("blog_id", blog_.id);
            }

            let cropper_response = this.crop();
            let uploaded_image = null;
            if (cropper_response?.canvas) {
                uploaded_image = await new Promise(blob => cropper_response.canvas.toBlob((blob), cropper_response.type));
            }

            if (uploaded_image) {
                formData.append("image", uploaded_image);
            } else if (!this_.main_image) {
                this_.$swal({
                    title: 'Saving Post failed',
                    text: "Image is required",
                    confirmButtonText: 'Close'
                });
                return;
            }

            if (continue_edit) {
                this_.submitEditButtonText = "Saving..";
                this_.submitEditButtonSpinner = true;
            } else {
                this_.submitButtonText = "Saving..";
                this_.submitButtonSpinner = true;
            }

            axios.post('/yie-admin/blogs', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                let data = response?.data;

                if (data.success) {
                    this_.$swal({
                        title: 'Post Saved successfully',
                        text: data.message,
                        confirmButtonText: 'Close'
                    }).then(() => {
                        if (!continue_edit) {
                            window.location.href = '/yie-admin/blogs';
                        } else {
                            window.location.href = data.url;
                        }
                    });
                } else {
                    this_.$swal({
                        title: 'Saving Post failed',
                        text: data.message,
                        confirmButtonText: 'Close'
                    });
                }

                if (continue_edit) {
                    this_.submitEditButtonText = "Save & Edit";
                    this_.submitEditButtonSpinner = false;
                } else {
                    this_.submitButtonText = "Save";
                    this_.submitButtonSpinner = false;
                }
            }).catch(error => {
                if (error?.response?.status === 419) {
                    this_.formErrors = "The page has expired due to inactivity. Please refresh the page and try again.";
                }

                this_.$swal({
                    title: 'Saving Post failed',
                    text: error?.response?.data?.message,
                    confirmButtonText: 'Close'
                });

                if (continue_edit) {
                    this_.submitEditButtonText = "Save & Edit";
                    this_.submitEditButtonSpinner = false;
                } else {
                    this_.submitButtonText = "Save";
                    this_.submitButtonSpinner = false;
                }
            });

        },
        crop() {
            const { canvas } = this.$refs.cropper.getResult();

            if (!canvas || !this.image) {
                return null;
            }
            return {
                canvas: canvas, type: this.image.type
            }
        },
        reset() {
            this.image = {
                src: null,
                type: null
            };
            this.image_uploaded = null;
        },
        loadImage(event) {
            // Reference to the DOM input element
            const { files } = event.target;
            // Ensure that you have a file before attempting to read it
            if (files && files[0]) {
                // 1. Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
                if (this.image.src) {
                    URL.revokeObjectURL(this.image.src)
                }
                // 2. Create the blob link to the file to optimize performance:
                const blob = URL.createObjectURL(files[0]);

                // 3. The steps below are designated to determine a file mime type to use it during the
                // getting of a cropped image from the canvas. You can replace it them by the following string,
                // but the type will be derived from the extension and it can lead to an incorrect result:
                //
                // this.image = {
                //    src: blob;
                //    type: files[0].type
                // }

                // Create a new FileReader to read this image binary data
                const reader = new FileReader();
                // Define a callback function to run, when FileReader finishes its job
                reader.onload = (e) => {
                    // Note: arrow function used here, so that "this.image" refers to the image of Vue component
                    this.image = {
                        // Set the image source (it will look like blob:http://example.com/2c5270a5-18b5-406e-a4fb-07427f5e7b94)
                        src: blob,
                        // Determine the image type to preserve it during the extracting the image from canvas:
                        type: getMimeType(e.target.result, files[0].type),
                    };
                };
                // Start the reader job - read file as a data url (base64 format)
                reader.readAsArrayBuffer(files[0]);
            }
        },
        handleImageAdded(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new UploadAdapter(loader);
            };
        },
    },
    destroyed() {
        // Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
        if (this.image.src) {
            URL.revokeObjectURL(this.image.src)
        }
    }
}
</script>