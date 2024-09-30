class Tools {
    validateEmail = (email) => {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    formIsValid = (requiredFields, formData) => {
        var errors = false;

        // for(var pair of formData.entries()) {
        //     console.log(pair[0]+ ', '+ pair[1]);
        //  }

        requiredFields.forEach(el => {
            let field = el.querySelector("input");
            const type = field.getAttribute("type");
            const name = field.getAttribute("name");

            if (type == "text" || type == "hidden" || type == "checkbox" || type == "password") {
                if (!formData.get(name)) {
                    if (el.dataset.typed == "1") el.classList.add("error");
                    errors = true;
                }
                else {el.classList.remove("error");}
            }
            else if (type == "email") {
                if (!this.validateEmail(formData.get(name))) {
                    if (el.dataset.typed == "1") el.classList.add("error");
                    errors = true;
                }
                else {el.classList.remove("error");}
            }

        });

        return !errors;
    }

    isVisible = (e) => {
        return !!( e.offsetWidth || e.offsetHeight || e.getClientRects().length );
    }
}

export default new Tools();