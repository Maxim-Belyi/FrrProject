<script setup lang="ts">
import type { CityAdvanced } from '@app/modules/map/map.types';
import MapAside from '@app/modules/map/components/MapAside.vue';
import MapMain from '@app/modules/map/components/MapMain.vue';
import { computed, ref } from 'vue';

interface Props {
	openedProject?: number;
	cities: CityAdvanced[];
}

const props = defineProps<Props>();

const selectedCity = ref(selectCity());
const currentCity = computed<CityAdvanced>(() => {
	return props.cities[props.cities.findIndex((city: CityAdvanced) => selectedCity.value === city.id)];
});

function changeCity(id: number) {
	selectedCity.value = id;
}
function selectCity() {
	props.cities.forEach((city: CityAdvanced) => {
		if (city.selected)
			return city.id;
	});

	return props.cities[0].id;
}
</script>

<template>
	<div class="map-view__info">
		<MapAside
			:statistics="currentCity.statistics || []"
		/>
	</div>
	<div class="map-view__main">
		<MapMain
			:selected-city
			:current-city
			:opened-project
			:cities
			@change-city="changeCity"
		/>
	</div>
</template>

<style scoped lang="sass">

</style>
