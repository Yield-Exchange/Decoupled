<template>
    <div>
        <b-row class="pb-2">
            <h3 style="font-size: 17px; font-weight: 600;color: black" class="pt-0 m-0">{{ data.product_name }}</h3>
            <p style="font-size: 17px; font-weight: 600;color: black" class="pt-0 m-0">{{
                    parseInt(data.term_length).toFixed(0) + " " + data.term_length_type.toLowerCase()
            }}</p>
        </b-row>
        <b-row class="pt-2 pb-2">
            <b-col cols="4">
                <OrganizationAvatar :pStyle="'display: flex; justify-content: flex-end'"
                    :organization="data.organization" :size="50" />
            </b-col>
            <b-col cols="8" class="text-left mt-2">
                <span
                    :style="'font-weight: bold;font-size: 25px;color:#000;' + (data.status === 'EXPIRED' ? 'color:red' : '')">{{
                            data.interest_rate
                    }}%</span>
            </b-col>
        </b-row>
        <b-row>
            <p class="card-text my-1" :style="data.status === 'EXPIRED' ? 'color:red' :  'color:#000'"> {{ data.status === 'ACTIVE' ? 'Expiry: '+data.expiry_date_formatted : 'Expired' }}</p>
        </b-row>
    </div>
</template>
<script>
import OrganizationAvatar from "../../shared/OrganizationAvatar";
export default {
    components: { OrganizationAvatar },
    props: ['data'],
    created() {
    },
    computed: {
    },
    data() {
        return {
        }
    },
    methods: {
    }
}
</script>