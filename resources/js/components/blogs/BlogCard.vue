<template>
        <div class="card-people card-shadow row col-md-12" style="min-height: 220px;padding: 0;">
            <div class="col-md-5 center-vertically" :style="'background-image: url('+this.blog_image+');background-size: cover;margin-bottom: 0;cursor: pointer;'" @click="openBlog()">
            </div>
            <div class="col-md-7">
                <div class="card-body" style="margin-top: 10px">
                    <h5 class="card-title" @click="openBlog()" style="cursor: pointer;border-bottom: 1px solid #eee;padding-bottom: 10px;text-align: left">{{ data.title }}
                        <p class="blog-title-desc">
                            <span><i class="fa fa-clock-o"></i> {{ new Date(data.created_at).toISOString().slice(0, 10) }}</span> <span><i class="fa fa-user"></i> {{ data.category ? data.created_by.name: '' }}</span> 
                            <!-- <span><i class="fa fa-cube"></i> {{ blog_slugs }}</span> -->
                            <span><i class="fa fa-tag"></i> {{ data.category.name }}</span>
                        </p>
                    </h5>
                    <p class="card-text">
                        {{ data.description }}
                    </p>
                </div>
            </div>
        </div>
</template>
<style scoped>
    .blog-title-desc{
            padding-bottom:0;margin-bottom:0;margin-top: 8px;
            width:100%;
    }
    .blog-title-desc span{
        margin-right: 10%;
    }
</style>
<script>
    export default {
        props: ['data'],
        data() {
            
            let blog_slugs="";
            if(this.data.slug_objects){
                this.data.slug_objects.map((item)=>{
                    blog_slugs += item.name+",";
                });
            }

            return {
                blog_image: '/blog/images/'+this.data.main_image,
                blog_slugs: blog_slugs,
            }
        },
        computed: {},
        methods: {
            openBlog(){
                if(true){
                    return false;
                }
                return window.location.href="/blog/"+this.data.id+"/"+(this.data.title_slug);
            }
        }
    }
</script>