import Vue from 'vue';
import app from '../vueComponents/app.vue';

export default {
	init() {
		// fetch list of vue components
		const files = require.context('../vueComponents', true, /\.vue$/i);

		// loop over the components and register them
		files.keys().forEach(key => {
			Vue.component(
				key.split('/').pop().split('.')[0],
				files(key).default
			);
		});

		// initialise the app
		new Vue({
			// tell it where to find its root
			el: '#app',
			// tell it which component to render
			template: `<app/>`,
			// give it access to the component to render
			components: { app },
		});
	},
};
