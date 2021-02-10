<template>
    <div style="border: 1px solid transparent;">
        <transition name="notif_block">
            <div v-if="!markedAll">

                <div class="row justify-content-center mb-4">
                    <div class="col-4">
                        <div class="mb-1 btn btn-outline-secondary btn-block disabled" v-if="markingAll">
                            <sync-loader :color="color" :size="size"></sync-loader>
                        </div>
                        <button class="btn btn-outline-secondary btn-block" @click="markAllAsRead" v-else>{{textbuttonall}}</button>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <transition-group name="notif">
                            <div class="row mb-2 text-secondary" v-for="notif in notifs" :key="notif.id">

                                <div class="col-4 font-weight-bold h5">
                                    <a class="text-secondary" :href="showuser(notif.data.id)">
                                        {{notif.data.name}} {{notif.data.surname}}
                                    </a>
                                </div>

                                <div class="col-4 h5">
                                    {{notif.data.email}}
                                </div>

                                <div class="col-4 h5">
                                    <div class="row justify-content-center">
                                        <div class="col-7">
                                            <div class="mb-1 btn btn-outline-secondary btn-block btn-sm disabled" v-if="marking && btnId===notif.id">
                                                <sync-loader :color="color" :size="size"></sync-loader>
                                            </div>
                                            <button class="btn btn-outline-secondary btn-block btn-sm" @click="markAsRead(notif.id)" v-else>{{textbutton}}</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </transition-group>
                    </div>
                </div>

                <div class="row border-top border-dark mt-3 mb-4"></div>

            </div>
        </transition>
    </div>
</template>

<script>
export default {
    data () {
        return {
            markedAll: false,
            markingAll: false,
            marking: false,
            color: '#bbb',
            size: '3px',
            ns: this.notifications,
            btnId: 0,
        }
    },
    props: [
        'showroute',
        'route',
        'notifications',
        'textbutton',
        'textbuttonall',
    ],
    computed: {
        notifs(){
            return JSON.parse(this.ns);
        }
    },
    methods: {
        markAsRead(id) {
            this.marking = true;
            this.btnId = id;
            axios
                .post(this.route, {
                    notificationId: id,
                })
                .then((response) => {
                    this.marking = false;
                    this.ns = JSON.stringify(this.notifs.filter(item => item.id !== id));
                    let newUsers = $('#newUsers');
                    if (this.ns.length === 2) {
                        this.markedAll = true;
                        newUsers.html('');
                    } else {
                        let count = parseInt(newUsers.html().substring(2, newUsers.html().length-1)) - 1;
                        newUsers.html(`(+${count})`);
                    }
                });
        },
        markAllAsRead() {
            this.markingAll = true;
            axios
                .post(this.route)
                .then((response) => {
                    this.markingAll = false;
                    this.ns = '';
                    this.markedAll = true;
                    $('#newUsers').html('');
                });
        },
        showuser(id){
            return this.showroute.replace('userid', id);
        }
    },
    mounted() {
        console.log('Component mounted.');
    }
}
</script>
