import {DefaultComponent, Env} from 'libs';
import { debounce } from 'throttle-debounce';

export class Text extends DefaultComponent{
	constructor(e, b){
        super(e, b);

        this.input = this.element.querySelector("input");
        this.name = this.input.getAttribute("name");
        this.element.dataset.typed = 0;
        this.value = undefined;

        this.eye = this.element.querySelector(".icon-eye"); // Champs password

        this.onKeyUp = debounce(500, this.onKeyUp.bind(this));
    }

    attach() {
        this.input.addEventListener("keyup", this.onKeyUp);
        if (this.eye) this.eye.addEventListener("click", this.onClickEye);
    }

    detach() {
        this.input.removeEventListener("keyup", this.onKeyUp);
        if (this.eye) this.eye.addEventListener("click", this.onClickEye);
    }

    onClickEye = (e) => {
        let input = e.currentTarget.parentElement.querySelector("input");
        const type = (input.getAttribute("type") == "password") ? "text" : "password";
        input.setAttribute("type", type);
        if (type == "text") {
            this.eye.classList.remove("icon-eye");
            this.eye.classList.add("icon-eye-close");
        }
        else {
            this.eye.classList.remove("icon-eye-close");
            this.eye.classList.add("icon-eye");
        }
    }

    onKeyUp() {
        if (this.input.value == this.value) {return;}
        this.element.dataset.typed = 1;
        this.value = this.input.value;

        this.emit("change", this, this.value);
    }
}