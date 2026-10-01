import { reactive } from 'vue';
import { api } from '../lib/api.js';

export const catalog = reactive({
    site: null,
    home: null,
    homeError: false,

    eventTheme: {
        active: false,
        event: null,
    },
});

let sitePromise = null;
let homePromise = null;
let eventThemePromise = null;

export function loadSite() {
    if (catalog.site) return Promise.resolve(catalog.site);
    if (sitePromise) return sitePromise;

    sitePromise = api('/site')
        .then((d) => (catalog.site = d))
        .finally(() => {
            sitePromise = null;
        });

    return sitePromise;
}

export function loadHome(force = false) {
    if (catalog.home && !force) return Promise.resolve(catalog.home);
    if (homePromise) return homePromise;

    catalog.homeError = false;

    homePromise = api('/home')
        .then((d) => (catalog.home = d))
        .catch((e) => {
            catalog.homeError = true;
            throw e;
        })
        .finally(() => {
            homePromise = null;
        });

    return homePromise;
}

export function loadEventTheme(force = false) {
    if (catalog.eventTheme.event && !force) {
        return Promise.resolve(catalog.eventTheme);
    }

    if (eventThemePromise) return eventThemePromise;

    eventThemePromise = api('/event-theme/active')
        .then((d) => {
            catalog.eventTheme = d;
            return d;
        })
        .finally(() => {
            eventThemePromise = null;
        });

    return eventThemePromise;
}