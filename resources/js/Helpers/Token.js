class Token {

    isValidFrom(token) {
        const payload = this.payload(token)
        if (payload) {
            return !!(payload.iss === "adminsite/api/auth/login" || "adminsite/api/auth/signup")
        }
    }

    payload(token) {
        const encoded_payload = token.split('.')[1]
        return this.decode(encoded_payload)
    }

    decode(encoded_payload) {
        return JSON.parse(atob(encoded_payload))
    }
}

export default Token = new Token()
