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
    id="passwordModal"
    ref="passwordModal"
  >
    <div
      class="modal-dialog"
      role="document"
    >
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ganti Password</h5>
          <a
            href="javascript(0)"
            class="btn btn-outline-secondary"
            @click.prevent="hideModal"
          >
            <span aria-hidden="true">&times;</span>
          </a>
        </div>
        <div class="modal-body">
          <div class="mb-1">
            <label for="password">Password</label>
            <div class="input-group">
              <input
                :type="viewPassword"
                v-model="form.password"
                class="form-control"
              >
              <button
                class="btn btn-secondary"
                type="button"
                id="button-addon2"
                @click="changeView"
              >Show</button>
            </div>
          </div>
          <div class="mb-1">
            <label for="password">Konfirmasi Password</label>
            <div class="input-group">
              <input
                :type="viewPasswordConfirm"
                v-model="form.password_confirm"
                class="form-control"
              >
              <button
                class="btn btn-secondary"
                type="button"
                id="button-addon2"
                @click="changeViewConfirm"
              >Show</button>
            </div>
          </div>
          <span
            v-if="checkPass"
            class="text-danger"
          >Password tidak sama</span>
        </div>
        <div class="modal-footer justify-conten-start">
          <div class="mb-1">
            <button
              type="button"
              class="btn btn-primary d-block"
              @click.prevent="onSubmit"
            >Simpan</button>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>
<script>
import { Modal } from "bootstrap";
import { http } from "../../config/http";
import Cookies from "js-cookie";

export default {
  data() {
    return {
      form: {
        password: "",
        password_confirm: "",
      },
      viewPassword: "password",
      viewPasswordConfirm: "password",
      modal: null,
      checkPass: false,
    };
  },
  mounted() {
    this.modal = new Modal(this.$refs.passwordModal);
  },
  watch: {
    "form.password_confirm"() {
      if (
        this.form.password !== "" &&
        this.form.password_confirm !== this.form.password
      ) {
        this.checkPass = true;
      } else {
        this.checkPass = false;
      }
    },
  },
  methods: {
    showModal() {
      this.modal.show();
    },
    hideModal() {
      this.modal.hide();
    },
    changeView() {
      if (this.viewPassword === "password") {
        this.viewPassword = "text";
      } else {
        this.viewPassword = "password";
      }
    },
    changeViewConfirm() {
      if (this.viewPasswordConfirm === "password") {
        this.viewPasswordConfirm = "text";
      } else {
        this.viewPasswordConfirm = "password";
      }
    },

    async onSubmit() {
      if (!this.checkPass && this.form.password !== "") {
        this.$Progress.start();

        await http
          .put("update-password", this.form)
          .then(() => {
            this.$Progress.finish();
            Cookies.remove("user");
            this.$router.go();
          })
          .catch((err) => {
            alert(err.response?.data?.message);
            this.$Progress.fail();
          });
      }
    },
  },
};
</script>