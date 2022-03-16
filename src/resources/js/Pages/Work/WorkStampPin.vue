<!-- 打刻時刻のピン -->
<template>
    <div
        ref="stampBoardElm"
        class="stamp-board"
        :class="{ 'select-stamp-board': isSelected }"
        :style="`left: ${setLeftPosition}%`"
        @click="selectStamp()"
        @mousedown.prevent="mouseDown()"
        @mouseup.prevent="mouseUp()"
        @mouseout="mouseUp()"
        @mousemove.prevent="moveOnHorizon($event)"
    >
        <div class="w-fit">
            <p class="w-full block">{{ stampCaption }}</p>
            <p>{{ workStampAt }}</p>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { computed, ref, watchEffect } from 'vue';

interface Props {
    workStamp: WorkStamp,
    workStampIdx: number,
    workStampsLen: number,
    selectedStamp: WorkStamp | null
    sequenceElm: HTMLDivElement | null
}
interface Emits {
    (e: "selectStamp", workStamp: WorkStamp): void
}
const props = defineProps<Props>();
const emits = defineEmits<Emits>();

const workStamp = computed(() => props.workStamp);
const workStampAt = ref(props.workStamp.stamp_at);
const selectedStamp = computed(() => props.selectedStamp);
const isSelected = computed(() => selectedStamp.value && selectedStamp.value.id == workStamp.value.id);
const sequenceElm = computed(() => props.sequenceElm);
const oneDateMin = Number(24) * Number(60);
const isMouseDown = ref<Boolean>(false);
const stampBoardElm = ref<HTMLDivElement | null>(null);

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
    console.log(wsi, wsl);

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

// スタンプを選択する
// WorkCardまで飛ばす
function selectStamp() {
    console.log("select stamp pin");
    emits("selectStamp", props.workStamp);
}

function mouseDown() {
    console.log("mouse donn");
    isMouseDown.value = true;
}

function mouseUp() {
    console.log("mouse up");
    isMouseDown.value = false;
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
    if (positionPercentage < 0) return "00:00";
    if (positionPercentage >= 100) return "23:59";

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
    return `${zeroPadHour}:${zeroPadMin}`;
}
</script>
<style scoped>
.stamp-board {
    inline-size: max-content;
    text-align: center;
    padding: 0.5rem;
    background-color: white;
    border-width: 2px;
    border-radius: 0.375rem;
    z-index: 10;
    position: absolute;
    top: -120%;
    left: 0%;
    transform: translate(-50%, 0);
    transform-origin: bottom left;
    cursor: pointer;
}

.stamp-board:hover {
    transform: scale(120%) translate(-50%, 0);
    z-index: 9999;
}

.stamp-board:before {
    content: "";
    background-color: inherit;
    position: absolute;
    bottom: -10%;
    left: 20%;
    height: 50%;
    width: 50%;
    transform: rotateZ(45deg);
    z-index: -1;
}

.select-stamp-board {
    transform: scale(150%) translate(-50%, 0);
    background-color: yellow;
}
.select-stamp-board:hover {
    transform: scale(150%) translate(-50%, 0);
}
</style>