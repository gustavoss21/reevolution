<style
	scope
	src="@/assets/style/scss/modules/_header.scss"
	lang="scss"
	scoped></style>
<template>
	<header>
		<nav class="custom-nav">
			<h1 class="title">Reevolution</h1>
			<ul>
				<li>
					<a
						:href  = "pathname = origin"
						:style = "urlActived"
						>Home
                    </a>
				</li>
				<li><a :href="origin + '/cronograma'">Cronograma</a></li>
				<li><a :href="origin + '/accompaniment'">Acompanhamento</a></li>
				<li><a :href="origin + '/estudo'">estudo</a></li>
			</ul>
		</nav>
	</header>
</template>

<script setup lang = "ts">
import { ref, computed } from 'vue'

let     origin     = window.location.origin + "/reevolution";
let     urlCurrent = window.location.href;
let     pathname   = ref("");
const urlActived = computed(() => { return urlCurrent === origin ? "background-color: aqua;" : ""; });
</script>
