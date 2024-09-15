// Composables/useTitle.js
import { watch, ref } from 'vue';

export function useTitle(title) {
    const titleRef = ref(title); // Make sure it's reactive

    // Watch for changes to the title
    watch(titleRef, (newTitle) => {
        if (newTitle) {
            document.title = newTitle; // Set the document title
        }
    }, { immediate: true }); // Update immediately on first run

    return titleRef; // Allow the user to update the title dynamically
}
