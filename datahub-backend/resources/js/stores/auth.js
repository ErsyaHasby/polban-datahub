import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: null, // JANGAN BACA DARI LOCALSTORAGE
        isLoggedIn: false,
    }),

    getters: {
        isAdmin: (state) => state.user?.role === 'admin',
        isParticipant: (state) => state.user?.role === 'participant',
    },

    actions: {
        async login(email, password) {
            try {
                const response = await axios.post('/login', {
                    email,
                    password,
                })

                this.token = response.data.token
                this.user = response.data.user
                this.isLoggedIn = true

                // KITA HAPUS PENYIMPANAN KE LOCALSTORAGE AGAR TIDAK PERSISTEN
                // localStorage.setItem('token', response.data.token) <--- HAPUS INI

                // Set header untuk request selanjutnya (selama belum refresh)
                axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`

                return { success: true }
            } catch (error) {
                return {
                    success: false,
                    message: error.response?.data?.message || 'Login failed',
                }
            }
        },

        async logout() {
            const tokenToUse = this.token

            // Clear state langsung
            this.user = null
            this.token = null
            this.isLoggedIn = false
            delete axios.defaults.headers.common['Authorization']

            // Logout API (Optional, best effort)
            if (tokenToUse) {
                try {
                    await axios.post('/logout', {}, {
                        headers: { Authorization: `Bearer ${tokenToUse}` }
                    })
                } catch (error) {
                    console.error('Logout API error:', error)
                }
            }
        },

        // Fetch User dihapus logic localStorage-nya
        async fetchUser() {
            if (!this.token) return

            try {
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
                const response = await axios.get('/user')
                this.user = response.data
                this.isLoggedIn = true
            } catch (error) {
                this.logout()
            }
        },
    },
})