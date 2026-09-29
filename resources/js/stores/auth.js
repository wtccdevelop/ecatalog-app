import { reactive } from 'vue';
import { api } from '../lib/api.js';

export const auth = reactive({ user: null, checked: false });

let pending = null;

export function fetchUser(force = false) {
    if (auth.checked && !force) return Promise.resolve(auth.user);
    if (pending) return pending;

    pending = api('/auth/me')
        .then((d) => { auth.user = d.user; return auth.user; })
        .catch(() => { auth.user = null; return null; })
        .finally(() => { auth.checked = true; pending = null; });

    return pending;
}

export async function login(payload) {
    const d = await api('/auth/login', { method: 'POST', body: payload });
    auth.user = d.user;
    auth.checked = true;
    return d.user;
}

export async function logout() {
    try {
        await api('/auth/logout', { method: 'POST' });
    } finally {
        auth.user = null;
        auth.checked = true;
    }
}