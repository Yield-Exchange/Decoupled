<template>
        <div class="card-people card-shadow row col-md-12" style="min-height: 280px">
            <div class="col-md-3 center-vertically">
                <img class="card-img-top" :src="data.pic" alt="Photo" style="margin-top: 10px" />
            </div>
            <div class="col-md-9">
                <div class="card-body">
                    <h5 class="card-title">{{ data.name }}<br> {{ data.role }}</h5>
                    <p class="card-text">
                        {{ data.description }}
                    </p>
                </div>
            </div>
        </div>
</template>
<script>
    export default {
        props: ['data'],
        data() {
            return {

            }
        },
        computed: {},
        methods: {}
    }
</script>