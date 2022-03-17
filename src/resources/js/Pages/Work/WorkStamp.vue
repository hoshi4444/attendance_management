<!-- 打刻時刻のピン -->
<template>
    <div
        ref="stampBoardElm"
        class="stamp-board"
        :class="{ 'select-stamp-board': isSelected, 'changed-stamp-board': isChangedStamp }"
        :style="`left: ${setLeftPosition}%`"
        @click="setSelectStamp()"
        @mousedown.prevent="mouseDown()"
        @mouseup.prevent="mouseUp()"
        @mousemove.prevent="moveOnHorizon($event)"
    >
        <div>
            <p class="w-full text-sm">{{ stampCaption }}</p>
            <!-- 更新時 -->
            <div v-if="isChangedStamp" class="text-xs my-1">
                <div class="flex justify-between">
                    <p>Origin At:</p>
                    <p>{{ originWorkStampAt }}</p>
                </div>
                <div class="flex justify-between">
                    <p>New At:</p>
                    <p>{{ workStampAt }}</p>
                </div>
            </div>

            <!-- 通常時 -->
            <div v-if="!isChangedStamp" class="text-xs my-1">
                <div class="flex justify-between">
                    <p>Stamp At:</p>
                    <p>{{ workStampAt }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { computed, ref, watchEffect } from 'vue';

interface Props {
    workStamp: WorkStamp,
    workStampIdx: number,
    workStampsLen: number,
    selectedStamp: WorkStamp | null,
    sequenceElm: HTMLDivElement | null,
}
interface Emits {
    (e: "setSelectStamp", workStamp: WorkStamp): void
    (e: "setUpdateStamp", workStamp: WorkStamp): void
}
const props = defineProps<Props>();
const emits = defineEmits<Emits>();

const workStamp = computed(() => props.workStamp);
const workStampAt = ref(props.workStamp.stamp_at);
const originWorkStampAt = ref(props.workStamp.stamp_at);
const selectedStamp = computed(() => props.selectedStamp);
const isSelected = computed(() => selectedStamp.value && selectedStamp.value.id == workStamp.value.id);
const sequenceElm = computed(() => props.sequenceElm);
const oneDateMin = Number(24) * Number(60);
const isMouseDown = ref<Boolean>(false);
const stampBoardElm = ref<HTMLDivElement | null>(null);
const isChangedStamp = computed(() => workStamp.value.stamp_at != workStampAt.value)
const updateStamp = ref<WorkStamp | null>(null);

// 時刻に合わせて左辺の位置を設定する
const setLeftPosition = computed(() => {
    // スタンプ時刻が1日の全分数:1440分の内、何%か求める
    // 例:) 12:35 => 755 / 1440 = 52%
    const [hour, min, sec] = [...workStamp.value.stamp_at.split(":").map(timeStr => Number(timeStr))];
    const workStampAtToMin = hour * Number(60) + min;
    const stampAtPercentage = Math.floor((workStampAtToMin / oneDateMin) * 100);

    return stampAtPercentage;
});

// 当日何番目のスタンプかによって分岐するキャプション
const stampCaption = computed(() => {
    // 簡単のため略字
    const wsi = props.workStampIdx;
    const wsl = props.workStampsLen;

    // TODO: 後でID式にしてタイプごとのコンポーネントにする
    // TODO: タイプを変更できるとおもろい
    if (props.workStamp.id == Number(0)) {
        return "It's New Stamp!";
    }
    if (wsi == Number(0)) {
        return "WorkStart!";
    }
    if (wsi + Number(1) == wsl) {
        return "WorkEnd!";
    }
    return "Break!";
});

// スタンプの選択をWorkCardに通知する
function setSelectStamp() {
    console.log("select stamp pin");
    emits("setSelectStamp", props.workStamp);
}

// スタンプの更新をWorkCardに通知する
function setUpdateStamp() {
    console.log("update stamp pin");
    emits("setUpdateStamp", updateStamp.value!);
}

function mouseDown() {
    console.log("mouse donn");
    isMouseDown.value = true;
}

function mouseUp() {
    console.log("mouse up");
    isMouseDown.value = false;

    // 更新を通知
    updateStamp.value = { ...workStamp.value };
    updateStamp.value.stamp_at = workStampAt.value;
    setUpdateStamp();
}

// initLeft ~ (100 - initLeft)間隔でスタンプを水平に動かす
function moveOnHorizon(event: MouseEvent) {
    watchEffect(() => {
        if (!event) return;
        if (!sequenceElm.value) return;
        if (!stampBoardElm.value) return;
        if (!isMouseDown.value) return;

        // シーケンス左端からどのくらいの位置にいるのか算出
        // 0なら左端
        const newLeftPixel = event.x - sequenceElm.value.getBoundingClientRect().left;

        // 位置をパーセンテージに直す
        const newLeftPercentage = Math.floor((newLeftPixel / sequenceElm.value!.offsetWidth) * 100);

        // leftの値を更新して動かす
        if (newLeftPercentage < 0) {
            stampBoardElm.value.style.left = `0%`
        } else if (newLeftPercentage >= 100) {
            stampBoardElm.value.style.left = `100%`
        } else {
            stampBoardElm.value.style.left = `${newLeftPercentage}%`
        }

        // パーセンテージを時間の文字列に直して時間を更新する
        workStampAt.value = convertPositionPercentageToStampAtStr(newLeftPercentage);
    });
}

// 位置のパーセンテージを逆算して時間の文字列にして返す
function convertPositionPercentageToStampAtStr(positionPercentage: number) {
    if (positionPercentage < 0) return "00:00:00";
    if (positionPercentage >= 100) return "23:59:00";

    // 分数に直す
    let newMin = Math.floor(oneDateMin * (positionPercentage / 100));

    // 時間と分にする
    let newHour = Number(0);
    while (true) {
        if (newMin < Number(60)) {
            break;
        } else {
            newMin = newMin - Number(60);
            newHour++;
        }
    }

    const zeroPadHour = String(newHour).padStart(2, "0");
    const zeroPadMin = String(newMin).padStart(2, "0");
    return `${zeroPadHour}:${zeroPadMin}:00`;
}
</script>
<style scoped>
.stamp-board {
    inline-size: max-content;
    text-align: center;
    padding: 0.5rem;
    background-color: white;
    border-radius: 0.375rem;
    z-index: 10;
    position: absolute;
    top: -120%;
    left: 0%;
    transform: translate(-50%, 0);
    transform-origin: bottom left;
    cursor: pointer;
    width: 12%;
    border-width: 2px;
}

.stamp-board:before {
    content: "";
    background-color: inherit;
    position: absolute;
    bottom: 0%;
    left: 20%;
    height: 50%;
    width: 50%;
    transform: rotateZ(45deg);
    z-index: -99;
}

.select-stamp-board {
    background-color: yellow;
}

.changed-stamp-board {
    top: -8vh;
}
</style>