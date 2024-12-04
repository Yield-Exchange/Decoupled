<template>
    <div
        style="width: 100%; height: 100%; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 13.60px; display: inline-flex;">
        <div style="justify-content: flex-start; align-items: flex-start; gap: 4px; display: inline-flex">
            <img src="/assets/dashboard/icons/Promo2.svg" />
            <div
                style="width: 309px; height: 28px; color: #2A2A2A; font-size: 20px; font-weight: 700; line-height: 32px; word-wrap: break-word">
                No Featured Product</div>
        </div>
        <div style="width: 100%; height: 0px; border: 0.50px #D9D9D9 solid"></div>
        <div style="width: 100%;font-size: 16px;font-weight: 200; line-height: 26px; word-wrap: break-word">
            <span style="color: #2A2A2A;">You can </span>
            <span style="color: #5063F4;">feature</span>
            <span style="color: #2A2A2A;"> a product from the campaigns summary page</span>
        </div>
        <div
            style="border-radius: 10px;width: 100%;background: white; display: flex; flex-direction: column; overflow-x: scroll;">
            <table class="table table-vue-custom ">
                <thead class="customHeader" style="background: transparent">

                    <tr>
                        <th>Product Name</th>
                        <th style="padding-left: 10px;min-width:50px;">
                            Term Length
                        </th>
                        <th style="padding-left: 10px;">
                            Rate
                        </th>
                        <th style="padding-left: 10px;">
                            Currency
                        </th>
                        <th style="padding-left: 10px;">
                            Minimum
                        </th>
                        <th style="padding-left: 10px;">
                            Maximum
                        </th>
                        <th style="padding-left: 10px;">
                            PDS
                        </th>
                        <th style="padding-left: 10px;">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1 px solid rgb(238, 233, 233);">
                        <td>Non-Redeemable
                        </td>
                        <td style="padding-left: 10px;min-width:50px;">
                            12 (Months)
                        </td>
                        <td>
                            3.78%

                        </td>

                        <td>CAD</td>

                        <td>1,000,000
                        </td>
                        <td>
                            70,000,000
                        </td>
                        <td>
                            <div><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 20 20" fill="none" style="cursor: pointer">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.33301 3.33341C3.33301 2.89139 3.5086 2.46746 3.82116 2.1549C4.13372 1.84234 4.55765 1.66675 4.99967 1.66675H14.9997C15.4417 1.66675 15.8656 1.84234 16.1782 2.1549C16.4907 2.46746 16.6663 2.89139 16.6663 3.33341V16.6667C16.6663 17.1088 16.4907 17.5327 16.1782 17.8453C15.8656 18.1578 15.4417 18.3334 14.9997 18.3334H4.99967C4.55765 18.3334 4.13372 18.1578 3.82116 17.8453C3.5086 17.5327 3.33301 17.1088 3.33301 16.6667V3.33341ZM14.9997 3.33341H4.99967V16.6667H14.9997V3.33341ZM6.66634 7.50008C6.66634 7.27907 6.75414 7.06711 6.91042 6.91083C7.0667 6.75455 7.27866 6.66675 7.49967 6.66675H12.4997C12.7207 6.66675 12.9326 6.75455 13.0889 6.91083C13.2452 7.06711 13.333 7.27907 13.333 7.50008C13.333 7.7211 13.2452 7.93306 13.0889 8.08934C12.9326 8.24562 12.7207 8.33341 12.4997 8.33341H7.49967C7.27866 8.33341 7.0667 8.24562 6.91042 8.08934C6.75414 7.93306 6.66634 7.7211 6.66634 7.50008ZM7.49967 10.8334C7.27866 10.8334 7.0667 10.9212 6.91042 11.0775C6.75414 11.2338 6.66634 11.4457 6.66634 11.6667C6.66634 11.8878 6.75414 12.0997 6.91042 12.256C7.0667 12.4123 7.27866 12.5001 7.49967 12.5001H9.99967C10.2207 12.5001 10.4326 12.4123 10.5889 12.256C10.7452 12.0997 10.833 11.8878 10.833 11.6667C10.833 11.4457 10.7452 11.2338 10.5889 11.0775C10.4326 10.9212 10.2207 10.8334 9.99967 10.8334H7.49967Z"
                                            fill="#5063F4"></path>
                                    </svg></a></div>
                        </td>
                        <td>
                            <div>
                                <div>
                                    <div
                                        style="width: 100%; height: 20px; padding: 4px 8px; border-radius: 200px; background: rgb(239, 242, 254); justify-content: flex-start; align-items: center; gap: 2px; display: inline-flex; color: rgb(80, 99, 244); font-size: 13px; font-weight: 500; line-height: 9px; overflow-wrap: break-word; cursor: pointer;">
                                        <span><svg xmlns="http://www.w3.org/2000/svg" width="9" height="6"
                                                viewBox="0 0 9 6" fill="none">
                                                <path d="M1 2.99984L3.33333 5.33317L8 0.666504" stroke="#5063F4"
                                                    stroke-width="1.33333" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg></span>

                                        Feature
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<style scoped>
    thead.customHeader {
        background: #eff2fe !important;
        height: 51px;
    }



    thead.customHeader tr th span .custom-checkbox ::before {
        border-radius: 4px !important;
        border: 0.50px #5063F4 solid !important;
        padding-left: 2px;
    }

    thead.customHeader tr th span .custom-checkbox .custom-control-label {
        border: 0.50px #5063F4 solid !important;
        margin-top: 0 !important;


    }

    thead .custom-control-label {
        margin-top: 0 !important;
    }

    thead.customHeader tr {
        border-bottom: 0 solid #b3b2b2 !important;
    }

    thead.customHeader tr th {
        color: black;
        font-size: 16px !important;
        font-weight: 700;
        background: inherit !important;
        max-width: 300px;
        /* min-width: 150px; */
        padding-right: 0.75rem;
        padding-left: 0.55rem;
    }

    @media screen and (max-width:1200px) {
        thead.customHeader tr th {
            font-size: .75em;
        }
    }
</style>
<script>
    export default {

    }
</script>