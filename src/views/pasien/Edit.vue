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
                  <label for="nama">Nama</label>
                  <input
                    type="text"
                    id="nama"
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
                  <label for="tgl_lahir">Tgl Lahir</label>
                  <input
                    type="text"
                    id="tgl_lahir"
                    class="form-control"
                    v-model="form.tgl_lahir"
                    :disabled="true"
                  />
                </div>
              </div>
              <div class="col-md-4 col-lg-2">
                <div class="mb-1">
                  <label for="tgl_mrs">Tgl MRS</label>
                  <input
                    type="text"
                    id="tgl_mrs"
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
            <component
              :is="Component"
              ref="viewByRegRef"
            />
          </transition>
        </router-view>
      </div>
    </div>
  </div>
</template>

<script>
import pasienService from "@/services/pasienService";
import dokumenService from "@/services/dokumenService";
import { useAuthStore } from "@/store/auth";

export default {
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
      listKunjungan: [],
    };
  },
  created() {
    this.onRefresh();
  },
  methods: {
    show(params) {
      this.$Progress.start();
      let url = `/pasiens-open/${params.id}/${params.type}`;

      if (useAuthStore().getLoggedIn()) {
        url = `/pasiens/${params.id}/${params.type}`;
      }

      pasienService
        .find(url)
        .then((resp) => {
          this.form.register = resp.data.data?.REGISTER;
          this.form.mr = resp.data.data?.pasien?.MR ?? resp.data.data?.MR;
          this.form.nama = resp.data.data?.pasien?.NAMA ?? resp.data.data?.NAMA;
          this.form.tgl_lahir =
            resp.data.data?.pasien?.TGL_LAHIR ?? resp.data.data?.TGL_LAHIR;
          this.form.alamat =
            resp.data.data?.pasien?.ALAMAT ?? resp.data.data?.ALAMAT;
          this.form.tanggal = resp.data.data?.TANGGAL;
          this.form.ruangan = resp.data.data?.RUANGAN;

          if (this.$route.name === "ViewByReg") {
            this.getListKunjungan(this.form.mr);
            this.$refs.viewByRegRef.filter.kunjungan = this.form.tanggal;
          }
          this.$Progress.finish();
        })
        .catch((err) => {
          this.$Progress.fail();
          alert(err);
        });
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

    getListKunjungan(mr) {
      let url = `/list-kunjungan/${mr}/pasien`;
      dokumenService
        .find(url)
        .then((resp) => {
          let kunjungan = resp.data;
          kunjungan.every((k) => {
            if (k.tgl_mrs !== this.form.tanggal) {
              // jika belum ada kunjungan maka tambahkan tanggal nya
              return false;
            } else if (k.tgl_mrs !== this.form.tanggal) {
              kunjungan.push({
                register: this.form.register,
                tgl_mrs: this.form.tanggal,
              });
            }
          });

          this.$refs.viewByRegRef.listKunjungan = kunjungan;
        })
        .catch((error) => {
          alert(error);
        });
    },
  },
};
</script>
