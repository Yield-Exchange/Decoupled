<template>
    <div>
        <div
            style="width: 100%; height: 70px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
            <div
                style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                <div style="justify-content: space-between; display: inline-flex; width: 99%">
                    <div class="page-title">
                        <div class="title-icon">
                            <img src="/assets/dashboard/icons/active-dposits.svg" />
                        </div>
                        <div class="text-div">Received Requests</div>
                    </div>
                    <!-- <div @click="toggleView(1)"
                        style="justify-content: flex-end !important; align-items: center; gap: 9px; display: flex; cursor: pointer;">
                        <div
                            style="text-align: center; color: #252525; font-size: 14px; font-weight: 500; line-height: 18px; word-wrap: break-word">
                            View {{ viewMore1 ? 'Less' : 'More' }}</div>
                        <img v-if="viewMore1" src="/assets/dashboard/icons/Polygon.svg" />
                        <img v-else src="/assets/dashboard/icons/Polygon 2.svg" />
                    </div> -->
                </div>
            </div>

        </div>
        <!-- <h1>Respond</h1> -->
        <Table @reloadData="getData()" :columns="columns" no-data-message="" no-data-title="No requests"
            :data="table_data" :has_action='false' :actions='actions' :is_loading="is_fetching_data" />
        <Pagination @click-next-page="paginateFunction" v-if="data && data.links" :data="data" />
    </div>
</template>
<script>
import Table from '../../shared/Table'
import Pagination from '../../shared/Table/Pagination.vue'
import { capitalize } from '../../../utils/commonUtils'
import ViewOffer from './ViewOffer.vue'


export default {
    components: { Table, Pagination, ViewOffer },
    mounted() {
        this.getData()
    },
    data() {
        let columns = ['#', 'User Name', 'Organization Name', 'Organization Type', 'Permission Requested', 'Status', 'Action']
        return {
            action: [],
            columns: columns,
            is_loading: true,
            is_fetching_data: false,
            actions: null,
            table_data: [],
            data: null,
            filters: null
        }
    },
    methods: {
        renderView(status) {
            return ({ 'component': ViewOffer, 'attrs': { actionId: status } });
        },
        paginateFunction(event) {
            this.getData(event)
        },

        getData(url) {
            this.is_fetching_data = true
            let requests = []
            let getpath = url ? url : "/yie-admin/accounts-management/get-org-access-requests"
            axios.get(getpath).then(response => {
                let responsdata = response.data.data
                // console.log(responsdata,"repsonse data")
                this.data = response.data
                if (responsdata.length > 0) {
                    responsdata.forEach((user, index) => {
                        let requestobj = [
                            user.encoded_id,
                            index + 1,
                            user.user.name,
                            user.organization.name,
                            capitalize(user.organization.type),
                            user.permission_details.name,
                            capitalize(user.status),
                            user.status == 'PENDING' ? () => this.renderView(user?.encoded_id) : '-',
                        ]
                        requests.push(requestobj)

                    });
                    this.is_fetching_data = false
                    this.table_data = requests

                } else {
                    this.is_fetching_data = false
                    this.table_data = []
                }

            }).catch(err => {
                this.is_fetching_data = false
                console.log(err)
            })
        },
    }

}

</script>