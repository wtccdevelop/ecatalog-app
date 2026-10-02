import { computed } from 'vue';
import { catalog } from '../stores/catalog.js';

export const useEventTheme = () => computed(() => catalog.site?.event_theme ?? null);