<template>
	<transition name="scale">
		<slot />
	</transition>
</template>

<style lang="sass">
.scale-enter-active,
.scale-leave-active
	transition: transform 0.4s ease

.scale-enter-from,
.scale-leave-to
	transform: scale(0.8)
</style>
