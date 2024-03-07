<style scoped>
.header-img {
  border-radius: 8px;
  width: 100%;
  height: 220px;
}
.card .card-header {
  padding: 0.5rem 0.5rem 0 0.5rem;
}
.card .card-body {
  padding: 0.5rem 0.5rem 1rem 0.5rem;
}
.card-body .cover {
  padding: 0.3rem;
}
.card-body .title-cover .title {
  font-size: 16px;
  font-weight: 600;
  display: inline-block;
  letter-spacing: 1.5px;
  padding-bottom: 0.2rem;
  margin-bottom: 0.2rem;
  border-bottom: 1px solid #67676773;
}
.card-body .subtitle-cover {
  display: inline-block;
  padding-bottom: 0.4rem;
  margin-bottom: 0.8rem;
  border-bottom: 1px solid #67676773;
}
.subtitle,
.subtitle-tgl,
.subtitle-penunjang {
  font-size: 12px;
  letter-spacing: 1px;
  margin-bottom: 2px;
  padding: 0.3rem;
}
.subtitle-tgl {
  padding-right: 0.5rem;
}
.subtitle-penunjang {
  display: inline-block;
  border-radius: 5px;
}
.subtitle-penunjang span {
  padding: 0.1rem;
  background: rgba(85, 214, 194, 0.5);
}
.btn {
  padding: 0.5rem 0.7rem;
}
.bg-cover {
  border-radius: 5px;
  background: #f8f9fa;
}
.title-cover {
  display: flex;
  justify-content: space-between;
}
.subtitle .ruangan {
  background: linear-gradient(
    90deg,
    hsla(205, 46%, 30%, 1) 0%,
    hsla(260, 29%, 36%, 1) 100%
  );
  color: white;
  padding: 0.4rem;
  border-radius: 4px;
  font-weight: 600;
}
</style>
<template>
  <div>
    <div class="mb-1 bg-cover p-2">
      <div class="row">
        <div class="col-lg-12">
          <form-upload
            v-if="isLoggedIn"
            @fetch="fetchData"
            :regist="register"
            ref="formUpload"
          />
        </div>
        <div class="col-lg-12">
          <div class="d-flex justify-content-between border-bottom mb-2 pb-2">
            <h6 class="ps-2 mb-2 d-inlin-block">File Penunjang</h6>
            <button
              class="btn btn-outline-secondary"
              @click.prevent="$router.go(-1)"
            >Kembali</button>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="row align-items-end">
            <div class="col-lg-3">
              <div class="mb-2">
                <label for="filter_kunjungan">Cari Kunjungan</label>
                <v-select
                  v-model="filter.kunjungan"
                  id="filter_kunjungan"
                  :options="listKunjungan"
                  placeholder="Pilih Kunjungan"
                  label="tgl_mrs"
                  @option:selected="kunjunganSelected"
                >
                </v-select>
              </div>
            </div>
            <div class="col-lg">
              <div class="mb-2">
                <button
                  class="btn btn-secondary px-3"
                  @click="onRefresh"
                >
                  <i
                    class="fa fa-refresh"
                    aria-hidden="true"
                  ></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div v-if="!adaBerkas">
          <h5 class="p-4 bg-light rounded">Belum ada Berkas</h5>
        </div>
        <div
          v-else
          class=" col-6 col-md-3 .col-lg-3"
          v-for="(row, idx) in rows"
          :key="idx"
        >
          <div class="card mb-3">
            <div class="card-header">
              <div class="mb-1 header-img-cover">
                <img
                  v-lazy="isPdf(row.filepond[0].extension) ? pdfThumb : routeImg(row.id)"
                  class="header-img"
                >
              </div>
            </div>
            <div class="card-body">
              <div class="mb-1 cover">
                <div class="title-cover">
                  <h6 class="title">{{ row.nama_dok }}</h6>
                  <a
                    v-if="isLoggedIn"
                    href="javascript(0)"
                    @click.prevent="onDestroy(row.id)"
                    class="text-danger"
                  >
                    <i
                      class="fa fa-times"
                      aria-hidden="true"
                    ></i>
                  </a>
                </div>
                <div class="subtitle-cover">
                  <p class="subtitle">- Sumber: <strong>{{ row.sumber }}</strong> </p>
                  <p class="subtitle-tgl">- Tgl Periksa: <strong>{{ row.tgl_periksa }}</strong></p>
                  <p class="subtitle-penunjang">- Penunjang: <strong>{{ row.penunjang }}</strong></p>
                </div>
                <div class="footer-cover">
                  <router-link
                    v-if="!isPdf(row.filepond[0].extension)"
                    :to="'/pasien/dokumen/' + row.id"
                    target="_blank"
                    class="btn btn-success mx-2"
                    @click.prevent="detailDokumen(row.id)"
                  >
                    <i
                      class="fa fa-eye"
                      aria-hidden="true"
                    ></i>
                    Lihat
                  </router-link>
                  <a
                    v-if="isPdf(row.filepond[0].extension)"
                    :href="getPdfRouteWeb(row.id)"
                    target="_blank"
                    class="btn btn-danger"
                  >
                    <i
                      class="fa fa-file-pdf-o"
                      aria-hidden="true"
                    ></i>
                    Download
                  </a>
                  <a
                    v-else
                    :href="getRouteWeb(row.id)"
                    target="_blank"
                    class="btn btn-danger"
                  >
                    <i
                      class="fa fa-file-pdf-o"
                      aria-hidden="true"
                    ></i>
                    PDF
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <ViewPdf ref="modalViewRef" />
  </div>
