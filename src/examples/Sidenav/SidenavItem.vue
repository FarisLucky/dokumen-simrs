<template>
  <router-link
    :to="url"
    class="nav-link"
    v-bind="$attrs"
  >
    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
      <slot name="icon"></slot>
    </div>
    <span
      class="nav-link-text"
      :class="setting.isRTL ? ' me-1' : 'ms-1'"
    >{{ navText }}</span>
  </router-link>
</template>
<script>
import { mapState } from "pinia";
import { useAppStore } from "../../store/index_pinia";
export default {
  name: "sidenav-item",
  props: {
    url: {
      type: String,
      required: true,
    },
    navText: {
      type: String,
      required: true,
    },
  },
  computed: {
    ...mapState(useAppStore, ["setting"]),
  },
};
</script>
