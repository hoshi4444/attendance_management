<template>
    <div class="bg-white space-y-2 p-2 shadow-xl">
        <!-- 打刻日情報 -->
        <div class="flex justify-between border-b-2 py-2 border-gray-300">
            <div class="right-side-info">
                <p>{{ day }}</p>
            </div>
            <div class="left-side-info flex space-x-2">
                <work-stamp-red-button
                    :disabled="newStampIdx == -1"
                    @click="postNewStamp()"
                >Save New Stamp!</work-stamp-red-button>
                <work-stamp-blue-button @click="createNewStamp()">Push New Stamp!!</work-stamp-blue-button>
            </div>
        </div>

        <!-- 打刻グラフ -->
        <div class="pt-12 pb-10 px-2">
            <work-sequence :work="work" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import WorkSequence from './WorkSequence.vue';
import WorkStampBlueButton from './WorkStampBlueButton.vue';
import WorkStampRedButton from './WorkStampRedButton.vue';
import { DateTime } from "luxon";

interface Props {
    work: Work,
    day: String
}

const props = defineProps<Props>();
const work = computed(() => props.work);
const newStampIdx = computed(() => work.value.work_stamps.findIndex(stamp => stamp.id == 0));

// 現在時刻のスタンプを追加
// TODO: 選択した日付の時刻を入力するため、午前0時移行だと失敗する
// TODO: 午前0時になったらリロードする処理が必要
function createNewStamp() {
    const newStamp: WorkStamp = {
        id: 0,
        stamp_at: DateTime.now().toFormat('H:m:s'),
        created_at: "",
        updated_at: "",
    };

    if (newStampIdx.value != -1) {
        work.value.work_stamps.splice(newStampIdx.value, 1);
    }

    work.value.work_stamps.push(newStamp);
}
</script>