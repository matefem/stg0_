import {DefaultComponent} from 'libs';

export class Checkbox extends DefaultComponent{
	constructor(e, b){
        super(e, b);

        this.input = this.element.querySelector("input");
        this.name = this.input.getAttribute("name");
        this.element.dataset.typed = 0;
        this.value = undefined;
    }

    attach() {
        this.input.addEventListener("click", this.onClick);
    }

    detach() {
        this.input.removeEventListener("click", this.onClick);
    }

    onClick = () => {
        this.element.dataset.typed = 1;
        this.value = this.input.value == "on";
        this.emit("change", this, this.value);
    }
}