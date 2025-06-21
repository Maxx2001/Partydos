<template>
  <div class="container">
    <h1>My Galleries</h1>
    <div v-if="loading" class="loading">Loading galleries...</div>
    <div v-if="error" class="error-message">{{ error }}</div>

    <!-- Form to create a new gallery -->
    <form @submit.prevent="createGallery" class="mb-3">
      <div>
        <label for="newGalleryName">Gallery Name:</label>
        <input type="text" v_model="newGallery.name" id="newGalleryName" required>
      </div>
      <div>
        <label for="newGalleryDescription">Description:</label>
        <textarea v_model="newGallery.description" id="newGalleryDescription"></textarea>
      </div>
      <button type="submit" :disabled="creatingGallery">
        {{ creatingGallery ? 'Creating...' : 'Create Gallery' }}
      </button>
      <div v-if="createError" class="error-message">{{ createError }}</div>
    </form>

    <gallery-list v-if="galleries.length" :galleries="galleries"></gallery-list>
    <p v-else-if="!loading && !error">No galleries found. Why not create one?</p>

    <!-- For non-Inertia, example of how data from Blade might be accessed -->
    <!-- <p>DEV: Initial galleries count: {{ initialGalleriesData ? initialGalleriesData.length : 'N/A' }}</p> -->
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios'; // Assuming axios is installed and configured
import GalleryList from '@/Components/Gallery/GalleryList.vue'; // Import the actual component

// Define props if using Inertia and passing data directly
// const props = defineProps({
//   initialGalleries: Array,
//   user: Object,
// });

const galleries = ref([]);
const loading = ref(true);
const error = ref(null);

// For creating a new gallery
const newGallery = ref({
  name: '',
  description: '',
});
const creatingGallery = ref(false);
const createError = ref(null);

// For non-Inertia, attempt to get data from Blade attributes
// const initialGalleriesData = ref(null);
// const userData = ref(null);

async function fetchGalleries() {
  loading.value = true;
  error.value = null;
  try {
    const response = await axios.get('/api/galleries');
    galleries.value = response.data;
  } catch (err) {
    console.error("Error fetching galleries:", err);
    error.value = 'Failed to load galleries. Please try again later.';
  } finally {
    loading.value = false;
  }
}

async function createGallery() {
  creatingGallery.value = true;
  createError.value = null;
  try {
    const response = await axios.post('/api/galleries', newGallery.value);
    galleries.value.unshift(response.data); // Add to the top of the list
    newGallery.value.name = ''; // Reset form
    newGallery.value.description = '';
  } catch (err) {
    console.error("Error creating gallery:", err);
    if (err.response && err.response.data && err.response.data.message) {
      createError.value = err.response.data.message;
    } else {
      createError.value = 'Failed to create gallery.';
    }
  } finally {
    creatingGallery.value = false;
  }
}

onMounted(() => {
  // For non-Inertia, parse data from Blade if needed:
  // const galleryAppIndexDiv = document.getElementById('gallery-app-index');
  // if (galleryAppIndexDiv) {
  //   const initialDataAttr = galleryAppIndexDiv.getAttribute('data-initial-galleries');
  //   if (initialDataAttr) {
  //     initialGalleriesData.value = JSON.parse(initialDataAttr);
  //     if (Array.isArray(initialGalleriesData.value)) {
  //        galleries.value = initialGalleriesData.value;
  //     }
  //   }
  //   const userDataAttr = galleryAppIndexDiv.getAttribute('data-user');
  //   if (userDataAttr) {
  //      userData.value = JSON.parse(userDataAttr);
  //   }
  // }

  // If using Inertia props, galleries might be pre-populated:
  // if (props.initialGalleries) {
  //   galleries.value = props.initialGalleries;
  //   loading.value = false;
  // } else {
     fetchGalleries(); // Fetch if not provided via props
  // }
});

// Removed the internal placeholder GalleryList component

</script>

<style scoped>
.error-message {
  color: red;
}
.loading {
  font-style: italic;
}
.mb-3 {
  margin-bottom: 1rem;
}
</style>
