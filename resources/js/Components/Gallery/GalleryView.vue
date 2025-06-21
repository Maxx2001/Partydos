<template>
  <div class="gallery-view-container">
    <h2 v-if="gallery && gallery.name" class="gallery-title">{{ gallery.name }}</h2>
    <p v-if="gallery && gallery.description" class="gallery-description">{{ gallery.description }}</p>
    <p v-if="gallery && gallery.user" class="gallery-owner">Owner: {{ gallery.user.name }}</p>

    <!-- Invite Form -->
    <div v-if="isOwner" class="invite-section card mb-4">
      <div class="card-body">
        <h3 class="card-title">Invite a Friend</h3>
        <form @submit.prevent="sendInvitation" class="form-inline">
          <div class="form-group mr-2">
            <input type="email" class="form-control" v-model="inviteEmail" placeholder="Friend's email" required />
          </div>
          <button type="submit" class="btn btn-primary" :disabled="inviting">
            {{ inviting ? 'Sending...' : 'Send Invitation' }}
          </button>
        </form>
        <p v-if="inviteMessage" :class="{ 'text-success': inviteSuccess, 'text-danger': !inviteSuccess }" class="mt-2">
          {{ inviteMessage }}
        </p>
      </div>
    </div>

    <!-- Display Sent Invitations List -->
    <div v-if="isOwner && gallery.invitations && gallery.invitations.length" class="invitations-list card mb-4">
      <div class="card-body">
        <h4 class="card-title">Sent Invitations</h4>
        <ul class="list-group list-group-flush">
          <li v-for="invitation in gallery.invitations" :key="invitation.id" class="list-group-item d-flex justify-content-between align-items-center">
            <span>
              {{ invitation.invited_email }}
              <span v-if="invitation.invitedUser"> ({{ invitation.invitedUser.name }})</span>
            </span>
            <span :class="`badge badge-${statusClass(invitation.status)}`">{{ invitation.status }}</span>
          </li>
        </ul>
      </div>
    </div>

    <hr v-if="isOwner">

    <div v-if="gallery && gallery.items && gallery.items.length" class="masonry-gallery">
      <GalleryItem
        v-for="item in gallery.items"
        :key="item.id || item.external_photo_id"
        :item="enhancedItem(item)"
        @image-loaded="handleImageLoaded"
      />
    </div>
    <p v-else class="no-items-message">This gallery has no items yet.</p>
  </div>
</template>

<script>
import GalleryItem from './GalleryItem.vue';
import axios from 'axios'; // Assuming axios is globally available or imported
// For isOwner computed property, need access to authenticated user.
// In Inertia, this is typically from this.$page.props.auth.user
// Since this is a component, it might need to be passed as a prop or use a global store/event bus.
// For simplicity, I'm assuming `currentUser` prop is passed down or available globally.

