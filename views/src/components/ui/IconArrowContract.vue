<template>
	<div class="icon-arrow-contract">
		<svg v-if="action === 'contract'" @click="toggleAction" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
			<path fill="currentColor" fill-rule="evenodd" d="M.172 15.828a.5.5 0 0 0 .707 0l4.096-4.096V14.5a.5.5 0 1 0 1 0v-3.975a.5.5 0 0 0-.5-.5H1.5a.5.5 0 0 0 0 1h2.768L.172 15.121a.5.5 0 0 0 0 .707M15.828.172a.5.5 0 0 0-.707 0l-4.096 4.096V1.5a.5.5 0 1 0-1 0v3.975a.5.5 0 0 0 .5.5H14.5a.5.5 0 0 0 0-1h-2.768L15.828.879a.5.5 0 0 0 0-.707" />
		</svg>
		<svg v-else @click="toggleAction" xmlns = "http://www.w3.org/2000/svg" viewBox = "0 0 16 16">
			<path fill  = "currentColor" fill-rule           = "evenodd" d = "M5.828 10.172a.5.5 0 0 0-.707 0l-4.096 4.096V11.5a.5.5 0 0 0-1 0v3.975a.5.5 0 0 0 .5.5H4.5a.5.5 0 0 0 0-1H1.732l4.096-4.096a.5.5 0 0 0 0-.707m4.344-4.344a.5.5 0 0 0 .707 0l4.096-4.096V4.5a.5.5 0 1 0 1 0V.525a.5.5 0 0 0-.5-.5H11.5a.5.5 0 0 0 0 1h2.768l-4.096 4.096a.5.5 0 0 0 0 .707" />
		</svg>
	</div>
</template>
<script setup lang = "ts">
	import {ref} from "vue";

	let options = ['expand', 'contract'];
	let action  = ref(options[1]);
	
	function toggleAction() {
		action.value = action.value === options[0] ? options[1] : options[0];
	}

</script>
