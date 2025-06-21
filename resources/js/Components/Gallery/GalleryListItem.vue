<template>
  <li class="gallery-list-item">
    <h3>{{ gallery.name }}</h3>
    <p v-if="gallery.description">{{ gallery.description }}</p>
    <p>
      <small>
        Items: {{ gallery.items_count !== undefined ? gallery.items_count : (gallery.items ? gallery.items.length : 0) }}
      </small>
    </p>
    <!-- Using a simple anchor tag for navigation. Replace with <inertia-link> if using Inertia.js -->
    <a :href="galleryShowUrl" class="view-gallery-link">View Gallery</a>
  </li>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  gallery: {
    type: Object,
    required: true,
  },
});

const galleryShowUrl = computed(() => {
  // Assuming the web route is something like '/gallery/{id}'
  // This would need to match the route defined in routes/web/gallery.php for the showGalleryPage method
  return `/gallery/${props.gallery.id}`;
});
</script>

<style scoped>
.gallery-list-item {
  border: 1px solid #ccc;
  padding: 1rem;
  margin-bottom: 1rem;
  border-radius: 4px;
}
.gallery-list-item h3 {
  margin-top: 0;
}
.view-gallery-link {
  text-decoration: none;
  color: #3490dc;
}
.view-gallery-link:hover {
  text-decoration: underline;
}
</style>
