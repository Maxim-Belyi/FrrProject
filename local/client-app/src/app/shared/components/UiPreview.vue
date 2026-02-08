<script setup lang="ts">
import BaseLoader from '@/app/shared/components/ui/BaseLoader.vue';
import transitions from '@/app/shared/consts/transitions';
import useBaseStore from '@/app/shared/stores/base/base.store';
import InputCheckbox from '@app/shared/components/inputs/InputCheckbox.vue';
import InputDate from '@app/shared/components/inputs/InputDate.vue';
import InputFile from '@app/shared/components/inputs/InputFile.vue';
import InputRadio from '@app/shared/components/inputs/InputRadio.vue';
import InputRange from '@app/shared/components/inputs/InputRange.vue';
import InputSearch from '@app/shared/components/inputs/InputSearch.vue';
import InputSelect from '@app/shared/components/inputs/InputSelect.vue';
import InputSwitch from '@app/shared/components/inputs/InputSwitch.vue';
import InputText from '@app/shared/components/inputs/InputText.vue';
import ModalCallback from '@app/shared/components/modals/ModalCallback.vue';
import { useI18n } from '@app/shared/composables/useI18n/useI18n';
import { storeToRefs } from 'pinia';
import { ref } from 'vue';

interface Props {
	tab: string;
}
defineProps<Props>();

// COMPONENTS
const FadeTransition = transitions.FadeTransition;

// STORE
const { errorMessage, successMessage, warningMessage, infoMessage } = useBaseStore();

// DATA
const firstLoaderShown = ref<boolean>(false);
const secondLoaderShown = ref<boolean>(false);

// METHODS
function loaderTestShow() {
	firstLoaderShown.value = true;

	setTimeout(() => {
		firstLoaderShown.value = false;
	}, 3000);
}
function loaderTestShowSecond() {
	secondLoaderShown.value = true;

	setTimeout(() => {
		secondLoaderShown.value = false;
	}, 3000);
}

// I18N
const { t } = useI18n();
const { docs } = storeToRefs(useBaseStore());

const { BASE_URL } = import.meta.env;
</script>

