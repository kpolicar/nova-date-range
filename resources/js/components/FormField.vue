<template>
    <default-field :field="field">
        <template slot="field">
            <date-range-picker
                class="w-full form-control form-input form-input-bordered"
                mode="sa"
                :name="field.name"
                :field="field"
                :value="value"
                :seperator="seperator"
                :firstDayOfWeek="firstDayOfWeek"
                :dateFormat="format"
                :placeholder="placeholder"
                @change="handleChange"
                :disabled="isReadonly"
            />

            <p v-if="hasError" class="my-2 text-danger">
                {{ firstError }}
            </p>
        </template>
    </default-field>
</template>

<script>
import DateRangePicker from './DateRangePicker'
import { FormField, HandlesValidationErrors, InteractsWithDates } from 'laravel-nova'

export default {
    mixins: [HandlesValidationErrors, FormField, InteractsWithDates],
    components: { DateRangePicker },
    mounted() {
        console.log('Test in Component');
    },
    computed: {
        format() {
            return this.field.format
        },
        seperator() {
            return this.field.seperator
        },
        firstDayOfWeek() {
            return this.field.firstDayOfWeek || 0;
        },        
        placeholder() {
            return moment().format('YYYY-MM-DD') + ` ${this.field.seperator} ` + moment().format('YYYY-MM-DD')
        },
    },
}
</script>
