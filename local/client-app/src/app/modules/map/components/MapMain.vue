<script setup lang="ts">
import type { CityAdvanced, Point } from '@app/modules/map/map.types';
import MapYandex from '@app/modules/map/components/MapYandex.vue';
import InputSearch from '@app/shared/components/inputs/InputSearch.vue';
import InputSelect from '@app/shared/components/inputs/InputSelect.vue';
import ModalProjectDetail from '@app/shared/components/modals/ModalProjectDetail.vue';
import { computed, ref } from 'vue';

interface Props {
	selectedCity: number;
	currentCity: CityAdvanced;
	openedProject?: number;
	cities: CityAdvanced[];
}

const props = defineProps<Props>();
const emit = defineEmits(['changeCity']);

const search = ref<string>('');
const selectedCity = ref(props.selectedCity);
const currentPoints = computed<Point[]>(() => {
	if (!props.currentCity.points)
		return [];

	return props.currentCity.points.filter((point: Point) => isMatches(point));
});
const currentPoint = ref<Point>(currentPoints.value[0] || null);
const showTooltip = ref<boolean>(false);

function countProjectsByStatus(status: string) {
	return computed(() =>
		currentPoints.value.filter((point: Point) => point.status.xmlId === status).length,
	);
}

const implementedProjects = countProjectsByStatus('green');
const progressProjects = countProjectsByStatus('yellow');
const plannedProjects = countProjectsByStatus('gray');

function isMatches(point: Point) {
	return point.label.toLowerCase().includes(search.value.toLowerCase()) || point.address?.toLowerCase().includes(search.value.toLowerCase());
}

function changeCity() {
	handleShowTooltip(false);
	emit('changeCity', selectedCity.value);
}

function changePoint(point: Point) {
	currentPoint.value = point;
}

function handleShowTooltip(value: boolean) {
	showTooltip.value = value;
}
</script>

<template>
	<div class="map-main">
		<div class="map-main__head">
			<div class="map-main__tags tags">
				<div class="tags__list">
					<div class="tag tag--green">
						<span class="tag__color"></span>
						<span class="tag__text">Реализован</span>
						<span class="tag__count">{{ implementedProjects }}</span>
					</div>
					<div class="tag tag--yellow">
						<span class="tag__color"></span>
						<span class="tag__text">В процессе</span>
						<span class="tag__count">{{ progressProjects }}</span>
					</div>
					<div class="tag tag--gray">
						<span class="tag__color"></span>
						<span class="tag__text">Планируется</span>
						<span class="tag__count">{{ plannedProjects }}</span>
					</div>
				</div>
			</div>
			<div class="map-main__filters">
				<InputSelect
					v-model="selectedCity"
					:options="cities"
					value-prop="id"
					label-prop="name"
					:can-clear="false"
					:can-deselect="false"
					class="map-main__select"
					@change="changeCity"
				/>
				<InputSearch
					v-model="search"
					placeholder="Поиск"
					class="map-main__search"
				/>
			</div>
		</div>
		<MapYandex
			:current-city="currentCity"
			:current-point="currentPoint"
			:points="currentPoints"
			:show-tooltip
			:opened-project
			class="map-main__map"
			@change-point="changePoint"
			@handle-show-tooltip="handleShowTooltip"
		/>
	</div>
	<ModalProjectDetail />
</template>

<style scoped lang="sass">

</style>
