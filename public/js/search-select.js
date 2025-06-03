window.onload = function () {
    const { createApp, ref, onMounted, computed } = Vue;

    createApp({
        setup() {
            const users = ref([]);
            const project = ref(null);
            const term = ref('');
            const selectedUser = ref(null);

            onMounted(() => {
                let el = document.getElementById('users');
                let rawData = el.dataset.users;

                try {
                    users.value = JSON.parse(rawData);
                } catch (e) {
                    console.error('Error parsing users:', e);
                }

                el = document.getElementById('project');
                rawData = el.dataset.project;

                try {
                    project.value = JSON.parse(rawData);
                } catch (e) {
                    console.error('Error parsing project:', e);
                }

                const taskUserEl = document.getElementById('task-user');
                if (taskUserEl) {
                    try {
                        selectedUser.value = JSON.parse(taskUserEl.dataset.taskUser);
                        return;
                    } catch (e) {
                        console.error('Error parsing task user:', e);
                    }
                }

                const oldUserEl = document.getElementById('old-user-id');
                if (oldUserEl) {
                    const oldUserId = oldUserEl.dataset.oldUserId;
                    const match = users.value.find(u => String(u.id) === oldUserId);
                    if (match) {
                        selectedUser.value = match;
                    }
                }
            });

            const filteredUsers = computed(() => {
                if (!term.value || selectedUser.value) return [];

                return users.value.filter(user =>
                    user.name.toLowerCase().includes(term.value.toLowerCase()) ||
                    user.email.toLowerCase().includes(term.value.toLowerCase())
                ).slice(0, 4);
            });

            const selectUser = (user) => {
                selectedUser.value = user;
                term.value = '';
            };

            const removeUser = () => {
                selectedUser.value = null;
            };

            return {
                users, term, project, selectedUser, filteredUsers,
                selectUser, removeUser,
            };
        }
    }).mount('#app');
}