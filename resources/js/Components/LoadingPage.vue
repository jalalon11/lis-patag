<template>
  <div class="loading-page">
    <div class="loading-content">
      <LoadingSpinner 
        :show="true" 
        :size="80" 
        :border="8"
        :text="text"
      />
    </div>
  </div>
</template>

<script>
export default {
  name: 'LoadingPage',
  props: {
    text: {
      type: String,
      default: 'Loading...'
    }
  }
}
</script>

<style scoped>
.loading-page {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 400px;
  width: 100%;
}

.loading-content {
  text-align: center;
}
</style>
