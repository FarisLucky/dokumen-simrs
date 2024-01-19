<style scoped>
.header-img {
  border-radius: 8px;
  width: 100%;
  height: 200px;
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
  font-size: 14px;
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
  font-weight: 800;
  padding-right: 0.5rem;
}
.subtitle-penunjang {
  display: inline-block;
  font-weight: 900;
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
.footer-cover {
  margin-top: 5px;
  /* text-align: right; */
}
.cover {
  max-height: 130px;
}
</style>
<template>
  <div class="mb-1 bg-cover p-2">
    <div class="row">
      <div class="col-lg-12">
        <div class="d-flex justify-content-between border-bottom mb-2 pb-2">
          <h6 class="ps-2 mb-2 d-inlin-block">Folder Penunjang</h6>
          <button
            class="btn btn-outline-secondary"
            @click.prevent="$router.go(-1)"
          >Kembali</button>
        </div>
      </div>
      <div class="col-lg-12">
        <Filter
          @rows="rowsData"
          @fetch="fetchData"
        />
      </div>
      <div v-if="!dir">
        <h5 class="p-4">Tidak ada Berkas</h5>
      </div>
      <div
        v-else
        class="col-6 col-md-3 col-lg-3"
        v-for="(row, idx) in rows"
        :key="idx"
      >
        <div class="card mb-3">
          <div class="card-header">
            <div class="mb-1 header-img-cover">
              <img
                v-lazy="FolderThumb"
                class="header-img"
              >
            </div>
          </div>
          <div class="card-body">
            <div class="mb-1 cover">
              <div class="title-cover">
                <h6 class="title">NO: {{ row.mr }}</h6>
                <h6 class="title d-block">Nama: {{ row.nama ?? '-' }}</h6>
              </div>
              <div class="footer-cover">
                <router-link
                  :to="{name: 'HistoryViewByMr', params: {mr: row.mr}}"
                  class="btn btn-danger pe-2"
                >
                  <i
                    class="fa fa-eye"
                    aria-hidden="true"
                  ></i>
                  Lihat
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import dokumenService from "@/services/dokumenService";
import { httpWeb } from "@/config/http";
import FolderThumb from "@/assets/img/folder-list.png";
import Filter from "./Filter.vue";

export default {
  components: {
    Filter,
  },
  data() {
    return {
      isLoading: false,
      dir: false,
      rows: [],
      totalRecords: 0,
      FolderThumb,
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    getRouteWeb(id) {
      return `${httpWeb}/pasien/${id}/pdf-convert`;
    },
    routeImg(id) {
      return `${httpWeb}/pasien/${id}/thumbnail`;
    },
    async fetchData() {
      this.$Progress.start();

      let url = `/history-dokumens/by-mr`;

      await dokumenService
        .get(url)
        .then((resp) => {
          this.rows = resp.data;
          if (this.rows !== null && this.rows.length >= 1) {
            this.dir = true;
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
    rowsData(rows) {
      this.rows = rows;
    },
  },
};
</script>
