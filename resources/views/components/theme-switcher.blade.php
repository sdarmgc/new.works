<div class="container flex justify-between items-center bg-gray-100 dark:bg-gray-900/50 p-1 rounded-full border border-gray-200 dark:border-gray-800"
    x-data="{
        theme: localStorage.theme || 'system',
        init() {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (this.theme === 'system') {
                    if (e.matches) document.documentElement.classList.add('dark');
                    else document.documentElement.classList.remove('dark');
                }
            });
        },
        setTheme(val) {
            this.theme = val;
            if (val === 'system') {
                localStorage.removeItem('theme');
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } else {
                localStorage.theme = val;
                if (val === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        }
    }" >

    <!-- Light Button -->
    <button type="button" @click="setTheme('light')" class="p-1.5 rounded-full transition-colors duration-200 focus:outline-none" :class="theme === 'light' ? 'bg-white dark:bg-gray-800 text-amber-500 shadow-sm' : 'text-gray-400 hover:text-gray-500 dark:hover:text-gray-300'">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21M4.22 4.22l1.59 1.59m12.38 12.38l1.59 1.59M3 12h2.25m13.5 0H21M5.81 18.19l1.59-1.59m12.38-12.38l1.59-1.59M12 7.5a4.5 4.5 0 110 9 4.5 4.5 0 010-9z" /></svg>
    </button>
    <!-- System Button -->
    <button type="button" @click="setTheme('system')" class="p-1.5 rounded-full transition-colors duration-200 focus:outline-none" :class="theme === 'system' ? 'bg-white dark:bg-gray-800 text-indigo-500 shadow-sm' : 'text-gray-400 hover:text-gray-500 dark:hover:text-gray-300'">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25M3 5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25z" /></svg>
    </button>
    <!-- Dark Button -->
    <button type="button" @click="setTheme('dark')" class="p-1.5 rounded-full transition-colors duration-200 focus:outline-none" :class="theme === 'dark' ? 'bg-white dark:bg-gray-800 text-indigo-400 shadow-sm' : 'text-gray-400 hover:text-gray-500 dark:hover:text-gray-300'">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
    </button>
</div>
