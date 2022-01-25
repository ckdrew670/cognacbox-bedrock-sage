<script>
	import exampleComponent from './exampleComponent.vue';

	export default {}
</script>

<template>
	<div>
		<exampleComponent v-bind:numberProp='1' stringProp='Yes'></exampleComponent>
		<exampleComponent v-bind:numberProp='2' stringProp='No'></exampleComponent>
		<exampleComponent v-bind:numberProp='3' stringProp='Maybe'></exampleComponent>
	</div>
</template>