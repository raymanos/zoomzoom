$(document).ready(function(){

    $("#regform").validate({
        onsubmit: false,
        invalidHandler: function(event, validator) {
            console.log(event);
            console.log(validator);
        },

       rules:{

            login:{
                required: true,
                minlength: 4,
                maxlength: 16,
            },

            pass:{
                required: true,
                minlength: 6,
                maxlength: 16,
            },
            pass2:{
                required: true,
                minlength: 6,
                maxlength: 16,
                equalTo: "#password",
            },
            email: {
                required: true,
                email: true
            },
       },

       messages:{

            login:{
                required: "Это поле обязательно для заполнения",
                minlength: "Логин должен быть минимум 4 символа",
                maxlength: "Максимальное число символо - 16",
            },

            pass:{
                required: "Это поле обязательно для заполнения",
                minlength: "Пароль должен быть минимум 6 символа",
                maxlength: "Пароль должен быть максимум 16 символов",
            },

            pass2:{
                required: "Это поле обязательно для заполнения",
                minlength: "Пароль должен быть минимум 6 символа",
                maxlength: "Пароль должен быть максимум 16 символов",
                equalTo: "Пароли должны совпадать",
            },

            email:{
                required: "Это поле обязательно для заполнения(подтверждение не требуется)",
                email: "E-mail должен быть корректен",
            },

       }

    });

    $("#addform").validate({
        rules:{

            name_en: {
                required: true,
            },
            name_ru: {
                required: true,
            },
        },

       messages:{

            name_en:{
                required: "Это поле обязательно для заполнения",
            },
            name_ru:{
                required: "Это поле обязательно для заполнения",
            },
        }
    });
});
