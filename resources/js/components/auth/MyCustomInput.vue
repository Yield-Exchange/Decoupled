<template>
    
        <div class="form-group">
            <label :for="inputprop.name">{{ inputprop.label }}</label>
            <input :value="modelValue" :type="inputprop.type" :id="inputprop.name"
            @input="$emit('input', $event.target.value)"
            class="form-control" required>
        </div>
</template>

<script>
export default{
    props:["inputprop","modelValue"]
}
</script>

<style scoped>
.form-control {
    border: none;
    border-bottom: 1px solid rgba(0, 0, 0, 0.332);
    border-radius: 0px;
}

.form-container {
    padding: 2rem;
    /* box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.209); */
    border-radius: 5px;

}

label:after {
    content: " *";
    color: red;
}

</style>