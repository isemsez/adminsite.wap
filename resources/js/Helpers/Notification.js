class Notification {

    success(txt = "Успешно!") {
        new Noty({
            type: "success",
            layout: "topRight",
            text: txt,
            timeout: 3000,
        }).show()
    }

    alert() {
        new Noty({
            type: "alert",
            layout: "topRight",
            text: "Вы уверены?",
            timeout: 5000,
        }).show()
    }

    error(txt= 'Ошибка!') {
        new Noty({
            type: "error",
            layout: "topRight",
            text: txt,
            timeout: 5000,
        }).show()
    }

    warning(txt= "Что-то неправильно!") {
        new Noty({
            type: "warning",
            layout: "topRight",
            text: txt,
            timeout: 5000,
        }).show()
    }

}

export default Notification = new Notification()