export default {
  components: {
    GalleryItem
  },
  props: {
    gallery: {
      type: Object,
      required: true,
    },
    // currentUser: { // Recommended to pass as prop if not using Inertia's $page
    //   type: Object,
    //   default: null
    // }
  },
  emits: ['invitation-sent'], // Added for Vue 3 best practices
  data() {
    return {
      inviteEmail: '',
      inviting: false,
      inviteMessage: '',
      inviteSuccess: false,
    };
  },
  computed: {
    // This is a simplified check. In Inertia, use this.$page.props.auth.user
    // For non-Inertia, this assumes `gallery.user` is the full owner object.
    // And `currentUser` is available. If it's a component, `currentUser` should be a prop.
    // For this example, let's assume a global `this.$page.props.auth.user` or similar.
    // This will likely cause an error if $page is not available.
    // A better way for components is to receive currentUser as a prop from the Page component (Show.vue)
    currentUser() {
      // Placeholder for actual current user object
      // In a real Inertia app, this would be:
      // return this.$page.props.auth ? this.$page.props.auth.user : null;
      // For this example, we'll assume Show.vue passes a currentUser prop if needed
      // or this component is used in a context where $page is available.
      // If GalleryView.vue is used directly by a Blade view, then user data would need to be on window or passed.
      // For now, let's assume gallery.user contains the owner details.
      // And isOwner will rely on a prop `authenticatedUser` passed to GalleryView.
      // This part needs careful handling depending on how auth user is exposed to Vue components.
      // Let's assume Show.vue will pass `authenticatedUser` prop.
      return this.$attrs.authenticatedUser || (this.$page && this.$page.props.auth ? this.$page.props.auth.user : null);

    },
    isOwner() {
      // Check if the gallery data and its user object exist, and if currentUser is available
      if (!this.gallery || !this.gallery.user || !this.currentUser) {
        return false;
      }
      return this.gallery.user_id === this.currentUser.id;
    }
  },
  methods: {
    enhancedItem(item) {
      const heights = ['150px', '200px', '250px', '300px'];
      let displayUrl = null;
      if (item.metadata && item.metadata.baseUrl) {
        displayUrl = item.metadata.baseUrl;
      } else if (item.thumbnailUrl) {
        displayUrl = item.thumbnailUrl;
      }
      return {
        ...item,
        display_url: displayUrl,
        alt_text: item.filename || `Image ${item.id || item.external_photo_id}`,
        placeholder_height: displayUrl ? null : heights[Math.floor(Math.random() * heights.length)],
        title: item.filename || `Item ${item.id || item.external_photo_id}`
      };
    },
    handleImageLoaded() {
      // console.log('Image loaded');
    },
    async sendInvitation() {
      if (!this.inviteEmail || !this.gallery || !this.gallery.id) return;
      this.inviting = true;
      this.inviteMessage = '';
      this.inviteSuccess = false;
      try {
        const response = await axios.post(`/api/galleries/${this.gallery.id}/invitations`, {
          invited_email: this.inviteEmail
        });
        this.inviteMessage = response.data.message || 'Invitation sent successfully!';
        this.inviteSuccess = true;
        this.inviteEmail = '';
        this.$emit('invitation-sent'); // Notify parent to refresh data
      } catch (error) {
        this.inviteMessage = error.response?.data?.message || 'Failed to send invitation.';
        if (error.response?.data?.errors?.invited_email) {
          this.inviteMessage = error.response.data.errors.invited_email[0];
        }
        this.inviteSuccess = false;
      } finally {
        this.inviting = false;
      }
    },
    statusClass(status) {
      if (status === 'pending') return 'warning';
      if (status === 'accepted') return 'success';
      if (status === 'rejected') return 'danger';
      return 'secondary';
    }
  }
};
</script>

<style scoped>
.gallery-view-container {
  padding: 1rem;
}
.gallery-title {
  text-align: center;
  margin-bottom: 0.5rem;
}
.gallery-description {
  text-align: center;
  font-size: 0.9rem;
  color: #666;
  margin-bottom: 0.5rem;
}
.gallery-owner {
  text-align: center;
  font-size: 0.8rem;
  color: #888;
  margin-bottom: 1.5rem;
}

.invite-section {
  margin-bottom: 2rem;
  padding: 1.5rem;
  background-color: #f9f9f9;
  border-radius: 8px;
}
.invite-section .form-inline .form-group {
    flex-grow: 1;
    margin-right: 0.5rem; /* Bootstrap 4 like mr-2 */
}
.invite-section .form-inline input[type="email"] {
    width: 100%;
}


.invitations-list {
   margin-bottom: 2rem;
}
.invitations-list .list-group-item {
    padding: 0.75rem 1.25rem;
}
.badge { /* Basic badge styling if not using Bootstrap classes directly */
    display: inline-block;
    padding: .25em .4em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: .25rem;
}
.badge-warning { color: #212529; background-color: #ffc107; }
.badge-success { color: #fff; background-color: #28a745; }
.badge-danger { color: #fff; background-color: #dc3545; }
.badge-secondary { color: #fff; background-color: #6c757d; }


.masonry-gallery {
  column-gap: 1rem;
  column-count: 1;
}
.no-items-message {
  text-align: center;
  font-style: italic;
  color: #666;
}
.text-success { color: green; }
.text-danger { color: red; }
.mt-2 { margin-top: 0.5rem; }
.mb-4 { margin-bottom: 1.5rem; }
.mr-2 { margin-right: 0.5rem; } /* For form-inline spacing */

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: .25rem;
}
.card-body {
    flex: 1 1 auto;
    padding: 1.25rem;
}
.card-title {
    margin-bottom: .75rem;
}


/* Responsive column counts */
@media (min-width: 600px) { .masonry-gallery { column-count: 2; } }
@media (min-width: 768px) { .masonry-gallery { column-count: 3; } }
@media (min-width: 992px) { .masonry-gallery { column-count: 4; } }
@media (min-width: 1200px) { .masonry-gallery { column-count: 5; } }
</style>
