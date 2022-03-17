<template>
    <button class="work-time-stamp-btn focus:ring">
        <slot />
    </button>
</template>
<style scoped>
.work-time-stamp-btn {
    font-weight: bold;
    color: rgba(0, 255, 0, 0.418);
    border-left: 5px solid rgba(0, 255, 0, 0.418);
    border-right: 5px solid rgba(0, 255, 0, 0);
    padding: 3px 5px;
}

.work-time-stamp-btn:disabled {
    font-weight: bold;
    color: rgba(0, 255, 0, 0.418);
    border-left: 5px solid rgba(0, 255, 0, 0.418);
    border-right: 5px solid rgba(0, 255, 0, 0);
    padding: 3px 5px;
    background-color: rgb(151, 151, 151);
}

.work-time-stamp-btn:hover:not([disabled]) {
    animation: slide-bg 0.5s ease-in forwards;
}

@keyframes slide-bg {
    0% {
        background: linear-gradient(
            90deg,
            rgba(0, 255, 0, 0.418) 1%,
            white 99%
        );
        color: rgba(0, 255, 0, 0.418);
        border-left: 5px solid rgba(0, 255, 0, 0.418);
        border-right: 5px solid rgba(0, 255, 0, 0);
    }
    100% {
        background: linear-gradient(
            90deg,
            rgba(0, 255, 0, 0.418) 100%,
            white 0%
        );
        color: white;
        border-left: 5px solid rgba(0, 255, 0, 0);
        border-right: 5px solid rgba(0, 255, 0, 0.418);
    }
}
</style>