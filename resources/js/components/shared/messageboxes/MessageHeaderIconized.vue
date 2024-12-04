<template>
  <div>
    <div v-if="width !== 100"
      :style="{ 'width': width + '%', 'display': 'flex', 'justify-content': 'space-between', 'align-items': 'center' }">
      <div class="box-icon" style="flex: 0 0 10%;">
        <div class="title-icon">
          <img :src="title_image" />
        </div>
      </div>
      <div class="box-text" style="flex: 1;">
        <div class="text-div">
          <span v-if="(titlespan)" style="color:#5063F4">{{ titlespan }}</span>
          <div style="
          font-family: Montserrat;
          font-size: 28px;
          font-weight: 700;
          line-height: 32px;
          letter-spacing: 0em;
          ">
            {{ title }}
          </div>

        </div>
      </div>
    </div>
    <div v-else style="width: 100%; display: flex; justify-content: space-between;align-items: center;">
      <div class="box-icon" style="flex: 0 0 10%;">
        <div class="title-icon">
          <img :src="title_image" />
        </div>
      </div>
      <div class="box-text" style="flex: 1;">
        <div class="text-div" :style="{ 'font-size': (fontsize) ? fontsize : '' }">
          {{ title }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['title', 'title_image', 'width', 'titlespan', 'fontsize'],
    data() {
      return {

      }
    },
    computed: {

    },
    methods: {

    }
  }
</script>
<style scoped>
  .box-icon {
    flex: 1;
  }

  .text-div {

    font-family: Montserrat;
    font-size: 18px;
    font-weight: 700;
    line-height: 32px;
    letter-spacing: 0em;
    text-align: left !important;
    margin-left: 10px;

  }
</style>