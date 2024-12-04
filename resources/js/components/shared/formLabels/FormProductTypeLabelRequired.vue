<template>
    <div style="width: 100%; display: flex; justify-content: space-between;">
        <div class="form-label-text-required-container">
            <span class="form-label" style=" word-wrap: break-word">{{ labelText }} <span
                    style="color: #5063F4; width:0.5px; height: 0.5px;">* </span></span>

        </div>
        <div v-if="showHelperText" class="form-label-helper-container">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"
                :id="helperId">
                <g clip-path="url(#clip0_1586_18945)">
                    <path
                        d="M6.06016 5.99992C6.2169 5.55436 6.52626 5.17866 6.93347 4.93934C7.34067 4.70002 7.81943 4.61254 8.28495 4.69239C8.75047 4.77224 9.17271 5.01427 9.47688 5.3756C9.78106 5.73694 9.94753 6.19427 9.94683 6.66659C9.94683 7.99992 7.94683 8.66659 7.94683 8.66659M8.00016 11.3333H8.00683M14.6668 7.99992C14.6668 11.6818 11.6821 14.6666 8.00016 14.6666C4.31826 14.6666 1.3335 11.6818 1.3335 7.99992C1.3335 4.31802 4.31826 1.33325 8.00016 1.33325C11.6821 1.33325 14.6668 4.31802 14.6668 7.99992Z"
                        stroke="#5063F4" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                </g>
                <defs>
                    <clipPath id="clip0_1586_18945">
                        <rect width="16" height="16" fill="white" />
                    </clipPath>
                </defs>
            </svg>
            <!-- {{ showHelperText }} -->
            <TooltipProduct :message="helperText" :target="helperId" />
        </div>
    </div>
</template>

<script>
    import TooltipProduct from "../../shared/TooltipProduct";
    export default {
        props: ['labelText', 'required', 'showHelperText', 'helperText', 'helperId'],
        data() {
            return {

            }
        },
        components: {
            TooltipProduct
        },
        computed: {

        },
        methods: {

        }
    }
</script>
<style>
    .form-label-helper-container {
        flex: 1;
        margin-left: 8px !important;
    }

    .form-label-text-required-container {
        font-family: Montserrat;
        font-size: 28px;
        font-weight: 700;
        line-height: 32px;
        letter-spacing: 0em;
        text-align: left;
        margin-left: 10px;

    }
</style>