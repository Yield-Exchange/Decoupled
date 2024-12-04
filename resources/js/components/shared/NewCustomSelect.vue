<template>
    <b-select v-model="selected" :disabled="disabled"
        :class="{ 'error-repo-inputs': haserror, 'new-custom-select-no-val': selected == null, 'new-custom-select': selected != null }"
        @change="inputChange" style="border-radius: 999px;" :style="inputStyle">
        <option :value="null" selected disabled>{{ placeholder }}</option>

        <template v-if="options">
            <template v-if="valuekey && idkey">
                <option v-for="item in options" :key="item[idkey]" :value="item[idkey]">{{ item[valuekey] }}
                </option>
            </template>
            <template v-else>
                <option v-for="item in options" :key="item" :value="item">{{ item }}
                </option>
            </template>
        </template>

        <option v-if="add_later" :value="0">Add Later</option>

    </b-select>
</template>

<script>

export default {

    props: ['options', 'defaultValue', 'inputStyle', 'idkey', 'valuekey', 'placeholder', 'haserror', 'add_later', 'disabled'],
    beforeMount() {
        if (this.defaultValue) {
            this.selected = this.defaultValue
        } else {
            this.selected = null
        }
    },
    data() {
        return {
            selected: null,
        }
    },
    methods: {
        inputChange() {
            // console.log(this.selected, "selecte")
            this.$emit('change', this.selected)
        }
    },
    watch: {
        defaultValue() {
            if (this.defaultValue || this.defaultValue == 0) {
                this.selected = this.defaultValue
            } else {
                this.selected = null
            }
        }
    }


}

</script>



<style>
.new-custom-select-no-val {
    color: #9CA1AA !important;
    height: 40px !important;
    padding: 6px 20px !important;
    font-weight: 400 !important;
    width: 100%
}

.new-custom-select-no-val option {
    color: #101828 !important;
}

.new-custom-select-no-val option:disabled {
    color: #9CA1AA !important;
}

.new-custom-select option:disabled {
    color: #9CA1AA !important;
}

.new-custom-select {
    color: #101828 !important;
    height: 40px !important;
    padding: 6px 20px !important;
    font-weight: 400 !important;
    width: 100%
}

.new-custom-select option {
    color: #101828 !important;

}
</style>