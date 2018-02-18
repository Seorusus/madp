<template>
    <div class="dasboard">
        <div class="row">
            <div class="col-lg col-md-6">
                <router-link to="/espace-client/mes-infos" class="d-block bg-light py-5 h-100">
                    <div class="big-image-link text-center">
                        <i class="fas fa-info fa-5x d-block mx-auto"></i>
                        <h3 class="pt-4">Mes infossssssss</h3>
                    </div>
                </router-link>
            </div>
            <div class="col-lg col-md-6">
                <router-link to="/espace-client/mes-contrats" class="d-block bg-light py-5 h-100">
                    <div class="big-image-link text-center">
                        <i class="fas fa-file-alt fa-5x d-block mx-auto"></i>
                        <h3 class="pt-4">Mes Contrats</h3>
                    </div>
                </router-link>
            </div>
            <div class="col-lg col-md-6">
                <router-link to="/espace-client/mes-sinistres"  class="d-block bg-light py-5 h-100">
                    <div class="big-image-link text-center">
                        <i class="fas fa-exclamation-triangle fa-5x d-block mx-auto"></i>
                        <h3 class="pt-4">Mes Sinistres</h3>
                    </div>
                </router-link>
            </div>
            <div class="col-lg col-md-6">
                <router-link to="/espace-client/messagerie"  class="d-block bg-light py-5 h-100">
                    <div class="big-image-link text-center">
                        <i class="fas fa-comments fa-5x d-block mx-auto"></i>
                        <h3 class="pt-4">Ma messagerie</h3>
                    </div>
                </router-link>
            </div>
        </div>
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