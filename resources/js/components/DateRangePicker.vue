<script>
import flatpickr from 'flatpickr'
import 'flatpickr/dist/themes/airbnb.css'

export default {
    props: {
        field: {
            required: true,
        },
        value: {
            required: false,
        },
        placeholder: {
            type: String,
            default: () => {
                return moment().format('YYYY-MM-DD') + ` ${this.default.props.seperator.default} ` + moment().format('YYYY-MM-DD')
            },
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        dateFormat: {
            type: String,
            default: 'Y-m-d',
        },
        seperator: {
            type: String,
            default: '-',
        },
        firstDayOfWeek: {
          type: Number,
          default: 0
        },
    },

    data: () => ({ flatpickr: null }),

    mounted() {
        this.$nextTick(() => {
            this.flatpickr = flatpickr(this.$refs.datePicker, {
                onOpen: this.onOpen,
                onClose: this.onClose,
                onChange: this.onChange,
                dateFormat: this.dateFormat,
                allowInput: true,
                mode: 'range',
                locale: {
                    rangeSeparator: ` ${this.seperator} `,
                    firstDayOfWeek: this.firstDayOfWeek,
                }
            })
        })
    },

    methods: {
        onChange(event) {
            this.$emit('change', { target: this.$refs.datePicker })
        },
        onOpen(event) {
            Nova.$emit('datepicker-opened', event)
        },
        onClose(event) {
            this.onChange(event)
            Nova.$emit('datepicker-closed', event)
        },        
    },
}
</script>

<template>
  <input
    :disabled="disabled"
    :dusk="field.attribute"
    :class="{'!cursor-not-allowed': disabled}"
    :value="value"
    :name="field.name"
    ref="datePicker"
    type="text"
    :placeholder="placeholder">
</template>

<style scoped>
.\!cursor-not-allowed {
    cursor: not-allowed !important;
}
</style>
