import { reactive } from 'vue';

export const toasts = reactive([]);
let seq = 0;

function push(type, text) {
    const t = { id: ++seq, type, text };
    toasts.push(t);
    setTimeout(() => {
        const i = toasts.findIndex((x) => x.id === t.id);
        if (i > -1) toasts.splice(i, 1);
    }, 3500);
}

export const toast = {
    success: (text) => push('success', text),
    error: (text) => push('error', text),
};