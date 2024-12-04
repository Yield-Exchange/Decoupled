<template>
    <div>
        <b-link :style="cStyle" @click="clicked" v-bind="attrs" :href="getLink" class="button round"
            :class="getType + ' ' + getClass">
            <span role="status" class="spinner-border spinner-border-sm text-light" style="margin-right: 5px;"
                v-if="loading">
                <span class="sr-only">Loading</span>
            </span>
            {{ getText }}
        </b-link>

    </div>
</template>

<style>
.button {
    position: relative;
    display: inline-block;
    padding: 6px 32px;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-color: transparent;
    border: 1px solid transparent;
    font-size: 1.1rem;
    border-radius: 0.25rem;
    min-width: 120px !important;
    /* color: var(--White, #FFF); */

    /* Yield Exchange Text Styles/Buttons Bold */
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    line-height: 20px;
    /* 125% */
    text-transform: capitalize;
    /* transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; */
}

.button-primary {
    color: #fff;
    background-color: #3656A6;
    border-color: #3d66cd;
}

.button-primary:hover {
    color: #fff;
    background-color: #456cce;
    border-color: #366aee;
}

.button-secondary {
    color: #3656A6;
    background-color: #ffffff;
    border: 1px solid #3656A6 !important;
}

.button-secondary:hover {
    background-color: #f1f1f1;
}

.round {
    border-radius: 36px;
}

.button.disabled,
.button:disabled {
    opacity: .65;
}
</style>


<script>

export default {

    props: ['text', 'link', 'type', "xclass", "loading", "attributes", "cStyle"],
    data() {
        return {
            attrs: this.attributes ? this.attributes : {}
        }
    },
    computed: {
        getLink() {
            return (this.link) ? this.link : "#";
        },
        getText() {
            return (this.text) ? this.text : "Empty Text Property";
        },
        getType() {
            return (this.type == 'secondary') ? "button-secondary" : "button-primary";
        },
        getClass() {
            return (this.xclass) ? this.xclass : "";
        }
    },
    methods: {
        clicked() {
            this.$emit('click', true);
        }
    }
}

</script>
