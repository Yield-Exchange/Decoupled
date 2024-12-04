<template>
    <div v-on:click="$emit('click')" v-bind:style="cStyle" style="cursor: pointer">
        <Primary v-if="type=='primary'">
            <slot></slot>
        </Primary>
        <Outlined v-if="type=='outlined'">
            <slot></slot>
        </Outlined>
        <OutlinedGray v-if="type=='outlined-gray'">
            <slot></slot>
        </OutlinedGray>
        <TableActionButton v-if="type=='table-action-btn'" :variant-color="variantColor">
            <slot></slot>
        </TableActionButton>
    </div>
</template>
<script>
    import Primary from "./Primary";
    import Outlined from "./Outlined";
    import OutlinedGray from "./OutlinedGray";
    import TableActionButton from "./TableActionButton";
    export default {
        components:{
            Primary,
            Outlined,
            OutlinedGray,
            TableActionButton
        },
        props:[
            'type','variantColor','cStyle'
        ],

    }
</script>