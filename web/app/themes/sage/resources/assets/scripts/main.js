import 'custom-event-polyfill';
import 'core-js/stable';

// import local dependencies
import Router from './util/Router';
import * as routes from './routes';

// Populate Router instance with DOM routes
const router = new Router({
	common: routes.common,
	home: routes.home,
	aboutUs: routes.about,
	vue: routes.vue,
});

// Load Events
jQuery(document).ready(() => router.loadEvents());
