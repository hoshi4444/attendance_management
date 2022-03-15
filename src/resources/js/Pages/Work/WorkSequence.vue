<!-- 打刻時刻のグラフ -->
<template>
    <!-- 打刻時刻メモリ -->
    <div class="flex items-end bg-blue-400 border-2 mt-6 h-14 rounded-sm">
        <!-- 時刻メモリ -->
        <div
            class="flex items-end justify-around px-2 border-l-2 cursor-pointer border-white h-8 w-1/12 relative"
            v-for="hourScale in 24"
            :key="hourScale"
        >
            <p class="absolute -top-12 -left-3">{{ hourScale }}時</p>

            <!--  15分間隔メモリ -->
            <div
                class="relative border-l-2 cursor-pointer border-white h-4 w-1/12"
                v-for="fifteenMinScale in 3"
                :key="fifteenMinScale"
            >
                <div v-if="work.work_stamps && work.work_stamps.length">
                    <!-- 確実にwork_stampが存在する場合のみ表示される -->
                    <work-stamp-pin
                        v-for="(workStamp, workStampIdx) in work.work_stamps"
                        :workStamp="workStamp"
                        :workStampIdx="workStampIdx"
                        :workStampsLen="work.work_stamps.length"
                        :hourScale="hourScale"
                        :fifteenMinScale="fifteenMinScale"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import WorkStampPin from './WorkStampPin.vue';

interface Props {
    work: Work,
}

const props = defineProps<Props>();

const work = computed(() => props.work);
</script>
