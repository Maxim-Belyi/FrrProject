<script setup lang="ts">
import IconSVG from '@app/shared/components/IconSVG.vue';
import InputSelect from '@app/shared/components/inputs/InputSelect.vue';
import { computed, ref } from 'vue';

interface City {
	id: number;
	name: string;
	phone: string[];
	email: string[];
	link: string;
}

interface Props {
	cities: City[];
}

const props = defineProps<Props>();

const selectedCity = ref<number>(props.cities[0].id);
const currentCity = computed<City>(() => {
	return props.cities[props.cities.findIndex((city: City) => selectedCity.value === city.id)];
});
</script>

<template>
	<div class="contacts__view">
		<div class="contacts__info">
			<InputSelect
				v-model="selectedCity"
				:options="cities"
				value-prop="id"
				label-prop="name"
				:can-clear="false"
				:can-deselect="false"
				label="Выберите населенный пункт"
			/>

			<div class="contacts__links">
				<a v-for="(item, i) in currentCity.phone" :key="i" :href="`tel:${item}`" class="contacts__link link link--color-primary">{{ item }}</a>
				<a v-for="(item, i) in currentCity.email" :key="i" :href="`mailto:${item}`" class="contacts__link link link--color-primary">{{ item }}</a>
			</div>
		</div>

		<a :href="currentCity.link" target="_blank" class="contacts__btn btn btn--color-secondary">
			<span class="btn__text">Сайт администрации</span>
			<IconSVG name="arrow-right-up" class="btn__icon" />
		</a>
	</div>
</template>

<style scoped lang="sass">

</style>
