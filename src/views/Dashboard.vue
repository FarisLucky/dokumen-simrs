<template>
  <div class="py-4 container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="row">
          <div class="col-lg-3 col-md-6 col-12">
            <card
              :title="stats.dokumen.title"
              :value="stats.dokumen.value"
              :percentage="stats.dokumen.percentage"
              :iconClass="stats.dokumen.iconClass"
              :iconBackground="stats.dokumen.iconBackground"
              :detail="stats.dokumen.detail"
              directionReverse
            ></card>
          </div>
          <div class="col-lg-3 col-md-6 col-12">
            <card
              :title="stats.dokumen_all.title"
              :value="stats.dokumen_all.value"
              :percentage="stats.dokumen_all.percentage"
              :iconClass="stats.dokumen_all.iconClass"
              :iconBackground="stats.dokumen_all.iconBackground"
              :detail="stats.dokumen_all.detail"
              directionReverse
            ></card>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Card from "@/examples/Cards/Card.vue";
import { http } from "../config/http";
export default {
  name: "dashboard-default",
  data() {
    return {
      stats: {
        dokumen: {
          title: "Upload Hari ini",
          value: "0",
          iconClass: "ni ni-money-coins",
          detail: "total dokumen diupload hari ini",
          iconBackground: "bg-gradient-primary",
        },
        dokumen_all: {
          title: "Total Dokumen",
          value: "0",
          iconClass: "ni ni-world",
          detail: "Total semua dokumen",
          iconBackground: "bg-gradient-danger",
        },
      },
    };
  },
  components: {
    Card,
  },
  created() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      await http
        .get("/dashboard")
        .then((resp) => {
          this.stats.dokumen.value = resp.data.data.doc_today;
          this.stats.dokumen_all.value = resp.data.data.doc_all;
        })
        .catch((err) => {
          alert(err);
          console.log(err);
        });
    },
  },
};
</script>
