window.onload = function () {
    const { createApp, ref, onMounted, computed } = Vue;

    createApp({
        setup() {
            const users = ref([]);
            const project = ref(null);
            const term = ref('');
            const selectedUsers = ref([]);

            onMounted(() => {
                
                let el = document.getElementById('users');
                let rawData = el.dataset.users;

                try {
                    users.value = JSON.parse(rawData);
                } catch (e) {
                    console.error('Error parsing Blade Data:', e);
                }

                el = document.getElementById('project');
                rawData = el.dataset.project;

                try {
                    project.value = JSON.parse(rawData);
                } catch (e) {
                    console.error('Error parsing Blade Data:', e);
                } 
            });


            const filteredUsers = computed(() => {
                if (!term.value) return [];

                return users.value.filter(user =>
                    user.name.toLowerCase().includes(term.value.toLowerCase()) ||
                    user.email.toLowerCase().includes(term.value.toLowerCase())
                ).filter(user =>
                    !selectedUsers.value.find(u => u.id === user.id)
                )
                .slice(0, 4);
            });

            return {
                users,
                term,
                filteredUsers,
                selectedUsers,
                project
            };
        }
    }).mount('#app');

    document.getElementById('app').style.display = 'block';

    //Tasks Chart
    const to_do = parseInt(document.getElementById('to_do').value);
    const stoped = parseInt(document.getElementById('stoped').value);
    const in_progress = parseInt(document.getElementById('in_progress').value);
    const done = parseInt(document.getElementById('done').value);

    const getCSSVar = (varName) => getComputedStyle(document.documentElement).getPropertyValue(varName).trim(); //BUSCAR TEXT COLOR
    const textColor = getCSSVar('--text-color');

    const taskChart = new Chart('taskChart', {
        type: 'doughnut',
        data: {
            labels: ["To Do", "Stoped", "In Progress", "Done"],
            datasets: [
                {
                    data: [to_do, stoped, in_progress, done],
                    backgroundColor: ['#ccc','#cf142b', '#f3c242', '#228B22'],
                    borderColor: 'transparent'
                },
                
            ],
        },
        options: {
            plugins: {
                legend: {
                    labels: {
                        color: textColor
                    }
                }
            }
        }
    });
}

//Clock
//
//
function updateClock() {
    const now = new Date();

    const day = now.getDate();
    const suffix = (day % 10 === 1 && day !== 11) ? 'st' :
                   (day % 10 === 2 && day !== 12) ? 'nd' :
                   (day % 10 === 3 && day !== 13) ? 'rd' : 'th';

    const dateOptions = { day: 'numeric', month: 'long', year: 'numeric' };
    const formattedDate = now.toLocaleDateString('en-GB', dateOptions)
        .replace(/(\d+) (\w+) (\d+)/, `$1${suffix} $2, $3`);

    const timeOptions = { hour: '2-digit', minute: '2-digit', hour12: false };
    const formattedTime = now.toLocaleTimeString('en-GB', timeOptions);

    const timezoneOptions = { timeZoneName: 'short' };
    const timezone = new Intl.DateTimeFormat('en-GB', timezoneOptions)
        .formatToParts(now)
        .find(part => part.type === 'timeZoneName').value;

    const weekday = now.toLocaleDateString('en-GB', { weekday: 'long' });

    document.getElementById('day').innerText = `${formattedDate} | ${formattedTime} (${timezone})`;
    document.getElementById('dayWeek').innerText = `${weekday}`;
}

updateClock();
setInterval(updateClock, 1000);
//
//
//Clock End