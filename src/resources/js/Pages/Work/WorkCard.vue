<template>
    <div class="card-bg space-y-2 p-2 shadow-xl">
        <!-- 打刻日情報 -->
        <div class="flex justify-between border-b-2 py-2 border-gray-300">
            <!-- 左端 -->
            <div class="right-side-info">
                <p>{{ day }}</p>
            </div>

            <!-- 右端のボタン群 -->
            <div class="left-side-info flex space-x-2">
                <work-stamp-blue-button @click="pushNewStamp()">Push New Stamp!!</work-stamp-blue-button>
                <!-- 新しいスタンプを作成 -->
                <work-stamp-green-button
                    :disabled="!newStamp"
                    @click="postNewStamp()"
                >Save New Stamp!!</work-stamp-green-button>
                <!-- 時間の変更を保存 -->
                <work-stamp-green-button
                    :disabled="!selectedStamp"
                    @click="updateSelectedStamp()"
                >Save Change Stamp At!!</work-stamp-green-button>
                <!-- 削除 -->
                <work-stamp-red-button
                    v-if="selectedStamp && selectedStamp.id === 0"
                    :disabled="!selectedStamp"
                    @click="clearSelectedNewStamp()"
                >Clear New Stamp!!</work-stamp-red-button>
                <work-stamp-red-button
                    v-else
                    :disabled="!selectedStamp"
                    @click="deleteSelectedStamp()"
                >Delete Select Stamp!!</work-stamp-red-button>
            </div>
        </div>

        <!-- 打刻グラフ -->
        <div class="pt-12 pb-10 px-2">
            <work-sequence ref="workSequence">
                <template #workStamps>
                    <!-- 打刻スタンプ -->
                    <div v-if="workStamps">
                        <work-stamp
                            v-for="(workStamp, workStampIdx) in workStamps"
                            :key="workStamp.id"
                            :workStamp="workStamp"
                            :workStampIdx="workStampIdx"
                            :workStampsLen="workStamps.length"
                            :selectedStamp="selectedStamp"
                            :sequenceElm="sequenceElm"
                            @setSelectStamp="setSelectStamp"
                            @setUpdateStamp="setUpdateStamp"
                        />
                    </div>
                </template>
            </work-sequence>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import WorkSequence from './WorkSequence.vue';
import WorkStamp from './WorkStamp.vue';
import WorkStampRedButton from './WorkStampRedButton.vue';
import WorkStampGreenButton from './WorkStampGreenButton.vue';
import WorkStampBlueButton from './WorkStampBlueButton.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { DateTime } from "luxon";

// props
interface Props {
    work: Work,
    day: String
}
const props = defineProps<Props>();
const work = computed(() => props.work);
const workStamps = computed(() => {
    // 時刻でソートして返す
    return work.value.work_stamps.sort((aStamp, bStamp) => {
        if (DateTime.fromSQL(aStamp.stamp_at) < DateTime.fromSQL(bStamp.stamp_at)) {
            return -1;
        } else {
            return 1;
        }
    });
});

// vars
const newStamp = ref<WorkStamp | null>(null)
const selectedStamp = ref<WorkStamp | null>(null);
// 更新したスタンプ・一意にするためIDをキーのオブジェクトにする
const updateStamps = ref<{ [workStampId: number]: WorkStamp }>({});
const workSequence = ref<typeof WorkSequence | null>(null);
const sequenceElm = computed(() => {
    if (!workSequence.value) return;
    return workSequence.value.sequenceElm;
});


const form = useForm({
    newStamp: <WorkStamp>{},
    updateStamp: <WorkStamp>{},
});

// 現在時刻のスタンプを追加
// TODO: 選択した日付の時刻を入力するため、午前0時移行だと失敗する
// TODO: 午前0時になったらリロードする処理が必要
function pushNewStamp() {
    newStamp.value = {
        id: 0,
        work_id: work.value.id,
        stamp_at: DateTime.now().toFormat('H:m:s'),
        created_at: "",
        updated_at: "",
    };

    // 新しいスタンプは1人で良い…
    clearNewStamp();

    workStamps.value.push(newStamp.value);
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

// 打刻時刻更新
function updateSelectedStamp() {
    form.updateStamp = updateStamps.value![selectedStamp.value!.id];

    form.put(`/work_stamps/${form.updateStamp.id}`, {
        onSuccess: () => {
            setSelectStamp(form.updateStamp);
            clearUpdateStamps();
        },
        onError: () => {
            alert("エラーが発生しました");
        }
    });
}

// 削除
function deleteSelectedStamp() {
    form.delete(`/work_stamps/${selectedStamp.value!.id}`, {
        onSuccess: () => {
            selectedStamp.value = null;
        },
        onError: () => {
            alert("エラーが発生しました");
        }
    });
}

// 新しいスタンプをクリア
function clearNewStamp() {
    const newStampIdx = work.value.work_stamps.findIndex(stamp => stamp.id == 0);
    if (newStampIdx != -1) {
        workStamps.value.splice(newStampIdx, 1);
    }
}

// 選択中の新しいスタンプをクリア
function clearSelectedNewStamp() {
    setSelectStamp(selectedStamp.value!);
    clearNewStamp();
}

// スタンプの更新をクリア
function clearUpdateStamps() {
    updateStamps.value = {};
}

// WorkStampからスタンプ選択の通知を受け取る
// 同じスタンプが渡されたら解除する
function setSelectStamp(selectStamp: WorkStamp) {
    console.log("Select Stamp Card");
    if (selectedStamp.value && selectedStamp.value.id == selectStamp.id) {
        console.log("Select Clear");
        selectedStamp.value = null;
    } else {
        selectedStamp.value = selectStamp;
    }
}

// WorkStampからスタンプ更新の通知を受け取る
// オブジェクトに格納する
function setUpdateStamp(updateStamp: WorkStamp) {
    console.log("Update Stamp Card");
    const workStampId: number = Number(updateStamp.id);
    updateStamps.value[workStampId] = updateStamp;
}
</script>