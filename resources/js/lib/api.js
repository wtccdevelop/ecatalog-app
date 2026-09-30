function xsrfToken() {
    const m = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);
    return m ? decodeURIComponent(m[1]) : '';
}

export async function api(path, { method = 'GET', body, signal } = {}) {
    const headers = { Accept: 'application/json' };
    const isForm = body instanceof FormData;

    if (body !== undefined && !isForm) headers['Content-Type'] = 'application/json';
    if (method !== 'GET') headers['X-XSRF-TOKEN'] = xsrfToken();

    const res = await fetch(`/api${path}`, {
        method,
        headers,
        body: body === undefined ? undefined : isForm ? body : JSON.stringify(body),
        credentials: 'same-origin',
        signal,
    });

    if (!res.ok) {
        const err = new Error(`HTTP ${res.status}`);
        err.status = res.status;
        err.data = await res.json().catch(() => null);
        throw err;
    }

    return res.json();
}