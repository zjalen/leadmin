<template>
    <div class="main" :style="{background: color}">
        <el-row class="row">
            <div class="grid-content-left">
                <i class="fa" :class="icon">

                </i>
            </div>
            <div class="grid-content-right">
                <div class="title">{{title}}</div>
                <div class="description">{{description}}</div>
            </div>
        </el-row>
    </div>
</template>

<script>
    export default {
        props: ['color', 'icon', 'title', 'description'],
    }
</script>

<style scoped>
    .main {
        height: 100px;
        width: 100%;
        border-radius: 4px;
    }
    .row {
        width: 100%;
        display: flex;
        background: none;
    }
    .main :hover {
        background: #58C9F3;
    }
    .grid-content-left {
        height: 100px;
        width: 111px;
        -webkit-border-radius: 4px 0 0 4px;
        -moz-border-radius: 4px 0 0 4px;
        border-radius: 4px 0 0 4px;
        color: #ffffff69;
        font-size: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #0000000f;
    }
    .grid-content-right {
        flex: 1;
        height: 100px;
        -webkit-border-radius: 0 4px 4px 0;
        -moz-border-radius: 0 4px 4px 0;
        border-radius: 0 4px 4px 0;
        font-size: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: #fff;
    }

    .title {
        font-size: 30px;
        font-weight: 500;
    }

    .description {
        font-size: 15px;
    }
</style>