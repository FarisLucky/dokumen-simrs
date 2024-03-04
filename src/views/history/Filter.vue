<template>
    <div class="p-2 bg-white rounded mb-2">
        <div class="row align-items-end">
            <div class="col-lg-3 pe-0">
                <div class="mb-1">
                    <label for="by_name">Filter By No RM</label>
                    <VSelect
                        type="text"
                        id="by_name"
                        v-model="filter.mr"
                        @search="fetchOpt"
                        @option:selected="setSelected"
                        :reduce="(pasien) => pasien.mr"
                        :options="pasiensOpt"
                    >
                        <div class="spinner" v-show="spinner">Loading...</div>
                    </VSelect>
                </div>
            </div>
            <div class="col">
                <div class="mb-1">
                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        @click.prevent="onRefresh"
                    >
                        <i class="fa fa-refresh" aria-hidden="true"></i>
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import VSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { http } from "@/config/http";
import dokumenService from "@/services/dokumenService";

function initState() {
    return {
        filter: {
            mr: "",
        },
        pasiensOpt: [],
    };
}

export default {
    components: {
        VSelect,
    },
    data() {
        return initState();
    },
    methods: {
        async fetchOpt(search, loading) {
            if (search.length >= 3) {
                loading(true);
                await http
                    .get(`/history-dokumens/by-reg?mr=${search}`)
                    .then((resp) => {
                        let pasiens = [];
                        resp.data.data.forEach((item) => {
                            pasiens.push({
                                mr: item.mr,
                                label: item.mr + " - " + item.nama,
                            });
                        });
                        this.pasiensOpt = pasiens;
                        loading(false);
                    })
                    .catch(() => {
                        loading(false);
                    });
            }
        },
        onRefresh() {
            Object.assign(this.$data, initState());
            this.$emit("fetch");
        },
        async setSelected() {
            this.$Progress.start();

            let mr = `/${this.filter.mr}`;
            let url = `/history-dokumens/by-mr${mr}`;
            await dokumenService
                .get(url)
                .then((resp) => {
                    this.$emit("update-data", resp);
                    this.$Progress.finish();
                })
                .catch((error) => {
                    alert(error);
                    this.$Progress.fail();
                });
        },
    },
};
</script>
<style scoped>
</style>