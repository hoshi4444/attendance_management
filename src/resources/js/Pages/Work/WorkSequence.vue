<!-- 背景のグラフ表示するだけのコンポーネント -->
<template>
    <!-- 打刻時刻ボード -->
    <div
        ref="sequenceElm"
        class="flex relative items-end bg-blue-400 border-2 mt-6 h-14 rounded-sm"
    >
        <!-- 打刻スタンプ -->
        <div>
            <slot name="workStamps" />
        </div>

        <!-- 1時間間隔メモリ -->
        <div
            class="flex items-end justify-around px-2 border-l-2 cursor-pointer border-white h-8 w-1/12 relative"
            v-for="hourScale in Array(24).keys()"
            :key="hourScale"
        >
            <p class="hour-memory">{{ hourScale }}</p>
            <!--  15間隔メモリ -->
            <div
                class="border-l-2 cursor-pointer border-white h-4 w-1/12"
                v-for="fifteenMinScale in 3"
                :key="fifteenMinScale"
            ></div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { ref } from 'vue';

const sequenceElm = ref(null);

defineExpose({
    sequenceElm
});
</script>
<style scoped>
.hour-memory {
    position: absolute;
    top: 2.25rem;
    left: -0.75rem;
    color: #555;
}
</style>