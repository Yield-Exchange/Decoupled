<template>
     <div>
    <div v-if="width !== 100"
      :style="{ 'width': width + '%', 'padding':'5px', 'display': 'flex','background': '#EFF2FE', 'justify-content': 'space-between', 'align-items': 'center' }">
      <div class="box-icon" style="flex: 0 0 3%;">
        <div class="title-icon">
          <img :src="title_image" />
        </div>
      </div>
      <div class="box-text" style="flex: 1;">
        <div class="text-div">
          {{ title }}
        </div>
      </div>
    </div>
    <div v-else style="width: 100%; display: flex; background: #EFF2FE,justify-content: space-between;align-items: center;">
      <div class="box-icon" style="flex: 0 0 10%;">
        <div class="title-icon">
          <img :src="title_image" />
        </div>
      </div>
      <div class="box-text" style="flex: 1;">
        <div class="text-div">
          {{ title }}
        </div>
      </div>
    </div>
  </div>
 
</template>
<script>
    export default {
        props: ['title','title_image','is_open','width'],
        data(){
            return {

            }
        },
        computed: {

        },
        methods: {

        }
    }
</script>