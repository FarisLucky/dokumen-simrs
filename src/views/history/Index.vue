<template>
  <div class="py-4 container-fluid">
    <router-view
      v-slot="{Component}"
      :key="$route.fullPath"
    >
      <transition
        name="fade-x"
        mode="out-in"
      >
        <component :is="Component" />
      </transition>
    </router-view>
  </div>
</template>

<script>
export default {};
</script>
