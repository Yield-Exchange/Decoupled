<template>
    <div>
        <accordion :is_open="is_open" title="Campaign insights" title_image="/assets/dashboard/icons/Promo.svg" />
        <div style="width: 100%;display: flex;justify-content: center;margin-top: 10px;">
            <img src="/assets/dashboard/icons/Product Empty State.svg" />
        </div>
    </div>

</template>
<script>
    import Accordion from "../../shared/Accordion";
    export default {
        components:{
            Accordion
        },
        props: {
            is_open : true
        }
    }
</script>