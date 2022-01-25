import Vue from 'vue';


export default () => {
	new Vue({
		el: '#header',

		data: {
			menuVisible: false,

			// track which submenu is open by index.
			// -1 indicates that no submenu is active
			activeSubmenu: -1,

			// sets how much opacity the settings container has
			settingsOpacity: 1,

			// determines whether the settings container is displayed
			showSettings: true,

			yPos: 0,

			// point at which header collapses
			collapseHeaderPoint: 250,
		},

		// computed properties behave like normal data
		// but can contain some logic. They are accessed
		// in the same way to normal properties i.e. by
		// referencing their name but not calling the method
		computed: {
			// menu container toggled by button
			contentsClass() {
				return this.menuVisible ? 'header__contents' : 'header__contents--collapsed';
			},

			toggleClass() {
				return this.menuVisible ? 'header__toggle--close' : 'header__toggle';
			},

			toggleAriaLabel() {
				return `${ this.menuVisible ? 'close' : 'open' } the primary navigation menu`;
			},

			headerClass() {
				return this.yPos > this.collapseHeaderPoint ? 'header__container--collapsed' : '';
			},
		},

		methods: {
			onResize() {
				// collapse nav when resizing the browsers
				this.menuVisible = false;
			},

			toggle() {
				this.menuVisible = !this.menuVisible;
			},

			toggleSubmenu(index) {

				this.activeSubmenu =
				this.activeSubmenu === index
				// set to -1 for no submenu active if clicking on the already active menu
				? -1
				: index;
			},

			closeSubmenus(e) {
				if ( e.type === 'scroll'
					|| (e.type === 'click' && !e.target.closest('.header__subnav-toggle'))
					|| e.key === 'Escape'
					) {
					this.activeSubmenu = -1;
				}
			},

			getLiClass(index) {
				return this.activeSubmenu === index ? 'header__item--active' : 'header__item';
			},

			getSubmenuIconClass(index) {
				return this.activeSubmenu === index ? 'header__subnav__icon--active' : 'header__subnav__icon';
			},

			getSubmenuAriaLabel(index, menuName) {
				return `${ this.activeSubmenu === index ? 'close' : 'open' } menu for ${ menuName.toLowerCase() }`;
			},

			setShowSettings() {
				this.showSettings = window.innerWidth < 992 || this.settingsOpacity > 0;
			},

			setSettingsOpacity() {
				this.settingsOpacity = window.innerWidth < 992 ? 1 : (1 - this.yPos / this.collapseHeaderPoint).toFixed(2);
			},

			onScroll(e) {
				this.setSettingsOpacity();
				this.setShowSettings();
				this.closeSubmenus(e);
				this.yPos = window.scrollY;
			},
		},

		mounted() {
			window.addEventListener('resize', this.onResize);
			window.addEventListener('click', this.closeSubmenus);
			window.addEventListener('scroll', this.onScroll);
		},

		beforeDestroy() {
			// clean up resize, click and scroll listeners
			window.removeEventListener('resize', this.onResize);
			window.removeEventListener('click', this.closeSubmenus);
			window.removeEventListener('scroll', this.onScroll);
		},
	});
};