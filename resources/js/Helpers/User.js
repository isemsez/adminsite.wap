import Token from './Token'
import AppStorage from "./AppStorage";

class User {
    responseAfterLogin(res) {
        const jwt = res.data.access_token
        const user = res.data.user
        if (Token.isValidFrom(jwt)) {
            AppStorage.store(jwt, JSON.stringify(user))
        }
    }

    loggedIn() {
        return this.hasToken()
    }

    hasToken() {
        const token = localStorage.getItem('token')
        return !!token
    }

    getUserObj() {
        return JSON.parse(localStorage.getItem('user'))
    }

    uName() {
        return this.getUserObj()['name']
    }

    clear() {
        localStorage.removeItem('token')
        localStorage.removeItem('user')
    }
}

export default User = new User()
