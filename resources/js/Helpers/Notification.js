class Notification {

    success() {
        new Noty({
            type: "success",
            layout: "topRight",
            text: "Успешно!",
            timeout: 1000,
        }).show()
    }

    alert() {
        new Noty({
            type: "alert",
            layout: "topRight",
            text: "Вы уверены?",
            timeout: 1000,
        }).show()
    }

    error(text='') {
        new Noty({
            type: "error",
            layout: "topRight",
            text: text ?? 'Ошибка!',
            timeout: 5000,
        }).show()
    }

    warning(text='') {
        new Noty({
            type: "warning",
            layout: "topRight",
            text: text ?? "Что-то неправильно!",
            timeout: 5000,
        }).show()
    }

    image_bigger_1mb() {
        console.log('Notification')
        new Noty({
            type: "error",
            layout: "topRight",
            text: "Фото должно быть не больше 1Мб.",
            timeout: 5000,
        }).show()
    }

}

export default Notification = new Notification()
