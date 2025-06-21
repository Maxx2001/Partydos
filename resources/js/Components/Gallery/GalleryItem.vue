<template>
  <div class="gallery-item">
    <img v-if="item.display_url" :src="item.display_url" :alt="item.alt_text || 'Gallery image'" @load="imageLoaded"/>
    <div v-else class="placeholder-image" :style="{ height: item.placeholder_height || '200px' }">
      {{ item.title || 'Image' }}
    </div>
    <p v-if="item.title" class="item-title">{{ item.title }}</p>
  </div>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true,
    }
  },
  emits: ['image-loaded'], // Added for Vue 3 best practices
  methods: {
    imageLoaded() {
      // This event can be used by a JS masonry library to re-layout if needed.
      // For CSS-only, it's less critical for layout but good for knowing image is there.
      this.$emit('image-loaded');
    }
  }
};
</script>

<style scoped>
.gallery-item {
  break-inside: avoid; /* Crucial for column-based masonry */
  margin-bottom: 1rem; /* Gap between items vertically */
  border: 1px solid #eee;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  overflow: hidden; /* To contain image border-radius */
}
.gallery-item img {
  width: 100%;
  height: auto;
  display: block;
  border-radius: 8px 8px 0 0; /* If title is below */
}
.placeholder-image {
  width: 100%;
  background-color: #f0f0f0;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #aaa;
  font-size: 0.9rem;
  border-radius: 8px 8px 0 0;
}
.item-title {
  padding: 0.5rem;
  font-size: 0.9rem;
  text-align: center;
  background-color: #fff;
  border-top: 1px solid #eee; /* Separator if image is above */
}
</style>
