import {DefaultComponent} from 'libs';
import tools from '../../../tools/Tools';
import ajaxFetcher from '../../../tools/AjaxFetcher';

export class AccountUpdate extends DefaultComponent {
    constructor(e, b){
        super(e, b);

        this.requiredFields = this.element.querySelectorAll(".form-item.required");
        this._formData = new FormData(this.element);

        this.buttonResetPassword = this.element.querySelector(".button-change-password");

        this.buttons = this.element.querySelectorAll("[name='form-button']");
    }

    attach() {
        this.requiredFields.forEach(el => this.bootstrap.components.get(el).on("change", this.onFormItemChange));
        this.element.addEventListener("submit", this.onSubmit);
        if (this.buttonResetPassword) this.buttonResetPassword.addEventListener("click", this.onClickButtonReset);
    }

    detach() {
        this.requiredFields.forEach(el => this.bootstrap.components.get(el).off("change", this.onFormItemChange));
        this.element.removeEventListener("submit", this.onSubmit);
        if (this.buttonResetPassword) this.buttonResetPassword.removeEventListener("click", this.onClickButtonReset);
    }

    onFormItemChange = () => {
        let valid = tools.formIsValid(this.requiredFields, this.formData);
        this.buttons.forEach(button => button.dataset.disabled = valid ? 0 : 1);
    }

    onClickButtonReset() {
        // On déconnecte car le token va changer
        ajaxFetcher.call("capstan_account_logout", new FormData());
    }

    get formData () {
        if (this.element.nodeName.toLowerCase() == "form") {
            this._formData = new FormData(this.element);
            return this._formData;
        }
        return undefined;
    }

    onSubmit = async (e) => {
        e.preventDefault();
        this.element.style.pointerEvents = "none";

        const result = await ajaxFetcher.call("capstan_account_update", this.formData);

        this.element.style.pointerEvents = "auto";

        document.location.reload();
    }



}