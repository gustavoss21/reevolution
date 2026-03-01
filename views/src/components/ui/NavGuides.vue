<template>
	<div>    
		<BTabs content-class = "mt-3" pills>
		<template   v-for         = "guide in guides as AccompanimentEnum[]">
				<BTabs v-if          = "guide == active" active>
					{{ guide }}
                </BTabs>
				<BTabs v-else :title = "guide">
					{{ guide }}

                </BTabs>
			</template>
		</BTabs>
	</div>
</template>
<script lang="ts">
	import {AccompanimentEnum} from "views/types/interface/AccompanimentInterface";
	import {ref} from "vue";
	import "bootstrap/dist/css/bootstrap.css";

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