<template>
	<div class="pv-blocks pv-blocks--list">
		<slot />

		<template v-if="['All', 'Inputs'].includes(tab)">
			<div class="pv-block">
				<h3 class="pv-block__title">
					Inputs
				</h3>
				<div class="pv-block__preview">
					<div class="pv-block__items pv-block__items--row pv-block__items--align-start">
						<InputText placeholder="Type here" label="Default Input" />
						<InputText
							label="Инпут с блоком рядом"
						>
							<template #action>
								<button class="input-btn btn">
									<span class="btn__text">Подтвердить</span>
								</button>
							</template>
						</InputText>
						<InputText
							label="Инпут с блоком снизу"
						>
							<template #underInput>
								<a class="link link--color-primary">Получить код</a>
							</template>
						</InputText>
						<InputText placeholder="Type here" label="Default Input" />
						<InputText placeholder="Type here" label="Disabled Input" disabled />
						<InputText placeholder="Type here" label="Readonly Input" readonly />
						<InputText placeholder="Type here" label="Disabled Input" :errors="['Some Error']" />
						<InputText placeholder="Type here" label="Loading Input" loading />
					</div>
				</div>
			</div>

			<div class="pv-block pv-block--dark">
				<h3 class="pv-block__title">
					Inputs
				</h3>
				<div class="pv-block__preview">
					<div class="pv-block__items pv-block__items--row pv-block__items--align-start">
						<InputText placeholder="Type here" label="Default Input" />
						<InputText
							label="Инпут с блоком рядом"
						>
							<template #action>
								<button class="input-btn btn">
									<span class="btn__text">Подтвердить</span>
								</button>
							</template>
						</InputText>
						<InputText
							label="Инпут с блоком снизу"
						>
							<template #underInput>
								<a class="link">Получить код</a>
							</template>
						</InputText>
						<InputText placeholder="Type here" label="Default Input" />
						<InputText placeholder="Type here" label="Disabled Input" disabled />
						<InputText placeholder="Type here" label="Readonly Input" readonly />
						<InputText placeholder="Type here" label="Disabled Input" :errors="['Some Error']" />
						<InputText placeholder="Type here" label="Loading Input" loading />
					</div>
				</div>
			</div>
		</template>

		<div v-if="['All', 'Select'].includes(tab)" class="pv-block">
			<h3 class="pv-block__title">
				Select
			</h3>
			<div class="pv-block__preview">
				<div class="pv-block__items pv-block__items--row pv-block__items--align-start">
					<InputSelect placeholder="Select option" label="Default Select" :options="['Option 1', 'Extremely Long Option 2', 'Option 3']" />
					<InputSelect placeholder="Select option" label="Disabled Select" disabled :options="['Option 1', 'Option 2', 'Option 3']" />
					<InputSelect placeholder="Select option" label="ReadOnly Select" readonly :options="['Option 1', 'Option 2', 'Option 3']" />
					<InputSelect placeholder="Select option" label="Unvalid Select" :errors="['Some Other Error']" :options="['Option 1', 'Option 2', 'Option 3']" />
					<InputSelect :model-value="['Option 1']" placeholder="Select option" label="Tags Select" mode="tags" :options="['Option 1', 'Option 2', 'Option 3']" />
					<InputSelect :model-value="['Option 1', 'Option 3']" placeholder="Select option" label="Multiple Select" mode="multiple" :options="['Option 1', 'Option 2', 'Option 3', 'Option 4', 'Option 5', 'Option 6', 'Option 7', 'Option 8', 'Option 9']" />
					<InputSelect placeholder="Select option" label="Loading Select" loading-select :options="['Option 1', 'Option 2', 'Option 3']" />
					<InputSelect placeholder="Select option" label="Group Select" mode="multiple" groups :options="['Option 1', 'Option 2', 'Option 3']" />
					<InputSelect placeholder="Select option" label="Searchable Select" searchable :options="['Option 1', 'Option 2', 'Option 3']" />
					<InputSelect placeholder="Select option" label="Default Select" :options="['Option 1', 'Extremely Long Option 2', 'Option 3']" loading />
				</div>
			</div>
		</div>

		<div v-if="['All', 'Checkbox'].includes(tab)" class="pv-block">
			<h3 class="pv-block__title">
				Checkbox
			</h3>
			<div class="pv-block__preview">
				<div class="pv-block__items pv-block__items--row pv-block__items--align-start">
					<InputCheckbox label="Default Checkbox" text="Default Checkbox" />
					<InputCheckbox label="Checked Checkbox" :model-value="true" text="Checked Checkbox" />
					<InputCheckbox label="Disabled Checkbox" disabled text="Disabled Checkbox" />
					<InputCheckbox label="Disabled-checked Checkbox" disabled :model-value="true" text="Disabled-checked Checkbox" />
					<InputCheckbox label="Readonly Checkbox" readonly :model-value="true" text="Readonly Checkbox" />
					<InputCheckbox label="Unvalid Checkbox" :errors="['Some error']" text="Unvalid Checkbox" />
					<InputCheckbox text="No label Checkbox" />
					<InputCheckbox text="No label Checkbox" loading />
				</div>
			</div>
		</div>

		<div v-if="['All', 'Radio'].includes(tab)" class="pv-block">
			<h3 class="pv-block__title">
				Radio
			</h3>
			<div class="pv-block__preview">
				<div class="pv-block__items pv-block__items--row pv-block__items--align-start">
					<InputRadio name="radio-preview" label="Default Radio" value-prop="Default Radio" />
					<InputRadio name="radio-preview" label="Checked Radio" value-prop="Checked Radio" />
					<InputRadio name="radio-preview" label="Disabled Radio" disabled value-prop="Disabled Radio" />
					<InputRadio name="radio-preview" label="Readonly Radio" readonly value-prop="Readonly Radio" />
					<InputRadio name="radio-preview" label="Unvalid Radio" :errors="['Some error']" value-prop="Unvalid Radio" />
					<InputRadio name="radio-preview" value-prop="No label Radio" />
					<InputRadio name="radio-preview" :loading="true" value-prop="No label Radio" />
				</div>
			</div>
		</div>

		<div v-if="['All', 'Switch'].includes(tab)" class="pv-block">
			<h3 class="pv-block__title">
				Switch
			</h3>
			<div class="pv-block__preview">
				<div class="pv-block__items pv-block__items--row pv-block__items--align-start">
					<InputSwitch label="Default Switch" on-label="On text" off-label="Off text" />
					<InputSwitch :model-value="true" label="Active Switch" on-label="On text" off-label="Off text" />
					<InputSwitch label="Disabled Switch" disabled on-label="On text" off-label="Off text" />
					<InputSwitch label="Unvalid Switch" :errors="['Some error']" on-label="On text" off-label="Off text" />
					<InputSwitch label="No text Switch" />
					<InputSwitch on-label="No label Switch" />
					<InputSwitch :loading="true" on-label="No label Switch" />
				</div>
			</div>
		</div>

		<div v-if="['All', 'Search'].includes(tab)" class="pv-block">
			<h3 class="pv-block__title">
				Search
			</h3>
			<div class="pv-block__preview">
				<div class="pv-block__items pv-block__items--row pv-block__items--align-start">
					<InputSearch placeholder="Search here" :tooltips="['First tooltip', 'Second tooltip']" />
					<InputSearch placeholder="Disabled search" disabled :tooltips="['First tooltip', 'Second tooltip']" />
				</div>
			</div>
		</div>

		<div v-if="['All', 'Date'].includes(tab)" class="pv-block">
			<h3 class="pv-block__title">
				Date
			</h3>
			<div class="pv-block__preview">
				<div class="pv-block__items pv-block__items--row pv-block__items--align-start">
					<InputDate placeholder="Select date" label="Default Date" />
					<InputDate placeholder="Select date" label="Disabled Date" disabled />
					<InputDate placeholder="Select date" label="Unvalid Date" :errors="['Some error']" />
					<InputDate placeholder="Select date" label="Selected Date" model-value="01.01.2020" />
					<InputDate placeholder="Select date" label="Readonly Date" readonly model-value="01.01.2020" />
					<InputDate placeholder="Select date" label="Custom Format (YY/MM/DD)" date-mask="yy/MM/dd" model-value="2020/01/01" />
					<InputDate placeholder="Select date" label="Max/Min Date" min-date="01.01.2020" max-date="18.01.2020" model-value="01.01.2020" />
					<InputDate placeholder="Select dates" label="Range Date" is-range />
					<InputDate placeholder="Select date" label="Time Date" is-time-picker />
					<InputDate placeholder="Select date" :loading="true" label="Loading" is-time-picker />
				</div>
			</div>
		</div>

		<div v-if="['All', 'File'].includes(tab)" class="pv-block">
			<h3 class="pv-block__title">
				Inputs Files
			</h3>
			<div class="pv-block__preview">
				<div class="pv-block__items pv-block__items--row pv-block__items--align-start">
					<InputFile placeholder="Choose file" label="Default File" :accept="['image/jpeg', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/rtf']" />
					<InputFile placeholder="Choose file" label="Filled File" :accept="['image/jpeg', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/rtf']" multiple :model-value="[`${BASE_URL}img/content/rmk-arena.jpg`, `${BASE_URL}img/content/traktor.jpg`]" />
					<InputFile placeholder="Choose file" label="Disabled File" :accept="['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/rtf']" disabled />
					<InputFile placeholder="Choose file" label="Readonly File" :accept="['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/rtf']" readonly multiple :model-value="[`${BASE_URL}img/content/rmk-arena.jpg`, `${BASE_URL}img/content/traktor.jpg`]" />
					<InputFile placeholder="Choose file" label="Unvalid File" :accept="['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/rtf']" :errors="['Some error']" />
				</div>
			</div>
		</div>

		<div v-if="['All', 'Range'].includes(tab)" class="pv-block">
			<h3 class="pv-block__title">
				Inputs Range
			</h3>
			<div class="pv-block__preview">
				<div class="pv-block__items pv-block__items--row pv-block__items--align-start">
					<InputRange label="Default Range" suffix="%" />
					<InputRange label="Decimal Range" suffix="%" :step="-1" />
					<InputRange label="Tooltip Range" suffix="%" :model-value="75" tooltips />
					<InputRange label="Double File" suffix="$" :model-value="[50, 100]" />
					<InputRange label="Readonly Range" suffix="%" :model-value="35" readonly />
					<InputRange label="Unvalid Range" suffix="%" :errors="['Some error']" />
				</div>
			</div>
		</div>

		<div v-if="['All', 'Loaders'].includes(tab)" class="pv-block">
			<h3 class="pv-block__title">
				Loaders
			</h3>
			<div class="pv-block__preview">
				<div class="pv-block__items pv-block__items--row pv-block__items--align-start">
					<div class="pv-block__item">
						<button class="btn btn--color-primary btn--size-small" @click="loaderTestShow()">
							<span class="btn__text">Show Fullscreen Loader</span>
						</button>
						<FadeTransition>
							<BaseLoader v-if="firstLoaderShown" color-scheme="dark" />
						</FadeTransition>
					</div>
					<div class="pv-block__item">
						<button class="btn btn--color-primary btn--size-small" @click="loaderTestShowSecond()">
							<span class="btn__text">Static Loader</span>
						</button>
						<BaseLoader color-scheme="dark" position="static" />
					</div>
				</div>
			</div>
		</div>

		<div v-if="['All', 'Toasts'].includes(tab)" class="pv-block">
			<h3 class="pv-block__title">
				Toasts
			</h3>
			<div class="pv-block__preview">
				<div class="pv-block__items pv-block__items--row pv-block__items--align-start">
					<button class="btn btn--color-primary btn--size-small" @click="successMessage('All good!')">
						<span class="btn__text">Success</span>
					</button>
					<button class="btn btn--border-primary btn--size-small" @click="errorMessage('Something wrong')">
						<span class="btn__text">Error</span>
					</button>
					<button class="btn btn--border-primary btn--size-small" @click="warningMessage('Be careful')">
						<span class="btn__text">Warning</span>
					</button>
					<button class="btn btn--border-primary btn--size-small" @click="infoMessage('Some info')">
						<span class="btn__text">Info</span>
					</button>
				</div>
			</div>
		</div>

		<div v-if="['All', 'I18n'].includes(tab)" class="pv-block">
			<h3 class="pv-block__title">
				i18n
			</h3>
			<div class="pv-block__preview">
				<span>{{ t('test.hello') }}</span>
				<br>
				<span>{{ t('test.applesCount', { count: 5 }) }}</span>
				<br>
				<p v-html="t('test.agreement', { policy: docs.privacyPolicy, personal: docs.userAgreement })"></p>
			</div>
		</div>
	</div>
	<ModalCallback />
</template>
