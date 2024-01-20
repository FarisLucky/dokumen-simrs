<template>
  <div class="py-3 px-2 mb-1">
    <form @submit.prevent="onSubmit">
      <div class="row px-2">
        <div class="col-lg-3">
          <div class="mb-1">
            <label for="nama_dok">Nama Dokumen</label>
            <input
              type="text"
              id="nama_dok"
              class="form-control"
              :value="form.nama_dok"
              @input="
                (event) => (form.nama_dok = event.target.value.toUpperCase())
              "
              placeholder="CT-SCAN atau PA..."
            />
          </div>
        </div>
        <div class="col-lg-3">
          <div class="mb-1">
            <label for="tgl_periksa">Tanggal Periksa</label>
            <div>
              <date-picker
                v-model:value="form.tgl_periksa"
                type="date"
                placeholder="Pilih Tanggal"
                value-type="format"
                format="YYYY-MM-DD"
                class="w-100"
              ></date-picker>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="mb-1">
            <label for="penunjang">Penunjang</label>
            <v-select
              :options="['RADIOLOGI', 'LABORATORIUM', 'LAINNYA']"
              v-model="form.penunjang"
              placeholder="Pilih Penunjang"
            ></v-select>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="mb-1">
            <label for="sumber">Sumber</label>
            <v-select
              :options="['INTERNAL', 'EKSTERNAL']"
              v-model="form.sumber"
              placeholder="Pilih Sumber"
            ></v-select>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="mb-1">
            <label for="upload">Upload Gambar</label>
            <UploadFilepond ref="filepond"></UploadFilepond>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="mb-1">
            <button
              type="submit"
              class="btn btn-success me-2"
            >
              Simpan
              <i
                class="fa fa-floppy-o"
                aria-hidden="true"
              ></i>
            </button>
            <button
              type="submit"
              class="btn btn-secondary"
              @click.prevent="clear"
            >
              Reset
              <i
                class="fa fa-refresh"
                aria-hidden="true"
              ></i>
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import DatePicker from "vue-datepicker-next";
import "vue-datepicker-next/index.css";
import dokumenService from "@/services/dokumenService";
import UploadFilepond from "@/views/pasien/UploadFilepond";
import { useAuthStore } from "@/store/auth";
import { RAJAL, RANAP, PENDAFTARAN, ADMIN } from "@/utils/authUtils";

const initState = () => ({
  form: {
    nama_dok: "",
    tgl_periksa: "",
    penunjang: "",
    file: null,
    sumber: "",
  },
  method: "POST",
  userLevel: null,
  isAdmin: null,
});

export default {
  components: {
    DatePicker,
    UploadFilepond,
  },
  props: ["regist"],
  data() {
    return initState();
  },
  created() {
    this.userLevel = useAuthStore().getUser().level;
    this.isAdmin = useAuthStore().getUser().role === ADMIN;
  },
  methods: {
    async onSubmit() {
      this.$Progress.start();

      let url = `/dokumens`;

      let formData = new FormData();

      formData.append("register", this.regist);
      formData.append("nama_dok", this.form.nama_dok);
      formData.append("tgl_periksa", this.form.tgl_periksa);
      formData.append("penunjang", this.form.penunjang);
      formData.append("sumber", this.form.sumber);

      let action = null;

      if (this.method === "PUT") {
        action = dokumenService.put(url + "/" + this.form.id, this.form, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });
      } else {
        let file = this.$refs.filepond.myFiles;
        // let fileData = [];
        file.forEach((item) => {
          // fileData.push(item);
          formData.append("files[]", item);
        });
        action = dokumenService.store(url, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });
      }

      await action
        .then(() => {
          this.$emit("fetch");
          this.$Progress.finish();
          this.clear();
          this.method = "POST";
        })
        .catch((err) => {
          if (err?.response?.status === 413) {
            alert("Konten terlalu besar");
          } else if (err?.response?.status === 422) {
            let txt = "";
            Object.entries(err?.response.data.errors).forEach((item) => {
              txt += item[0] + ": " + item[1][0] + "\n";
            });
            alert(txt);
          } else {
            alert(
              err.response.data.message + ": " + err.response.data?.error?.msg
            );
          }
          this.$Progress.fail();
        });
    },
    async onShow(id) {
      try {
        let url = `/dokumens/${id}`;

        const resp = await dokumenService.find(url);
        this.form.id = resp.data.id;
        this.form.nama_dok = resp.data.nama_dok;
        this.form.register = resp.data.register;
        this.form.tgl_periksa = resp.data.tgl_periksa;
        this.form.penunjang = resp.data.penunjang;
        this.form.sumber = resp.data.sumber;
        this.method = "PUT";
      } catch (error) {
        console.log(error);
      }
    },

    clear() {
      Object.assign(this.$data, initState());
      this.$refs.filepond.removeFiles();
    },

    levelRajal() {
      return this.userLevel === RAJAL;
    },

    levelRanap() {
      return this.userLevel === RANAP;
    },

    levelPendaftaran() {
      return this.userLevel === PENDAFTARAN;
    },
  },
};
</script>
