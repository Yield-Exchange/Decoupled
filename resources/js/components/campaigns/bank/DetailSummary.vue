<template>
    <div style="">
        <!-- <accordion :is_open="true" width="100" title="Campaign Summary" title_image="/assets/dashboard/icons/Promo.svg" /> -->

        <div
            style="width: 99%; background: #EFF2FE; padding-left: 2px; justify-content: flex-start; align-items: center; display: inline-flex">
            <div style="justify-content: space-between; display: inline-flex; width: 99%;">
                <div class="page-title">
                    <div class="title-icon">
                        <img src="/assets/dashboard/icons/Promo.svg" style="height: 40px; width: 50px;" />
                    </div>
                    <div class="text-div">Campaign Summary</div>
                </div>
                <div @click="toggleView(1)"
                    style="justify-content: flex-start; align-items: center; gap: 9px; display: flex;cursor: pointer">
                    <div
                        style="text-align: center; color: #252525; font-size: 14px; font-weight: 500; line-height: 18px; word-wrap: break-word">
                        View {{ viewMore1 ? 'Less' : 'More' }}</div>
                    <img v-if="viewMore1" src="/assets/dashboard/icons/Polygon.svg" />
                    <img v-else src="/assets/dashboard/icons/Polygon 2.svg" />
                </div>
            </div>
        </div>
        <div v-if="viewMore1">
            <div style="margin-top: 30px; width:99%">
                <div
                    style="flex-direction: column; justify-content: center; align-items: flex-start; gap: 10px; display: flex; width: 90%;">
                    <table class="table" style="background: transparent">
                        <thead class="customHeader1" style="background: transparent;border-collapse: collapse;">
                            <tr style="border: none!important;">
                                <th>Campaign Name</th>
                                <th>Products</th>
                                <th>Currency</th>
                                <th>Subscription Limit</th>
                                <!-- <th v-if="camp?.currency.campaign_depositors_invite_type==='Blanket'">Groups</th> -->
                                <th>Depositors</th>
                                <th>Start Date</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="customBody1" style="background: transparent;border-collapse: collapse;">
                            <tr style="border: none!important;">
                                <td>{{ capitalize(camp?.campaign_name) }}</td>
                                <td>{{ camp?.campaign_products?.length }}</td>
                                <td>{{ camp?.currency }}</td>
                                <td>{{ addCommas(camp?.subscription_amount) }}</td>
                                <td>{{ camp?.campaign_depositor_count?.invitees }}</td>
                                <td>{{ camp?.start_date }}</td>
                                <td>
                                    <TimerClock v-if="camp?.status === 'ACTIVE'" :target-time="camp?.expiry_date"
                                        :timezone="formattedtimezone" />
                                    <span v-else>Not Active</span>
                                </td>
                                <td>{{ capitalize(camp?.status) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="width: 99%;margin-top: 40px">
                <table class="table table-vue-custom">
                    <thead class="customHeader" style="background: transparent">
                        <tr>
                            <th>Product&nbsp;Name</th>
                            <th>Featured</th>
                            <th>Term&nbsp;Length</th>
                            <th>Rate</th>
                            <th>Minimum</th>
                            <th>Maximum</th>
                            <!-- <th>Order&nbsp;Limit</th> -->
                            <th>
                                <div style="display: flex; flex-direction: row; gap: 5px">
                                    PDS
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17"
                                        fill="none" id="pds">
                                        <path
                                            d="M6.06016 6.50016C6.2169 6.05461 6.52626 5.6789 6.93347 5.43958C7.34067 5.20027 7.81943 5.11279 8.28495 5.19264C8.75047 5.27249 9.17271 5.51451 9.47688 5.87585C9.78106 6.23718 9.94753 6.69451 9.94683 7.16683C9.94683 8.50016 7.94683 9.16683 7.94683 9.16683M8.00016 11.8335H8.00683M14.6668 8.50016C14.6668 12.1821 11.6821 15.1668 8.00016 15.1668C4.31826 15.1668 1.3335 12.1821 1.3335 8.50016C1.3335 4.81826 4.31826 1.8335 8.00016 1.8335C11.6821 1.8335 14.6668 4.81826 14.6668 8.50016Z"
                                            stroke="#5063F4" stroke-width="1.33333" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    <Tooltip placement="top" target="pds" message="Product disclosure statement" />
                                </div>
                            </th>
                            <th style="width: 100px">Action</th>
                        </tr>
                    </thead>
                    <tbody class="customBody">
                        <tr v-if="camp.campaign_products" v-for="(product, index) in camp.campaign_products">
                            <td>{{ capitalize(product?.product?.custom_product_name) }}</td>

                            <td>
                                <FeaturedProduct :featured="product?.isFeatured" />
                            </td>
                            <td>{{ product?.product?.term_length }} {{ capitalize(product?.product?.term_length_type) }}
                            </td>
                            <td>{{ product.rate }}%</td>
                            <td>{{ addCommas(product.minimum) }}</td>
                            <td>{{ addCommas(product.maximum) }}</td>
                            <!-- <td>{{ addCommas(product.order_limit) }}</td> -->
                            <td>

                                <div v-if="product.product.pds"
                                    @click="viewLink('/uploads/pds/' + product.product.pds)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                        fill="none" style="cursor: pointer">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.33301 3.33341C3.33301 2.89139 3.5086 2.46746 3.82116 2.1549C4.13372 1.84234 4.55765 1.66675 4.99967 1.66675H14.9997C15.4417 1.66675 15.8656 1.84234 16.1782 2.1549C16.4907 2.46746 16.6663 2.89139 16.6663 3.33341V16.6667C16.6663 17.1088 16.4907 17.5327 16.1782 17.8453C15.8656 18.1578 15.4417 18.3334 14.9997 18.3334H4.99967C4.55765 18.3334 4.13372 18.1578 3.82116 17.8453C3.5086 17.5327 3.33301 17.1088 3.33301 16.6667V3.33341ZM14.9997 3.33341H4.99967V16.6667H14.9997V3.33341ZM6.66634 7.50008C6.66634 7.27907 6.75414 7.06711 6.91042 6.91083C7.0667 6.75455 7.27866 6.66675 7.49967 6.66675H12.4997C12.7207 6.66675 12.9326 6.75455 13.0889 6.91083C13.2452 7.06711 13.333 7.27907 13.333 7.50008C13.333 7.7211 13.2452 7.93306 13.0889 8.08934C12.9326 8.24562 12.7207 8.33341 12.4997 8.33341H7.49967C7.27866 8.33341 7.0667 8.24562 6.91042 8.08934C6.75414 7.93306 6.66634 7.7211 6.66634 7.50008ZM7.49967 10.8334C7.27866 10.8334 7.0667 10.9212 6.91042 11.0775C6.75414 11.2338 6.66634 11.4457 6.66634 11.6667C6.66634 11.8878 6.75414 12.0997 6.91042 12.256C7.0667 12.4123 7.27866 12.5001 7.49967 12.5001H9.99967C10.2207 12.5001 10.4326 12.4123 10.5889 12.256C10.7452 12.0997 10.833 11.8878 10.833 11.6667C10.833 11.4457 10.7452 11.2338 10.5889 11.0775C10.4326 10.9212 10.2207 10.8334 9.99967 10.8334H7.49967Z"
                                            fill="#5063F4" />
                                    </svg>
                                </div>
                                <div v-else> -</div>
                            </td>
                            <td style="width: 100px" v-if="camp?.status === 'ACTIVE' || camp?.status === 'SCHEDULED'">
                                <Actions>
                                    <b-dropdown-item v-on:click="() => false" href="#" :key="'DropDownItem1'">
                                        <FeatureUnfeatureProduct :productName="product?.product?.default_product_name"
                                            :actionObjectDetails="[camp?.id, camp?.campaign_name, capitalize(product?.product?.custom_product_name), product?.product?.product_type?.description, product?.product?.custom_product_name, product?.product?.term_length + ' ' + product?.product?.term_length_type]"
                                            :variant-color="product?.isFeatured === 1 ? 'rgba(68, 224, 170, 0.60);' : ''"
                                            :text-color="product?.isFeatured === 1 ? '#252525' : ''"
                                            :campaign_id="camp.id"
                                            :action="product?.isFeatured === 1 ? 'Un Feature' : 'Feature'"
                                            :action-id="product?.id" />
                                    </b-dropdown-item>
                                    <b-dropdown-item v-on:click="() => false" href="#" :key="'DropDownItem2'"
                                        v-if="userCan(JSON.parse(userloggedin),'bank/my-campaigns/remove-campaign-products')">
                                        <DeactivateCampaignProduct :action-id="product?.id" />
                                    </b-dropdown-item>
                                </Actions>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div
                style="justify-content: flex-end; align-items: center; display: inline-flex;width: 99%; margin-bottom: 10px;">
                <div style="justify-content: flex-end; align-items: flex-start; gap: 50px; display: inline-flex">
                    <b-button @click="back()"
                        style="width:300px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: #9CA1AA 2px solid !important;background-color: #EFF2FE !important;color:#9CA1AA  !important;">
                        View Other Campaigns
                    </b-button>
                    <b-button v-if="camp?.status.toLowerCase() !== 'expired'" @click="edit()"
                        style="width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: #5063F4 2px solid !important;background-color: #EFF2FE !important;color:#5063F4;  !important;">
                        Edit
                    </b-button>
                    <b-button @click="back()"
                        style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                        Next
                    </b-button>
                </div>
            </div>
        </div>
        <div v-if="userCan(JSON.parse(userloggedin),'bank/my-campaigns/campaign-insights')"
            style="width: 99%; background: #EFF2FE; padding-left: 2px; justify-content: flex-start; align-items: center; display: inline-flex ; margin-top: 30px;">
            <div style="justify-content: space-between; display: inline-flex; width: 99%;">
                <div class="page-title">
                    <div class="title-icon">
                        <img src="/assets/dashboard/icons/orgsettings.svg" style="height: 40px; width: 50px;" />
                    </div>
                    <div class="text-div">Campaign Insights</div>
                </div>
                <div @click="toggleView(2)"
                    style="justify-content: flex-start; align-items: center; gap: 9px; display: flex;cursor: pointer">
                    <div
                        style="text-align: center; color: #252525; font-size: 14px; font-weight: 500; line-height: 18px; word-wrap: break-word">
                        View {{ viewMore2 ? 'Less' : 'More' }}</div>
                    <img v-if="viewMore2" src="/assets/dashboard/icons/Polygon.svg" />
                    <img v-else src="/assets/dashboard/icons/Polygon 2.svg" />
                </div>
            </div>
        </div>
        <div v-if="viewMore2">
            <div v-if="insights_available"
                style="display: flex; flex-direction:column ; margin-top: 5spx; gap: 0.5%; width: 99%; ">
                <div style="display: flex; flex-direction:row ; margin-top: 10px; gap: 0.5%; width: 100%; ">
                    <div style="width:50%; display: flex;justify-content:flex-start; align-items: center">
                        <div
                            style="width: 100%; color: #44E0AA; font-size: 21px; font-family: Montserrat; font-weight: 800; word-wrap: break-word">
                            <TimerClock v-if="camp?.status === 'ACTIVE'" :target-time="camp?.expiry_date" />
                        </div>
                    </div>
                    <div style="width:50%; display: flex;justify-content:flex-end; ">
                        <div
                            style="display: flex; flex-direction:row ; margin-top: 10px; justify-content: flex-end; width: 100%; margin-bottom: 15px;">
                            <div
                                style="display: flex;justify-content:flex-end; border-radius: 12px; background-color: white; ">
                                <div class="filter-button" :class="{ 'filter-button-active': isFilterActive(1) }"
                                    @click="changeFilterDuration(1)">7 Days</div>
                                <div class="filter-button" :class="{ 'filter-button-active': isFilterActive(2) }"
                                    @click="changeFilterDuration(2)">14 Days</div>
                                <div class="filter-button" :class="{ 'filter-button-active': isFilterActive(3) }"
                                    @click="changeFilterDuration(3)">30 Days</div>

                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="activeInsight === 1"
                    style="display: flex; flex-direction:row ; margin-top: 10px; gap: 0.5%; width: 100%;  ">
                    <div style=" height:527px; width:60%;  border-radius:25px; min-width:700px;">
                        <div style="display: flex; flex-direction:column;justify-content: space-between; height:100%; ">
                            <div style="width:100%;height:215px;padding:0px;  display:flex; flex-direction:row">
                                <div
                                    style="width:50%;   height:auto;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.10); border-radius: 10px; padding:5px;display:flex; flex-direction:column; justify-content:center; align-items:center;">
                                    <div
                                        style="color: #5063F4;text-align:left; width: 100%; margin-left: 18px; font-size: 16px; font-family: Montserrat; font-weight: 800; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                        Invited depositors</div>
                                    <div style="width:100%; display: flex; flex-direction:row">
                                        <div style="width:45%; padding:20px">
                                            <div
                                                style="width: 100%;  height: 100%; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 16px; display: inline-flex">
                                                <div
                                                    style="align-self: stretch; height:auto;width:100%; justify-content: flex-start; align-items: center; gap: 6px; display: inline-flex">
                                                    <div
                                                        style="width: 10px; height: 10px; background: #2A9928; border-radius: 9999px">
                                                    </div>
                                                    <div
                                                        style="color: #2A9928; font-size: 14px; font-family: Montserrat; font-weight: 500; line-height: 15px; word-wrap: break-word">
                                                        Redeemable</div>
                                                </div>
                                                <div
                                                    style="width: 100%; height:auto; justify-content: flex-start; align-items: center; gap: 6px; display: inline-flex">
                                                    <div
                                                        style="width: 10px; height: 10px; background: #F4B63C; border-radius: 9999px">
                                                    </div>
                                                    <div
                                                        style="color: #F4B63C; height:auto; font-size: 14px; font-family: Montserrat; font-weight: 500; line-height: 15px; word-wrap: break-word">
                                                        Non-Redeemable</div>
                                                </div>
                                                <div
                                                    style="width: 100%; height:auto; justify-content: flex-start; align-items: center; gap: 6px; display: inline-flex">
                                                    <div
                                                        style="width: 10px; height: 10px; background: #5063F4; border-radius: 9999px">
                                                    </div>
                                                    <div
                                                        style="color: #5063F4; font-size: 14px; font-family: Montserrat; font-weight: 500; line-height: 15px; word-wrap: break-word">
                                                        Overnight</div>
                                                </div>
                                                <div
                                                    style="width: 100%;height:auto; justify-content: flex-start; align-items: center; gap: 6px; display: inline-flex">
                                                    <div
                                                        style="width: 10px; height: 10px; background: #44E0AA; border-radius: 9999px">
                                                    </div>
                                                    <div
                                                        style="color: #44E0AA; font-size: 14px; font-family: Montserrat; font-weight: 500; line-height: 15px; word-wrap: break-word">
                                                        High Interest</div>
                                                </div>
                                                <div
                                                    style="width: 100%; height:auto;justify-content: flex-start; align-items: center; gap: 6px; display: inline-flex">
                                                    <div
                                                        style="width: 10px; height: 10px; background: #9CA1AA; border-radius: 9999px">
                                                    </div>
                                                    <div
                                                        style="color: #9CA1AA; font-size: 14px; font-family: Montserrat; font-weight: 500; line-height: 15px; word-wrap: break-word">
                                                        Cashable</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="width:55%;" v-if="inviteddepositorsValues.length >= 1">
                                            <Invited :showlegent=false dheight="200px" dwidth="200px"
                                                :labels="inviteddepositors" :values="inviteddepositorsValues"
                                                seriesName="Depositors" :seriescolors="inviteddepositorsColors" />
                                        </div>
                                        <div style="width:55%;" v-else>
                                            <div class="d-flex flex-column justify-content-center">
                                                <div>
                                                    <img src="/assets/dashboard/icons/nodatacircular.svg"
                                                        style="height:100px;width: 100%; align-items: center;" alt="">
                                                </div>
                                                <p class="no-data">No Data</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div
                                    style="width:25%;   height:auto;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.10); border-radius: 10px; padding:5px; display:flex; flex-direction:column; justify-content:flex-start;align-items:center;">
                                    <div
                                        style="color: #5063F4;text-align:left; width: 100%; margin-left: 8px; font-size: 16px; font-family: Montserrat; font-weight: 800; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                        in progress</div>
                                    <InvitedPrrogress v-if="inProgressDepositsProducts.length >= 1" :showlegent=false
                                        dheight="200px" dwidth="200px" :labels="inProgressDepositsProducts"
                                        :values="inProgressDepositsProductsValues" seriesName="Depositors"
                                        :seriescolors="inProgressDepositsProductsColors" />
                                    <div style="width:55%;" v-else>
                                        <div class="d-flex flex-column justify-content-center">
                                            <div>
                                                <img src="/assets/dashboard/icons/nodatacircular.svg"
                                                    style="height:100px;width: 100%; align-items: center;" alt="">
                                            </div>
                                            <p class="no-data">No Data</p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    style="width:25% ; height:auto;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.10); border-radius: 10px; padding:5px; display:flex; flex-direction:column;align-items:center; justify-content:flex-start;">
                                    <div
                                        style="color: #5063F4; text-align:left; width: 100%; margin-left: 8px; font-size: 16px; font-family: Montserrat; font-weight: 800; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                        Active</div>
                                    <InvitedActive v-if="activeDepositsProducts.length >= 1" :showlegent=false
                                        dheight="200px" dwidth="200px" :labels="activeDepositsProducts"
                                        :values="activeDepositsProductsValues"
                                        :seriescolors="activeDepositsProductsColors" />
                                    <div style="width:55%;" v-else>
                                        <div class="d-flex flex-column justify-content-center">
                                            <div>
                                                <img src="/assets/dashboard/icons/nodatacircular.svg"
                                                    style="height:100px;width: 100%; align-items: center;" alt="">
                                            </div>
                                            <p class="no-data">No Data</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div
                                style="width:100%; height:300px;  box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.10); border-radius: 10px; margin-top:5px; padding:20px;  ">

                                <div
                                    style="width: 100%; padding: 10px; justify-content: flex-start; align-items: center; gap: 90px; display: inline-flex">
                                    <div style="display: flex; width:100%; flex-direction: row;">
                                        <div
                                            style="color: #5063F4; width: 60%; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                            Unique user clicks per product</div>

                                        <div
                                            style="display: flex; width: 40%; flex-direction: row; justify-content: flex-end;">
                                            <span
                                                style="color: #9CA1AA; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">Total
                                                clicks: </span>
                                            <span
                                                style="color: #5063F4; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">{{
                                                product_clicks }}</span>

                                        </div>

                                    </div>

                                </div>
                                <UniqueClicksProduct v-if="productWithClicks.length >= 1" height="200"
                                    barcolor="#5063F4" seriesName="Clicks" :labels="productWithClicks"
                                    :values="productWithClicksValues" />
                                <div v-else>
                                    <UniqueClicksProduct height="200" barcolor="#5063F4" seriesName="Clicks"
                                        :labels="products" :values="productsClicks" />

                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        style=" display: flex; flex-direction: column;width:40%; justify-content: space-between; height:530px; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.10); border-radius: 10px; padding:20px; overflow-x: scroll;">
                        <div class="d-flex justify-content-between">
                            <div
                                style="color: #5063F4;height:2%; margin-left:30px; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                Unique user clicks per province</div>

                            <div class="province_clicks">
                                Total Clicks: <span>{{ province_count }}</span>
                            </div>
                        </div>
                        <div style="height:98%;" v-if="provinceClicks.length >= 1">
                            <UniqueClicksProvince height="445" barcolor="#44E0AA" :labels="provinces"
                                seriesName="Clicks" :values="provinceClicks" />
                        </div>
                        <div v-else>
                            <NoInsights image="nodata.svg" maxheight="150"
                                desc="Your Unique User reach per Province  for this Campaign will populate  will populate once depositors start engaging with it">
                            </NoInsights>
                        </div>

                    </div>
                </div>
                <div v-if="activeInsight === 2"
                    style="display: flex; flex-direction:row ; margin-top: 10px; gap: 0.5%; width: 100%; ">
                    <div style=" height:580px;  width:40%; border-radius:25px;">
                        <div style="display: flex; height:100%;  flex-direction:column;justify-content: flex-start;   ">
                            <div
                                style="width:100%;overflow-y: auto;  height:280px; display:flex; flex-direction:column;  box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.10); border-radius: 10px; margin-top:5px; padding:20px;  ">
                                <div
                                    style="width: 100%;  padding: 10px; justify-content: flex-start; align-items: center; gap: 20px; display: inline-flex">
                                    <div
                                        style="color: #5063F4; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                        Subscription Goal</div>
                                    <div><span
                                            style="color: #9CA1AA; font-size: 12px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 14px; word-wrap: break-word">Goal
                                            : </span><span
                                            style="color: #FF2E2E; font-size: 12px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 14px; word-wrap: break-word">(CAD)
                                            {{ addCommas(camp?.subscription_amount) }}</span></div>
                                    <div><span
                                            style="color: #9CA1AA; font-size: 12px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 14px; word-wrap: break-word">Sold
                                            : </span><span
                                            style="color: #2A9928; font-size: 12px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 14px; word-wrap: break-word">(CAD)
                                            {{ addCommas(sold_amount) }}</span></div>
                                    <!-- {{ percentSold }} -->


                                </div>
                                <div>
                                    <SubscriptioGoal v-if="sold_percent != null" :counter_series="sold_percent"
                                        dheight="250" />
                                    <EmptySubscriptionGoal v-else counter_series="0" dheight="200" />
                                </div>
                            </div>
                            <div
                                style="width:100%; height:300px;  box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.10); border-radius: 10px; margin-top:5px; padding:20px;  ">
                                <div
                                    style="width: 100%;  padding: 10px; justify-content: flex-start; align-items: center; gap: 90px; display: inline-flex">
                                    <div style="display: flex; width:100%; flex-direction: row;">
                                        <div
                                            style="color: #5063F4; width: 60%; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                            Market Sector Reach</div>

                                        <div
                                            style="display: flex; width: 40%; flex-direction: row; justify-content: flex-end;">
                                            <span
                                                style="color: #9CA1AA; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">Total
                                                clicks: </span>
                                            <span
                                                style="color: #5063F4; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">{{
                                                total_market_clicks }}</span>

                                        </div>

                                    </div>

                                </div>
                                <MarketSectorReach v-if="marketTypesClicks.length >= 1" height="200"
                                    seriesName="No  Of Clicks" :labels="marketTypes" :values="marketTypesClicks" />
                                <div v-else>
                                    <MarketSectorReach height="200" seriesName="No  Of Clicks"
                                        :labels="['Finance', 'Municipality', 'Real Estate', 'Small Business', 'Non-Profits']"
                                        :values="[0, 0, 0, 0, 0]" />

                                </div>
                            </div>

                        </div>
                    </div>
                    <div
                        style="  display: flex; justify-content:space-between; flex-direction: column;width:60%; height:580px; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.10); border-radius: 10px; padding:20px;">
                        <div
                            style="color: #5063F4;height:2%; margin-left:10px; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                            Campaign rate to market rate comparison</div>
                        <div style="">
                            <LineColumn v-if="marketRatesLabels.length >= 1" :labels="marketRatesLabels"
                                :marketRates="marketRates" :myRate="myRate" />
                            <div v-else>
                                <NoInsights image="nodata.svg" maxheight="800" height="300"
                                    desc="Your Campaign rate to market rate insights for this Campaign will populate once depositors start engaging with it">

                                </NoInsights>
                            </div>
                        </div>

                    </div>
                </div>
                <div
                    style="width: 100%; height: 100%; margin-top:10px;  display: flex; align-items: center; justify-content:center; cursor:pointer">
                    <div @click="setActiveInsight(1)"
                        v-bind:class="(activeInsight === 1) ? 'activeInsightBar' : 'inactiveInsightBar'"
                        style="width: 40px; height: 7px; left: 0px; top: 0px;   border-radius: 10px; margin-right: 15px;">

                    </div>
                    <div @click="setActiveInsight(2)"
                        v-bind:class="(activeInsight === 2) ? 'activeInsightBar' : 'inactiveInsightBar'"
                        style="width: 40px; height: 7px; left: 49px; top: 0px;  border-radius: 10px;">

                    </div>
                </div>
            </div>
            <NoInsights image="noinsights.svg" maxheight="280"
                desc="Your insights for this Campaign will populate once depositors start engaging with it"
                title="No Market Insights Yet" v-else>

            </NoInsights>
        </div>


    </div>
</template>

<style scoped>
    .filter-button {
        cursor: pointer;
        text-align: center;
        color: #9291A5;
        font-size: 11px;
        font-family: Montserrat;
        padding: 10px;
        font-weight: 400;
        line-height: 14px;
        word-wrap: break-word;
        margin-left: 30px;
        color: var(--neutral-colors-400, #9291A5);
        text-align: center;

        /* Yield Exchange Text Styles/Tooltips */
        font-family: Montserrat;
        font-size: 11px;
        font-style: normal;
        font-weight: 400;
        line-height: 14px;
        /* 127.273% */

    }

    .no-data {
        color: var(--yield-exchange-pallette-yield-exchange-blue, var(--yield-exchange-colors-yield-exchange-purple, #5063F4));
        text-align: center;
        font-family: Montserrat;
        font-size: 14px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .province_clicks {
        color: var(--yield-exchange-pallette-yield-exchange-grey, #9CA1AA);
        font-feature-settings: 'clig' off, 'liga' off;

        /* Yield Exchange Text Styles/Promotion Chart titles */
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: 26px;
        /* 162.5% */
        text-transform: capitalize;
    }

    .province_clicks>span {
        color: #5063F4
    }

    .filter-button-active {
        background: var(--yield-exchange-colors-yield-exchange-purple, #5063F4);
        color: white;
        border-radius: 10px;
        padding-left: 15px;


    }

    .activeInsightBar {
        background: #44E0AA;
    }

    .inactiveInsightBar {
        background: #9CA1AA;
    }

    .table-vue-custom {
        background: white;
    }

    thead.customHeader1 {
        background: none !important;
        height: 51px;
    }

    thead.customHeader {
        background: #EFF2FE !important;
        height: 51px;
    }

    thead.customHeader tr {
        border-bottom: 0 solid #b3b2b2 !important;
    }

    thead.customHeader tr th {
        color: black;
        font-size: 13px !important;
        font-weight: 700;
        background: inherit !important;
        text-align: left !important;
    }

    thead.customBody tr td {
        /*text-align: center!important;*/
    }

    tbody.customBody1>tr>td {
        background: inherit !important;
        text-align: left !important;
        border: none !important;
        color: #5063F4;
        font-size: 14px !important;
        font-weight: 800 !important;
        word-wrap: break-word;
    }

    thead.customHeader1>tr>th {
        background: inherit !important;
        text-align: left !important;
        border: none !important;
        color: #252525;
        font-size: 16px;
        font-weight: 700;
        word-wrap: break-word;
    }

    tbody tr td {
        font-size: 13px !important;
    }

    .term_length_custom>.form-control {
        max-width: 70px !important;
    }
</style>
<script>
    import FeaturedProduct from "../../shared/FeaturedProduct";
    import Accordion from "../../shared/Accordion";
    import Tooltip from "../../shared/Tooltip";
    import Button from "../../shared/Buttons/Button"
    import FeatureUnfeatureProduct from "./FeatureUnfeatureProduct";
    import Actions from "../../shared/Table/Actions";
    import DeactivateCampaignProduct from "./DeactivateCampaignProduct";
    import TimerClock from "../TimerClock";
    import BarGraph from "../../shared/Graphs/bar/BarGraph";
    import DonutPie from "../../shared/Graphs/pie/DonutPie.vue";
    import SemiCircularGraph from "../../shared/Graphs/pie/SemiCircularGraph";
    import EmptySubscriptionGoal from "../../shared/Graphs/pie/SemiCircularGraph";

    import LineColumnGraphVue from '../../shared/Graphs/bar/LineColumnGraph.vue';
    import LineColumn from '../../shared/Graphs/line/LineColumn.vue';
    import NoInsights from '../../shared/Graphs/NoInsights.vue'
    import { userCan } from "../../../utils/GlobalUtils";

    // LineColumnGraphVue
    export default {
        components: {
            FeaturedProduct,
            LineColumn,
            Invited: DonutPie,
            LineColumnGraphVue,
            InvitedPrrogress: DonutPie,
            InvitedActive: DonutPie,
            SubscriptioGoal: SemiCircularGraph,
            EmptySubscriptionGoal: EmptySubscriptionGoal,
            UniqueClicksProvince: BarGraph,
            UniqueClicksProduct: BarGraph,
            MarketSectorReach: BarGraph,
            TimerClock,
            FeatureUnfeatureProduct,
            Accordion,
            Tooltip,
            NoInsights,
            Button,
            Actions,
            DeactivateCampaignProduct
        },
        props: ['campaign', 'userloggedin', 'formattedtimezone'],
        mounted() {
            console.log(this.camp?.id)
        },
        created() {
            this.getDetailSummaryInsights(30)
        },
        computed: {
            percentSold() {
                return (Math.round(this.camp.subscription_amount / this.sold_amount))
            }
        },

        data() {
            return {
                activeInsight: 1,
                products: ['Redeemable', 'Non-Redeemable', 'Overnight', 'High Interest', 'Cashable'],
                productsClicks: ['0', '0', '0', '0', '0'],
                provinces: [],
                viewMore1: true,
                viewMore2: false,
                provinceClicks: [],
                camp: JSON.parse(this.campaign),
                activeDepositsProducts: [],
                activeDepositsProductsValues: [],
                activeDepositsProductsColors: [],
                inProgressDepositsProducts: [],
                inProgressDepositsProductsValues: [],
                inProgressDepositsProductsColors: [],
                productWithClicks: [],
                productWithClicksValues: [],
                productWithClicksColors: [],
                inviteddepositors: [],
                inviteddepositorsValues: [],
                inviteddepositorsColors: [],
                sold_amount: 0,
                marketTypes: [],
                marketTypesClicks: [],
                total_market_clicks: 0,
                marketRates: [],
                marketRatesLabels: [],
                myRate: [],
                sold_percent: null,
                activeFilterDuration: 3,
                insights_available: false,
                product_clicks: 0,
                invited_depositors: 0,
                inprogress_depositors: 0,
                active_depositors: 0,
                province_count: 0


            }
        },
        methods: {
            userCan(user, permission) {
                return userCan(user, permission);
            },
            changeFilterDuration(value) {
                this.provinces = []
                this.provinceClicks = []
                this.activeDepositsProducts = []
                this.activeDepositsProductsValues = []
                this.activeDepositsProductsColors = []
                this.inProgressDepositsProducts = []
                this.inProgressDepositsProductsValues = []
                this.inProgressDepositsProductsColors = []
                this.productWithClicks = []
                this.productWithClicksValues = []
                this.productWithClicksColors = []
                this.inviteddepositors = []
                this.inviteddepositorsValues = []
                this.inviteddepositorsColors = []
                this.sold_amount = 0
                this.marketTypes = []
                this.marketTypesClicks = []
                this.total_market_clicks = 0
                this.marketRates = []
                this.marketRatesLabels = []
                this.product_clicks = 0
                this.myRate = []
                this.sold_percent = null
                this.province_count = 0
                this.activeFilterDuration = value
                if (value == 1)
                    this.getDetailSummaryInsights(7)
                if (value == 2)
                    this.getDetailSummaryInsights(14)
                if (value == 3)
                    this.getDetailSummaryInsights(30)
            },
            isFilterActive(value) {
                if (this.activeFilterDuration === value)
                    return true
            },
            getSoldPercentage() {
                let value = 0
                value = this.sold_amount / this.camp.subscription_amount
                // console.log("value start", value)
                value = Math.round(value * 100)
                this.sold_percent = value
                // console.log("value end", value)
            },
            setActiveInsight(insight) {
                this.activeInsight = insight;
            },
            getColor(label) {
                switch (label) {
                    case 'Redeemable':
                        return '#2A9928';
                    case 'Non-Redeemable':
                        return '#F4B63C';
                    case 'Overnight':
                        return '#5063F4';
                    case 'High Interest':
                        return '#44E0AA';
                    case 'Cashable':
                        return '#9CA1AA';
                    default:
                        return 'No Color Found'; // Or any default color if needed
                }
            },

            getDetailSummaryInsights(inthelast = 30) {
                let campaign = this.camp?.id
                let this_ = this
                let url = `/campaigns/fi/analytics/get-campaign-insights?campaign=${campaign}&inTheLast=${inthelast}`
                // let url = `https://mocki.io/v1/3efe1dbc-59a3-4fb6-800d-e679a65202ca`
                axios.get(url)
                    .then(response => {
                        const data = response.data;
                        //console.log(data)
                        this_.insights_available = true
                        if (data.clicksByProvince) {
                            data.clicksByProvince.forEach(item => {
                                this_.provinces.push(item.province);
                                this_.provinceClicks.push(item.unique_count);
                                this.province_count += item.unique_count
                            });
                        }

                        if (data.activePurchases) {
                            data.activePurchases.forEach(item => {
                                this_.activeDepositsProducts.push(item.product);
                                this_.activeDepositsProductsValues.push(item.total_offers);
                                this_.activeDepositsProductsColors.push(this_.getColor(item.product));
                                this_.sold_amount += item.total_offers
                            });
                            this.getSoldPercentage()

                        }

                        if (data.inProgressPurchases) {
                            data.inProgressPurchases.forEach(item => {
                                this_.inProgressDepositsProducts.push(item.product);
                                this_.inProgressDepositsProductsValues.push(item.total_offers);
                                this_.inProgressDepositsProductsColors.push(this_.getColor(item.product));
                            });
                        }

                        if (data.activePendingPurchases) {
                            data.activePendingPurchases.forEach(item => {
                                this_.inviteddepositors.push(item.product);
                                this_.inviteddepositorsValues.push(item.total_offers);
                                this_.inviteddepositorsColors.push(this_.getColor(item.product));
                            });
                        }

                        if (data.clicksByProduct) {
                            data.clicksByProduct.forEach(item => {
                                this_.productWithClicks.push(item.product);
                                this_.productWithClicksValues.push(item.unique_count);
                                this.product_clicks += item.unique_count;
                            });
                        }
                        if (data.marketSectorClicks) {
                            data.marketSectorClicks.forEach(item => {
                                this_.marketTypes.push(item.name);
                                this_.marketTypesClicks.push(item.unique_count);
                                this.total_market_clicks += item.unique_count
                            });
                        }
                        if (data.rates) {
                            data.rates.market_rates.forEach((item, index) => {
                                if (item != null) {
                                    this_.marketRates.push(item.unique_count);
                                    this_.marketRatesLabels.push(item.product.split(' '));
                                    // this_.marketRatesLabels.push(typeof item.product === 'string' && item.product.includes(' ') ? item.product.split(' ') : item.product);

                                    const foundRate = data.rates.my_rates.find(rate => rate != null && rate.product === item.product);
                                    if (foundRate) {
                                        this_.myRate.push(foundRate.unique_count);
                                    } else {
                                        this_.myRate.push(0);
                                    }
                                }
                            });

                            // console.log(this.marketRates, "Market rates")
                            // console.log(this.myRate, "My rates")
                            console.log(this.marketRatesLabels, "Market rates labels")
                            // console.log(this_.seriesValues)
                        }
                    })
                    .catch(error => {
                        // this.insights_available = false
                        console.error(error);
                    });

            },
            addCommas(newvalue) {
                return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
            back() {
                window.location.href = '/campaigns';
            },
            viewLink(link) {
                window.open(link, '_blank')
            },
            edit() {
                window.location.href = '/campaigns?campaign_id=' + this.camp?.id;
            },
            capitalize(thestring) {
                if (thestring != undefined) {
                    return thestring
                        .toLowerCase()
                        .split(' ')
                        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                        .join(' ');
                }

            },
            toggleView(index) {
                if (index === 1)
                    this.viewMore1 = !this.viewMore1;
                else
                    this.viewMore2 = !this.viewMore2
            },
        }
    }
</script>