<!-- 打刻時刻のピン -->
<template>
    <div
        v-if="workStampAt"
        class="stamp-board"
        :class="{ 'select-stamp-board': isSelected }"
        @click="selectStamp()"
    >
        <div class="w-fit">
            <p class="w-full block">{{ stampCaption }}</p>
            <p>{{ workStampAt }}</p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    workStamp: WorkStamp,
    workStampIdx: number,
    workStampsLen: number,
    hourScale: number,
    fifteenMinScale: number
    selectedStamp: WorkStamp | null
}
interface Emits {
    (e: "selectStamp", workStamp: WorkStamp): void
}
const props = defineProps<Props>();
const emits = defineEmits<Emits>();

const workStamp = computed(() => props.workStamp);
const hourScale = computed(() => props.hourScale);
const fifteenMinScale = computed(() => props.fifteenMinScale);
const selectedStamp = computed(() => props.selectedStamp);
const isSelected = computed(() => selectedStamp.value && selectedStamp.value.id == workStamp.value.id);

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

// 渡された時間が近かったら時刻を表示する
const workStampAt = computed(() => {
    // 打刻時刻の文字列を桁ごとの数値にする
    const [hour, min, sec]: Array<number> = [...props.workStamp.stamp_at.split(':').map(timeStr => Number(timeStr))];

    if (hour != hourScale.value) return null;

    // 範囲内の分数なら時刻を返す
    const isInMinutes = min >= (fifteenMinScale.value * 15) && min <= ((fifteenMinScale.value + 1) * 15);

    if (isInMinutes) {
        return props.workStamp.stamp_at;
    } else {
        return null;
    }
})

// スタンプを選択する
// WorkCardまで飛ばす
function selectStamp() {
    console.log("select stamp pin");
    emits("selectStamp", props.workStamp);
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
    top: -7rem;
    position: absolute;
    left: -4.5vw;
    transform-origin: bottom;
}

.stamp-board:hover {
    transform: scale(120%);
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
    transform: scale(150%);
    background-color: yellow;
}
.select-stamp-board:hover {
    transform: scale(150%);
}
</style>