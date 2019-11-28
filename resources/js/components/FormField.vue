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
                :dateFormat="format"
                :placeholder="placeholder"
                @change="handleChange"
            />

            <p v-if="hasError" class="my-2 text-danger">
                {{ firstError }}
            </p>
        </template>
    </default-field>
</template>

<script>
    import DateRangePicker from './DateRangePicker';
    import {Errors, FormField, HandlesValidationErrors, InteractsWithDates} from 'laravel-nova';

    export default {
        mixins: [HandlesValidationErrors, FormField, InteractsWithDates],
        components: {DateRangePicker},

        computed: {
            format() {
                return this.field.format;
            },
            placeholderFormat() {
                return this.field.placeholderFormat || 'YYYY-MM-DD';
            },
            seperator() {
                return this.field.seperator;
            },
            placeholder() {
                return moment().format(this.placeholderFormat) + ` ${this.field.seperator} ` + moment().format(this.placeholderFormat);
            },
        },
    };
</script>
