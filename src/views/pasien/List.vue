<style scoped>
.title {
  font-size: 15px;
  font-weight: 600;
}
</style>
<template>
  <div>
    <div class="mb-1">
      <h5 class="py-2">Rawat Jalan &#60; dari 1 Tahun</h5>
    </div>
    <vue-good-table
      mode="remote"
      :totalRows="totalRecords"
      :pagination-options="paginations"
      :isLoading="isLoading"
      :columns="columns"
      :rows="rows"
      :line-numbers="true"
      styleClass="vgt-table striped"
      v-on:page-change="onPageChange"
      v-on:per-page-change="onPerPageChange"
      v-on:column-filter="onColumnFilter"
      v-on:sort-change="onSortChange"
    >
      <template #table-row="props">
        <span v-if="props.column.field == 'action'">
          <router-link
            :to="{
                    name: 'ViewByReg',
                    params: {
                      register: props.row.daftar_REGISTER,
                    },
                  }"
            class="text-info me-1"
          >
            <i class="fa fa-pencil"></i>
          </router-link>
          <router-link
            :to="{
                    name: 'ViewByMr',
                    params: {
                      mr: props.row.daftar_MR,
                    },
                  }"
            class="text-secondary me-1"
          >
            <i class="fa fa-user"></i>
          </router-link>
          <a
            href="javascript(0)"
            class="text-danger"
          >
            <i class="fa fa-trash-o"></i>
          </a>
        </span>
      </template>
    </vue-good-table>
  </div>
</template>

<script>
import "vue-good-table-next/dist/vue-good-table-next.css";
import { VueGoodTable } from "vue-good-table-next";
import pasienService from "@/services/pasienService";
import queryString from "query-string";

export default {
  name: "pasien-table",
  components: {
    VueGoodTable,
  },
  data() {
    return {
      isLoading: false,
      serverParams: {
        columnFilters: {},
        sort: {
          field: "",
          type: "",
        },
        page: 1,
        perPage: 10,
        search: "",
        filterBy: {
          column_key: "",
          column_val: "",
        },
      },
      paginations: {
        enabled: true,
        mode: "records",
      },
      columns: [
        {
          label: "Tanggal",
          field: "daftar_TANGGAL",
          filterOptions: {
            enabled: false,
          },
        },
        {
          label: "Register",
          field: "daftar_REGISTER",
          filterOptions: {
            enabled: true,
          },
        },
        {
          label: "MR",
          field: "daftar_MR",
          filterOptions: {
            enabled: true,
          },
        },
        {
          label: "Nama",
          field: "m_pasien_NAMA",
          filterOptions: {
            enabled: false,
          },
        },
        {
          label: "Tgl Lahir",
          field: "m_pasien_TGL_LAHIR",
          filterOptions: {
            enabled: false,
          },
        },
        {
          label: "Sex",
          field: "m_pasien_SEX",
          filterOptions: {
            enabled: false,
          },
        },
        {
          label: "Diagnosa",
          field: "daftar_DIAGNOSA_MRS",
          filterOptions: {
            enabled: false,
          },
        },
        {
          label: "Ruangan",
          field: "daftar_RUANGAN",
          filterOptions: {
            enabled: false,
          },
        },
        {
          label: "Aksi",
          field: "action",
        },
      ],
      rows: [],
      totalRecords: 0,
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      this.loading(true);
      try {
        // console.log(queryString);
        let query = queryString.stringify(
          Object.assign(this.serverParams.filterBy, this.serverParams.sort),
          { arrayFormat: "index" }
        );

        // let query = "";
        let url = `/pasiens?${query}&page=${this.serverParams.page}&per_page=${this.serverParams.perPage}`;

        const resp = await pasienService.get(url);

        let from_page = resp.data.from;
        let rows = resp.data.data.map((item) => {
          item.no = from_page;
          from_page++;
          return item;
        });

        this.totalRecords = resp.data.total;
        this.rows = rows;
      } catch (error) {
        alert(error);
        console.log(error);
      } finally {
        this.loading(false);
      }
    },

    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },

    onPageChange(params) {
      this.loading();
      this.updateParams({ page: params.currentPage });
      this.fetchData();
    },

    onPerPageChange(params) {
      this.loading();
      this.updateParams({
        page: 1, // mulai dari awal ketika rows per page diubah
        perPage: params.currentPerPage,
      });
      this.fetchData();
    },
    removeNullFilter(obj, type) {
      for (var propName in obj) {
        if (obj[propName] === "") {
          delete obj[propName];
        }
      }
      if (type == "key") {
        return Object.keys(obj);
      }
      return Object.values(obj);
    },
    onColumnFilter(params) {
      let vKey = this.removeNullFilter(params.columnFilters, "key");
      let vVal = this.removeNullFilter(params.columnFilters, "val");
      let customParams = {
        filterBy: {
          column_key: vKey,
          column_val: vVal,
        },
      };
      this.updateParams(customParams);
      this.fetchData();
    },

    onSortChange(params) {
      let sort = {
        sort: {
          field: params[0].field,
          type: params[0].type,
        },
      };
      this.updateParams(sort);
      this.fetchData();
    },
    loading(status) {
      this.isLoading = status;
    },
  },
};
</script>
