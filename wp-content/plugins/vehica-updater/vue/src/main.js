import Vue from 'vue'
import VehicaUpdater from './Updater'

Vue.component('vehica-updater', VehicaUpdater)

const vue = document.getElementsByClassName('vehica-updater-app')
Array.prototype.forEach.call(vue, function (el) {
  new Vue({
    el: el,
  })
});
