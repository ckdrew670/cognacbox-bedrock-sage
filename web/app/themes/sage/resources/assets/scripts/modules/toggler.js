import Vue from 'vue';

const toggler = () => {
	const els = Array.prototype.slice.call(document.querySelectorAll('.toggle-button'));

	els.forEach(el => {
		new Vue({
		  el,
			data: {
				toggled: false,
			},
			computed: {
				toggledClass() {
					return this.toggled ? 'dropdown-button__text--toggled' : 'dropdown-button__text';
				},
			},
			methods: {
				handleToggle() {
					this.toggled = !this.toggled;
				},
			},
		});
	});

}

export default toggler;