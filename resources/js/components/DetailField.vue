<template>
    <PanelItem :field="field">
        <template #value>
            <excerpt :content="field.value" :should-show="field.shouldShow" />
        </template>
    </PanelItem>
</template>


<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
}
</script>
