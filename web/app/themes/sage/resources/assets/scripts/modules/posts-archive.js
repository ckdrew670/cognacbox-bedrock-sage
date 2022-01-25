import Vue from 'vue';
import postcardhorizontal from '../vueComponents/postcardhorizontal.vue';
import axios from 'axios';
import EventBus from '../util/eventBus';

export default () => {
	const postsArchive = document.getElementById('posts-archive');
	if(postsArchive) {
		new Vue({
			el: '#posts-archive',

			data: {
				postType: 'posts',
				posts: [],
				loading: true,
				page: 1,
				maxPages: 1,
			},

			components: {
				postcardhorizontal,
			},

			methods: {
				getPostType() {
					this.postType = this.$el.getAttribute('data-post-type') || 'any'
				},

				getPosts() {
					!this.posts.length && (this.loading = true);

					const qp = new URLSearchParams(window.location.search)
					const search = qp.get('search') ? qp.get('search') : qp.get('s');

					let params = qp.toString().replace(/-/g, '_')
					if(search) {
						params = params.substring(0, params.lastIndexOf('&search'))
					}

					let url;
					if(this.postType !== 'any') {
						url = `${LOCALISED_VARS.siteUrl}/wp-json/wp/v2/filter?post_type=${this.postType}&per_page=4&page=${this.page}`;
					} else {
						url = `${LOCALISED_VARS.siteUrl}/wp-json/wp/v2/searchall?per_page=4&page=${this.page}`;
					}

					search && (url += `&search=${search}`);
					params && (url += `&split&${params}`);

				axios({
					method: 'get',
					url,
				})
						.then(({headers, data}) => {
							this.maxPages = headers['x-wp-totalpages']
							this.posts = [...this.posts, ...data.map(post => post.card)]
						})
						.then(() => this.loading = false)
						.catch(() => this.posts = [])
				},

				loadMore() {
					this.page = this.page + 1
					this.getPosts()
				},

				morePosts() {
					return this.page < this.maxPages
				},
			},

			mounted() {
				EventBus.subscribe('filters:changed', () => {
					this.posts = []
					this.page = 1
					this.getPosts()
				})
			},

			beforeMount() {
				this.getPostType()
				this.getPosts()
			},
		});
	}
}