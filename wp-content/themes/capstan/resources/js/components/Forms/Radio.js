import {DefaultComponent} from 'libs';

export class Radio extends DefaultComponent{
	constructor(e, b){
        super(e, b);

        this.inputs = this.element.querySelectorAll("input");
        this.name = this.inputs[0].getAttribute("name");
        this.element.dataset.typed = 0;
        this.value = undefined;
    }

    attach() {
        this.inputs.forEach(el => el.addEventListener("click", this.onClick));
    }

    detach() {
        this.inputs.forEach(el => el.removeEventListener("click", this.onClick));
    }

    onClick = (e) => {
        this.element.dataset.typed = 1;
        this.value = e.currentTarget.value;
        this.emit("change", this, this.value);
    }
}