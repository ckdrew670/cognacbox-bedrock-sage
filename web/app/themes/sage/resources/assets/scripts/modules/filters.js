import Vue from 'vue';
import EventBus from '../util/eventBus';

export default () => {
	const filters = document.getElementById('filters');
	if(filters) {
		new Vue({
			el: '#filters',

			data: {
				activeFilter: -1,
				terms: [],
				search: '',
				params: {},
			},

			methods: {
				toggleFilter(index) {
					this.activeFilter = this.activeFilter === index
						? -1
						: index;
				},

				checkBoxesClass(index) {
					return this.activeFilter === index ? 'filter__checkboxes--expanded' : 'filter__checkboxes';
				},

				checkboxesHeight(index, numOfItems) {
					return this.activeFilter !== index
						? '0'
						: `${numOfItems*40}px`
				},

				filterIsVisible(filterIndex) {
					return this.activeFilter === filterIndex
				},

				isChecked(termId) {
					return this.terms.includes(+termId)
				},

				updateParams() {
					const qp = Object.keys(this.params)
						.filter(key => this.params[key].length)
						.map(key => `${key}=${this.params[key]}`).join('&');

					const search = this.search ? `&${filters.getAttribute('data-search')}=${this.search}` : ''
					const params = `?${qp}${search}`
					history.replaceState(null, null, params);
					EventBus.publish('filters:changed');
				},

				updateTermsParam(e) {
					const isChecked = e.target.checked
					const taxomony = e.target.getAttribute('data-taxonomy')
					const termId = e.target.getAttribute('data-term')

					let param = this.params[taxomony] || []

					const terms = isChecked
						? [...param, +termId]
						: param.filter(t => t !== +termId)

					this.params[taxomony] = terms
					this.terms = [...this.terms, ...terms]

					this.updateParams()
				},

				getTaxonomies() {
					const qp = new URLSearchParams(window.location.search)
					const keys = JSON.parse(this.$el.getAttribute('data-taxonomies')) || {}
					keys.map(key => this.params[key] = qp.get(key) ? qp.get(key).split(',').map(t => +t) : [])
					this.terms = Object.values(this.params).reduce((acc, cur) => acc.concat(cur), [])
				},

				getSearch() {
					const qp = new URLSearchParams(window.location.search)
					const search = qp.get(filters.getAttribute('data-search'))
					this.search = search || ''
				},
			},

			beforeMount() {
				this.getTaxonomies()
				this.getSearch()
			},
		});
	}
}