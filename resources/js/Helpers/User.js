import Token from './Token'
import AppStorage from "./AppStorage";

class User {
    responseAfterLogin(res) {
        const jwt = res.data.access_token
        let user = res.data.user
        if (Token.isValidFrom(jwt)) {
            AppStorage.store(jwt, JSON.stringify(user))
        }
    }

    responseAfterRegistration(res) {

    }

    responseAfterForgot(res) {

    }

    responseAfter(res) {

    }

    loggedIn() {
        return this.hasToken()
    }

    hasToken() {
        const token = localStorage.getItem('token')
        return !!token
    }

    uName() {
        return localStorage.getItem('user').name
    }
}

export default User = new User()
