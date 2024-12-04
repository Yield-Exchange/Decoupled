<template>

    <div class="w-100">
        <!-- header -->
        <div
            style="width: 100%; height: 70px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
            <div
                style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                <div style="justify-content: space-between; display: inline-flex; width: 99%">
                    <div class="page-title">
                        <div class="title-icon">
                            <img src="/assets/dashboard/icons/secthistory.svg" />
                        </div>
                        <div class="text-div">History</div>
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
        <div class="w-100">
            <div style="width: 100%; margin-top: 10px;" class="row mt-3">
                <b-tabs content-class="mt-3"
                    nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom">
                    <b-tab title="Deposits History" active>
                        <DepositSection></DepositSection>
                    </b-tab>
                    <b-tab title="Offers History">
                        <RequestSection></RequestSection>
                    </b-tab>
                </b-tabs>
            </div>
        </div>


    </div>
</template>

<script>

import Table from '../../../shared/Table'
import Pagination from '../../../shared/Table/Pagination.vue'

import RequestSection from './RequestSection.vue'
import DepositSection from './DepositsSection.vue'

export default {
    components: { Pagination, Table, RequestSection, DepositSection },
    props: ['organization', 'offer', 'depositRequest'],
    data() {
        return {
            viewMore1: true,
            viewMore2: false,
        }
    }
}
</script>