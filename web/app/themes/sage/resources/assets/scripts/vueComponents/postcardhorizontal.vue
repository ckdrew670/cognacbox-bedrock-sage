<script>
	export default {
		/**
		 * -- Vue Props ---
		 * https://vuejs.org/v2/guide/components-props.html#Prop-Types
		 */
		props: {
			posttype: String,
			title: String,
			link: String,
			date: String,
			author: String,
			excerpt: String,
			image: String,
			alt: String,
			location: String,
			position: String,
			left: String,
			right: String,
			type: String,
		},
	}
</script>

<template>
	<a class="post-card--horizontal__wrapper" :href="link" :aria-label="'Read more about ' + title">
		<article
			class="post-card--horizontal post-card--horizontal--list"
		>
			<div v-if="image" class="post-card--horizontal__image">
				<img :src="image" :alt="alt"/>
			</div>
			<div class="post-card--horizontal__content">
				<div class="post-card--horizontal__text">
					<div class="post-card--horizontal__info">
						<p
							v-if="posttype === 'programmes' || posttype === 'case-studies' || posttype === 'projects'"
							style="border-right: none; padding: 0;"
						>{{ posttype.charAt(0).toUpperCase() + posttype.slice(1).replace("-", ' ') }}</p>
						<p
							v-else-if="posttype === 'team'"
							style="border-right: none; padding: 0;"
						>{{ position }}</p>
						<p
							v-else-if="posttype === 'news'"
							style="border-right: none; padding: 0;"
						>{{ date }}</p>
						<template v-else>
							<p v-if="left && right" class="post-card--horizontal__info--left">{{ left }}</p>
							<p v-if="right && left" class="post-card--horizontal__info--right">{{ right }}</p>
							<p
							v-else-if="left && !right"
							style="border-right: none; padding: 0;"
						>{{ left }}</p>
						<p
							v-else-if="!left && right"
							style="border-right: none; padding: 0;"
						>{{ right }}</p>
						</template>
					</div>
					<h3 class="post-card--horizontal__title">{{ title }}</h3>
					<div class="post-card--horizontal__copy">{{ excerpt }}</div>
				</div>
				<span class="post-card--horizontal__link" :aria-label="'Read more about ' + title">Read more</span>
			</div>
		</article>
	</a>
</template>