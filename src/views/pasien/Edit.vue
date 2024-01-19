<template>
  <div>
    <div class="card mb-1">
      <div class="card-body px-0 pt-0 pb-0">
        <div class="d-flex justify-content-between px-4 pt-3">
          <h6 class="border-bottom d-inline-block">Rawat Jalan {{ $route.params.register }}</h6>
        </div>
        <div class="py-3 px-2 mb-1">
          <form @submit.prevent="onSubmit">
            <div class="row px-3">
              <div class="col-md-4 col-lg-2">
                <div class="mb-1">
                  <label for="mr">Nama</label>
                  <input
                    type="text"
                    id="mr"
                    class="form-control"
                    v-model="form.nama"
                    :disabled="true"
                  />
                </div>
              </div>
              <div class="col-md-4 col-lg-4">
                <div class="mb-1">
                  <label for="alamat">Alamat</label>
                  <input
                    type="text"
                    id="alamat"
                    class="form-control"
                    v-model="form.alamat"
                    :disabled="true"
                  />
                </div>
              </div>
              <div class="col-md-4 col-lg-2">
                <div class="mb-1">
                  <label for="mr">MR</label>
                  <input
                    type="text"
                    id="mr"
                    class="form-control"
                    v-model="form.mr"
                    :disabled="true"
                  />
                </div>
              </div>
              <div class="col-md-4 col-lg-2">
                <div class="mb-1">
                  <label for="mr">Tgl Lahir</label>
                  <input
                    type="text"
                    id="mr"
                    class="form-control"
                    v-model="form.tgl_lahir"
                    :disabled="true"
                  />
                </div>
              </div>
              <div class="col-md-4 col-lg-2">
                <div class="mb-1">
                  <label for="mr">Tgl MRS</label>
                  <input
                    type="text"
                    id="mr"
                    class="form-control"
                    v-model="form.tanggal"
                    style="border: 3px solid rgb(214,107,107);"
                    :disabled="true"
                  />
                </div>
              </div>
              <div class="col-md-4 col-lg-2">
                <div class="mb-1">
                  <label for="ruangan">Ruangan</label>
                  <input
                    type="text"
                    id="ruangan"
                    class="form-control"
                    style="border: 3px solid rgb(214,107,107);"
                    v-model="form.ruangan"
                    :disabled="true"
                  />
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <router-view
          v-slot="{Component}"
          :key="$route.fullPath"
        >
          <transition
            name="fade-x"
            mode="out-in"
          >
            <component :is="Component" />
          </transition>
        </router-view>
      </div>
    </div>
  </div>
</template>

<script>
import pasienService from "@/services/pasienService";
import { useAuthStore } from "@/store/auth";
import { httpWeb } from "@/config/http";

export default {
  name: "pasien-table",
  data() {
    return {
      form: {
        register: "",
        mr: "",
        nama: "",
        tgl_lahir: "",
        tanggal: "",
        diagnosa: "",
      },
    };
  },
  created() {
    this.onRefresh();
  },
  methods: {
    getRouteWeb(id) {
      return `${httpWeb}/pasien/${id}/pdf-convert`;
    },
    routeImg(id) {
      return `${httpWeb}/pasien/${id}/thumbnail`;
    },
    detailDokumen(id) {
      this.$refs.modalViewRef.showModal(id);
    },
    async show(params) {
      try {
        this.$Progress.start();

        let url = `/pasiens-open/${params.id}/${params.type}`;

        if (useAuthStore().getLoggedIn()) {
          url = `/pasiens/${params.id}/${params.type}`;
        }

        const resp = await pasienService.get(url);
        // console.log(resp.data);
        this.form.register = resp.data?.REGISTER;
        this.form.mr = resp.data?.pasien?.MR ?? resp.data?.MR;
        this.form.nama = resp.data?.pasien?.NAMA ?? resp.data?.NAMA;
        this.form.tgl_lahir =
          resp.data?.pasien?.TGL_LAHIR ?? resp.data?.TGL_LAHIR;
        this.form.alamat = resp.data?.pasien?.ALAMAT ?? resp.data?.ALAMAT;
        this.form.tanggal = resp.data?.TANGGAL;
        this.form.ruangan = resp.data?.RUANGAN;

        this.$Progress.finish();
      } catch (error) {
        this.$Progress.fail();

        console.log(error);
      }
    },

    onRefresh() {
      let params = {
        id: 1,
        type: "mr",
      };

      if (this.$route.name === "ViewByMr") {
        params.id = this.$route.params.mr;
        params.type = "mr";
      } else {
        params.id = this.$route.params.register;
        params.type = "register";
      }

      this.show(params);
    },
  },
};
</script>
