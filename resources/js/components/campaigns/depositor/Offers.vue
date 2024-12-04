<template>
    <div style="width: 100%">
        <div v-if="data.data && data.data.length > 0" style="width: 100%; margin-bottom: 10px">
            <div
                style="width: fit-content; display: flex; flex-wrap: wrap; gap: 2px; justify-content: flex-start; align-items: flex-start">
                <TileView :key="datum?.id" v-if="view==='tile' && data.data" v-for="(datum) in data.data" :datum="datum"
                    :formattedtimezone="formattedtimezone" />
            </div>
            <ListView v-if="view==='list'" :data="data" :formattedtimezone="formattedtimezone" />
        </div>
        <NoData title="No Offers Found" message="New offers by banks will be listed here" v-else />
    </div>
</template>
<script>
    import TileView from "./TileView";
    import ListView from "./ListView";
    import NoData from "../../shared/Table/NoData";
    export default {
        props: ['view', 'data', 'formattedtimezone'],
        components: {
            NoData,
            TileView,
            ListView
        },
        data() {
            return {

            }
        }
    }
</script>