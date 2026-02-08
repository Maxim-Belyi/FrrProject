<script setup lang="ts">
import type { CityStatistic } from '@app/modules/map/map.types';
import IconSVG from '@app/shared/components/IconSVG.vue';
import StatisticsAccordion from '@app/shared/components/StatisticsAccordion.vue';

interface Props {
	statistics: CityStatistic[];
}

defineProps<Props>();
</script>

<template>
	<StatisticsAccordion class="map-info">
		<template #default="{ onToggle, isOpen }">
			<div class="map-info__header" @click="onToggle">
				<span class="title title--h4">Статистика города</span>
				<IconSVG :name="`${isOpen ? 'chevron-up' : 'chevron-down'}`" class="map-info__header-icon" />
			</div>
		</template>
		<template #body>
			<div v-if="statistics.length" class="map-info__list">
				<div v-for="item in statistics" :key="item.id" class="map-info__item">
					<span class="map-info__item-value text text--medium">{{ item.name }}</span>
					<span class="map-info__item-title">{{ item.previewText }}</span>
				</div>
			</div>
			<div v-else class="map-info__list">
				<div class="map-info__item title title--h6">
					Статистика не найдена
				</div>
			</div>
		</template>
	</StatisticsAccordion>
</template>

<style scoped lang="sass">

</style>
