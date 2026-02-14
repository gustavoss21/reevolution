# Vue 3 + TypeScript Setup

## Configuração Completa

Sua aplicação agora está configurada para usar Vue 3 com TypeScript!

### Arquivos Criados/Atualizados:

- **tsconfig.json** - Configuração do TypeScript para Vue
- **tsconfig.node.json** - Configuração do TypeScript para build tools
- **vite.config.ts** - Configuração do Vite (em TypeScript)
- **env.d.ts** - Tipos globais para arquivos .vue
- **main.ts** - Exemplo de entry point da aplicação
- **components/ExampleComponent.vue** - Exemplo de componente Vue com TypeScript

### Pacotes Instalados:

- `typescript` - Compilador TypeScript
- `@vitejs/plugin-vue` - Plugin Vue para Vite (já estava)
- `@vue/tsconfig` - Configuração recomendada de TypeScript para Vue
- `vue-tsc` - Compilador TypeScript para Vue

### Como Usar

#### 1. **Criar Componentes Vue com TypeScript**

```vue
<template>
	<div>
		<h1>{{ title }}</h1>
		<button @click="handleClick">Clique</button>
	</div>
</template>

<script setup lang="ts">
	import { ref } from "vue";

	const title = ref<string>("Olá TypeScript!");
	const count = ref<number>(0);

	const handleClick = (): void => {
		count.value++;
	};
</script>
```

#### 2. **Executar o Dev Server**

```bash
npm run dev
```

#### 3. **Build para Produção**

```bash
npm run build
```

### Sintaxes Suportadas

#### Composition API com `<script setup>`

```typescript
<script setup lang="ts">
import { ref, computed } from 'vue'
import type { Ref, ComputedRef } from 'vue'

const count: Ref<number> = ref(0)
const doubled: ComputedRef<number> = computed(() => count.value * 2)

const increment = (): void => {
  count.value++
}
</script>
```

#### Options API com TypeScript

```typescript
<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  data(): { count: number } {
    return {
      count: 0
    }
  },
  methods: {
    increment(): void {
      this.count++
    }
  },
  computed: {
    doubled(): number {
      return this.count * 2
    }
  }
})
</script>
```

### Funções Úteis

#### 1. **Validação de Tipos em Componentes**

```typescript
interface Props {
	title: string;
	count?: number;
	items: string[];
}

interface Emits {
	(e: "update:count", value: number): void;
	(e: "delete", id: string): void;
}

defineProps<Props>();
const emit = defineEmits<Emits>();
```

#### 2. **Composables Tipados**

```typescript
// composables/useCounter.ts
import { ref, computed } from "vue";

export function useCounter(initialValue: number = 0) {
	const count = ref(initialValue);

	const increment = (): void => {
		count.value++;
	};

	const decrement = (): void => {
		count.value--;
	};

	const doubled = computed(() => count.value * 2);

	return { count, increment, decrement, doubled };
}
```

### Verificação de Tipos

Para verificar tipos de todos os componentes Vue:

```bash
npx vue-tsc --noEmit
```

### Aliases de Import

Você pode usar o alias `@` para imports:

```typescript
// Em vez de:
import Component from "../../../components/MyComponent.vue";

// Use:
import Component from "@/components/MyComponent.vue";
```

### Próximos Passos

1. Migre seus componentes `.js` para `.ts`
2. Renomeie componentes `.vue` existentes para usar `<script setup lang="ts">`
3. Use tipos TypeScript em todos os seus componentes
4. Execute verificação de tipos regularmente

### Referências

- [Vue 3 + TypeScript Docs](https://vuejs.org/guide/typescript/)
- [Vite Documentation](https://vitejs.dev/)
