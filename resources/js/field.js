Nova.booting((Vue, router) => {
    Vue.component('index-date-range', require('./components/IndexField'));
    Vue.component('detail-date-range', require('./components/DetailField'));
    Vue.component('form-date-range', require('./components/FormField'));
})
