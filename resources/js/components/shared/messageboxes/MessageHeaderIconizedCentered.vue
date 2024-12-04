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
            <h4 style="width: 100%;text-align: left;">{{ title }}</h4>
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
        <div class="text-div">
            <h4 style="width: 100%;text-align: left;">{{ title }}</h4>
        </div>
      </div>
    </div>
  </div>
</template>
  
<script>
export default {
  props: ['title', 'title_image', 'width'],
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
<style>
.box-icon {
  flex: 1;
}

.text-div {

  font-family: Montserrat;
  font-size: 5px;
  font-weight: 600;
  letter-spacing: 0em;
  text-align: center;
  margin-left: 10px;
  width: 100%;

}
</style>