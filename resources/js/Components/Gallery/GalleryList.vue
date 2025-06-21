<template>
  <ul class="gallery-list">
    <gallery-list-item
      v-for="gallery in galleries"
      :key="gallery.id"
      :gallery="gallery"
    ></gallery-list-item>
  </ul>
</template>

<script setup>
// import GalleryListItem from './GalleryListItem.vue'; // Assumed to be globally registered or imported locally

defineProps({
  galleries: Array,
});

// Placeholder for GalleryListItem if not a separate file or auto-imported
const GalleryListItem = {
  props: ['gallery'],
  template: `
    <li class="gallery-list-item">
      <strong>{{ gallery.name }}</strong>
      <p v-if="gallery.description">{{ gallery.description }}</p>
      <small>{{ gallery.items_count !== undefined ? gallery.items_count : (gallery.items ? gallery.items.length : 0) }} item(s)</small>
      <br>
      <!-- If using Inertia: <inertia-link :href="galleryShowUrl(gallery.id)">View Gallery</inertia-link> -->
      <a :href="'/gallery/' + gallery.id">View Gallery (Placeholder Link)</a>
    </li>
  `
  // methods: {
  //   galleryShowUrl(id) {
  //     return `/gallery/${id}`; // Adjust if route names/paths are different
  //   }
  // }
};
</script>

<style scoped>
.gallery-list {
  list-style: none;
  padding: 0;
}
.gallery-list-item {
  border: 1px solid #eee;
  padding: 10px;
  margin-bottom: 10px;
}
</style>
