import IndexField from "./components/IndexField";
import DetailField from "./components/DetailField";
import FormField from "./components/FormField";

Nova.booting((Vue, router) => {
  Vue.component("index-date-range", IndexField);
  Vue.component("detail-date-range", DetailField);
  Vue.component("form-date-range", FormField);
});
