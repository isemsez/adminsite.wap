import AppStorage from "./AppStorage"
import Token from "./Token"

class User {

    responseAfterLogin(res) {
        const access_token = res.data.access_token
        const user = res.data.user
        if (Token.isValidFrom(access_token)) {
            AppStorage.store(access_token, user)
        }
    }

    hasValidToken() {
        const stored_token = localStorage.getItem('token')
        if (stored_token) {
            return Token.isValidFrom(stored_token)
        }
        return false
    }

    isLoggedIn() {
        return this.hasValidToken()
    }

    name() {
        if (this.isLoggedIn()) {
            return localStorage.getItem('user').name
        }
        return false
    }

    id() {
        if (this.isLoggedIn()) {
            return localStorage.getItem('user').id
        }
        return false
    }
}

export default User = new User()
