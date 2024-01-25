<template>
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a
        class="nav-link active"
        aria-current="page"
        href="#"
        id="nav-home-tab"
        data-toggle="tab"
        data-target="#nav-home"
      >Rawat Jalan</a>
    </li>
  </ul>
  <div
    class="tab-content"
    id="nav-tabContent"
  >
    <div
      class="tab-pane fade show active"
      id="nav-home"
    >
      <div class="px-1 py-2">
        <List />
      </div>
    </div>
  </div>
</template>
<script>
import List from "@/views/pasien/List.vue";
export default {
  components: {
    List,
  },
};
</script>