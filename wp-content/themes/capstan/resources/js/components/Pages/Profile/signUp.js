import {ProfilePage} from './ProfilePage';
import ajaxFetcher from '../../../tools/AjaxFetcher';

export class ProfileSignUp extends ProfilePage {
    constructor(e, b){
        super(e, b);
    }

    async onSubmit (e) {
        e.preventDefault();
        this.element.style.pointerEvents = "none";

        const formData = this.formData;
        const result = await ajaxFetcher.call("capstan_account_create", formData);

        this.element.style.pointerEvents = "auto";

        if (result["success"]) {
            if (result["code"] != 201) {
                this.element.querySelector("input[name='email']").parentElement.querySelector(".error-message").innerHTML = result["detail"]["messages"][0];
                this.element.querySelector("input[name='email']").parentElement.classList.add("error");
            }
            else {
                this.bootstrap.components.get(document.querySelector("header")).refreshAccountStatus();
                this.goToPage("profile-created");
            }
        }
    }
}