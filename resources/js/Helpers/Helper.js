class Helper {

    warn( err_data ) {
        let log_title
        let log_message = ''

        if ( typeof err_data == "string") {

            let tmp = err_data.replace(/\r/, '')
            log_message = tmp.replace(/(.|\n)+<title>(.*)<\/title>(.|\n)+?/, "$2")

        } else {
            if ( 'error' in err_data ) {
                log_title = err_data.error + ': '
            } else if ( 'exception' in err_data ) {
                log_title = err_data.exception + ': '
            }

            if ( 'message' in err_data ) {
                log_message = err_data.message
            }
        }
        console.log( '!!! ' + log_title + log_message )

        Toast.fire({
            title: log_title ?? null,
            text: log_message,
        })
    }

}

export default Helper = new Helper()
