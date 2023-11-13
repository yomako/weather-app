<script setup>
import { ref } from 'vue';
import axios from "axios";
import { FwbInput, FwbButton, FwbListGroup, FwbListGroupItem } from 'flowbite-vue'
import LineChart from '../Components/LineChart.vue';

const city = ref('')
const selectedCity = ref('')
const cities = ref([]);
const weather = ref([]);

const searchCityFormSubmit = async () => {
    const { data }  = await axios.post('/search-city', { cityName: city.value });

    cities.value = data;
    weather.value = [];
};

const checkWeather = async (city) => {
    const { data }  = await axios.post('/check-weather', { cityId: city.id });
    city.value = '';
    selectedCity.value = city.display_name;
    cities.value = [];
    weather.value = data;
};

const clearCities = () => {
  cities.value = [];
}
</script>

<template>
    <div class="relative w-screen min-h-[100vh]">
      <img src="/images/background.webp" class="absolute w-full h-full object-cover z-0" />
      <div class="absolute w-full h-full bg-[#00000033]" @click="clearCities"></div>
      <div
        style="position: inherit;"
        class="container mx-auto flex flex-col items-center z-10"
      >
        <div class="flex gap-4 pt-10">
          <div class="relative">
            <fwb-input
              v-model="city"
              class="min-w-[15em]"
              placeholder="Wprowadź nazwę miasta"
              @keyup.enter="searchCityFormSubmit"
            />
            <fwb-list-group
              v-if="cities.length"
              class="absolute top-[45px] w-full z-20 max-h-[70vh] overflow-y-auto"
            >
              <fwb-list-group-item
                v-for="city in cities"
                :key="city.place_id"
                @click="checkWeather(city)"
                hover
              >
                {{ city.display_name }}
              </fwb-list-group-item>
            </fwb-list-group>
          </div>
          <fwb-button @click="searchCityFormSubmit" size="md">
            Szukaj
          </fwb-button>
        </div>
        <div
          v-if="weather.length"
          class="mt-10"
        >
          <h1 class="text-center text-xl text-[#fff8dc]">Prognoza na najbliższy tydzień dla miasta <strong>{{ selectedCity }}</strong></h1>
          <div class="grid lg:grid-cols-2 gap-8 mt-6 justify-items-center">
            <LineChart
              title="Temperatura [°C]"
              :data="weather"
              :dataKeys="['time', 'temperature']"
              :config="{
                time: { label: 'Czas'},
                rain: { hide: true },
                temperature: { label: 'Temteratura [°C]', color: '#0077b6' },
              }"
            />
            <LineChart
              title="Opady [mm]"
              :data="weather"
              :dataKeys="['time', 'rain']"
              :config="{
                time: { label: 'Czas'},
                temperature: { hide: true },
                rain: { label: 'Opady [mm]', color: '#0077b6' },
              }"
            />
          </div>
        </div>
      </div>
    </div>
  </template>

