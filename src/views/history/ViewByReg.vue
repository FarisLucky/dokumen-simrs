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
  padding: 0 0.3rem;
  border-bottom: 1px solid #69696980;
}
.subtitle-tgl {
  padding-right: 0.5rem;
}
.subtitle-penunjang {
  display: inline-block;
  border-radius: 5px;
  display: block;
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
  margin-bottom: 0.5rem;
}
i.by-ruangan {
  background: linear-gradient(
    90deg,
    hsla(205, 46%, 30%, 1) 0%,
    hsla(260, 29%, 36%, 1) 100%
  );
  color: white;
  padding: 0.4rem;
  border-radius: 4px;
  font-weight: 600;
  display: inline-block;
}
</style>
<template>
  <div>
    <div class="mb-1 bg-cover p-2">
      <div class="row">
        <div class="col-lg-12">
          <div class="d-flex justify-content-between border-bottom mb-2 pb-2">
            <h6 class="ps-2 mb-2 d-inlin-block">History Dokumen</h6>
            <button
              class="btn btn-outline-secondary"
              @click.prevent="$router.go(-1)"
            >Kembali</button>
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
                <div class="title-cover d-flex justify-content-between">
                  <h6 class="title">{{ row.nama_dok }}</h6>
                </div>
                <div class="subtitle-cover">
                  <p class="subtitle">- <strong>{{ row.nama }} ({{ row.mr }})</strong> </p>
                  <p class="subtitle">- Sumber: <strong>{{ row.sumber }}</strong> </p>
                  <p class="subtitle-tgl">- Tgl Periksa: <strong>{{ row.tgl_periksa }}</strong></p>
                  <p class="subtitle-penunjang">- Penunjang: <b>{{ row.penunjang }}</b></p>
                  <i
                    class="by-ruangan"
                    v-if="row.created_by_ruangan !== null"
                  >{{ row.created_by_ruangan }}</i>
                </div>
                <div class="footer-cover">
                  <a
                    v-if="!isPdf(row.filepond[0].extension)"
                    href="javascript(0)"
                    target="_blank"
                    class="btn btn-warning mx-2"
                    @click.prevent="editDokumen(row.id)"
                  >
                    <i
                      class="fa fa-pencil"
                      aria-hidden="true"
                    ></i>
                    Edit
                  </a>
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
    <EditImage ref="EditImageRef" />
  </div>
</template>
  
<script>
import dokumenService from "@/services/dokumenService";
import { httpWeb } from "@/config/http";
import { defineAsyncComponent } from "vue";
import pdfThumb from "@/assets/gif/pdf.gif";

export default {
  components: {
    ViewPdf: defineAsyncComponent(() => import("./ViewPdf.vue")),
    EditImage: defineAsyncComponent(() => import("./EditImage.vue")),
  },
  data() {
    return {
      isLoading: false,
      adaBerkas: false,
      rows: [],
      totalRecords: 0,
      register: null,
      pdfThumb,
      pdfExt: "pdf",
    };
  },
  created() {
    this.register = this.$route.params.register;
    this.fetchData();
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
    editDokumen(id) {
      this.$refs.EditImageRef.showModal(id);
    },
    getPdfRouteWeb(id) {
      return `${httpWeb}/pasien/${id}/pdf-type-download`;
    },
    isPdf(ext) {
      return ext === this.pdfExt;
    },
    async fetchData() {
      this.$Progress.start();
      let reg = `/${this.$route.params.reg}`;
      let url = `/history-dokumens/by-reg${reg}`;

      await dokumenService
        .get(url)
        .then((resp) => {
          this.rows = resp.data;
          console.log(this.rows.length);
          if (this.rows.length >= 1) {
            this.adaBerkas = true;
          }
          this.$Progress.finish();
        })
        .catch((error) => {
          alert(error);
          this.$Progress.fail();
        });
    },
    onRefresh() {
      this.fetchData();
    },
  },
};
</script>
  