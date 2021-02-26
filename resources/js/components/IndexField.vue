<template>
    <div class="truncate">{{ cleanValue }}</div>
</template>

<script>
export default {
    props: ['resourceName', 'field'],
    computed: {
        cleanValue () {
            return this._stripHTML(this.field.value)
        },
    },
    methods: {
        _stripHTML (html) {
            let tmp = document.createElement("div");
            tmp.innerHTML = html;
            return tmp.textContent || tmp.innerText || "";
        }
    }
}
</script>
