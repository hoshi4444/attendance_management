<template>
    <div class="bg-white space-y-2 p-2 shadow-xl">
        <!-- 打刻日情報 -->
        <div class="flex justify-between border-b-2 py-2 border-gray-300">
            <div class="right-side-info">
                <p>{{ day }}</p>
            </div>
            <div class="left-side-info flex space-x-2">
                <work-stamp-green-button
                    :disabled="!selectedStamp"
                    @click="deleteSelectedStamp()"
                >Select Delete Stamp!!</work-stamp-green-button>
                <work-stamp-blue-button @click="createNewStamp()">Push New Stamp!!</work-stamp-blue-button>
                <work-stamp-red-button :disabled="!newStamp" @click="postNewStamp()">Save New Stamp!</work-stamp-red-button>
            </div>
        </div>

        <!-- 打刻グラフ -->
        <div class="pt-12 pb-10 px-2">
            <work-sequence :work="work" :selectedStamp="selectedStamp" @selectStamp="selectStamp" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import WorkSequence from './WorkSequence.vue';
import WorkStampRedButton from './WorkStampRedButton.vue';
import WorkStampGreenButton from './WorkStampGreenButton.vue';
import WorkStampBlueButton from './WorkStampBlueButton.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { DateTime } from "luxon";

interface Props {
    work: Work,
    day: String
}
interface Emits {
    (e: "selectStamp", workStamp: WorkStamp): void
}
const props = defineProps<Props>();
const emits = defineEmits<Emits>();

const work = computed(() => props.work);
const newStamp = ref<WorkStamp | null>(null)
const selectedStamp = ref<WorkStamp | null>(null);

const form = useForm({
    newStamp: <WorkStamp>{},
});

// 現在時刻のスタンプを追加
// TODO: 選択した日付の時刻を入力するため、午前0時移行だと失敗する
// TODO: 午前0時になったらリロードする処理が必要
function createNewStamp() {
    newStamp.value = {
        id: 0,
        work_id: work.value.id,
        stamp_at: DateTime.now().toFormat('H:m:s'),
        created_at: "",
        updated_at: "",
    };

    const newStampIdx = work.value.work_stamps.findIndex(stamp => stamp.id == 0);
    if (newStampIdx != -1) {
        work.value.work_stamps.splice(newStampIdx, 1);
    }

    work.value.work_stamps.push(newStamp.value);
}

// 打刻
function postNewStamp() {
    form.newStamp = newStamp.value!

    form.post('/work_stamps', {
        onSuccess: () => newStamp.value = null,
        onError: () => {
            alert("エラーが発生しました");
        }
    });
}

// 削除
function deleteSelectedStamp() {
    form.delete(`/work_stamps/${selectedStamp.value}`, {
        onSuccess: () => {
            selectedStamp.value = null;
        },
        onError: () => {
            alert("エラーが発生しました");
        }
    });
}

// スタンプを選択する
// WorkStampPinで発火してSequenceを経由する
function selectStamp(workStamp: WorkStamp) {
    console.log("select stamp Card");
    selectedStamp.value = workStamp;
}
</script>