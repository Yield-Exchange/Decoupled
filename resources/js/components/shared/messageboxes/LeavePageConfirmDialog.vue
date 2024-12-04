<template>
    <b-modal :size="size" v-model="visible" hide-footer hide-header centered id="twobtnaction" :no-close-on-esc="true">
        <b-container class="bv-example-row" style="width: 100% !important;padding: 0px !important;">
            <b-row style="width:100%;padding: 0px !important;">
                <b-col md="12 " class="d-flex justify-content-end " style="width:100%;padding: 0px !important;">

                </b-col>

            </b-row>
            <b-row style="width:100%;padding: 0px !important;">
                <b-col md="12" style="width:100%;padding: 0px !important;">
                    <MessageHeaderIconized :title="title" width="100"
                        title_image="/assets/dashboard/icons/Promo-1.svg" />
                </b-col>
            </b-row>
            <b-row style="margin-top: 30px;width:100%;padding: 0px !important; margin-top: 10px;">
                <b-col md="12" class="align-items-center" style="width:100%;padding: 0px !important;">
                    <div class="message-body" style="width:100%;text-align: center;">Changes you made will not be saved.
                    </div>
                </b-col>
            </b-row>
            <b-row style="width:100%; padding: 0px !important;margin-top: 15px;">
                <b-col md="6" style="padding: 10px;padding-right: 4px; ">
                    <div style="display: flex; align-items: center; justify-content: end;">
                        <b-button @click="btnOneClicked()"
                            style="width: 80% !important;height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px solid #5063F4 !important;background-color: white !important;color: #5063F4;">
                            {{ btnOneText }}
                        </b-button>
                    </div>
                </b-col>
                <b-col md="6" style="padding: 10px; padding-right: 4px; !important;">
                    <div style="display: flex; align-items: center; justify-content: end;">

                        <b-button @click="btnTwoClicked()"
                            style="width: 80% !important; height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                            {{ btnTwoText }}
                        </b-button>
                    </div>
                </b-col>
            </b-row>

        </b-container>

    </b-modal>
</template>
<style scoped>
    #twobtnaction {
        .modal-body {
            width: 100% !important;
            padding: 30px !important;

        }

        .modal-content {
            border-radius: 20px !important;
        }

        .row {
            display: -ms-flexbox !important;
            display: flex !important;
            -ms-flex-wrap: wrap !important;
            flex-wrap: wrap !important;
            margin-right: 0px !important;
            margin-left: 0px !important;
        }

        .message-body {
            font-family: Montserrat;
            font-size: 20px;
            font-weight: 500;
            line-height: 26px;
            letter-spacing: 0px;
            text-align: left;

        }

        #message-action-btn-solid {
            width: 80% !important;
            height: 40px !important;
            padding: 10px, 30px, 10px, 30px !important;
            border-radius: 20px !important;
            border: 2px !important;
            background-color: #5063F4 !important;
            color: white !important;
        }

        #message-action-btn-outline {
            width: 80% !important;
            height: 40px !important;
            padding: 10px, 30px, 10px, 30px !important;
            border-radius: 20px !important;
            border: 2px solid #5063F4 !important;
            background-color: white !important;
            color: #5063F4;


        }
    }
</style>
<script>
    import MessageHeaderIconized from "../messageboxes/MessageHeaderIconizedCentered.vue";
    import Button from "../../shared/Buttons/Button";
    export default {
        components: {
            MessageHeaderIconized,
            Button

        },

        props: ['title', 'message', 'showm', 'size', 'btnOneText', 'btnTwoText'],
        computed: {
            visible() {
                return this.showm;
            }
        },
        methods: {
            btnOneClicked() {
                this.$emit('stayOnPage', new Date());
            },
            btnTwoClicked() {
                this.$emit('leaveThepage', new Date());
            }
        }

    }
</script>