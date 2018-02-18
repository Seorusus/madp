<template id="breadcrumb">
    <span>
        <router-link v-if="!route.meta.bcDynamic" :to="route.path" :class="{'inactive': inactive}">
            {{route.meta.bcLinkText}}
        </router-link>
        <router-link v-if="route.meta.bcDynamic" :to="{name: route.name, params: {id: $route.params.id}}" :class="{'inactive': inactive}">
            <template v-if="value">
                {{formattedValue}}
            </template>
            <template v-if="!value">
                {{loadingText}}
            </template>
        </router-link>
    </span>
</template>

<script>
    export default {
        name: "bread-crumb-item",
        props: ['route', 'inactive'],
        beforeCreate() {
            this.$options.computed.value = function () {
                if (this.route.meta.bcGetter) {
                    return this.$store.getters[this.route.meta.bcGetter]
                } else {
                    return null
                }
            }
        },
        computed: {
            formattedValue() {
                return this.route.meta.bcLinkText(this.value)
            },
            loadingText() {
                const loadingText = this.route.meta.bcLoadingText
                return loadingText ? loadingText : 'Chargement...'
            }
        }
    }
</script>

<style scoped>
a.inactive{
    pointer-events: none;
    cursor: default;
    color: #4e555b;
}
</style>