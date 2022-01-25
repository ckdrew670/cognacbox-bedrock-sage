{{--
	This element expects

	$type {str}
--}}

<section class="posts-archive">
	<div class="container posts-archive__container">
		<div class="posts-archive__filters">
			@include('patterns.filters.filters', [
				'post_type' => $type
			])
		</div>
		<div id="posts-archive" class="posts-archive__list" data-post-type="{{ $type }}">
				<p v-if="loading">Searching...</p>
				<p v-else-if="!posts.length">Sorry, nothing matches your search terms.</p>
				<template v-else>
					<postcardhorizontal
						v-for="post in posts"
						v-bind:key="post.id"
						:posttype="post.post_type"
						:title="post.title"
						:link="post.link"
						:date="post.date"
						:author="post.author"
						:excerpt="post.excerpt"
						:image="post.image"
						:alt="post.image_alt || ''"
						:location="post.location"
						:position="post.position"
						:left="post.left"
						:right="post.right"
					/>
				</template>
				<div
					v-if="!loading && posts.length && morePosts()"
					class="cta-button__container"
				>
					<a
						class="cta-button"
						v-on:click="loadMore()"
					>
						<span class="cta-button__text">Load more</span>
					</a>
				</div>
		</div>
	</div>
</section>