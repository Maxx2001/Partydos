<template>
  <div class="container">
    <div v-if="loading">Loading gallery details...</div>
    <div v-if="error" class="error-message">{{ error }}</div>
    <div v-if="gallery">
      <h1>{{ gallery.name }}</h1>
      <p v-if="gallery.description">{{ gallery.description }}</p>
      <hr />

      <!-- Placeholder for adding new items -->
      <h3>Add New Item (Placeholder)</h3>
      <form @submit.prevent="addGalleryItem" class="mb-3">
        <div>
          <label for="newItemExternalId">External Photo ID:</label>
          <input type="text" v_model="newItem.external_photo_id" id="newItemExternalId" required>
        </div>
        <div>
          <label for="newItemType">Type (e.g., photo):</label>
          <input type="text" v_model="newItem.type" id="newItemType" required>
        </div>
        <button type="submit" :disabled="addingItem">
            {{ addingItem ? 'Adding...' : 'Add Item' }}
        </button>
        <div v-if="addItemError" class="error-message">{{ addItemError }}</div>
      </form>

      <h3>Items in this Gallery:</h3>
      <gallery-view
        v-if="gallery"
        :gallery="gallery"
        :authenticatedUser="pageAuthenticatedUser"
        @invitation-sent="handleInvitationSent"
      ></gallery-view>
      <p v-else-if="!loading && !gallery">Gallery data is not available or you might not have access.</p>

    </div>
    <p v-if="!loading"><a href="/gallery">Back to Galleries List (Placeholder Link)</a></p>
    <!-- <inertia-link href="/gallery">Back to Galleries List</inertia-link> -->
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'; // computed might be used for pageAuthenticatedUser
import axios from 'axios';
import GalleryView from '@/Components/Gallery/GalleryView.vue';
import { usePage } from '@inertiajs/vue3'; // For Inertia.js to get authenticated user

// Using usePage() to access shared Inertia props like authenticated user
const page = usePage();
const pageAuthenticatedUser = computed(() => page.props.auth ? page.props.auth.user : null);

// Props passed from the controller, e.g., when rendering via Inertia::render
const props = defineProps({
  galleryId: [String, Number],      // Passed from web route via controller
  initialGallery: Object,       // Gallery object pre-loaded by controller
  // authenticatedUser: Object // Could be passed directly too
});

const gallery = ref(props.initialGallery || null);
const loading = ref(!props.initialGallery); // Don't set loading if gallery is already provided
const error = ref(null);
// galleryIdFromBlade is for non-Inertia setups, less relevant if using Inertia props fully
const galleryIdFromBlade = ref(null);

// For adding a new item
const newItem = ref({
    external_photo_id: '',
    type: 'photo', // Default type
});
const addingItem = ref(false);
const addItemError = ref(null);


async function fetchGalleryDetails(id) {
  loading.value = true;
  error.value = null;
  try {
    const response = await axios.get(`/api/galleries/${id}`);
    gallery.value = response.data;
  } catch (err) {
    console.error(`Error fetching gallery ${id}:`, err);
    if (err.response) {
        if (err.response.status === 403) {
            error.value = "Access Denied: You do not have permission to view this gallery.";
        } else if (err.response.status === 404) {
            error.value = "Gallery not found. It may have been deleted or the link is incorrect.";
        } else {
            error.value = `Failed to load gallery details. Server responded with status ${err.response.status}.`;
        }
    } else {
        error.value = 'Failed to load gallery details. Please check your network connection.';
    }
  } finally {
    loading.value = false;
  }
}

function handleInvitationSent() {
  // Re-fetch gallery data to show the updated list of invitations
  const idToFetch = props.galleryId || (gallery.value ? gallery.value.id : galleryIdFromBlade.value);
  if (idToFetch) {
    fetchGalleryDetails(idToFetch);
  }
}

async function addGalleryItem() {
    if (!gallery.value) return;
    addingItem.value = true;
    addItemError.value = null;
    try {
        const response = await axios.post(`/api/galleries/${gallery.value.id}/items`, newItem.value);
        if (!gallery.value.items) {
            gallery.value.items = [];
        }
        gallery.value.items.push(response.data);
        newItem.value.external_photo_id = ''; // Reset form
        newItem.value.type = 'photo';
    } catch (err) {
        console.error("Error adding gallery item:", err);
        if (err.response && err.response.data && err.response.data.message) {
          addItemError.value = err.response.data.message;
        } else {
          addItemError.value = 'Failed to add item to gallery.';
        }
    } finally {
        addingItem.value = false;
    }
}


onMounted(() => {
  let idToFetch = props.galleryId;

  if (!idToFetch) { // Fallback for non-Inertia or if ID not passed as prop
      const galleryAppShowDiv = document.getElementById('gallery-app-show');
      if (galleryAppShowDiv) {
        idToFetch = galleryAppShowDiv.getAttribute('data-gallery-id');
        galleryIdFromBlade.value = idToFetch; // Store for reference if needed
        // User data could also be parsed here for non-Inertia setups
        // const userDataAttr = galleryAppShowDiv.getAttribute('data-user');
        // if (userDataAttr) { pageAuthenticatedUser.value = JSON.parse(userDataAttr); } // Not ideal, better via props
      }
  }

  if (idToFetch && !props.initialGallery) { // Fetch only if ID is available and gallery not pre-loaded
    fetchGalleryDetails(idToFetch);
  } else if (!idToFetch && !props.initialGallery) {
    error.value = "No gallery ID provided.";
    loading.value = false;
  } else if (props.initialGallery) {
    // Gallery data is already loaded via props
    loading.value = false;
  }
});

// Removed the internal placeholder GalleryView component, as it's now imported.

</script>

<style scoped>
.error-message {
  color: red;
}
.mb-3 {
  margin-bottom: 1rem;
}
</style>
