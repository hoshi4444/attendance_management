<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">マイページ</h2>
        </template>

        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 打刻状況カード -->
            <div v-if="worksOfDays" class="space-y-4 sm:rounded-lg">
                <div
                    class="bg-white space-y-2 p-2 shadow-xl"
                    v-for="(work, day) in worksOfDays"
                    :key="day"
                >
                    <!-- 打刻日情報 -->
                    <div class="flex justify-between border-b-2 py-2 border-gray-300">
                        <div class="right-side-info">
                            <p>{{ day }}</p>
                        </div>
                        <div class="left-side-info">
                            <work-stamp-button @click="WorkTimeStamp()">Stamp Now Time!!</work-stamp-button>
                        </div>
                    </div>
                    <div class="py-2">
                        <work-sequence :work="work" />
                    </div>
                </div>
            </div>

            <div v-else class="bg-white shadow-xl py-4 sm:px-6 lg:px-8">
                <p>打刻記録がありません</p>
            </div>
        </div>
    </app-layout>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import WorkSequence from './WorkSequence.vue';
import WorkStampButton from './WorkStampButton.vue';

interface Props {
    works_of_days: {
        day: Work
    }
}

const props = defineProps<Props>();

const worksOfDays = computed(() => props.works_of_days);
</script>