</template>
  
<script>
import dokumenService from "@/services/dokumenService";
import { httpWeb } from "@/config/http";
import { useAuthStore } from "@/store/auth";
import { defineAsyncComponent } from "vue";
import Cookies from "js-cookie";
import pdfThumb from "@/assets/gif/pdf.gif";

export default {
  components: {
    FormUpload: defineAsyncComponent(() =>
      import("@/views/pasien/FormUpload.vue")
    ),
    ViewPdf: defineAsyncComponent(() => import("./ViewPdf.vue")),
  },
  data() {
    return {
      isLoading: false,
      adaBerkas: false,
      rows: [],
      isLoggedIn: false,
      totalRecords: 0,
      register: null,
      pdfThumb,
      pdfExt: "pdf",
      listKunjungan: [],
      filter: {
        kunjungan: "",
      },
    };
  },
  mounted() {
    // Cookies.set("from", this.$route.name);
    if (Cookies.get("fromLogin")) {
      this.$router.go();
      Cookies.remove("fromLogin");
    }
  },
  created() {
    this.register = this.$route.params.register;
    this.fetchData(this.register);
    this.isLoggedIn = useAuthStore().getLoggedIn();
  },
  methods: {
    getRouteWeb(id) {
      return `${httpWeb}/pasien/${id}/pdf-download`;
    },
    routeImg(id) {
      return `${httpWeb}/pasien/${id}/thumbnail`;
    },
    detailDokumen(id) {
      this.$refs.modalViewRef.showModal(id);
    },
    getPdfRouteWeb(id) {
      return `${httpWeb}/pasien/${id}/pdf-type-download`;
    },
    isPdf(ext) {
      return ext === this.pdfExt;
    },
    async fetchData(register) {
      this.$Progress.start();

      let url = `/dokumens-open?register=` + register;
      if (useAuthStore().getLoggedIn()) {
        url = `/dokumens?register=` + register;
      }

      await dokumenService
        .get(url)
        .then((resp) => {
          this.rows = resp.data;
          if (this.rows.length >= 1) {
            this.adaBerkas = true;
          } else {
            this.adaBerkas = false;
          }
          this.$Progress.finish();
        })
        .catch((error) => {
          alert(error);
          this.$Progress.fail();
        });
    },
    async onDestroy(id) {
      if (confirm("Apakah ingin dihapus ?")) {
        this.$Progress.start();
        let url = `/dokumens/${id}`;
        await dokumenService
          .destroy(url)
          .then(() => {
            this.onRefresh();
            this.$Progress.finish();
          })
          .catch((error) => {
            alert(error);
            this.$Progress.fail();
          });
      }
    },
    onRefresh() {
      this.filter.kunjungan = "";
      this.fetchData(this.register);
    },

    kunjunganSelected(event) {
      this.fetchData(event.register);
    },
  },
};
</script>
  