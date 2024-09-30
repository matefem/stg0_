import {ProfilePage} from './ProfilePage';
import ajaxFetcher from '../../../tools/AjaxFetcher';

export class ProfileWelcome extends ProfilePage {
    constructor(e, b){
        super(e, b);
    }

    async onSubmit (e) {
        e.preventDefault();
        this.element.style.pointerEvents = "none";

        const formData = this.formData;

        const result = await ajaxFetcher.call("capstan_account_login", formData);

        if (result["code"] == "200") {
            document.location.href = this.element.dataset.successRedirect;
        }
        else {
            this.element.querySelector("input[name='password']").parentElement.classList.add("error");
        }

        this.element.style.pointerEvents = "auto";
    }

}