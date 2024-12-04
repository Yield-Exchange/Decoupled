<template>
    <TableActionButton type="table-action-btn" @click="viewOffer()" text-color="#5063F4" variant-color="#EFF2FE">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
            <g clip-path="url(#clip0_2219_46040)">
                <path d="M5.5 4.5C5.89782 4.5 6.27936 4.65804 6.56066 4.93934C6.84196 5.22064 7 5.60218 7 6C7 6.39782 6.84196 6.77936 6.56066 7.06066C6.27936 7.34196 5.89782 7.5 5.5 7.5C5.10218 7.5 4.72064 7.34196 4.43934 7.06066C4.15804 6.77936 4 6.39782 4 6C4 5.60218 4.15804 5.22064 4.43934 4.93934C4.72064 4.65804 5.10218 4.5 5.5 4.5ZM5.5 2.25C8 2.25 10.135 3.805 11 6C10.135 8.195 8 9.75 5.5 9.75C3 9.75 0.865 8.195 0 6C0.865 3.805 3 2.25 5.5 2.25ZM1.09 6C1.49413 6.82515 2.12165 7.52037 2.90124 8.00663C3.68083 8.49288 4.5812 8.75066 5.5 8.75066C6.4188 8.75066 7.31917 8.49288 8.09876 8.00663C8.87835 7.52037 9.50587 6.82515 9.91 6C9.50587 5.17485 8.87835 4.47963 8.09876 3.99337C7.31917 3.50712 6.4188 3.24934 5.5 3.24934C4.5812 3.24934 3.68083 3.50712 2.90124 3.99337C2.12165 4.47963 1.49413 5.17485 1.09 6Z" fill="#5063F4"/>
            </g>
            <defs>
                <clipPath id="clip0_2219_46040">
                    <rect width="12" height="12" fill="white" transform="translate(0.5 0.5)"/>
                </clipPath>
            </defs>
        </svg>

        View</TableActionButton>
</template>
<script>
    import Button from "../../shared/Buttons/Button";
    import TableActionButton from "../../shared/Buttons/TableActionButton";
    export default {
        components:{
            TableActionButton,
            Button,
        },
        methods:{
            viewOffer(){
                window.location.href=`/inv-camp-offers/'+${this.actionId}?fromPage=in_progress`
            }
        },
        props: ['actionId']
    }
</script>