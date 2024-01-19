<style scoped>
.modal {
  z-index: 9999;
}
img {
  width: 100%;
  height: 100%;
}
</style>
<template>
  <div
    class="modal fade"
    id="exampleModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
    ref="modalRef"
  >
    <div
      class="modal-dialog modal-xl"
      role="document"
    >
      <div class="modal-content">
        <div class="modal-header">
          <h5
            class="modal-title font-weight-normal"
            id="exampleModalLabel"
          >Dokumen Pasien</h5>
          <button
            type="button"
            class="btn-close text-dark"
            data-bs-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="p-1">
            <div
              class="mb-3 text-center border p-2"
              v-for="(source, idx) in sources"
              :key="idx"
            >
              <div id="editor">
              </div>
              <img :src="routePdf(source.id)" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { http } from "@/config/http";
import { httpWeb } from "@/config/http";
import { Modal } from "bootstrap";
import { useAuthStore } from "@/store/auth";

export default {
  props: ["id_file"],
  data() {
    return {
      sources: [],
      modal: null,
      editor: null,
    };
  },
  mounted() {
    this.modal = new Modal(this.$refs.modalRef);
  },
  methods: {
    routePdf(id) {
      return `${httpWeb}/pasien/${id}/pdf`;
    },
    showModal(id) {
      this.initSources(id);
    },
    async initSources(id) {
      let url = `/img-url/generate/${id}/open`;
      if (useAuthStore().getLoggedIn()) {
        url = `/img-url/generate/${id}`;
      }
      await http.get(url).then((resp) => {
        this.sources = resp.data;
        if (this.sources.length < 1) {
          alert("Tidak ada Data");
        }
        this.modal.show();
      });
    },
  },
};
</script>
<style lang=""></style>
