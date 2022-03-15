<template>
    <app-layout title="Dashboard">
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 打刻状況カード -->
            <div v-if="worksOfDays" class="space-y-4 sm:rounded-lg">
                <div v-for="(work, day) in worksOfDays" :key="day">
                    <work-card :work="work" :day="day" />
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
import WorkCard from './WorkCard.vue';

interface Props {
    works_of_days: {
        day: Work
    }
}

const props = defineProps<Props>();

const worksOfDays = computed(() => props.works_of_days);

</script>