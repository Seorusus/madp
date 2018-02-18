<template>
    <div class="subscription">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" v-for="(route, index) in $route.matched" v-if="route.meta.bcLinkText">
                <bread-crumb-item :route="route" :inactive="index == $route.matched.length - 1"></bread-crumb-item>
            </li>
        </ol>
        <router-view/>
    </div>
</template>

<script>
    import BreadCrumbItem from "../../components/BreadCrumbItem";

    export default {
        components: {BreadCrumbItem},
        name: "Client",
        mounted() {
            console.log(this.$route.matched);
        }
    }
</script>

<style scoped>
    .breadcrumb-item::before {
        content: '>';
    }
    .breadcrumb-item + .breadcrumb-item::before {
        content: '>';
    }
</style>