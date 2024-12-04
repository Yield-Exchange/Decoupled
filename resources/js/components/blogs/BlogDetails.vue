<template>
    <div class="row">
        <div class="col-md-10">
            <div class="card  card-center" id="card">
                <div class="card-body" id="card-body">
                    <div class="row" style="border-bottom: 1px solid #eee;margin-bottom: 40px;border-bottom: 1px solid #eee;text-align: left;padding-bottom: 5px;">
                         <h5 style="font-weight: 600;">{{ blog_detail.title }}</h5>
                          <p class="blog-title-desc">
                            <span><i class="fa fa-clock-o"></i> {{ new Date(blog_detail.created_at).toISOString().slice(0, 10) }}</span> <span><i class="fa fa-user"></i> {{ blog_detail.category ? blog_detail.created_by.name: '' }}</span> 
                            <span><i class="fa fa-cube"></i> {{ blog_slugs }}</span>
                            <span><i class="fa fa-tag"></i> {{ blog_detail.category.name }}</span>
                          </p>
                          <br/>
                    </div>
                    <div class="row">
                        <b-img style="max-height: 500px" :src="blog_image" fluid alt="Blog Image"></b-img>
                    </div>
                    <div class="row blog_detail_body" v-html="blog_detail.body" style="margin-top: 20px" />
                </div>
            </div>
        </div>

         <div class="col-md-2">
            <h5 style="margin-top: 50px;border-bottom: 1px solid #ccc;padding-bottom: 10px;">Tags <i class="fa fa-cube"></i></h5>
            <ul class="tag-link list-inline">
                <li class="list-inline-item" v-for="(tag, indx) in tag_data" :key="indx" :index="indx">
                       <a style="color:white" :href="base_path+'/blogs/tag/'+tag.id">{{ tag.name }}</a>
                </li>
            </ul>

             <h5 style="margin-top: 50px;border-bottom: 1px solid #ccc;padding-bottom: 10px;">Categories <i class="fa fa-tag"></i></h5>
             <ul style="padding-left: 0">
                 <li v-for="(category, indx) in category_data" :key="indx" :index="indx">
                    <a :href="base_path+'/blogs/category/'+category.id">{{ category.name }}</a>
                 </li>
             </ul>
        </div>

        <div class="card-spacer" style="height:200px"></div>

    </div>
</template>
<style>
    .blog_detail_body p{
        font-size: unset; 
        font-family: unset;
        font-weight: unset;
        color: unset;
        text-align: unset;
    }
</style>
<style scoped>
    .blog-title-desc{
            padding-bottom:0;margin-bottom:0;margin-top: 8px;
            width:100%;
    }
    .blog-title-desc span{
        margin-right: 10%;
    }

    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: transparent;
        background-clip: border-box;
        border: none;
        /* border-radius: 0.25rem; */
    }

    .tag-link{
        /* padding-left: 3; */
        display: inline-block;
        font-size: 12px;
        margin-bottom: 10px;
        margin-right: 10px;
        color: #fff;
    }
    .list-inline-item{
        background-color: #628BF2;
        padding: 5px;
        margin-bottom: 5px;
    }
</style>
<script>
    export default {
        components: {
            
        },
        mounted(){
        },
        created() {
            // console.log(this.blog);
        },
        props: ['blog','categories','tags','base_path'],
        data() {
            let blog_slugs_ = JSON.parse(this.blog).slug_objects;
            let blog_slugs__string = "";
            blog_slugs_ && blog_slugs_.map((slug)=>{
                blog_slugs__string+=slug.name+",";
            });
           return {
                blog_detail: JSON.parse(this.blog),
                blog_image: this.base_path+'/blog/images/'+JSON.parse(this.blog).main_image,
                blog_slugs: blog_slugs__string,
                category_data: this.categories ? JSON.parse(this.categories) : null,
                tag_data: this.tags ? JSON.parse(this.tags) : null
           }
        },
        watch: {

        },
        computed: {
            
        },
        methods: {
            
        }
    }
</script>