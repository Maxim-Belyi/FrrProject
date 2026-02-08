<script setup lang="ts">
import type { CityAdvanced, Point } from '@app/modules/map/map.types';
import type { YMap } from '@yandex/ymaps3-types';
import type { Feature as ClustererFeature } from '@yandex/ymaps3-types/packages/clusterer';
import useModalsStore from '@app/shared/stores/modals/modals.store';
import { computed, onMounted, ref, shallowRef, watchEffect } from 'vue';
import {
	YandexMap,
	YandexMapClusterer,
	YandexMapControls,
	YandexMapDefaultFeaturesLayer,
	YandexMapDefaultSchemeLayer,
	YandexMapGeolocationControl,
	YandexMapMarker,
	YandexMapZoomControl,
} from 'vue-yandex-maps';

interface Props {
	currentCity: CityAdvanced;
	currentPoint: Point | null;
	points: Point[];
	showTooltip: boolean;
	openedProject?: number;
}

const { currentCity, currentPoint, points, showTooltip, openedProject } = defineProps<Props>();
const emit = defineEmits(['changePoint', 'handleShowTooltip']);
const { openModal } = useModalsStore();

const mapContainer = ref<HTMLElement | null>(null);
const map = shallowRef<YMap | null>(null);

const mapPoints = computed(() => {
	return points.filter((point: Point) => point.location?.lon && point.location?.lat);
});

const mapCenter = ref({
	center: [currentCity.coordinates.lon, currentCity.coordinates.lat],
	zoom: currentCity.zoomMap || 14,
	duration: 700,
});

function handleChangePoint(point: Point) {
	if (!currentPoint)
		return;

	if (currentPoint.id === point.id && showTooltip) {
		emit('handleShowTooltip', false);
	}
	else if (currentPoint.id === point.id && !showTooltip) {
		emit('handleShowTooltip', true);
	}
	else {
		emit('changePoint', point);
		emit('handleShowTooltip', true);
	}
}

function getConicGradient(features: ClustererFeature[]) {
	const clusterColors = new Set<string>();

	features.forEach((feature) => {
		const coordinates = {
			lon: feature.geometry.coordinates[0],
			lat: feature.geometry.coordinates[1],
		};
		mapPoints.value.forEach((point) => {
			if (point.location.lon === coordinates.lon && point.location.lat === coordinates.lat) {
				clusterColors.add(point.status.xmlId);
			}
		});
	});

	const angle = 360 / clusterColors.size;
	const segments = [...clusterColors];
	const gradientStops: string[] = [];
	segments.forEach((segment, index) => {
		const startAngle = index * angle;
		const endAngle = (index + 1) * angle;

		if (segments.length === 1) {
			gradientStops.push(`${getColor(segment)} 0deg`);
			gradientStops.push(`${getColor(segment)} 360deg`);
		}
		else {
			gradientStops.push(`${getColor(segment)} ${startAngle}deg`);
			gradientStops.push(`${getColor(segment)} ${endAngle - 5}deg`);
			gradientStops.push(`white ${endAngle - 5}deg`);
			gradientStops.push(`white ${endAngle}deg`);
		}
	});

	return `conic-gradient(${gradientStops.join(', ')})`;
}

function getColor(colorName: string) {
	const colorMap = [
		{
			name: 'green',
			code: '#42AD3F',
		},
		{
			name: 'yellow',
			code: '#E7A516',
		},
		{
			name: 'gray',
			code: '#7C8287',
		},
	];
	return colorMap[colorMap.findIndex(color => color.name === colorName)].code;
}

async function openProjectDetail(id: number) {
	openModal('modal-project-detail', id);
}

watchEffect(() => {
	mapCenter.value.center = [currentCity.coordinates.lon, currentCity.coordinates.lat];
	mapCenter.value.zoom = currentCity.zoomMap || 14;
});

onMounted(() => {
	if (openedProject) {
		setTimeout(() => {
			openProjectDetail(openedProject);
		}, 1000);
	}
});
</script>

<template>
	<div ref="mapContainer" class="map">
		<YandexMap
			v-model="map"
			:settings="{
				location: mapCenter,
				behaviors: ['drag', 'pinchZoom', 'dblClick'],
			}"
		>
			<YandexMapDefaultSchemeLayer />
			<YandexMapDefaultFeaturesLayer />
			<YandexMapControls :settings="{ position: 'right' }">
				<YandexMapZoomControl />
				<YandexMapGeolocationControl />
			</YandexMapControls>
			<YandexMapClusterer zoom-on-cluster-click>
				<YandexMapMarker
					v-for="point in mapPoints"
					:key="point.id"
					position="left-center top"
					:container-attrs="{ style: { 'z-index': 1 } }"
					:settings="{ coordinates: [point.location?.lon, point.location?.lat] }"
				>
					<div :class="`map-marker map-marker--${point.status.xmlId}`">
						<div
							class="map-marker__point"
							@click="handleChangePoint(point)"
						/>
						<div v-if="currentPoint?.id === point.id && showTooltip" class="map-marker__info" @click="openProjectDetail(point.id)">
							<div class="map-marker__info-label">
								{{ point.label }}
							</div>
							<div class="map-marker__info-address">
								{{ point.address }}
							</div>
						</div>
					</div>
				</YandexMapMarker>

				<template #cluster="{ length, clusterer }">
					<div
						class="map-cluster"
						:style="`background: ${getConicGradient(clusterer.features)}`"
					>
						<div class="map-cluster__inner">
							{{ length }}
						</div>
					</div>
				</template>
			</YandexMapClusterer>
		</YandexMap>
	</div>
</template>
