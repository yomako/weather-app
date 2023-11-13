<template>
    <div>
        <h2 class="text-center mb-4 text-[#fff8dc] text-lg">{{ title }}</h2>
        <Chart
            style="background-color: #fffa;"
            :size="{ width: 500, height: 400 }"
            :data="chartData"
            :margin="margin"
            :direction="direction"
            :axis="{
                primary: {
                    tickValues: chartData.map((el, indeks) => (indeks % 24 !== 0) ? '' : el.time),
                }
            }"
        >
            <template #layers>
                <Grid strokeDasharray="2,2" />
                <Line :dataKeys="dataKeys" />
            </template>

            <template #widgets>
                <Tooltip
                    borderColor="#48CAE4"
                    :config="config"
                />
            </template>
        </Chart>
    </div>
</template>
  
<script lang="ts">
import { defineComponent, ref, PropType } from 'vue'
import { Chart, Grid, Line, Tooltip } from 'vue3-charts'

const transformDate = (dateString: string) => {
    const inputDate = new Date(dateString);
    const day = inputDate.getDate();
    const month = inputDate.getMonth() + 1;
    const hours = inputDate.getHours();
    const minutes = inputDate.getMinutes();

    return `${day < 10 ? '0' : ''}${day}-${month < 10 ? '0' : ''}${month} ${hours}:${minutes < 10 ? '0' : ''}${minutes}`;
}

export default defineComponent({
    name: 'LineChart',
    components: { Chart, Grid, Line, Tooltip },
    props: {
        data: {
            type: Array as PropType<any[]>,
            required: true,
        },
        dataKeys: {
            type: Array as PropType<string[]>,
            required: true,
        },
        title: {
            type: String,
        },
        config: {
            type: Object,
            required: true
        }
    },
    setup(props) {
        const chartData = ref(props.data.map(el => ({ ...el, time: transformDate(el.time) })));
        const dataKeys = ref(props.dataKeys)
        const config = ref(props.config)
        const direction = ref('horizontal')
        const margin = ref({
            left: 0,
            top: 20,
            right: 20,
            bottom: 0
        })

        return { chartData, direction, margin, dataKeys, config, transformDate }
    }
})
</script>

<style>
    #app {
        color: #000
    }
</style>
  