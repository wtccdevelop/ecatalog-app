import { ref, computed } from 'vue';

const data = ref(null);
const error = ref(null);
let promise = null;

function load() {
    if (promise) return promise;
    promise = fetch('/api/home', { headers: { Accept: 'application/json' } })
        .then((r) => {
            if (!r.ok) throw new Error(`HTTP ${r.status}`);
            return r.json();
        })
        .then((json) => { data.value = json; })
        .catch((e) => { error.value = e; promise = null; });
    return promise;
}

export function useHome() {
    load();
    const pick = (key) => computed(() => data.value?.[key] ?? []);
    return { data, error, pick };
}