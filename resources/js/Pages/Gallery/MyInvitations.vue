<template>
  <div class="container py-4">
    <Head title="My Received Invitations" />
    <h1>My Received Invitations</h1>

    <div v-if="loading" class="alert alert-info">Loading invitations...</div>
    <div v-if="error" class="alert alert-danger">{{ error }}</div>

    <div v-if="invitations.length > 0">
      <ul class="list-group">
        <li v-for="invitation in invitations" :key="invitation.id" class="list-group-item">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Gallery: {{ invitation.gallery.name }}</h5>
            <small>Received: {{ formatDate(invitation.created_at) }}</small>
          </div>
          <p class="mb-1">Invited by: {{ invitation.inviter.name }} ({{ invitation.inviter.email }})</p>
          <small>Status: <span :class="`badge badge-${statusClass(invitation.status)}`">{{ invitation.status }}</span></small>

          <div v-if="invitation.status === 'pending'" class="mt-2">
            <a :href="`/gallery-invitations/${invitation.token}/accept`" class="btn btn-success btn-sm mr-2">Accept</a>
            <a :href="`/gallery-invitations/${invitation.token}/reject`" class="btn btn-danger btn-sm">Reject</a>
          </div>
        </li>
      </ul>
    </div>
    <div v-else-if="!loading && !error">
      <p>You have no pending or processed invitations.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3'; // For setting page title with Inertia

// If not using Inertia globally, or for non-Inertia projects:
// import AppLayout from '@/Layouts/AppLayout.vue'; // Assuming a main layout
// defineOptions({ layout: AppLayout });


const invitations = ref([]);
const loading = ref(true);
const error = ref(null);

async function fetchMyInvitations() {
  loading.value = true;
  error.value = null;
  try {
    const response = await axios.get('/api/me/gallery-invitations');
    invitations.value = response.data;
  } catch (err) {
    console.error("Failed to fetch invitations:", err);
    error.value = "Failed to load your invitations. Please try again later.";
  } finally {
    loading.value = false;
  }
}

function formatDate(dateString) {
  if (!dateString) return '';
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
}

function statusClass(status) {
  if (status === 'pending') return 'warning';
  if (status === 'accepted') return 'success';
  if (status === 'rejected') return 'danger';
  return 'secondary';
}

onMounted(() => {
  fetchMyInvitations();
});
</script>

<style scoped>
/* Basic Bootstrap-like styling if not globally available */
.container { max-width: 960px; margin-left: auto; margin-right: auto; }
.py-4 { padding-top: 1.5rem; padding-bottom: 1.5rem; }
.alert { padding: .75rem 1.25rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: .25rem; }
.alert-info { color: #0c5460; background-color: #d1ecf1; border-color: #bee5eb; }
.alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
.list-group { display: flex; flex-direction: column; padding-left: 0; margin-bottom: 0; }
.list-group-item { position: relative; display: block; padding: .75rem 1.25rem; margin-bottom: -1px; background-color: #fff; border: 1px solid rgba(0,0,0,.125); }
.list-group-item:first-child { border-top-left-radius: .25rem; border-top-right-radius: .25rem; }
.list-group-item:last-child { margin-bottom: 0; border-bottom-right-radius: .25rem; border-bottom-left-radius: .25rem; }
.d-flex { display: flex !important; }
.w-100 { width: 100% !important; }
.justify-content-between { justify-content: space-between !important; }
.mb-1 { margin-bottom: .25rem !important; }
.mt-2 { margin-top: .5rem !important; }
.mr-2 { margin-right: .5rem !important; }
.btn { display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; border: 1px solid transparent; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; }
.btn-sm { padding: .25rem .5rem; font-size: .875rem; line-height: 1.5; border-radius: .2rem; }
.btn-success { color: #fff; background-color: #28a745; border-color: #28a745; }
.btn-danger { color: #fff; background-color: #dc3545; border-color: #dc3545; }
.badge { display: inline-block; padding: .25em .4em; font-size: 75%; font-weight: 700; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; }
.badge-warning { color: #212529; background-color: #ffc107; }
.badge-success { color: #fff; background-color: #28a745; }
.badge-danger { color: #fff; background-color: #dc3545; }
.badge-secondary { color: #fff; background-color: #6c757d; }
</style>
