<script setup lang="ts">
interface Props {
	name: string;
}

defineProps<Props>();
</script>

<template>
	<svg aria-hidden="true">
		<use :xlink:href="`/__spritemap#icon-${name}`" />
	</svg>
</template>
