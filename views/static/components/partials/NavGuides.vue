<template>
	<div>
        
		<v-b-tabs content-class = "mt-3">
		<template v-for         = "guide in guides as AccompanimentEnum[]">
				<v-b-tab v-if="guide == active" active>
                    <p>Im the first {{ guide }}</p>
                </v-b-tab
				>
				<v-b-tab :title = "guide">
                    <p>I'm the first {{ guide }}</p>
                </v-b-tab>
			</template>
		</v-b-tabs>
	</div>
</template>
<script lang="ts">
	import {AccompanimentEnum} from "@/interface/AccompanimentInterface.ts";
	import {ref} from "vue";

	export default {
		props: {
			//['guides','active'],
			guides: {type: Array, required: true},
			active: String,
		},
		data() {
			return {active: this.active ?? this.guides[0]};
		},
		mounted() {
			console.log(this.active);
		},
	};
</script>
