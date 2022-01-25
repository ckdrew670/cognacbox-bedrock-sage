<script>
	export default {
		/**
		 * -- Vue Props ---
		 * https://vuejs.org/v2/guide/components-props.html#Prop-Types
		 */
		props: {
			stringProp: String,
			numberProp: Number,
			boolProp: Boolean,
			arrayProp: Array,
			objectProp: Object,
			callbackProp: Function,
			constructorProp: Promise, // or any other constructor
		},

		/**
		 * --- Vue Lifecycle methods ---
		 * https://vuejs.org/v2/guide/instance.html#Lifecycle-Diagram
		 */
		beforeCreate() { console.log('example component beforeCreate') },
		created() { console.log('example component created') },
		beforeMount() { console.log('example component beforeMount') },
		mounted() { console.log('example component mounted') },
		beforeUpdate() { console.log('example component beforeUpdate') },
		updated() { console.log('example component updated') },
		beforeDestroy() { console.log('example component beforeDestroy') },
		destroyed() { console.log('example component destroyed') },
	}
</script>

<template>
	<article>
		<h2>Example</h2>

		<p>I'm an example component.</p>

		<p>Here's a string passed in via props: {{ stringProp }}</p>

		<p>Here's a number passed in via props: {{ numberProp }}</p>
	</article>
</template>