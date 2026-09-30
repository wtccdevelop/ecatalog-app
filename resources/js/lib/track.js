import { api } from './api.js';

export function track(event, extra = {}) {
    api('/track', {
        method: 'POST',
        body: { event, path: location.pathname, referrer: document.referrer || null, ...extra },
    }).catch(() => {});
}