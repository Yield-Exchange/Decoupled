<template>
    <div class="row">
        <div class="col-md-10">
            <div class="row" v-for="(blog, index) in blog_data" :key="index" :index="index">
                <div class="card-spacer"></div>

                <div class="col-md-12 col-padding-remover">
                    <div class="card-center">
                        <blog-card :data="blog" />
                    </div>
                </div>
            </div>
            <div class="row" v-if="blog_data.length > 10 && tag_list.length == 0 && s_category.length == 0">
                <b-pagination v-model="blogs_.current_page" :total-rows="blogs_.total" :per-page="blogs_.per_page" pills
                    size="lg" align="center" @change="pageClickCallback">
                </b-pagination>
            </div>
        </div>
        <div class="col-md-2">
            <h5 style="margin-top: 50px;border-bottom: 1px solid #ccc;padding-bottom: 10px;">Tags <i
                    class="fa fa-cube"></i></h5>
            <ul class="tag-link list-inline">
                <li class="list-inline-item" v-for="(tag, indx) in tag_data" :key="indx" :index="indx" :class="{
                    'tag-active': activeTag(tag.id)
                }" @click="addTag(tag.id)">
                    <a>{{ tag.name }}</a>
                </li>
            </ul>

            <h5 style="margin-top: 50px;border-bottom: 1px solid #ccc;padding-bottom: 10px;">Categories <i
                    class="fa fa-tag"></i></h5>
            <ul style="padding-left: 0">
                <li v-for="(category, indx) in category_data" :key="indx" :index="indx" class="category"
                    :class="{ 'category-active': category.id == s_category }" @click="changeCategory(category.id)">
                    <a>{{ category.name }}</a>
                </li>
            </ul>
        </div>

        <div class="card-spacer" style="height:200px"></div>
    </div>
</template>
<style scoped>
.tag-link {
    /* padding-left: 3; */
    display: inline-block;
    font-size: 12px;
    margin-bottom: 10px;
    margin-right: 10px;
    color: #fff;
}

.list-inline-item {
    background-color: #628BF2;
    padding: 5px;
    margin-bottom: 5px;
    cursor: pointer;
}

.list-inline-item a {
    color: white;
}

.tag-active {
    background-color: #43E0AA;
}

.category {
    color: #628BF2;
    cursor: pointer;
}

.category-active a {
    color: #43E0AA;
}

.pagination {}

.page-item {}
</style>
<script>
import axios from 'axios';
export default {
    components: {

    },
    mounted() {
    },
    created() {

    },
    props: ['blogs', 'tags', 'categories', 'base_path'],
    data() {
        let blogs_ = this.blogs ? JSON.parse(this.blogs) : null;
        return {
            blog_data: blogs_ ? blogs_.data : [],
            category_data: this.categories ? JSON.parse(this.categories) : null,
            tag_data: this.tags ? JSON.parse(this.tags) : null,
            blogs_: blogs_,
            tag_value: "tag",
            category_value: "category",
            tag_list: '',
            s_category: '',
        }
    },
    watch: {

    },
    computed: {
        last_url_segment() {
            return window.location.pathname.split("/").pop();
        },
        second_last_url_segment() {
            return document.URL.split('/').slice(-2)[0]
        },
        tagslist() {
            return this.tag_list.split(',');
        }
    },
    methods: {
        pageClickCallback(pageNum) {
            window.location.href = this.base_path + "/blogs?page=" + pageNum;
        },

        addTag(tagId) {
            if (!this.tag_list.includes(tagId)) {
                this.tag_list = this.tag_list + tagId + ",";
            } else {
                this.tag_list = this.tag_list.replace(tagId + ',', '');
            }
            this.getBlogs();
        },
        changeCategory(categoryId) {
            if (this.s_category == categoryId) {
                this.s_category = "";
            } else {
                this.s_category = categoryId;
            }
            this.getBlogs();
        },

        async getBlogs() {
            document.getElementById('proloader').style.display = 'block';
            let blogs = await axios.post(this.base_path + '/blogs', {

                tags: this.tag_list,
                category: this.s_category

            }).then(function (response) {
                document.getElementById('proloader').style.display = 'none';
                return response.data.blogs;
            }).catch(function (error) {
                return error;
            });

            if (this.tag_list.length == 0 &&
                this.s_category.length == 0) {
                this.blog_data = this.blogs_.data;
            } else {
                this.blog_data = blogs;
            }



        },
        activeTag(tagId) {
            if (this.tag_list.includes(tagId)) {
                let tagList = this.tag_list.split(',');
                let tag = tagList.find(tag => tag == tagId);
                return (tag == tagId) ? true : false;
            }
            return false;
        }
    }
}
</script>