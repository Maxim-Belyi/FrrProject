<script setup lang="ts">
import IconSVG from '@app/shared/components/IconSVG.vue';
import { onClickOutside } from '@vueuse/core';
import { ref, useId, useTemplateRef } from 'vue';

interface Props {
	id?: string;
	name?: string;
	placeholder?: string;
	disabled?: boolean;
	title?: string;
	tooltips?: string[];
}

const {
	id,
	disabled,
} = defineProps<Props>();

const emit = defineEmits(['search']);

const modelValue = defineModel<string>();

const inputSearch = useTemplateRef<HTMLInputElement>('inputSearch');

const isFocus = ref(false);
function setValue(value: string): void {
	modelValue.value = value;
}

function onFocusOut(): void {
	isFocus.value = false;
}

function onFocusIn(): void {
	if (disabled)
		return;
	isFocus.value = true;
}

function onSearch(): void {
	if (disabled)
		return;
	emit('search', modelValue.value);
}

function onTooltipClick(tooltip: string): void {
	setValue(tooltip);
	isFocus.value = false;
}

// https://vueuse.org/core/onClickOutside/#onclickoutside
onClickOutside(inputSearch, () => onFocusOut());

const inputId = id ?? useId();
</script>

<template>
	<div class="default-input">
		<div class="default-input__input">
			<div ref="inputSearch" class="input" @focusin="onFocusIn">
				<span
					class="input__icon input__icon--lens-search"
					aria-hidden="true"
					tabindex="0"
					role="button"
					:class="{ disabled }"
					@click="onSearch"
					@keydown.enter="onSearch"
				>
					<IconSVG class="input__icon-image" name="lens" />
				</span>
				<input
					:id="inputId"
					v-model="modelValue"
					:placeholder
					:disabled
					inputmode="text"
					:title
					:name
					type="text"
					class="input__input input__input--search"
					@keydown.enter="onSearch"
				>
				<span
					v-if="modelValue"
					class="input__icon input__icon--clear-search"
					aria-hidden="true"
					tabindex="0"
					role="button"
					@click="setValue('')"
					@keydown.enter="setValue('')"
				>
					<IconSVG class="input__icon-image" name="close" />
				</span>
				<span
					v-if="isFocus || modelValue"
					class="input__icon input__icon--confirm-search"
					aria-hidden="true"
					tabindex="0"
					role="button"
					@click="onSearch"
					@keydown.enter="onSearch"
				>
					<IconSVG class="input__icon-image" name="arrow-right" />
				</span>
				<div v-show="tooltips?.length && isFocus" class="input__dropdown">
					<ul class="input__tooltips">
						<li
							v-for="tooltip in tooltips"
							:key="tooltip"
							tabindex="0"
							role="button"
							class="input__tooltip"
							@click="onTooltipClick(tooltip)"
							@keydown.enter="onTooltipClick(tooltip)"
						>
							{{ tooltip }}
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</template>
