<!-- 打刻時刻のグラフ -->
<template>
    <!-- 打刻時刻メモリ -->
    <div class="flex items-end bg-blue-400 border-2 mt-6 h-14 rounded-sm">
        <!-- 時刻メモリ -->
        <div
            class="flex items-end justify-around px-2 border-l-2 cursor-pointer border-white h-8 w-1/12 relative"
            v-for="hourScale in Array(24).keys()"
            :key="hourScale"
        >
            <p class="hour-memory">{{ hourScale }}</p>

            <!--  15分間隔メモリ -->
            <div
                class="relative border-l-2 cursor-pointer border-white h-4 w-1/12"
                v-for="fifteenMinScale in 3"
                :key="fifteenMinScale"
            >
                <div v-if="workStamps">
                    <!-- 確実にwork_stampが存在する場合のみ表示される -->
                    <work-stamp-pin
                        v-for="(workStamp, workStampIdx) in workStamps"
                        :workStamp="workStamp"
                        :workStampIdx="workStampIdx"
                        :workStampsLen="workStamps.length"
                        :hourScale="hourScale"
                        :fifteenMinScale="fifteenMinScale"
                        :selectedStamp="selectedStamp"
                        @selectStamp="selectStamp"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import WorkStampPin from './WorkStampPin.vue';
import { DateTime } from "luxon";

interface Props {
    work: Work,
    selectedStamp: WorkStamp | null
}
interface Emits {
    (e: "selectStamp", workStamp: WorkStamp): void
}
const props = defineProps<Props>();
const emits = defineEmits<Emits>();

const work = computed(() => props.work);
const workStamps = computed(() => {
    if (!work.value.work_stamps) {
        return null;
    }
    if (!work.value.work_stamps.length) {
        return null
    }

    // 時刻でソートして返す
    return work.value.work_stamps.sort((aStamp, bStamp) => {
        if (DateTime.fromSQL(aStamp.stamp_at) < DateTime.fromSQL(bStamp.stamp_at)) {
            return -1;
        } else {
            return 1;
        }
    });
});
const selectedStamp = computed(() => props.selectedStamp);

function selectStamp(workStamp: WorkStamp) {
    console.log("select stamp Sequence");
    emits("selectStamp", workStamp);
}
</script>
<style scoped>
.hour-memory {
    position: absolute;
    top: 2.25rem;
    left: -0.75rem;
    color: #555;
}
</style>