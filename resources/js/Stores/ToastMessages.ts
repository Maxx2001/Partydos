// @/store/modal.ts
import {defineStore} from "pinia";

export type Toast = {
    id: string;
    isOpen: boolean;
    message: string;
    type: string;
    closeable: boolean;
    close: () => void;  // function that takes no arguments and returns nothing
};

export const toastsStore = defineStore("toasts", {
    state: () => ({
        toasts: {},  // Changed to an array
        count: 0,
    }),

    actions: {
        open(message: string, type = 'success', closeable = true) {
            const id = Math.random().toString(36);
            this.count++;

            this.toasts[id] = {
                id,
                message,
                isOpen: true,
                type,
                closeable,
                close: () => {
                    this.close(id); // This function will call the `close` action of the store
                },
            } as Toast;

            setTimeout(() => {
                if (this.toasts[id]) {
                    this.close(id);
                }
            }, 5000);


        },

        close(id: number = null) {
            this.toasts[id].isOpen = false;
            this.count--;

            if (this.count === 0) {
                setTimeout(() => {
                    this.toasts = {};
                }, 500);
            }
        },

        removeAll() {
            this.toasts = {};
        },
    },
});

export default toastsStore;
